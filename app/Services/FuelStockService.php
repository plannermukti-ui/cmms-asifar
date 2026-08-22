<?php

namespace App\Services;

use App\Models\FuelDistribution;
use App\Models\FuelDistributionShift;
use App\Models\FuelFlowmeterAdjustment;
use App\Models\FuelReceiving;
use App\Models\FuelStockLog;
use App\Models\FuelStorage;
use App\Models\FuelTransfer;
use App\Models\FuelTruck;
use App\Models\FuelTruckFilling;
use Illuminate\Support\Facades\DB;

class FuelStockService
{
    /**
     * Setujui Penerimaan BBM dari Vendor -> Update Stok Tangki & Akumulasi Totalizer Flowmeter
     */
    public function approveReceiving(FuelReceiving $receiving, int $approverId): void
    {
        DB::transaction(function () use ($receiving, $approverId) {
            $storage = FuelStorage::findOrFail($receiving->fuel_storage_id);

            // Update receiving status
            $receiving->update([
                'status' => 'Approved',
                'approved_by' => $approverId,
                'approved_at' => now(),
            ]);

            // Tambah stok di Fuel Storage
            $newStock = $storage->current_stock + $receiving->received_volume_liters;
            
            // Akumulasi Totalizer Flowmeter Storage (terus bertambah baik in maupun out)
            $newTotalizer = ($receiving->totalizer_after && $receiving->totalizer_after > $storage->current_totalizer)
                ? $receiving->totalizer_after
                : ($storage->current_totalizer + $receiving->received_volume_liters);

            $storage->update([
                'current_stock' => $newStock,
                'current_totalizer' => $newTotalizer,
            ]);

            // Catat Kartu Stok
            FuelStockLog::create([
                'site_id' => $storage->site_id,
                'reference_type' => 'fuel_storage',
                'reference_id' => $storage->id,
                'transaction_type' => 'Receiving (Inbound)',
                'transaction_number' => $receiving->receiving_number,
                'date_time' => $receiving->date_receive,
                'qty_in' => $receiving->received_volume_liters,
                'qty_out' => 0,
                'balance_after' => $newStock,
                'totalizer_record' => $newTotalizer,
                'notes' => 'Penerimaan dari ' . ($receiving->vendor->name ?? 'Vendor') . ' (DO: ' . $receiving->delivery_order_number . ')',
                'created_by' => $approverId,
            ]);
        });
    }

    /**
     * Rollback & Hapus Transaksi Penerimaan BBM (Khusus Super Admin)
     */
    public function rollbackAndForceDeleteReceiving(FuelReceiving $receiving, int $userId): void
    {
        DB::transaction(function () use ($receiving, $userId) {
            $storage = FuelStorage::find($receiving->fuel_storage_id);

            // Jika sebelumnya sudah disetujui, kembalikan (potong) stok tangki timbun
            if ($receiving->status === 'Approved' && $storage) {
                $newStock = max(0, $storage->current_stock - $receiving->received_volume_liters);
                $storage->update([
                    'current_stock' => $newStock,
                ]);

                // Hapus catatan mutasi kartu stok terkait nomor penerimaan ini
                FuelStockLog::where('reference_type', 'fuel_storage')
                    ->where('reference_id', $storage->id)
                    ->where('transaction_number', $receiving->receiving_number)
                    ->delete();
            }

            // Hapus file scan jika ada
            if ($receiving->document_scan) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($receiving->document_scan);
            }

            // Force delete dari database
            $receiving->forceDelete();
        });
    }

    /**
     * Tolak Penerimaan BBM
     */
    public function rejectReceiving(FuelReceiving $receiving, int $approverId, string $reason): void
    {
        $receiving->update([
            'status' => 'Rejected',
            'approved_by' => $approverId,
            'approved_at' => now(),
            'rejected_reason' => $reason,
        ]);
    }

    /**
     * Eksekusi Mutasi Antar Storage (Direct Pompa atau via Mobile Fuel Truck)
     */
    public function executeTransfer(FuelTransfer $transfer, int $userId): void
    {
        DB::transaction(function () use ($transfer, $userId) {
            $source = FuelStorage::findOrFail($transfer->source_storage_id);
            $dest = FuelStorage::findOrFail($transfer->destination_storage_id);

            if ($source->current_stock < $transfer->volume_liters) {
                throw new \Exception("Stok di {$source->name} tidak mencukupi untuk transfer (Sisa: {$source->current_stock} L, Diminta: {$transfer->volume_liters} L).");
            }

            // Kurangi asal & akumulasi totalizer flowmeter pompa keluar
            $newSourceStock = $source->current_stock - $transfer->volume_liters;
            $newSourceTot = ($transfer->source_totalizer_after && $transfer->source_totalizer_after > $source->current_totalizer)
                ? $transfer->source_totalizer_after
                : ($source->current_totalizer + $transfer->volume_liters);

            $source->update([
                'current_stock' => $newSourceStock,
                'current_totalizer' => $newSourceTot,
            ]);

            // Tambah tujuan & akumulasi totalizer flowmeter pompa masuk
            $newDestStock = $dest->current_stock + $transfer->volume_liters;
            $newDestTot = ($transfer->dest_totalizer_after && $transfer->dest_totalizer_after > $dest->current_totalizer)
                ? $transfer->dest_totalizer_after
                : ($dest->current_totalizer + $transfer->volume_liters);

            $dest->update([
                'current_stock' => $newDestStock,
                'current_totalizer' => $newDestTot,
            ]);

            $methodNote = $transfer->transfer_method === 'Via Fuel Truck' && $transfer->fuelTruck
                ? " (via Fuel Truck {$transfer->fuelTruck->masterUnit->nomor_unit})"
                : " (Pompa Langsung)";

            // Log Source
            FuelStockLog::create([
                'site_id' => $source->site_id,
                'reference_type' => 'fuel_storage',
                'reference_id' => $source->id,
                'transaction_type' => 'Transfer Out',
                'transaction_number' => $transfer->transfer_number,
                'date_time' => $transfer->transfer_date,
                'qty_in' => 0,
                'qty_out' => $transfer->volume_liters,
                'balance_after' => $newSourceStock,
                'totalizer_record' => $newSourceTot,
                'notes' => 'Transfer keluar ke ' . $dest->name . $methodNote,
                'created_by' => $userId,
            ]);

            // Log Destination
            FuelStockLog::create([
                'site_id' => $dest->site_id,
                'reference_type' => 'fuel_storage',
                'reference_id' => $dest->id,
                'transaction_type' => 'Transfer In',
                'transaction_number' => $transfer->transfer_number,
                'date_time' => $transfer->transfer_date,
                'qty_in' => $transfer->volume_liters,
                'qty_out' => 0,
                'balance_after' => $newDestStock,
                'totalizer_record' => $newDestTot,
                'notes' => 'Transfer masuk dari ' . $source->name . $methodNote,
                'created_by' => $userId,
            ]);
        });
    }

    /**
     * Rollback & Hapus Mutasi Antar Tangki (Khusus Super Admin)
     */
    public function rollbackAndForceDeleteTransfer(FuelTransfer $transfer, int $userId): void
    {
        DB::transaction(function () use ($transfer, $userId) {
            $source = FuelStorage::find($transfer->source_storage_id);
            $dest = FuelStorage::find($transfer->destination_storage_id);

            // Kembalikan stok ke source dan kurangi dari dest
            if ($source) {
                $source->update([
                    'current_stock' => $source->current_stock + $transfer->volume_liters,
                ]);
                FuelStockLog::where('reference_type', 'fuel_storage')
                    ->where('reference_id', $source->id)
                    ->where('transaction_number', $transfer->transfer_number)
                    ->delete();
            }

            if ($dest) {
                $dest->update([
                    'current_stock' => max(0, $dest->current_stock - $transfer->volume_liters),
                ]);
                FuelStockLog::where('reference_type', 'fuel_storage')
                    ->where('reference_id', $dest->id)
                    ->where('transaction_number', $transfer->transfer_number)
                    ->delete();
            }

            $transfer->forceDelete();
        });
    }

    /**
     * Eksekusi Pengisian BBM ke Fuel Truck
     */
    public function executeTruckFilling(FuelTruckFilling $filling, int $userId): void
    {
        DB::transaction(function () use ($filling, $userId) {
            $storage = FuelStorage::findOrFail($filling->fuel_storage_id);
            $truck = FuelTruck::findOrFail($filling->fuel_truck_id);

            if ($storage->current_stock < $filling->volume_liters) {
                throw new \Exception("Stok di {$storage->name} tidak mencukupi (Sisa: {$storage->current_stock} L, Diisi: {$filling->volume_liters} L).");
            }

            // Kurangi Storage & Akumulasi totalizer storage
            $newStorageStock = $storage->current_stock - $filling->volume_liters;
            $newStorageTot = ($filling->storage_totalizer_after && $filling->storage_totalizer_after > $storage->current_totalizer)
                ? $filling->storage_totalizer_after
                : ($storage->current_totalizer + $filling->volume_liters);

            $storage->update([
                'current_stock' => $newStorageStock,
                'current_totalizer' => $newStorageTot,
            ]);

            // Tambah Fuel Truck
            $truckBefore = $truck->current_stock;
            $truckAfter = $truckBefore + $filling->volume_liters;
            $truck->update([
                'current_stock' => $truckAfter,
            ]);

            $filling->update([
                'truck_stock_before' => $truckBefore,
                'truck_stock_after' => $truckAfter,
            ]);

            // Log Storage Out
            FuelStockLog::create([
                'site_id' => $storage->site_id,
                'reference_type' => 'fuel_storage',
                'reference_id' => $storage->id,
                'transaction_type' => 'Truck Refill Out',
                'transaction_number' => $filling->refill_number,
                'date_time' => $filling->fill_date,
                'qty_in' => 0,
                'qty_out' => $filling->volume_liters,
                'balance_after' => $newStorageStock,
                'totalizer_record' => $newStorageTot,
                'notes' => 'Pengisian ke Fuel Truck ' . ($truck->masterUnit->nomor_unit ?? ''),
                'created_by' => $userId,
            ]);

            // Log Truck In
            FuelStockLog::create([
                'site_id' => $truck->site_id,
                'reference_type' => 'fuel_truck',
                'reference_id' => $truck->id,
                'transaction_type' => 'Truck Refill In',
                'transaction_number' => $filling->refill_number,
                'date_time' => $filling->fill_date,
                'qty_in' => $filling->volume_liters,
                'qty_out' => 0,
                'balance_after' => $truckAfter,
                'totalizer_record' => $truck->current_totalizer,
                'notes' => 'Isi ulang BBM dari ' . $storage->name . ' (' . $filling->shift . ')',
                'created_by' => $userId,
            ]);
        });
    }

    /**
     * Rollback & Hapus Pengisian BBM ke Fuel Truck (Khusus Super Admin)
     */
    public function rollbackAndForceDeleteTruckFilling(FuelTruckFilling $filling, int $userId): void
    {
        DB::transaction(function () use ($filling, $userId) {
            $storage = FuelStorage::find($filling->fuel_storage_id);
            $truck = FuelTruck::find($filling->fuel_truck_id);

            // Kembalikan stok ke storage
            if ($storage) {
                $storage->update([
                    'current_stock' => $storage->current_stock + $filling->volume_liters,
                ]);
                FuelStockLog::where('reference_type', 'fuel_storage')
                    ->where('reference_id', $storage->id)
                    ->where('transaction_number', $filling->refill_number)
                    ->delete();
            }

            // Potong stok dari Fuel Truck
            if ($truck) {
                $truck->update([
                    'current_stock' => max(0, $truck->current_stock - $filling->volume_liters),
                ]);
                FuelStockLog::where('reference_type', 'fuel_truck')
                    ->where('reference_id', $truck->id)
                    ->where('transaction_number', $filling->refill_number)
                    ->delete();
            }

            $filling->forceDelete();
        });
    }

    /**
     * Tutup Shift Distribusi & Rekonsiliasi Pengisian Unit
     */
    public function closeDistributionShift(FuelDistributionShift $shift, float $totalizerEnd, int $userId): void
    {
        DB::transaction(function () use ($shift, $totalizerEnd, $userId) {
            $truck = FuelTruck::findOrFail($shift->fuel_truck_id);

            $deltaFlowmeter = max(0, $totalizerEnd - $shift->totalizer_start);
            $totalDistributed = $shift->distributions()->sum('volume_liters');
            $variance = $deltaFlowmeter - $totalDistributed;

            // Update shift
            $shift->update([
                'totalizer_end' => $totalizerEnd,
                'total_liters_flowmeter' => $deltaFlowmeter,
                'total_liters_distributed' => $totalDistributed,
                'variance_liters' => $variance,
                'status' => 'Closed',
                'closed_by' => $userId,
                'closed_at' => now(),
            ]);

            // Potong stok Fuel Truck berdasarkan total terdistribusi atau flowmeter
            $usedVolume = $totalDistributed > 0 ? $totalDistributed : $deltaFlowmeter;
            $newTruckStock = max(0, $truck->current_stock - $usedVolume);

            $truck->update([
                'current_stock' => $newTruckStock,
                'current_totalizer' => max($truck->current_totalizer, $totalizerEnd),
            ]);

            // Catat Kartu Stok Fuel Truck
            FuelStockLog::create([
                'site_id' => $truck->site_id,
                'reference_type' => 'fuel_truck',
                'reference_id' => $truck->id,
                'transaction_type' => 'Unit Dispensing',
                'transaction_number' => $shift->shift_doc_number,
                'date_time' => now(),
                'qty_in' => 0,
                'qty_out' => $usedVolume,
                'balance_after' => $newTruckStock,
                'totalizer_record' => $totalizerEnd,
                'notes' => "Distribusi {$shift->shift} ({$shift->distributions()->count()} unit) oleh {$shift->fuelman_name}",
                'created_by' => $userId,
            ]);
        });
    }

    /**
     * Buka Kembali Shift Distribusi yang Telah Ditutup (Reopen Shift)
     */
    public function reopenDistributionShift(FuelDistributionShift $shift, int $userId): void
    {
        DB::transaction(function () use ($shift, $userId) {
            if ($shift->status !== 'Closed') {
                return;
            }

            $truck = FuelTruck::findOrFail($shift->fuel_truck_id);
            $usedVolume = $shift->total_liters_distributed > 0 ? $shift->total_liters_distributed : $shift->total_liters_flowmeter;

            // Kembalikan stok ke Fuel Truck
            $truck->update([
                'current_stock' => $truck->current_stock + $usedVolume,
            ]);

            // Hapus kartu stok log shift ini
            FuelStockLog::where('reference_type', 'fuel_truck')
                ->where('reference_id', $truck->id)
                ->where('transaction_number', $shift->shift_doc_number)
                ->delete();

            // Set status shift kembali ke Open
            $shift->update([
                'status' => 'Open',
                'totalizer_end' => null,
                'total_liters_flowmeter' => 0,
                'total_liters_distributed' => 0,
                'variance_liters' => 0,
                'closed_by' => null,
                'closed_at' => null,
            ]);
        });
    }

    /**
     * Batalkan & Hapus Seluruh Sesi Shift Distribusi (Khusus Super Admin)
     */
    public function rollbackAndForceDeleteDistributionShift(FuelDistributionShift $shift, int $userId): void
    {
        DB::transaction(function () use ($shift, $userId) {
            $truck = FuelTruck::find($shift->fuel_truck_id);

            // Jika status Closed, kembalikan stok
            if ($shift->status === 'Closed' && $truck) {
                $usedVolume = $shift->total_liters_distributed > 0 ? $shift->total_liters_distributed : $shift->total_liters_flowmeter;
                $truck->update([
                    'current_stock' => $truck->current_stock + $usedVolume,
                ]);

                FuelStockLog::where('reference_type', 'fuel_truck')
                    ->where('reference_id', $truck->id)
                    ->where('transaction_number', $shift->shift_doc_number)
                    ->delete();
            }

            // Hapus seluruh item distribusi unit di dalamnya
            $shift->distributions()->delete();

            // Force delete shift
            $shift->forceDelete();
        });
    }

    /**
     * Catat Berita Acara Pergantian/Adjustment Flowmeter & Update Totalizer
     */
    public function applyFlowmeterAdjustment(FuelFlowmeterAdjustment $adj, int $userId): void
    {
        DB::transaction(function () use ($adj, $userId) {
            if ($adj->device_type === 'fuel_storage') {
                $storage = FuelStorage::findOrFail($adj->device_id);
                $storage->update([
                    'current_totalizer' => $adj->new_totalizer_initial,
                ]);

                FuelStockLog::create([
                    'site_id' => $storage->site_id,
                    'reference_type' => 'fuel_storage',
                    'reference_id' => $storage->id,
                    'transaction_type' => 'Flowmeter Adjustment',
                    'transaction_number' => $adj->adjustment_number,
                    'date_time' => $adj->incident_date,
                    'qty_in' => 0,
                    'qty_out' => 0,
                    'balance_after' => $storage->current_stock,
                    'totalizer_record' => $adj->new_totalizer_initial,
                    'notes' => "Berita Acara Flowmeter ({$adj->incident_type}): Totalizer diubah dari {$adj->old_totalizer_final} ke {$adj->new_totalizer_initial}",
                    'created_by' => $userId,
                ]);
            } else {
                $truck = FuelTruck::findOrFail($adj->device_id);
                $truck->update([
                    'current_totalizer' => $adj->new_totalizer_initial,
                    'flowmeter_serial_number' => $adj->new_flowmeter_serial ?: $truck->flowmeter_serial_number,
                ]);

                FuelStockLog::create([
                    'site_id' => $truck->site_id,
                    'reference_type' => 'fuel_truck',
                    'reference_id' => $truck->id,
                    'transaction_type' => 'Flowmeter Adjustment',
                    'transaction_number' => $adj->adjustment_number,
                    'date_time' => $adj->incident_date,
                    'qty_in' => 0,
                    'qty_out' => 0,
                    'balance_after' => $truck->current_stock,
                    'totalizer_record' => $adj->new_totalizer_initial,
                    'notes' => "Berita Acara Flowmeter FT ({$adj->incident_type}): Totalizer diubah dari {$adj->old_totalizer_final} ke {$adj->new_totalizer_initial}",
                    'created_by' => $userId,
                ]);
            }
        });
    }
}
