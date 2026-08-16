<?php

namespace App\Http\Controllers;

use App\Models\MasterUnit;
use App\Models\UnitType;
use App\Models\UnitModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MasterUnitController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_master_units')->only(['index', 'show']);
        $this->middleware('permission:create_master_units')->only(['create', 'store']);
        $this->middleware('permission:edit_master_units')->only(['edit', 'update']);
        $this->middleware('permission:delete_master_units')->only(['destroy']);
    }

    public function index(Request $request)
    {
        $query = MasterUnit::withoutGlobalScope('active')->with(['type', 'model']);

        if ($request->has('search')) {
            $search = $request->search;
            $query->where('nomor_unit', 'like', "%{$search}%")
                  ->orWhere('no_polisi', 'like', "%{$search}%")
                  ->orWhere('sn_chassis', 'like', "%{$search}%");
        }

        $masterUnits = $query->orderBy('nomor_unit')->paginate(15);
        $unitTypes = UnitType::orderBy('name')->get();
        $unitModels = UnitModel::orderBy('name')->get();
        $sites = \App\Models\Site::orderBy('name')->get();
        return view('master-units.index', compact('masterUnits', 'unitTypes', 'unitModels', 'sites'));
    }

    public function create()
    {
        $unitTypes = UnitType::orderBy('name')->get();
        $unitModels = UnitModel::orderBy('name')->get();
        $sites = \App\Models\Site::orderBy('name')->get();
        return view('master-units.create', compact('unitTypes', 'unitModels', 'sites'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nomor_unit' => 'required|string|max:20|unique:master_units',
            'unit_type_id' => 'required|exists:unit_types,id',
            'unit_model_id' => 'nullable|exists:unit_models,id',
            'site_id' => 'nullable|exists:sites,id',
            // other fields are nullable strings
        ]);

        MasterUnit::create($request->all());
        return redirect()->route('master-units.index')->with('success', 'Master Unit berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $masterUnit = MasterUnit::withoutGlobalScope('active')->findOrFail($id);
        $unitTypes = UnitType::orderBy('name')->get();
        $unitModels = UnitModel::orderBy('name')->get();
        $sites = \App\Models\Site::orderBy('name')->get();
        return view('master-units.edit', compact('masterUnit', 'unitTypes', 'unitModels', 'sites'));
    }

    public function update(Request $request, $id)
    {
        $masterUnit = MasterUnit::withoutGlobalScope('active')->findOrFail($id);
        
        $request->validate([
            'nomor_unit' => 'required|string|max:20|unique:master_units,nomor_unit,'.$masterUnit->id,
            'unit_type_id' => 'required|exists:unit_types,id',
            'unit_model_id' => 'nullable|exists:unit_models,id',
            'site_id' => 'nullable|exists:sites,id',
        ]);

        $masterUnit->update($request->all());
        return redirect()->route('master-units.index')->with('success', 'Master Unit berhasil diperbarui.');
    }

    public function show($id)
    {
        $masterUnit = MasterUnit::withoutGlobalScope('active')
            ->with(['type', 'model', 'siteRelation'])
            ->findOrFail($id);

        // 1. Work Orders History
        $workOrders = \App\Models\WorkOrder::where('master_unit_id', $id)
            ->with(['creator', 'tasks.subtasks.parts.part', 'jwos'])
            ->latest('waktu_bd')
            ->get();

        // Biaya maintenance = biaya JWO + pemakaian part (qty x harga master part).
        $woIds = $workOrders->pluck('id');
        $totalPartCost = DB::table('wo_subtask_parts')
            ->join('wo_subtasks', 'wo_subtask_parts.wo_subtask_id', '=', 'wo_subtasks.id')
            ->join('wo_tasks', 'wo_subtasks.wo_task_id', '=', 'wo_tasks.id')
            ->join('parts', 'wo_subtask_parts.part_id', '=', 'parts.id')
            ->whereIn('wo_tasks.work_order_id', $woIds)
            ->where(function($q) {
                $q->where('wo_subtask_parts.part_status', 'Replace')
                  ->orWhereNull('wo_subtask_parts.part_status')
                  ->orWhere('wo_subtask_parts.part_status', '');
            })
            ->sum(DB::raw('COALESCE(wo_subtask_parts.qty, 0) * COALESCE(parts.cost, 0)'));

        $totalJwoCost = \App\Models\Jwo::whereIn('work_order_id', $woIds)->sum('cost')
            + \App\Models\Jwo::where('unit_id', $id)->whereNull('work_order_id')->sum('cost');

        $totalWoCost = (float) $totalPartCost + (float) $totalJwoCost;

        $workOrders->each(function ($workOrder) {
            $workOrder->part_cost = $workOrder->tasks->sum(function ($task) {
                return $task->subtasks->sum(function ($subtask) {
                    return $subtask->parts->filter(function ($usedPart) {
                        return in_array($usedPart->part_status, ['Replace', null, '']);
                    })->sum(function ($usedPart) {
                        return (float) $usedPart->qty * (float) ($usedPart->part->cost ?? 0);
                    });
                });
            });
            $workOrder->maintenance_cost = (float) $workOrder->part_cost + (float) $workOrder->jwos->sum('cost');
        });

        $totalPlannedCost = \App\Models\PlanBudgetUnit::where('master_unit_id', $id)->sum('planned_cost');

        // 2. Production History (Digger, Hauler, Support)
        $diggerFleets = \App\Models\ProductionFleet::where('digger_id', $id)
            ->with(['production', 'haulers'])
            ->latest()
            ->get();

        $haulerRecords = \App\Models\ProductionHauler::where('hauler_id', $id)
            ->with(['fleet.production', 'fleet.digger'])
            ->latest()
            ->get();

        $supportRecords = \App\Models\ProductionSupport::where('support_id', $id)
            ->with(['production'])
            ->latest()
            ->get();

        // 3. FAR & JWO History
        $fars = \App\Models\Far::where('master_unit_id', $id)->latest()->get();
        $jwos = \App\Models\Jwo::where('unit_id', $id)->latest()->get();

        // 4. PM Schedules & Pra WO & Hour Meter
        $pmSchedules = \App\Models\PmSchedule::where('master_unit_id', $id)->latest()->get();
        $praWorkOrders = \App\Models\PraWorkOrder::where('master_unit_id', $id)->latest()->get();
        $hourMeters = \App\Models\HourMeter::where('master_unit_id', $id)->latest()->get();

        // 5. Activity Log (Audit Trail)
        $activityLogs = \Spatie\Activitylog\Models\Activity::forSubject($masterUnit)
            ->latest()
            ->limit(20)
            ->get();

        return view('master-units.show', compact(
            'masterUnit',
            'workOrders',
            'totalWoCost',
            'totalPlannedCost',
            'diggerFleets',
            'haulerRecords',
            'supportRecords',
            'fars',
            'jwos',
            'pmSchedules',
            'praWorkOrders',
            'hourMeters',
            'activityLogs'
        ));
    }

    public function destroy($id)
    {
        $masterUnit = MasterUnit::withoutGlobalScope('active')->findOrFail($id);
        $masterUnit->delete();
        return redirect()->route('master-units.index')->with('success', 'Master Unit berhasil dihapus.');
    }
}
