<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MasterUnit;
use App\Models\UnitType;
use App\Models\UnitModel;
use App\Models\BreakdownType;
use App\Models\ComponentGroup;
use App\Models\WoCategory;
use App\Models\Part;
use Illuminate\Http\Request;

class WorkOrderApiController extends Controller
{
    /**
     * Get distinct unit types based on site filter from master_units.
     * GET /api/wo/unit-types?site=XX
     */
    public function unitTypes(Request $request)
    {
        $site = $request->get('site_id');
        $typeIds = MasterUnit::where('site_id', $site)->distinct()->pluck('unit_type_id')->filter();
        $types = UnitType::whereIn('id', $typeIds)->orderBy('name')->get(['id', 'name']);
        return response()->json($types);
    }

    /**
     * Get units filtered by site and unit_type_id.
     * GET /api/wo/units?site=XX&unit_type_id=YY
     */
    public function units(Request $request)
    {
        $query = MasterUnit::query();
        if ($request->filled('site_id')) {
            $query->where('site_id', $request->site_id);
        }
        if ($request->filled('unit_type_id')) {
            $query->where('unit_type_id', $request->unit_type_id);
        }
        $units = $query->orderBy('nomor_unit')->get(['id', 'nomor_unit']);
        return response()->json($units);
    }

    /**
     * Get unit detail (model name) for auto-fill.
     * GET /api/wo/unit-detail?unit_id=ZZ
     */
    public function unitDetail(Request $request)
    {
        $unit = MasterUnit::with(['type', 'model'])->find($request->unit_id);
        if (!$unit) {
            return response()->json(['error' => 'Unit not found'], 404);
        }
        return response()->json([
            'id' => $unit->id,
            'nomor_unit' => $unit->nomor_unit,
            'type_name' => $unit->type->name ?? '-',
            'model_name' => $unit->model->name ?? '-',
            'site' => $unit->site_id,
        ]);
    }

    /**
     * Inline add for lookup tables (breakdown_types, component_groups, wo_categories, parts).
     * POST /api/wo/inline-add
     * Body: { table: 'breakdown_types', name: 'xxx', level: 1 (for wo_categories) }
     */
    public function inlineAdd(Request $request)
    {
        $request->validate([
            'table' => 'required|in:breakdown_types,component_groups,wo_categories,parts',
            'name' => 'required|string|max:255',
        ]);

        $table = $request->table;

        switch ($table) {
            case 'breakdown_types':
                $item = BreakdownType::firstOrCreate(
                    ['name' => $request->name],
                    ['code' => $request->code ?? null]
                );
                return response()->json(['id' => $item->id, 'name' => $item->name, 'code' => $item->code]);

            case 'component_groups':
                $item = ComponentGroup::firstOrCreate(['name' => $request->name]);
                return response()->json(['id' => $item->id, 'name' => $item->name]);

            case 'wo_categories':
                $request->validate(['level' => 'required|integer|min:1|max:5']);
                $item = WoCategory::firstOrCreate([
                    'level' => $request->level,
                    'name' => $request->name,
                ]);
                return response()->json(['id' => $item->id, 'name' => $item->name, 'level' => $item->level]);

            case 'parts':
                $request->validate([
                    'part_number' => 'required|string|max:100',
                    'part_description' => 'required|string|max:255',
                ]);
                $item = Part::firstOrCreate(
                    ['part_number' => $request->part_number],
                    [
                        'part_description' => $request->part_description,
                        'satuan' => $request->satuan ?? null,
                        'cost' => $request->cost ?? 0,
                    ]
                );
                return response()->json([
                    'id' => $item->id,
                    'part_number' => $item->part_number,
                    'part_description' => $item->part_description,
                ]);
        }

        return response()->json(['error' => 'Invalid table'], 400);
    }

    /**
     * Update status_wo of a WorkOrder (for Kanban drag & drop).
     * POST /api/wo/update-status
     */
    public function updateStatus(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:work_orders,id',
            'status_wo' => 'required|in:Open,Inprogress,Completed,Cancel,Backlog',
            'waktu_rfu' => 'nullable|date',
        ]);

        $wo = \App\Models\WorkOrder::with(['tasks.subtasks'])->findOrFail($request->id);

        if ($request->filled('waktu_rfu')) {
            $wo->waktu_rfu = $request->waktu_rfu;
        }

        if ($request->status_wo === 'Completed') {
            if (!$wo->waktu_rfu) {
                return response()->json([
                    'success' => false,
                    'requires_rfu' => true,
                    'message' => 'Waktu RFU wajib diisi sebelum mengubah status Work Order ' . $wo->no_wo . ' menjadi Completed.',
                    'edit_url' => route('work-orders.edit', $wo->id),
                ], 422);
            }
            
            // Cascade status update to all child tasks and subtasks
            foreach ($wo->tasks as $task) {
                $task->status = 'Completed';
                $task->save();
                
                foreach ($task->subtasks as $subtask) {
                    $subtask->status = 'Completed';
                    $subtask->save();
                }
            }
        }

        $wo->status_wo = $request->status_wo;
        $wo->save();

        // If this is a PM Plan WO and it is completed, update the PM Schedule
        if ($wo->status_wo === 'Completed' && $wo->pm_schedule_id) {
            $schedule = \App\Models\PmSchedule::with('pmTemplate')->find($wo->pm_schedule_id);
            if ($schedule && $schedule->pmTemplate) {
                $schedule->last_executed_value = $wo->hours_meter ?? $schedule->next_due_value;
                $schedule->next_due_value = $schedule->next_due_value + $schedule->pmTemplate->interval_value;
                $schedule->status_jadwal = 'Upcoming';
                $schedule->save();
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Status Work Order ' . $wo->no_wo . ' beserta seluruh Task & SubTask berhasil diubah ke ' . $wo->status_wo,
            'status_wo' => $wo->status_wo,
            'waktu_rfu' => $wo->waktu_rfu ? $wo->waktu_rfu->format('d/m/Y H:i') : null,
            'edit_url' => route('work-orders.edit', $wo->id),
            'is_completed' => $request->status_wo === 'Completed',
        ]);
    }
}
