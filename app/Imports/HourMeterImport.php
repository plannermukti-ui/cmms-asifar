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
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            // Check if essential row data is present
            if (!isset($row['date']) || !isset($row['unit']) || !isset($row['hm'])) {
                continue;
            }

            // Find unit by nomor_unit
            $masterUnit = MasterUnit::where('nomor_unit', $row['unit'])->first();
            
            if (!$masterUnit) {
                // If unit is not found, skip or log (we'll just skip here)
                continue;
            }

            try {
                // Convert excel date if it's numeric, or parse string
                if (is_numeric($row['date'])) {
                    $date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['date'])->format('Y-m-d');
                } else {
                    $date = Carbon::parse($row['date'])->format('Y-m-d');
                }
            } catch (\Exception $e) {
                // Invalid date format, fallback to today or skip
                $date = Carbon::now()->format('Y-m-d');
            }

            // Create or update the hour meter
            // Here we just create a new record
            HourMeter::create([
                'date' => $date,
                'master_unit_id' => $masterUnit->id,
                'site_id' => $masterUnit->site_id,
                'hm' => is_numeric($row['hm']) ? floatval($row['hm']) : 0,
            ]);
        }
    }
}
