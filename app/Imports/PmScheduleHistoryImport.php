<?php

namespace App\Imports;

use App\Models\PmSchedule;
use App\Models\PmScheduleHistory;
use App\Models\PmTemplate;
use App\Models\MasterUnit;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class PmScheduleHistoryImport implements ToCollection, WithHeadingRow
{
    protected $importedCount = 0;
    protected $skippedCount = 0;
    protected $errors = [];

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            $rowNum = $index + 2; // Baris 1 adalah header

            // 1. Ambil kode unit
            $unitCode = $row['unit'] ?? $row['nomor_unit'] ?? $row['no_unit'] ?? null;
            if (!$unitCode) {
                $this->skippedCount++;
                continue;
            }

            $masterUnit = MasterUnit::where('nomor_unit', trim($unitCode))->first();
            if (!$masterUnit) {
                $this->errors[] = "Baris {$rowNum}: Unit '{$unitCode}' tidak ditemukan di Master Unit.";
                $this->skippedCount++;
                continue;
            }

            // 2. Ambil HM Service
            $hmRaw = $row['hm'] ?? $row['hm_service'] ?? $row['hours_meter'] ?? $row['hm_terakhir'] ?? null;
            if ($hmRaw === null || $hmRaw === '') {
                $this->errors[] = "Baris {$rowNum}: Nilai HM Service untuk unit '{$unitCode}' tidak boleh kosong.";
                $this->skippedCount++;
                continue;
            }
            $hmService = floatval(str_replace(',', '.', (string)$hmRaw));

            // 3. Ambil Tanggal Servis
            $dateRaw = $row['date'] ?? $row['tanggal'] ?? $row['tanggal_service'] ?? $row['date_service'] ?? null;
            try {
                if (is_numeric($dateRaw)) {
                    $executedAt = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($dateRaw)->format('Y-m-d');
                } elseif ($dateRaw) {
                    $executedAt = Carbon::parse($dateRaw)->format('Y-m-d');
                } else {
                    $executedAt = Carbon::now()->format('Y-m-d');
                }
            } catch (\Exception $e) {
                $executedAt = Carbon::now()->format('Y-m-d');
            }

            // 4. Cocokkan Template PM & Jadwal PM
            $templateName = $row['template'] ?? $row['pm_template'] ?? $row['service_template'] ?? $row['nama_template'] ?? null;
            $pmSchedule = null;

            if ($templateName) {
                $template = PmTemplate::where('name', 'like', '%' . trim($templateName) . '%')->first();
                if ($template) {
                    $pmSchedule = PmSchedule::firstOrCreate([
                        'master_unit_id' => $masterUnit->id,
                        'pm_template_id' => $template->id,
                    ], [
                        'site_id' => $masterUnit->site_id,
                        'next_due_value' => $template->interval_value ?: 250,
                        'status_jadwal' => 'Upcoming',
                    ]);
                }
            }

            // Jika template tidak spesifik, gunakan jadwal PM yang sudah ada untuk unit ini
            if (!$pmSchedule) {
                $pmSchedule = PmSchedule::where('master_unit_id', $masterUnit->id)->first();
            }

            // Jika belum ada jadwal sama sekali, coba generate dari template model unit
            if (!$pmSchedule) {
                $template = PmTemplate::where('unit_model_id', $masterUnit->unit_model_id)->first()
                    ?? PmTemplate::first();

                if ($template) {
                    $pmSchedule = PmSchedule::create([
                        'master_unit_id' => $masterUnit->id,
                        'pm_template_id' => $template->id,
                        'site_id' => $masterUnit->site_id,
                        'next_due_value' => $template->interval_value ?: 250,
                        'status_jadwal' => 'Upcoming',
                    ]);
                }
            }

            if (!$pmSchedule) {
                $this->errors[] = "Baris {$rowNum}: Template / Jadwal PM untuk unit '{$unitCode}' tidak ditemukan.";
                $this->skippedCount++;
                continue;
            }

            // 5. Work Order No & Catatan
            $woNo = $row['wo_no'] ?? $row['nomor_wo'] ?? $row['no_wo'] ?? $row['work_order_no'] ?? null;
            $notes = $row['notes'] ?? $row['catatan'] ?? $row['keterangan'] ?? 'Import massal Excel';

            // 6. Simpan Riwayat History Servis
            PmScheduleHistory::create([
                'pm_schedule_id' => $pmSchedule->id,
                'hm_service' => $hmService,
                'executed_at' => $executedAt,
                'work_order_no' => $woNo ? trim($woNo) : null,
                'notes' => $notes,
                'created_by' => Auth::id(),
            ]);

            // 7. Update Jadwal Induk (PmSchedule)
            $pmSchedule->last_executed_value = $hmService;
            $pmSchedule->load('pmTemplate');
            
            $interval = $pmSchedule->pmTemplate?->interval_value ?: 250;
            $oprHrs = $pmSchedule->pmTemplate?->opr_hrs_per_day ?? 20;

            $pmSchedule->next_due_value = floor($hmService / $interval) * $interval + $interval;
            $hrsToGo = $pmSchedule->next_due_value - $hmService;

            if ($oprHrs > 0) {
                $daysToGo = $hrsToGo / $oprHrs;
                $pmSchedule->next_due_date = Carbon::parse($executedAt)->addHours(round($daysToGo * 24));
            }

            $pmSchedule->status_jadwal = 'Upcoming';
            $pmSchedule->save();

            $this->importedCount++;
        }
    }

    public function getImportedCount(): int
    {
        return $this->importedCount;
    }

    public function getSkippedCount(): int
    {
        return $this->skippedCount;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
