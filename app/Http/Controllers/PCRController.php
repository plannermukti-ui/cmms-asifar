<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Part;
use App\Models\MasterUnit;
use App\Models\PmTemplate;
use App\Models\WoSubtaskPart;

class PCRController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_pcr')->only(['index']);
        $this->middleware('permission:edit_pcr')->only(['updateManual']);
    }

    public function index(Request $request)
    {
        // Get all units that have a model which has parts
        $units = MasterUnit::with(['model.parts', 'latestHourMeter'])
            ->whereHas('model.parts')
            ->orderBy('nomor_unit')
            ->get();
            
        $unitIds = $units->pluck('id');
        $partIds = Part::has('unitModels')->pluck('id');

        // Fetch all relevant last changes in one query
        $allLastChanges = WoSubtaskPart::whereIn('part_id', $partIds)
            ->whereIn('part_status', ['Replace', 'Swap / Canibal'])
            ->whereHas('subtask.task.workOrder', function ($q) use ($unitIds) {
                $q->whereIn('master_unit_id', $unitIds);
            })
            ->with(['subtask.task.workOrder'])
            ->orderBy('id', 'desc')
            ->get()
            ->groupBy(function($item) {
                // Group by part_id and master_unit_id
                $unitId = optional(optional(optional($item->subtask)->task)->workOrder)->master_unit_id;
                return $item->part_id . '_' . $unitId;
            });

        $pcrData = collect();
        $no = 1;

        foreach ($units as $unit) {
            $model = $unit->model;
            if (!$model || $model->parts->isEmpty()) continue;

            $pmTemplate = PmTemplate::where('unit_model_id', $model->id)->first();
            $oprHrsPerDay = $pmTemplate ? $pmTemplate->opr_hrs_per_day : null;
            $defaultOprHrs = $oprHrsPerDay ?? 24; 

            $currentHm = $unit->latestHourMeter ? $unit->latestHourMeter->hm : 0;
            
            $components = collect();

            foreach ($model->parts as $part) {
                $targetLife = $part->target ?? 0;
                
                $key = $part->id . '_' . $unit->id;
                $lastChange = isset($allLastChanges[$key]) ? $allLastChanges[$key]->first() : null;

                $lastDate = $lastChange ? optional($lastChange->subtask->task->workOrder)->waktu_rfu : null;
                $lastHm = $lastChange ? (optional($lastChange->subtask->task->workOrder)->hours_meter ?? 0) : 0;
                $brandPart = $lastChange ? $lastChange->mol_pr : '-'; 
                $remarksPart = $lastChange ? $lastChange->swap_remarks : '-';

                $currentLife = $currentHm - $lastHm;
                $planSmu = $lastHm + $targetLife;
                $remain = $planSmu - $currentHm;
                
                $daysRemain = $defaultOprHrs > 0 ? ($remain / $defaultOprHrs) : 0;
                $datePlan = \Carbon\Carbon::now()->addDays($daysRemain);

                $components->push([
                    'wo_subtask_part_id' => $lastChange ? $lastChange->id : null,
                    'component' => $part->part_description,
                    'target_life' => $targetLife,
                    'current_life' => $currentLife,
                    'date_plan' => $datePlan,
                    'plan_smu' => $planSmu,
                    'remain' => $remain,
                    'last_date' => $lastDate,
                    'last_hm' => $lastHm,
                    'brand_part' => $brandPart,
                    'remarks_part' => $remarksPart,
                ]);
            }

            // Sort components by name? Or just leave as is.
            $pcrData->push([
                'no' => $no++,
                'unit_no' => $unit->nomor_unit,
                'model' => $model->name,
                'current_hm' => $currentHm,
                'opr_warning' => is_null($oprHrsPerDay),
                'components' => $components
            ]);
        }

        $filterUnits = $units->pluck('nomor_unit')->unique()->sort();
        $filterModels = $units->map->model->pluck('name')->unique()->sort();
        $filterComponents = Part::has('unitModels')->pluck('part_description')->unique()->sort();

        // Filters
        if ($request->filled('unit_no')) {
            $pcrData = $pcrData->filter(fn($item) => $item['unit_no'] == $request->unit_no);
        }
        if ($request->filled('model')) {
            $pcrData = $pcrData->filter(fn($item) => $item['model'] == $request->model);
        }
        if ($request->filled('component')) {
            $pcrData = $pcrData->map(function ($item) use ($request) {
                $item['components'] = $item['components']->filter(fn($c) => $c['component'] == $request->component);
                return $item;
            })->filter(fn($item) => $item['components']->isNotEmpty());
        }
        if ($request->filled('date_start') || $request->filled('date_end')) {
            $start = $request->filled('date_start') ? \Carbon\Carbon::parse($request->date_start)->startOfDay() : \Carbon\Carbon::minValue();
            $end = $request->filled('date_end') ? \Carbon\Carbon::parse($request->date_end)->endOfDay() : \Carbon\Carbon::maxValue();
            
            $pcrData = $pcrData->map(function ($item) use ($start, $end) {
                $item['components'] = $item['components']->filter(function($c) use ($start, $end) {
                    if (!$c['date_plan']) return false;
                    return $c['date_plan']->between($start, $end);
                });
                return $item;
            })->filter(fn($item) => $item['components']->isNotEmpty());
        }

        // Sorting
        $sort = $request->get('sort', 'no');
        $dir = $request->get('direction', 'asc');
        
        // Disable sorting on component level fields if grouping is used, or sort the parent collection
        if ($dir === 'asc') {
            $pcrData = $pcrData->sortBy($sort);
        } else {
            $pcrData = $pcrData->sortByDesc($sort);
        }

        $currentSort = $sort;
        $currentDir = $dir;
        $nextDir = $dir === 'asc' ? 'desc' : 'asc';

        return view('plan_strategy.pcr.index', compact('pcrData', 'currentSort', 'currentDir', 'nextDir', 'filterUnits', 'filterModels', 'filterComponents'));
    }

    public function updateManual(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:wo_subtask_parts,id',
            'brand_part' => 'nullable|string',
            'remarks_part' => 'nullable|string',
            'field' => 'nullable|in:brand_part,remarks_part',
            'value' => 'nullable|string'
        ]);

        $part = WoSubtaskPart::findOrFail($request->id);
        
        if ($request->filled('field')) {
            if ($request->field === 'brand_part') {
                $part->mol_pr = $request->value;
            } else {
                $part->swap_remarks = $request->value;
            }
        } else {
            $part->mol_pr = $request->brand_part;
            $part->swap_remarks = $request->remarks_part;
        }

        $part->save();

        return response()->json(['success' => true]);
    }
}
