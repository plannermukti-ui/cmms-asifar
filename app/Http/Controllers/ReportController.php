<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MasterUnit;
use App\Models\UnitType;
use App\Models\Site;
use App\Models\WorkOrder;
use App\Models\BreakdownType;
use App\Models\HourMeter;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_breakdown_reports')->only(['breakdown']);
    }

    public function breakdown(Request $request)
    {
        $sites          = Site::all();
        $unitTypes      = UnitType::all();
        $breakdownTypes = BreakdownType::all();

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

        // Global Filters
        $siteId           = $request->input('site_id');
        $globalUnitTypeIds = array_map('intval', $parseFilter($request->input('unit_type_id')));
        $isoWeek          = $request->input('iso_week');
        $dateRange        = $request->input('date_range');

        // Card Specific Filters (arrays of unit_type_ids)
        $cardFilters = [];
        foreach ($request->all() as $key => $value) {
            if (preg_match('/^card_unit_type_(\d+)$/', $key, $matches)) {
                $cardFilters[(int) $matches[1]] = $parseFilter($value);
            }
        }

        if (empty($cardFilters)) {
            $cardFilters[1] = [];
        }

        ksort($cardFilters);

        $shouldAddCard = $request->has('card_unit_type_new');
        if ($shouldAddCard) {
            $maxCardNumber = 0;
            foreach ($cardFilters as $cardNumber => $values) {
                $maxCardNumber = max($maxCardNumber, (int) $cardNumber);
            }
            $cardFilters[$maxCardNumber + 1] = [];
        }

        $generated = $request->has('_generate')
            || !empty($cardFilters[1])
            || count(array_filter($cardFilters, function ($values) {
                return !empty($values);
            })) > 1;

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

        $trendUnitTypeIds = !empty($globalUnitTypeIds) ? $globalUnitTypeIds : $unitTypes->pluck('id')->all();

        $buildPeriodPaTrend = function ($mode) use ($siteId, $trendUnitTypeIds, $startDate, $endDate) {
            $periods = [];

            if ($mode === 'weekly') {
                $anchor = $endDate ? $endDate->copy() : Carbon::now();
                $weekStart = $anchor->copy()->startOfWeek();
                for ($i = 0; $i < 4; $i++) {
                    $periodStart = $weekStart->copy()->subWeeks(3 - $i);
                    $periodEnd = $periodStart->copy()->endOfWeek();
                    $periods[] = [
                        'label' => 'W' . $periodStart->weekOfYear,
                        'start' => $periodStart,
                        'end'   => $periodEnd,
                    ];
                }
            } else {
                $anchor = $endDate ? $endDate->copy() : Carbon::now();
                $monthStart = $anchor->copy()->startOfMonth();
                for ($i = 0; $i < 4; $i++) {
                    $periodStart = $monthStart->copy()->subMonths(3 - $i)->startOfMonth();
                    $periodEnd = $periodStart->copy()->endOfMonth();
                    $periods[] = [
                        'label' => $periodStart->translatedFormat('M'),
                        'start' => $periodStart,
                        'end'   => $periodEnd,
                    ];
                }
            }

            $series = [];
            $unitTypesForTrend = UnitType::whereIn('id', $trendUnitTypeIds)->get();

            foreach ($unitTypesForTrend as $unitType) {
                $points = [];

                foreach ($periods as $period) {
                    $units = MasterUnit::with(['type', 'model'])
                        ->when($siteId, function ($q) use ($siteId) {
                            return $q->where('site_id', $siteId);
                        })
                        ->where('unit_type_id', $unitType->id)
                        ->get();

                    $totalEwh = 0;
                    $totalBd  = 0;

                    foreach ($units as $unit) {
                        $hmAwalRecord = HourMeter::where('master_unit_id', $unit->id)
                            ->where('date', '<=', $period['start']->format('Y-m-d'))
                            ->orderBy('date', 'desc')
                            ->first();
                        $hmAwal = $hmAwalRecord ? (float) $hmAwalRecord->hm : 0;

                        $hmAkhirRecord = HourMeter::where('master_unit_id', $unit->id)
                            ->where('date', '<=', $period['end']->format('Y-m-d'))
                            ->orderBy('date', 'desc')
                            ->first();
                        $hmAkhir = $hmAkhirRecord ? (float) $hmAkhirRecord->hm : $hmAwal;

                        $wos = WorkOrder::where('master_unit_id', $unit->id)
                            ->where('opportunity', false)
                            ->where(function ($q) use ($period) {
                                $q->whereBetween('waktu_bd', [$period['start'], $period['end']])
                                  ->orWhereBetween('waktu_rfu', [$period['start'], $period['end']])
                                  ->orWhere(function ($q2) use ($period) {
                                      $q2->where('waktu_bd', '<', $period['start'])
                                         ->where(function ($q3) use ($period) {
                                             $q3->whereNull('waktu_rfu')
                                                ->orWhere('waktu_rfu', '>', $period['end']);
                                         });
                                  });
                            })
                            ->get();

                        $unitBdTotal = 0;
                        foreach ($wos as $wo) {
                            if (strtolower($wo->tipe_wo) == 'plan' && strtolower($wo->status_wo) != 'completed') continue;

                            $bdStart = $wo->waktu_bd ? Carbon::parse($wo->waktu_bd) : null;
                            $bdEnd   = $wo->waktu_rfu ? Carbon::parse($wo->waktu_rfu) : $period['end'];

                            if (!$bdStart) continue;

                            $overlapStart = max($bdStart->timestamp, $period['start']->timestamp);
                            $overlapEnd   = min($bdEnd->timestamp, $period['end']->timestamp);

                            if ($overlapEnd > $overlapStart) {
                                $unitBdTotal += ($overlapEnd - $overlapStart) / 3600;
                            }
                        }

                        $days = $period['start']->copy()->startOfDay()->diffInDays($period['end']->copy()->startOfDay()) + 1;
                        $ewh = $days * 24;

                        $totalEwh += $ewh;
                        $totalBd  += $unitBdTotal;
                    }

                    $pa = $totalEwh > 0 ? (($totalEwh - $totalBd) / $totalEwh) * 100 : 0;
                    $points[] = [
                        'label' => $period['label'],
                        'pa'    => round($pa, 1),
                    ];
                }

                $series[] = [
                    'name' => $unitType->name,
                    'points' => $points,
                ];
            }

            return $series;
        };

        $weeklyTrend = $buildPeriodPaTrend('weekly');
        $monthlyTrend = $buildPeriodPaTrend('monthly');

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

                // Work Orders (Eager load componentGroup and tasks/subtasks)
                $wos = WorkOrder::with(['tasks.componentGroup', 'tasks.subtasks.breakdownType'])
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

                        // 1 & 2. Get Breakdown Type and Component Group totals from Tasks/Subtasks
                        foreach ($wo->tasks as $task) {
                            $cgName = $task->componentGroup ? $task->componentGroup->name : null;
                            foreach ($task->subtasks as $subtask) {
                                $subHrs = (float) $subtask->duration_hours;
                                if ($subHrs > 0) {
                                    if ($subtask->breakdown_type_id && isset($bdTypeTotals[$subtask->breakdown_type_id])) {
                                        $bdTypeTotals[$subtask->breakdown_type_id]['total_jam'] += $subHrs;
                                    }
                                    if ($cgName) {
                                        if (!isset($compGroupTotals[$cgName])) {
                                            $compGroupTotals[$cgName] = 0;
                                        }
                                        $compGroupTotals[$cgName] += $subHrs;
                                    }
                                }
                            }
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
                $mtbf = $eventBd > 0 ? ($opHrs / $eventBd) : $opHrs;
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
            $totalSubtaskJamBdTypes = array_sum(array_column($bdTypeTotals, 'total_jam'));
            $chartBdTypes = [];
            foreach ($bdTypeTotals as $id => &$bdt) {
                $bdt['percentage'] = $totalSubtaskJamBdTypes > 0 ? ($bdt['total_jam'] / $totalSubtaskJamBdTypes) * 100 : 0;
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

        $cardConfigs = [];
        foreach ($cardFilters as $cardNumber => $selectedTypes) {
            $cardConfigs[] = [
                'num' => $cardNumber,
                'data' => !empty($selectedTypes) ? $processCard($selectedTypes) : null,
                'selectedTypes' => $selectedTypes,
            ];
        }

        return view('reports.breakdown', compact(
            'sites', 'unitTypes',
            'siteId', 'globalUnitTypeIds', 'currentDateRange',
            'generated',
            'cardConfigs',
            'weeklyTrend', 'monthlyTrend'
        ));
    }
}
