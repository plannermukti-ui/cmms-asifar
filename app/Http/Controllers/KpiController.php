<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MasterUnit;
use App\Models\UnitType;
use App\Models\UnitModel;
use App\Models\Site;
use App\Models\HourMeter;
use App\Models\WorkOrder;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\KpiMasterDataExport;

class KpiController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_kpi_master_data')->only(['masterData']);
        $this->middleware('permission:export_kpi')->only(['exportMasterData']);
    }

    public function masterData(Request $request)
    {
        $sites = Site::all();
        $unitTypes = UnitType::all();
        $unitModels = UnitModel::all();

        // Filters
        $siteId = $request->input('site_id');
        if (is_array($siteId) && count($siteId) === 1 && strpos($siteId[0], ',') !== false) $siteId = explode(',', $siteId[0]);
        if (is_array($siteId)) { $siteId = array_filter($siteId); if (empty($siteId)) $siteId = null; }
        
        $unitTypeId = $request->input('unit_type_id');
        if (is_array($unitTypeId) && count($unitTypeId) === 1 && strpos($unitTypeId[0], ',') !== false) $unitTypeId = explode(',', $unitTypeId[0]);
        if (is_array($unitTypeId)) { $unitTypeId = array_filter($unitTypeId); if (empty($unitTypeId)) $unitTypeId = null; }
        
        $unitModelId = $request->input('unit_model_id');
        if (is_array($unitModelId) && count($unitModelId) === 1 && strpos($unitModelId[0], ',') !== false) $unitModelId = explode(',', $unitModelId[0]);
        if (is_array($unitModelId)) { $unitModelId = array_filter($unitModelId); if (empty($unitModelId)) $unitModelId = null; }
        
        $dateRange = $request->input('date_range');

        // Parse dates
        $startDate = null;
        $endDate = null;
        if ($dateRange) {
            $dates = explode(' - ', $dateRange);
            if (count($dates) == 2) {
                try {
                    $startDate = Carbon::parse($dates[0])->startOfDay();
                    $endDate = Carbon::parse($dates[1])->endOfDay();
                } catch (\Exception $e) {
                    $startDate = Carbon::now()->startOfMonth();
                    $endDate = Carbon::now()->endOfDay();
                }
            }
        } else {
            $startDate = Carbon::now()->startOfMonth();
            $endDate = Carbon::now()->endOfDay();
        }

        // Calculate EWH (Expected Working Hours) based on days in range
        $ewh = 0;
        if ($startDate && $endDate) {
            $days = $startDate->copy()->startOfDay()->diffInDays($endDate->copy()->startOfDay()) + 1;
            $ewh = $days * 24;
        }

        $query = MasterUnit::with(['type', 'model'])
            ->when($siteId, function ($q) use ($siteId) {
                return is_array($siteId) ? $q->whereIn('site_id', $siteId) : $q->where('site_id', $siteId);
            })
            ->when($unitTypeId, function ($q) use ($unitTypeId) {
                return is_array($unitTypeId) ? $q->whereIn('unit_type_id', $unitTypeId) : $q->where('unit_type_id', $unitTypeId);
            })
            ->when($unitModelId, function ($q) use ($unitModelId) {
                return is_array($unitModelId) ? $q->whereIn('unit_model_id', $unitModelId) : $q->where('unit_model_id', $unitModelId);
            });
            
        // Use pagination for better performance on large datasets
        $units = $query->paginate(20)->withQueryString();

        // Calculate KPI for each unit
        $units->getCollection()->transform(function ($unit) use ($startDate, $endDate, $ewh) {
            // HM Awal (Closest to or at start date)
            $hmAwalRecord = HourMeter::where('master_unit_id', $unit->id)
                ->where('date', '<=', $startDate->format('Y-m-d'))
                ->orderBy('date', 'desc')
                ->first();
                
            $hmAwal = $hmAwalRecord ? (float) $hmAwalRecord->hm : 0;

            // HM Akhir
            $hmAkhirRecord = HourMeter::where('master_unit_id', $unit->id)
                ->where('date', '<=', $endDate->format('Y-m-d'))
                ->orderBy('date', 'desc')
                ->first();
                
            $hmAkhir = $hmAkhirRecord ? (float) $hmAkhirRecord->hm : $hmAwal;

            // Op Hrs
            $opHrs = $hmAkhir - $hmAwal;
            if ($opHrs < 0) $opHrs = 0;

            // Breakdown logic
            // Get all WO that intersect with the date range, opportunity = false
            $wos = WorkOrder::where('master_unit_id', $unit->id)
                ->where('opportunity', false)
                ->where(function($q) use ($startDate, $endDate) {
                    $q->whereBetween('waktu_bd', [$startDate, $endDate])
                      ->orWhereBetween('waktu_rfu', [$startDate, $endDate])
                      ->orWhere(function($q2) use ($startDate, $endDate) {
                          $q2->where('waktu_bd', '<', $startDate)
                             ->where(function($q3) use ($endDate) {
                                 $q3->whereNull('waktu_rfu')
                                    ->orWhere('waktu_rfu', '>', $endDate);
                             });
                      });
                })
                ->get();

            $eventBd = 0;
            $bdHrsTotal = 0;

            foreach ($wos as $wo) {
                // Jika Tipe WO Plan hanya di hitung yang sudah ber Status WO Completed saja
                if (strtolower($wo->tipe_wo) == 'plan' && strtolower($wo->status_wo) != 'completed') {
                    continue;
                }
                
                $eventBd++;
                
                // Calculate overlapping duration
                $bdStart = $wo->waktu_bd ? Carbon::parse($wo->waktu_bd) : null;
                
                // Resolve 'jam sekarang' to match DB timezone context (UTC+8 stored as UTC)
                $jamSekarang = Carbon::parse(Carbon::now('Asia/Makassar')->format('Y-m-d H:i:s'));
                
                if ($wo->waktu_rfu) {
                    $bdEnd = Carbon::parse($wo->waktu_rfu);
                } else {
                    // Jika RFU kosong, gunakan jam sekarang. Nanti akan dilimit oleh $endDate di min()
                    $bdEnd = $jamSekarang;
                }
                
                if (!$bdStart) continue;

                $overlapStart = max($bdStart->timestamp, $startDate->timestamp);
                $overlapEnd = min($bdEnd->timestamp, $endDate->timestamp);
                
                if ($overlapEnd > $overlapStart) {
                    $bdHrsTotal += ($overlapEnd - $overlapStart) / 3600; // in hours
                }
            }

            $stb = $ewh - $bdHrsTotal - $opHrs;
            if ($stb < 0) $stb = 0;

            $pa = $ewh > 0 ? (($ewh - $bdHrsTotal) / $ewh) * 100 : 0;
            $ma = ($opHrs + $bdHrsTotal) > 0 ? ($opHrs / ($opHrs + $bdHrsTotal)) * 100 : 0;
            $mtbf = $eventBd > 0 ? ($opHrs / $eventBd) : $opHrs;
            $mttr = $eventBd > 0 ? $bdHrsTotal / $eventBd : 0;
            $ua = ($ewh - $bdHrsTotal) > 0 ? ($opHrs / ($ewh - $bdHrsTotal)) * 100 : 0;
            $eu = $ewh > 0 ? ($opHrs / $ewh) * 100 : 0;

            // Attach to unit object
            $unit->hm_awal = $hmAwal;
            $unit->hm_akhir = $hmAkhir;
            $unit->op_hrs = $opHrs;
            $unit->ewh = $ewh;
            $unit->event_bd = $eventBd;
            $unit->bd_hrs = $bdHrsTotal;
            $unit->stb = $stb;
            $unit->pa = $pa;
            $unit->ma = $ma;
            $unit->mtbf = $mtbf;
            $unit->mttr = $mttr;
            $unit->ua = $ua;
            $unit->eu = $eu;

            return $unit;
        });

        // For date picker value
        $currentDateRange = $dateRange ?: ($startDate->format('Y-m-d') . ' - ' . $endDate->format('Y-m-d'));

        return view('kpi.master-data', compact(
            'units', 'sites', 'unitTypes', 'unitModels',
            'siteId', 'unitTypeId', 'unitModelId', 'currentDateRange'
        ));
    }

    public function exportMasterData(Request $request)
    {
        $siteId = $request->input('site_id');
        if (is_array($siteId) && count($siteId) === 1 && strpos($siteId[0], ',') !== false) $siteId = explode(',', $siteId[0]);
        if (is_array($siteId)) { $siteId = array_filter($siteId); if (empty($siteId)) $siteId = null; }
        
        $unitTypeId = $request->input('unit_type_id');
        if (is_array($unitTypeId) && count($unitTypeId) === 1 && strpos($unitTypeId[0], ',') !== false) $unitTypeId = explode(',', $unitTypeId[0]);
        if (is_array($unitTypeId)) { $unitTypeId = array_filter($unitTypeId); if (empty($unitTypeId)) $unitTypeId = null; }
        
        $unitModelId = $request->input('unit_model_id');
        if (is_array($unitModelId) && count($unitModelId) === 1 && strpos($unitModelId[0], ',') !== false) $unitModelId = explode(',', $unitModelId[0]);
        if (is_array($unitModelId)) { $unitModelId = array_filter($unitModelId); if (empty($unitModelId)) $unitModelId = null; }
        
        $dateRange = $request->input('date_range');

        $startDate = null;
        $endDate = null;
        if ($dateRange) {
            $dates = explode(' - ', $dateRange);
            if (count($dates) == 2) {
                try {
                    $startDate = Carbon::parse($dates[0])->startOfDay();
                    $endDate = Carbon::parse($dates[1])->endOfDay();
                } catch (\Exception $e) {
                    $startDate = Carbon::now()->startOfMonth();
                    $endDate = Carbon::now()->endOfDay();
                }
            }
        } else {
            $startDate = Carbon::now()->startOfMonth();
            $endDate = Carbon::now()->endOfDay();
        }

        $ewh = 0;
        if ($startDate && $endDate) {
            $days = $startDate->copy()->startOfDay()->diffInDays($endDate->copy()->startOfDay()) + 1;
            $ewh = $days * 24;
        }

        $query = MasterUnit::with(['type', 'model'])
            ->when($siteId, function ($q) use ($siteId) {
                return is_array($siteId) ? $q->whereIn('site_id', $siteId) : $q->where('site_id', $siteId);
            })
            ->when($unitTypeId, function ($q) use ($unitTypeId) {
                return is_array($unitTypeId) ? $q->whereIn('unit_type_id', $unitTypeId) : $q->where('unit_type_id', $unitTypeId);
            })
            ->when($unitModelId, function ($q) use ($unitModelId) {
                return is_array($unitModelId) ? $q->whereIn('unit_model_id', $unitModelId) : $q->where('unit_model_id', $unitModelId);
            });
            
        $units = $query->get();

        $units->transform(function ($unit) use ($startDate, $endDate, $ewh) {
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

            $opHrs = $hmAkhir - $hmAwal;
            if ($opHrs < 0) $opHrs = 0;

            $wos = WorkOrder::where('master_unit_id', $unit->id)
                ->where('opportunity', false)
                ->where(function($q) use ($startDate, $endDate) {
                    $q->whereBetween('waktu_bd', [$startDate, $endDate])
                      ->orWhereBetween('waktu_rfu', [$startDate, $endDate])
                      ->orWhere(function($q2) use ($startDate, $endDate) {
                          $q2->where('waktu_bd', '<', $startDate)
                             ->where(function($q3) use ($endDate) {
                                 $q3->whereNull('waktu_rfu')
                                    ->orWhere('waktu_rfu', '>', $endDate);
                             });
                      });
                })
                ->get();

            $eventBd = 0;
            $bdHrsTotal = 0;
            $jamSekarang = Carbon::parse(Carbon::now('Asia/Makassar')->format('Y-m-d H:i:s'));

            foreach ($wos as $wo) {
                if (strtolower($wo->tipe_wo) == 'plan' && strtolower($wo->status_wo) != 'completed') continue;
                $eventBd++;
                $bdStart = $wo->waktu_bd ? Carbon::parse($wo->waktu_bd) : null;
                $bdEnd = $wo->waktu_rfu ? Carbon::parse($wo->waktu_rfu) : $jamSekarang;
                
                if (!$bdStart) continue;

                $overlapStart = max($bdStart->timestamp, $startDate->timestamp);
                $overlapEnd = min($bdEnd->timestamp, $endDate->timestamp);
                
                if ($overlapEnd > $overlapStart) {
                    $bdHrsTotal += ($overlapEnd - $overlapStart) / 3600;
                }
            }

            $stb = $ewh - $bdHrsTotal - $opHrs;
            if ($stb < 0) $stb = 0;

            $pa = $ewh > 0 ? (($ewh - $bdHrsTotal) / $ewh) * 100 : 0;
            $ma = ($opHrs + $bdHrsTotal) > 0 ? ($opHrs / ($opHrs + $bdHrsTotal)) * 100 : 0;
            $mtbf = $eventBd > 0 ? ($opHrs / $eventBd) : $opHrs;
            $mttr = $eventBd > 0 ? $bdHrsTotal / $eventBd : 0;
            $ua = ($ewh - $bdHrsTotal) > 0 ? ($opHrs / ($ewh - $bdHrsTotal)) * 100 : 0;
            $eu = $ewh > 0 ? ($opHrs / $ewh) * 100 : 0;

            $unit->hm_awal = $hmAwal;
            $unit->hm_akhir = $hmAkhir;
            $unit->op_hrs = $opHrs;
            $unit->ewh = $ewh;
            $unit->event_bd = $eventBd;
            $unit->bd_hrs = $bdHrsTotal;
            $unit->stb = $stb;
            $unit->pa = $pa;
            $unit->ma = $ma;
            $unit->mtbf = $mtbf;
            $unit->mttr = $mttr;
            $unit->ua = $ua;
            $unit->eu = $eu;

            return $unit;
        });

        return Excel::download(new KpiMasterDataExport($units, $startDate, $endDate), 'KPI_Master_Data_' . date('Ymd_His') . '.xlsx');
    }
}
