<?php

namespace App\Http\Controllers;

use App\Models\FuelStorage;
use App\Models\FuelTransfer;
use App\Models\FuelTruck;
use App\Models\Site;
use App\Services\FuelStockService;
use Illuminate\Http\Request;

class FuelTransferController extends Controller
{
    protected FuelStockService $stockService;

    public function __construct(FuelStockService $stockService)
    {
        $this->stockService = $stockService;
        $this->middleware('permission:view_fuel_transfers')->only(['index', 'show']);
        $this->middleware('permission:create_fuel_transfers')->only(['create', 'store']);
    }

    public function index(Request $request)
    {
        $siteId = $request->get('site_id');
        $transfers = FuelTransfer::with(['sourceStorage', 'destinationStorage', 'fuelTruck.masterUnit', 'creator', 'site'])
            ->when($siteId, fn($q) => $q->where('site_id', $siteId))
            ->orderBy('transfer_date', 'desc')
            ->paginate(15)
            ->withQueryString();

        $sites = Site::orderBy('name')->get();

        return view('fuel.transfers.index', compact('transfers', 'sites', 'siteId'));
    }

    public function create()
    {
        $storages = FuelStorage::where('is_active', true)->orderBy('code')->get();
        $fuelTrucks = FuelTruck::with('masterUnit')->where('is_active', true)->get();
        $autoNumber = FuelTransfer::generateTransferNumber();

        return view('fuel.transfers.create', compact('storages', 'fuelTrucks', 'autoNumber'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'source_storage_id' => 'required|exists:fuel_storages,id|different:destination_storage_id',
            'destination_storage_id' => 'required|exists:fuel_storages,id',
            'transfer_method' => 'required|in:Direct Pump,Via Fuel Truck',
            'fuel_truck_id' => 'nullable|required_if:transfer_method,Via Fuel Truck|exists:fuel_trucks,id',
            'transfer_date' => 'required|date',
            'volume_liters' => 'required|numeric|min:1',
            'source_totalizer_before' => 'nullable|numeric|min:0',
            'source_totalizer_after' => 'nullable|numeric|min:0',
            'dest_totalizer_before' => 'nullable|numeric|min:0',
            'dest_totalizer_after' => 'nullable|numeric|min:0',
            'operator_name' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $source = FuelStorage::findOrFail($request->source_storage_id);

        try {
            $transfer = FuelTransfer::create([
                'transfer_number' => FuelTransfer::generateTransferNumber(),
                'site_id' => $source->site_id,
                'source_storage_id' => $request->source_storage_id,
                'destination_storage_id' => $request->destination_storage_id,
                'transfer_method' => $request->transfer_method,
                'fuel_truck_id' => $request->fuel_truck_id,
                'transfer_date' => $request->transfer_date,
                'volume_liters' => $request->volume_liters,
                'source_totalizer_before' => $request->source_totalizer_before,
                'source_totalizer_after' => $request->source_totalizer_after,
                'dest_totalizer_before' => $request->dest_totalizer_before,
                'dest_totalizer_after' => $request->dest_totalizer_after,
                'operator_name' => $request->operator_name,
                'notes' => $request->notes,
                'status' => 'Completed',
                'created_by' => auth()->id(),
            ]);

            $this->stockService->executeTransfer($transfer, auth()->id());

            return redirect()->route('fuel.transfers.index')->with('success', "Mutasi BBM {$transfer->transfer_number} berhasil dieksekusi.");
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal memproses mutasi: ' . $e->getMessage());
        }
    }

    public function destroy(FuelTransfer $transfer)
    {
        if (!auth()->user()->hasRole('Super Admin')) {
            return back()->with('error', 'Hanya Super Admin yang dapat membatalkan dan menghapus transaksi mutasi.');
        }

        try {
            $num = $transfer->transfer_number;
            $this->stockService->rollbackAndForceDeleteTransfer($transfer, auth()->id());
            return redirect()->route('fuel.transfers.index')->with('success', "Mutasi BBM {$num} berhasil dibatalkan dan stok tangki asal/tujuan telah dikembalikan.");
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal membatalkan mutasi: ' . $e->getMessage());
        }
    }
}
