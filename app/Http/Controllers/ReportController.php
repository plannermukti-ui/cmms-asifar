<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MasterUnit;
use App\Models\UnitType;
use App\Models\UnitModel;
use App\Models\Site;
use App\Models\WorkOrder;
use App\Models\BreakdownType;
use App\Models\ComponentGroup;
use App\Models\HourMeter;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function breakdown(Request $request)
    {
        $sites          = Site::all();
        $unitTypes      = UnitType::all();
        $breakdownTypes = BreakdownType::all();

        // Global Filters
        $siteId           = $request->input('site_id');
        $globalUnitTypeId = $request->input('unit_type_id');
        $isoWeek          = $request->input('iso_week');
        $dateRange        = $request->input('date_range');

        // Helper to parse comma-separated strings inside arrays (from VirtualSelect)
        $parseFilter = function ($input) {
            if (empty($input)) return [];
            $arr = is_array($input) ? $input : [$input];
            $res = [];
            foreach ($arr as $item) {
                if (is_string($item)) {
                    foreach (explode(',', $item) as $val) {
                        $res[] = trim($val);
                    }
                } else {
                    $res[] = $item;
                }
            }
            return array_filter(array_unique($res));
        };

        // Card Specific Filters (arrays of unit_type_ids)
        $cardUnitTypes1 = $parseFilter($request->input('card_unit_type_1'));
        $cardUnitTypes2 = $parseFilter($request->input('card_unit_type_2'));
        $cardUnitTypes3 = $parseFilter($request->input('card_unit_type_3'));

        $generated = $request->has('_generate')
            || !empty($cardUnitTypes1)
            || !empty($cardUnitTypes2)
            || !empty($cardUnitTypes3);

        // Parse dates
        $startDate = null;
        $endDate   = null;

        if ($dateRange) {
            $dates = explode(' - ', $dateRange);
            if (count($dates) == 2) {
                try {
                    $startDate = Carbon::parse(trim($dates[0]))->startOfDay();
                    $endDate   = Carbon::parse(trim($dates[1]))->endOfDay();
                } catch (\Exception $e) {
                    $startDate = Carbon::now()->startOfMonth();
                    $endDate   = Carbon::now()->endOfDay();
                }
            }
        } elseif ($isoWeek) {
            try {
                $year      = substr($isoWeek, 0, 4);
                $week      = substr($isoWeek, 6, 2);
                $startDate = Carbon::now()->setISODate($year, $week)->startOfWeek();
                $endDate   = $startDate->copy()->endOfWeek();
            } catch (\Exception $e) {
                $startDate = Carbon::now()->startOfMonth();
                $endDate   = Carbon::now()->endOfDay();
            }
        } else {
            $startDate = Carbon::now()->startOfMonth();
            $endDate   = Carbon::now()->endOfDay();
        }

        $currentDateRange = $startDate->format('Y-m-d') . ' - ' . $endDate->format('Y-m-d');

        // EWH (Expected Working Hours)
        $ewh = 0;
        if ($startDate && $endDate) {
            $days = $startDate->copy()->startOfDay()->diffInDays($endDate->copy()->startOfDay()) + 1;
            $ewh  = $days * 24;
        }

        // Function to process a single card
        $processCard = function ($cardUnitTypeIds) use (
            $siteId, $startDate, $endDate, $breakdownTypes, $ewh
        ) {
            $effectiveTypeIds = array_values(array_filter($cardUnitTypeIds));
            if (empty($effectiveTypeIds)) {
                return null; // Return null if no filter selected for this card
            }

            $units = MasterUnit::with(['type', 'model'])
                ->when($siteId, function ($q) use ($siteId) {
                    return $q->where('site_id', $siteId);
                })
                ->whereIn('unit_type_id', $effectiveTypeIds)
                ->orderBy('unit_type_id')
                ->orderBy('nomor_unit')
                ->get();

            // Initialize BD Type totals (keep ALL breakdown types, even if 0)
            $bdTypeTotals = [];
            foreach ($breakdownTypes as $bt) {
                $bdTypeTotals[$bt->id] = [
                    'code'      => $bt->code ?? '-',
                    'name'      => $bt->name,
                    'total_jam' => 0,
                ];
            }

            $totalJamBdCard     = 0;
            $jamSekarang        = Carbon::parse(Carbon::now('Asia/Makassar')->format('Y-m-d H:i:s'));
            $compGroupTotals    = []; // ['Component Name' => total_hrs]
            $downtimeCodeTotals = []; // ['Schedule' => total_hrs, 'Unschedule' => total_hrs, ...]

            $unitRows = [];

            foreach ($units as $unit) {
                // Hour meter calculation
                $hmAwalRecord = HourMeter::where('master_unit_id', $unit->id)
                    ->where('date', '<=', $startDate->format('Y-m-d'))
                    ->orderBy('date', 'desc')
                    ->first();
                $hmAwal = $hmAwalRecord ? (float) $hmAwalRecord->hm : 0;

                $hmAkhirRecord = HourMeter::where('master_unit_id', $unit->id)
                    ->where('date', '<=', $endDate->format('Y-m-d'))
                    ->orderBy('date', 'desc')
                    ->first();
                $hmAkhir = $hmAkhirRecord ? (float) $hmAkhirRecord->hm : $hmAwal;

                $opHrs = max(0, $hmAkhir - $hmAwal);

                // Work Orders (Eager load componentGroup)
                $wos = WorkOrder::with(['componentGroup'])
                    ->where('master_unit_id', $unit->id)
                    ->where('opportunity', false)
                    ->where(function ($q) use ($startDate, $endDate) {
                        $q->whereBetween('waktu_bd', [$startDate, $endDate])
                          ->orWhereBetween('waktu_rfu', [$startDate, $endDate])
                          ->orWhere(function ($q2) use ($startDate, $endDate) {
                              $q2->where('waktu_bd', '<', $startDate)
                                 ->where(function ($q3) use ($endDate) {
                                     $q3->whereNull('waktu_rfu')
                                        ->orWhere('waktu_rfu', '>', $endDate);
                                 });
                          });
                    })
                    ->get();

                $eventBd    = 0;
                $unitBdTotal = 0;

                foreach ($wos as $wo) {
                    if (strtolower($wo->tipe_wo) == 'plan' && strtolower($wo->status_wo) != 'completed') continue;

                    $bdStart = $wo->waktu_bd ? Carbon::parse($wo->waktu_bd) : null;
                    $bdEnd   = $wo->waktu_rfu ? Carbon::parse($wo->waktu_rfu) : $jamSekarang;

                    if (!$bdStart) continue;

                    $overlapStart = max($bdStart->timestamp, $startDate->timestamp);
                    $overlapEnd   = min($bdEnd->timestamp, $endDate->timestamp);

                    if ($overlapEnd > $overlapStart) {
                        $hrs = ($overlapEnd - $overlapStart) / 3600;
                        $unitBdTotal   += $hrs;
                        $totalJamBdCard += $hrs;
                        $eventBd++;

                        // 1. Breakdown Type total
                        if ($wo->breakdown_type_id && isset($bdTypeTotals[$wo->breakdown_type_id])) {
                            $bdTypeTotals[$wo->breakdown_type_id]['total_jam'] += $hrs;
                        }

                        // 2. Component Group total (only if component group exists)
                        if ($wo->componentGroup && !empty($wo->componentGroup->name)) {
                            $cgName = $wo->componentGroup->name;
                            if (!isset($compGroupTotals[$cgName])) {
                                $compGroupTotals[$cgName] = 0;
                            }
                            $compGroupTotals[$cgName] += $hrs;
                        }

                        // 3. Downtime Code total
                        if (!empty($wo->downtime_code)) {
                            $dtCode = $wo->downtime_code;
                            if (!isset($downtimeCodeTotals[$dtCode])) {
                                $downtimeCodeTotals[$dtCode] = 0;
                            }
                            $downtimeCodeTotals[$dtCode] += $hrs;
                        }
                    }
                }

                $stb  = max(0, $ewh - $unitBdTotal - $opHrs);
                $pa   = $ewh > 0 ? (($ewh - $unitBdTotal) / $ewh) * 100 : 0;
                $ma   = ($opHrs + $unitBdTotal) > 0 ? ($opHrs / ($opHrs + $unitBdTotal)) * 100 : 0;
                $mtbf = ($eventBd > 0 && $opHrs > 0) ? ($opHrs / $eventBd) : 0;
                $mttr = $eventBd > 0 ? $unitBdTotal / $eventBd : 0;
                $ua   = ($ewh - $unitBdTotal) > 0 ? ($opHrs / ($ewh - $unitBdTotal)) * 100 : 0;
                $eu   = $ewh > 0 ? ($opHrs / $ewh) * 100 : 0;

                $unitRows[] = [
                    'nomor_unit'  => $unit->nomor_unit,
                    'type_name'   => $unit->type ? $unit->type->name : '-',
                    'model_name'  => $unit->model ? $unit->model->name : '-',
                    'hm_awal'     => $hmAwal,
                    'hm_akhir'    => $hmAkhir,
                    'op_hrs'      => $opHrs,
                    'ewh'         => $ewh,
                    'event_bd'    => $eventBd,
                    'bd_hrs'      => $unitBdTotal,
                    'stb'         => $stb,
                    'pa'          => $pa,
                    'ma'          => $ma,
                    'mtbf'        => $mtbf,
                    'mttr'        => $mttr,
                    'ua'          => $ua,
                    'eu'          => $eu,
                ];
            }

            // Calculate percentages for BD types
            $chartBdTypes = [];
            foreach ($bdTypeTotals as $id => &$bdt) {
                $bdt['percentage'] = $totalJamBdCard > 0 ? ($bdt['total_jam'] / $totalJamBdCard) * 100 : 0;
                $chartBdTypes[] = [
                    'name'  => $bdt['name'],
                    'value' => round($bdt['percentage'], 2),
                    'hrs'   => round($bdt['total_jam'], 1),
                ];
            }
            unset($bdt);

            // Format Comp Group chart data
            arsort($compGroupTotals);
            $compGroupChart = [];
            foreach ($compGroupTotals as $name => $hrs) {
                $compGroupChart[] = [
                    'name'  => $name,
                    'value' => round($hrs, 1),
                ];
            }

            // Format Downtime Code chart data
            arsort($downtimeCodeTotals);
            $downtimeCodeChart = [];
            foreach ($downtimeCodeTotals as $code => $hrs) {
                $downtimeCodeChart[] = [
                    'name'  => $code,
                    'value' => round($hrs, 1),
                ];
            }

            return [
                'unit_type_ids'       => $effectiveTypeIds,
                'unit_rows'           => $unitRows,
                'total_jam_bd'        => $totalJamBdCard,
                'bd_type_totals'      => array_values($bdTypeTotals),
                'chart_bd_types'      => $chartBdTypes,
                'comp_group_chart'    => $compGroupChart,
                'downtime_code_chart' => $downtimeCodeChart,
            ];
        };

        // Only process cards that have card_unit_type filter selected!
        $card1 = !empty($cardUnitTypes1) ? $processCard($cardUnitTypes1) : null;
        $card2 = !empty($cardUnitTypes2) ? $processCard($cardUnitTypes2) : null;
        $card3 = !empty($cardUnitTypes3) ? $processCard($cardUnitTypes3) : null;

        return view('reports.breakdown', compact(
            'sites', 'unitTypes',
            'siteId', 'globalUnitTypeId', 'currentDateRange',
            'generated',
            'card1', 'card2', 'card3',
            'cardUnitTypes1', 'cardUnitTypes2', 'cardUnitTypes3'
        ));
    }
}
