<?php

namespace App\Imports;

use App\Models\HourMeter;
use App\Models\MasterUnit;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;

class HourMeterImport implements ToCollection, WithHeadingRow
{
    public $createdCount = 0;
    public $updatedCount = 0;
    public $skippedCount = 0;
    public $filledCount = 0; // Untuk menghitung berapa data kosong yang di-auto-fill

    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        \Log::info("HourMeterImport started with " . $rows->count() . " rows.");
        
        $validRows = [];

        // Tahap 1: Validasi dan Kumpulkan data per Unit
        foreach ($rows as $index => $row) {
            if ($index === 0) {
                \Log::info("HourMeterImport first row keys: " . implode(', ', array_keys($row->toArray())));
            }

            if (!isset($row['date']) || !isset($row['unit']) || !isset($row['hm'])) {
                $this->skippedCount++;
                \Log::warning("Skipped row $index due to missing essential keys. Row data: ", $row->toArray());
                continue;
            }

            $masterUnit = MasterUnit::where('nomor_unit', $row['unit'])->first();
            
            if (!$masterUnit) {
                $this->skippedCount++;
                \Log::warning("Skipped row $index because Unit '{$row['unit']}' not found.");
                continue;
            }

            try {
                if (is_numeric($row['date'])) {
                    $dateStr = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['date'])->format('Y-m-d');
                } else {
                    $rawDate = trim($row['date']);
                    if (preg_match('/^\d{2}[-\/]\d{2}[-\/]\d{2}$/', $rawDate)) {
                        $dateStr = Carbon::createFromFormat('d-m-y', str_replace('/', '-', $rawDate))->format('Y-m-d');
                    } elseif (preg_match('/^\d{2}[-\/]\d{2}[-\/]\d{4}$/', $rawDate)) {
                        $dateStr = Carbon::createFromFormat('d-m-Y', str_replace('/', '-', $rawDate))->format('Y-m-d');
                    } else {
                        $dateStr = Carbon::parse($rawDate)->format('Y-m-d');
                    }
                }
                $date = $dateStr;
            } catch (\Exception $e) {
                $this->skippedCount++;
                continue;
            }

            $hmValue = is_numeric($row['hm']) ? floatval($row['hm']) : 0;

            $validRows[$masterUnit->id][] = [
                'date' => $date,
                'hm' => $hmValue,
                'site_id' => $masterUnit->site_id,
            ];
        }

        // Tahap 2: Proses data per Unit dengan pengurutan Tanggal & Gap Filling
        foreach ($validRows as $unit_id => $unitRows) {
            // Urutkan data dari tanggal paling awal ke paling akhir (Ascending)
            usort($unitRows, function ($a, $b) {
                return strtotime($a['date']) <=> strtotime($b['date']);
            });

            $earliestDate = Carbon::parse($unitRows[0]['date']);
            $latestDate = Carbon::parse(end($unitRows)['date']);
            
            // Batasi pencarian data sebelumnya maksimal 100 hari ke belakang untuk mencegah query terlalu berat jika ada typo tahun
            $maxLookbackDate = $earliestDate->copy()->subDays(100)->format('Y-m-d');

            // Cari data HM terakhir sebelum tanggal pertama di Excel
            $previousRecord = HourMeter::where('master_unit_id', $unit_id)
                ->where('date', '<', $earliestDate->format('Y-m-d'))
                ->where('date', '>=', $maxLookbackDate)
                ->orderBy('date', 'desc')
                ->first();

            $lastDate = $previousRecord ? Carbon::parse($previousRecord->date) : null;
            $lastHm = $previousRecord ? $previousRecord->hm : null;

            // Ambil semua tanggal yang sudah ada di database dalam rentang waktu ini untuk meminimalkan SELECT query di dalam loop
            $searchStartDate = $lastDate ? $lastDate->format('Y-m-d') : $earliestDate->format('Y-m-d');
            $existingDatesCollection = HourMeter::where('master_unit_id', $unit_id)
                ->where('date', '>=', $searchStartDate)
                ->where('date', '<=', $latestDate->format('Y-m-d'))
                ->pluck('date');
                
            $existingDates = [];
            foreach ($existingDatesCollection as $ed) {
                $existingDates[Carbon::parse($ed)->format('Y-m-d')] = true;
            }

            foreach ($unitRows as $row) {
                $currDate = Carbon::parse($row['date']);
                $currDateStr = $currDate->format('Y-m-d');

                // Jika ada data sebelumnya, cek gap
                if ($lastDate && $lastHm !== null) {
                    $daysDiff = $lastDate->diffInDays($currDate);
                    
                    // Batasi auto-fill maksimal 100 hari untuk mencegah freeze jika ada typo tahun di Excel (misal 2126 bukannya 2026)
                    if ($daysDiff > 1 && $daysDiff <= 100 && $lastDate->lt($currDate)) {
                        for ($i = 1; $i < $daysDiff; $i++) {
                            $fillDateStr = $lastDate->copy()->addDays($i)->format('Y-m-d');
                            
                            // Jika tanggal ini belum ada di database, buat baru
                            if (!isset($existingDates[$fillDateStr])) {
                                HourMeter::create([
                                    'date' => $fillDateStr,
                                    'master_unit_id' => $unit_id,
                                    'site_id' => $row['site_id'],
                                    'hm' => $lastHm, // Gunakan HM dari hari sebelumnya
                                ]);
                                $existingDates[$fillDateStr] = true;
                                $this->filledCount++;
                            }
                        }
                    }
                }

                // Proses data utama dari Excel
                if (isset($existingDates[$currDateStr])) {
                    // Update data lama
                    HourMeter::where('date', $currDateStr)
                        ->where('master_unit_id', $unit_id)
                        ->update([
                            'site_id' => $row['site_id'],
                            'hm' => $row['hm'],
                        ]);
                    $this->updatedCount++;
                } else {
                    // Buat data baru
                    HourMeter::create([
                        'date' => $currDateStr,
                        'master_unit_id' => $unit_id,
                        'site_id' => $row['site_id'],
                        'hm' => $row['hm'],
                    ]);
                    $existingDates[$currDateStr] = true;
                    $this->createdCount++;
                }

                $lastDate = $currDate;
                $lastHm = $row['hm'];
            }
        }
    }
}
