<?php

namespace App\Http\Controllers;

use App\Models\WorkOrder;
use App\Models\WoTask;
use App\Models\WoSubtask;
use App\Models\WoSubtaskManpower;
use App\Models\WoSubtaskPart;
use App\Models\WoSubtaskTool;
use App\Models\MasterUnit;
use App\Models\UnitType;
use App\Models\UnitModel;
use App\Models\BreakdownType;
use App\Models\ComponentGroup;
use App\Models\WoCategory;
use App\Models\Part;
use App\Models\Mechanic;
use App\Models\ToolTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\WorkOrderExport;
use App\Services\WorkOrderDateValidationService;
use App\Services\WorkOrderDurationService;
use Illuminate\Support\Facades\Validator;

class WorkOrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_work_orders')->only(['index', 'show']);
        $this->middleware('permission:create_work_orders|create_work_orders_kanban')->only(['create', 'store']);
        $this->middleware('permission:edit_work_orders|edit_work_orders_kanban')->only(['edit', 'update']);
        $this->middleware('permission:delete_work_orders')->only(['destroy']);
        $this->middleware('permission:view_work_orders_kanban')->only(['kanban']);
    }

    public function index(Request $request)
    {
        $query = WorkOrder::with(['unit.type', 'unit.model', 'creator']);

        if ($request->filled('status_wo')) {
            $status = (array) $request->status_wo;
            if (count($status) === 1 && strpos($status[0], ',') !== false) $status = explode(',', $status[0]);
            $status = array_filter($status);
            if (!empty($status)) $query->whereIn('status_wo', $status);
        }
        if ($request->filled('tipe_wo')) {
            $tipe = (array) $request->tipe_wo;
            if (count($tipe) === 1 && strpos($tipe[0], ',') !== false) $tipe = explode(',', $tipe[0]);
            $tipe = array_filter($tipe);
            if (!empty($tipe)) $query->whereIn('tipe_wo', $tipe);
        }
        if ($request->filled('site_id')) {
            $site = (array) $request->site_id;
            if (count($site) === 1 && strpos($site[0], ',') !== false) $site = explode(',', $site[0]);
            $site = array_filter($site);
            if (!empty($site)) $query->whereHas('unit', fn($q) => $q->whereIn('site_id', $site));
        }
        if ($request->filled('date_from')) {
            $query->where('waktu_bd', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('waktu_bd', '<=', $request->date_to . ' 23:59:59');
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('no_wo', 'like', "%{$search}%")
                  ->orWhereHas('unit', fn($uq) => $uq->where('nomor_unit', 'like', "%{$search}%"));
            });
        }

        $workOrders = $query->orderBy('created_at', 'desc')->paginate(15);
        
        // Data for Create Modal
        $data = $this->getFormData();
        $data['no_wo'] = WorkOrder::generateNoWo();
        $data['workOrders'] = $workOrders;
        $data['sites'] = \App\Models\Site::orderBy('name')->get();

        return view('work_orders.index', $data);
    }

    public function export(Request $request)
    {
        $query = WorkOrder::with([
            'unit.type', 
            'unit.model', 
            'creator',
            'tasks',
            'category1',
            'category2',
            'category3',
            'category4',
            'category5'
        ]);

        if ($request->filled('status_wo')) {
            $status = (array) $request->status_wo;
            if (count($status) === 1 && strpos($status[0], ',') !== false) $status = explode(',', $status[0]);
            $status = array_filter($status);
            if (!empty($status)) $query->whereIn('status_wo', $status);
        }
        if ($request->filled('tipe_wo')) {
            $tipe = (array) $request->tipe_wo;
            if (count($tipe) === 1 && strpos($tipe[0], ',') !== false) $tipe = explode(',', $tipe[0]);
            $tipe = array_filter($tipe);
            if (!empty($tipe)) $query->whereIn('tipe_wo', $tipe);
        }
        if ($request->filled('site_id')) {
            $site = (array) $request->site_id;
            if (count($site) === 1 && strpos($site[0], ',') !== false) $site = explode(',', $site[0]);
            $site = array_filter($site);
            if (!empty($site)) $query->whereHas('unit', fn($q) => $q->whereIn('site_id', $site));
        }
        if ($request->filled('date_from')) {
            $query->where('waktu_bd', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('waktu_bd', '<=', $request->date_to . ' 23:59:59');
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('no_wo', 'like', "%{$search}%")
                  ->orWhereHas('unit', fn($uq) => $uq->where('nomor_unit', 'like', "%{$search}%"));
            });
        }

        $workOrders = $query->orderBy('created_at', 'desc')->get();
        return Excel::download(new WorkOrderExport($workOrders), 'Work_Orders_' . date('Ymd_His') . '.xlsx');
    }

    public function exportDmbd(Request $request)
    {
        $query = WorkOrder::with([
            'unit.type', 
            'unit.model', 
            'creator',
            'tasks.subtasks.parts'
        ]);

        if ($request->filled('status_wo')) {
            $status = (array) $request->status_wo;
            if (count($status) === 1 && strpos($status[0], ',') !== false) $status = explode(',', $status[0]);
            $status = array_filter($status);
            if (!empty($status)) $query->whereIn('status_wo', $status);
        }
        if ($request->filled('tipe_wo')) {
            $tipe = (array) $request->tipe_wo;
            if (count($tipe) === 1 && strpos($tipe[0], ',') !== false) $tipe = explode(',', $tipe[0]);
            $tipe = array_filter($tipe);
            if (!empty($tipe)) $query->whereIn('tipe_wo', $tipe);
        }

        $workOrders = $query->orderBy('waktu_bd', 'desc')->get();
        
        $fileName = 'DMBD ' . \Carbon\Carbon::now()->isoFormat('D MMMM Y') . ' (HW).xlsx';
        return Excel::download(new \App\Exports\DmbdExport($workOrders), $fileName);
    }

    public function create(Request $request)
    {
        $data = $this->getFormData();
        $data['no_wo'] = WorkOrder::generateNoWo();
        
        $praWorkOrder = null;
        if ($request->has('pra_work_order_id')) {
            $praWorkOrder = \App\Models\PraWorkOrder::with('masterUnit')->find($request->pra_work_order_id);
        }
        $data['praWorkOrder'] = $praWorkOrder;

        return view('work_orders.create', $data);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'status_wo' => 'required|in:Open,Inprogress,Completed,Cancel,Backlog',
            'tipe_wo' => 'required|in:BD,Plan',
            'downtime_code' => 'required|in:Schedule,Unschedule,Accident',
            'master_unit_id' => 'required|exists:master_units,id',
            'hours_meter' => 'nullable|numeric',
            'lokasi_kerusakan' => 'nullable|string',
            'waktu_bd' => 'nullable|date',
            'waktu_rfu' => 'required_if:status_wo,Completed|nullable|date|after_or_equal:waktu_bd',
            'pra_work_order_id' => 'nullable|exists:pra_work_orders,id',
        ]);

        $validator->after(function ($validator) use ($request) {
            if ($request->filled('waktu_rfu') && $request->status_wo !== 'Completed') {
                $validator->errors()->add('waktu_rfu', 'Perhatian: Waktu RFU hanya dapat diisi jika Status Work Order diubah menjadi Completed.');
            }

            $dateErrors = app(WorkOrderDateValidationService::class)->validate($request->all());
            foreach ($dateErrors as $field => $message) {
                $validator->errors()->add($field, $message);
            }
        });

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Cek apakah ada WO aktif untuk unit ini
        $activeWO = WorkOrder::where('master_unit_id', $request->master_unit_id)
            ->whereIn('status_wo', ['Open', 'Inprogress'])
            ->first();
        if ($activeWO) {
            return back()->with('error_popup', "Gagal! Unit ini masih memiliki Work Order yang aktif (No WO: " . $activeWO->no_wo . "). Selesaikan terlebih dahulu!")->withInput();
        }

        DB::beginTransaction();
        try {
            $unit = \App\Models\MasterUnit::find($request->master_unit_id);
            $wo = WorkOrder::create([
                'site_id' => $unit ? $unit->site_id : null,
                'no_wo' => WorkOrder::generateNoWo(),
                'status_wo' => $request->status_wo,
                'tipe_wo' => $request->tipe_wo,
                'downtime_code' => $request->downtime_code,
                'master_unit_id' => $request->master_unit_id,
                'hours_meter' => $request->hours_meter,
                'lokasi_kerusakan' => $request->lokasi_kerusakan,
                'waktu_bd' => $request->waktu_bd,
                'waktu_rfu' => $request->status_wo == 'Completed' ? ($request->waktu_rfu ?: now()) : null,
                'wo_category_1_id' => $request->wo_category_1_id,
                'wo_category_2_id' => $request->wo_category_2_id,
                'wo_category_3_id' => $request->wo_category_3_id,
                'wo_category_4_id' => $request->wo_category_4_id,
                'wo_category_5_id' => $request->wo_category_5_id,
                'created_by' => auth()->id(),
            ]);

            // If generated from PraWorkOrder, update the PraWorkOrder and add the task
            if ($request->pra_work_order_id) {
                $pra = \App\Models\PraWorkOrder::find($request->pra_work_order_id);
                if ($pra) {
                    $pra->update([
                        'status' => 'Generated',
                        'work_order_id' => $wo->id
                    ]);

                    // Task is already submitted from the frontend via the tasks[] array
                }
            }

            $this->saveTasks($wo, $request->input('tasks', []));

            DB::commit();
            return redirect()->route('work-orders.index')->with('success', 'Work Order ' . $wo->no_wo . ' berhasil dibuat.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyimpan WO: ' . $e->getMessage())->withInput();
        }
    }

    public function show(WorkOrder $workOrder)
    {
        $workOrder->load([
            'unit.site',
            'unit.type',
            'unit.model',
            'category1',
            'category2',
            'category3',
            'category4',
            'category5',
            'creator',
            'tasks.subtasks.manpower.mechanic',
            'tasks.subtasks.parts.part',
            'tasks.subtasks.tools.toolTransaction.tool',
            'tasks.subtasks.breakdownType',
            'jsas.steps',
            'ptws',
            'lotos.applier',
            'lotos.remover',
            'fars',
            'jwos'
        ]);
        $summary = app(WorkOrderDurationService::class)->summarize($workOrder);
        return view('work_orders.show', compact('workOrder', 'summary'));
    }

    public function edit(WorkOrder $workOrder)
    {
        $workOrder->load([
            'unit.type', 'unit.model',
            'tasks.componentGroup',
            'tasks.subtasks.manpower',
            'tasks.subtasks.parts',
            'tasks.subtasks.tools',
        ]);
        $data = $this->getFormData();
        $data['workOrder'] = $workOrder;
        $data['existingTasks'] = $workOrder->tasks->map(function ($task) {
            return [
                'problem' => $task->problem,
                'component_group_id' => $task->component_group_id,
                'date_problem' => $task->date_problem ? $task->date_problem->format('Y-m-d\TH:i') : '',
                'status' => $task->status,
                'subtasks' => $task->subtasks->map(function ($st) {
                    return [
                        'action' => $st->action,
                        'date_action' => $st->date_action ? $st->date_action->format('Y-m-d\TH:i') : '',
                        'date_finish' => $st->date_finish ? $st->date_finish->format('Y-m-d\TH:i') : '',
                        'duration_hours' => $st->duration_hours,
                        'breakdown_type_id' => $st->breakdown_type_id,
                        'status' => $st->status,
                        'manpower' => $st->manpower->map(function ($mp) {
                            return ['mechanic_id' => $mp->mechanic_id];
                        })->values()->all(),
                        'parts' => $st->parts->map(function ($p) {
                            return [
                                'part_id' => $p->part_id, 
                                'qty' => $p->qty, 
                                'satuan' => $p->satuan,
                                'part_status' => $p->part_status,
                                'mol_pr' => $p->mol_pr,
                                'order_status' => $p->order_status,
                                'swap_type' => $p->swap_type,
                                'swap_unit_id' => $p->swap_unit_id,
                                'swap_status' => $p->swap_status,
                                'swap_remarks' => $p->swap_remarks,
                            ];
                        })->values()->all(),
                        'tools' => $st->tools->map(function ($t) {
                            return ['tool_transaction_id' => $t->tool_transaction_id];
                        })->values()->all(),
                    ];
                })->values()->all(),
            ];
        })->values()->all();

        return view('work_orders.edit', $data);
    }

    public function update(Request $request, WorkOrder $workOrder)
    {
        $validator = Validator::make($request->all(), [
            'status_wo' => 'required|in:Open,Inprogress,Completed,Cancel,Backlog',
            'tipe_wo' => 'required|in:BD,Plan',
            'downtime_code' => 'required|in:Schedule,Unschedule,Accident',
            'master_unit_id' => 'required|exists:master_units,id',
            'hours_meter' => 'nullable|numeric',
            'lokasi_kerusakan' => 'nullable|string',
            'waktu_bd' => 'nullable|date',
            'waktu_rfu' => 'required_if:status_wo,Completed|nullable|date|after_or_equal:waktu_bd',
        ]);

        $validator->after(function ($validator) use ($request) {
            if ($request->filled('waktu_rfu') && $request->status_wo !== 'Completed') {
                $validator->errors()->add('waktu_rfu', 'Perhatian: Waktu RFU hanya dapat diisi jika Status Work Order diubah menjadi Completed.');
            }

            $dateErrors = app(WorkOrderDateValidationService::class)->validate($request->all());
            foreach ($dateErrors as $field => $message) {
                $validator->errors()->add($field, $message);
            }
        });

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        if (in_array($request->status_wo, ['Open', 'Inprogress']) || $request->master_unit_id != $workOrder->master_unit_id) {
            $activeWO = WorkOrder::where('master_unit_id', $request->master_unit_id)
                ->whereIn('status_wo', ['Open', 'Inprogress'])
                ->where('id', '!=', $workOrder->id)
                ->first();
            if ($activeWO) {
                return back()->with('error_popup', "Gagal! Unit ini masih memiliki Work Order lain yang aktif (No WO: " . $activeWO->no_wo . ").")->withInput();
            }
        }

        DB::beginTransaction();
        try {
            $unit = \App\Models\MasterUnit::find($request->master_unit_id);
            $workOrder->update([
                'site_id' => $unit ? $unit->site_id : null,
                'status_wo' => $request->status_wo,
                'tipe_wo' => $request->tipe_wo,
                'downtime_code' => $request->downtime_code,
                'master_unit_id' => $request->master_unit_id,
                'hours_meter' => $request->hours_meter,
                'lokasi_kerusakan' => $request->lokasi_kerusakan,
                'waktu_bd' => $request->waktu_bd,
                'waktu_rfu' => $request->status_wo == 'Completed' ? ($request->waktu_rfu ?: now()) : null,
                'wo_category_1_id' => $request->wo_category_1_id,
                'wo_category_2_id' => $request->wo_category_2_id,
                'wo_category_3_id' => $request->wo_category_3_id,
                'wo_category_4_id' => $request->wo_category_4_id,
                'wo_category_5_id' => $request->wo_category_5_id,
                'updated_by' => auth()->id(),
            ]);

            // Delete old tasks (cascade deletes subtasks, manpower, parts, tools)
            $workOrder->tasks()->delete();

            // Re-create
            $this->saveTasks($workOrder, $request->input('tasks', []));

            // If this is a PM Plan WO and it is completed, update the PM Schedule
            if ($workOrder->status_wo === 'Completed' && $workOrder->pm_schedule_id) {
                $schedule = \App\Models\PmSchedule::with('pmTemplate')->find($workOrder->pm_schedule_id);
                if ($schedule && $schedule->pmTemplate) {
                    $schedule->last_executed_value = $workOrder->hours_meter ?? $schedule->next_due_value;
                    $interval = $schedule->pmTemplate->interval_value ?: 250;
                    $schedule->next_due_value = floor($schedule->last_executed_value / $interval) * $interval + $interval;
                    
                    $opr_hrs = $schedule->pmTemplate->opr_hrs_per_day ?? 20;
                    if ($opr_hrs > 0) {
                        $hrs_to_go = $schedule->next_due_value - $schedule->last_executed_value;
                        $days_to_go = $hrs_to_go / $opr_hrs;
                        $baseDate = $workOrder->date_end ?? now();
                        $schedule->next_due_date = \Carbon\Carbon::parse($baseDate)->addHours(round($days_to_go * 24));
                    }
                    $schedule->status_jadwal = 'Upcoming';
                    $schedule->save();

                    // Record history
                    $schedule->histories()->firstOrCreate([
                        'work_order_no' => $workOrder->no_wo,
                    ], [
                        'hm_service' => $schedule->last_executed_value,
                        'executed_at' => $workOrder->date_end ?? now(),
                        'notes' => 'Generated otomatis dari Work Order ' . $workOrder->no_wo,
                        'created_by' => auth()->id() ?? $workOrder->created_by,
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('work-orders.index')->with('success', 'Work Order ' . $workOrder->no_wo . ' berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal update WO: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(WorkOrder $workOrder)
    {
        $workOrder->delete(); // cascade deletes everything
        return redirect()->route('work-orders.index')->with('success', 'Work Order berhasil dihapus.');
    }

    // =============================================
    // Private helpers
    // =============================================

    private function getFormData(): array
    {
        return [
            'sites' => \App\Models\Site::orderBy('name')->get(),
            'breakdownTypes' => BreakdownType::orderBy('name')->get(),
            'componentGroups' => ComponentGroup::orderBy('name')->get(),
            'categories1' => WoCategory::where('level', 1)->orderBy('name')->get(),
            'categories2' => WoCategory::where('level', 2)->orderBy('name')->get(),
            'categories3' => WoCategory::where('level', 3)->orderBy('name')->get(),
            'categories4' => WoCategory::where('level', 4)->orderBy('name')->get(),
            'categories5' => WoCategory::where('level', 5)->orderBy('name')->get(),
            'mechanics' => Mechanic::where('is_active', true)->orderBy('nama_lengkap')->get(),
            'parts' => Part::orderBy('part_number')->get(),
            'units' => \App\Models\MasterUnit::with('model')->orderBy('nomor_unit')->get(),
            'toolTransactions' => ToolTransaction::with('tool', 'mechanic')
                ->where('status', 'Borrowed')
                ->orderBy('created_at', 'desc')->get(),
        ];
    }

    private function saveTasks(WorkOrder $wo, array $tasksData): void
    {
        foreach ($tasksData as $taskData) {
            if (empty($taskData['problem'])) continue;

            $task = $wo->tasks()->create([
                'problem' => $taskData['problem'],
                'component_group_id' => $taskData['component_group_id'] ?? null,
                'date_problem' => $taskData['date_problem'] ?? null,
                'status' => $taskData['status'] ?? 'Open',
            ]);

            foreach ($taskData['subtasks'] ?? [] as $subtaskData) {
                if (empty($subtaskData['action'])) continue;

                $dateAction = $subtaskData['date_action'] ?? null;
                $dateFinish = $subtaskData['date_finish'] ?? null;
                if (empty($dateFinish)) {
                    $dateFinish = now();
                }

                $durationHours = null;
                if ($dateAction && $dateFinish) {
                    $durationHours = round((\Carbon\Carbon::parse($dateAction)->diffInMinutes(\Carbon\Carbon::parse($dateFinish)) / 60), 2);
                }

                $subtask = $task->subtasks()->create([
                    'action' => $subtaskData['action'],
                    'date_action' => $dateAction,
                    'date_finish' => $dateFinish,
                    'duration_hours' => $durationHours,
                    'breakdown_type_id' => $subtaskData['breakdown_type_id'] ?? null,
                    'status' => $subtaskData['status'] ?? 'Open',
                ]);

                // Manpower
                foreach ($subtaskData['manpower_ids'] ?? [] as $mechanicId) {
                    if ($mechanicId) {
                        $subtask->manpower()->create(['mechanic_id' => $mechanicId]);
                    }
                }

                // Parts
                foreach ($subtaskData['parts'] ?? [] as $partData) {
                    if (!empty($partData['part_id'])) {
                        $subtask->parts()->create([
                            'part_id' => $partData['part_id'],
                            'qty' => $partData['qty'] ?? 1,
                            'satuan' => $partData['satuan'] ?? null,
                            'part_status' => $partData['part_status'] ?? 'Replace',
                            'mol_pr' => $partData['mol_pr_order'] ?? $partData['mol_pr_swap'] ?? $partData['mol_pr'] ?? null,
                            'order_status' => $partData['order_status'] ?? null,
                            'swap_type' => $partData['swap_type'] ?? null,
                            'swap_unit_id' => $partData['swap_unit_id'] ?? null,
                            'swap_status' => $partData['swap_status'] ?? null,
                            'swap_remarks' => $partData['swap_remarks'] ?? null,
                        ]);
                    }
                }

                // Tools (existing transactions)
                foreach ($subtaskData['tool_transaction_ids'] ?? [] as $txId) {
                    if ($txId) {
                        $subtask->tools()->create(['tool_transaction_id' => $txId]);
                    }
                }
            }
        }
    }

    public function kanban(Request $request)
    {
        $query = WorkOrder::with(['unit.type', 'unit.model', 'creator', 'tasks']);

        if ($request->filled('site_id')) {
            $query->whereHas('unit', function($q) use ($request) {
                $q->where('site_id', $request->site_id);
            });
        }

        if ($request->filled('tipe_wo')) {
            $query->where('tipe_wo', $request->tipe_wo);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('no_wo', 'like', "%{$search}%")
                  ->orWhereHas('unit', function($qu) use ($search) {
                      $qu->where('nomor_unit', 'like', "%{$search}%");
                  });
            });
        }

        // Optimized History Filtering for Completed & Cancel statuses
        $historyRange = $request->input('history_range', '14'); // Default 14 days
        if ($historyRange !== 'all' && !$request->filled('search')) {
            $days = (int) $historyRange;
            $cutoff = \Carbon\Carbon::now()->subDays($days);
            $query->where(function($q) use ($cutoff) {
                $q->whereIn('status_wo', ['Open', 'Inprogress', 'Backlog'])
                  ->orWhere(function($subQ) use ($cutoff) {
                      $subQ->whereIn('status_wo', ['Completed', 'Cancel'])
                           ->where('updated_at', '>=', $cutoff);
                  });
            });
        }

        $workOrders = $query->orderBy('created_at', 'desc')->get();

        $statuses = ['Open', 'Inprogress', 'Completed', 'Cancel', 'Backlog'];
        $groupedWorkOrders = [];
        foreach ($statuses as $s) {
            $groupedWorkOrders[$s] = $workOrders->where('status_wo', $s);
        }

        $data = $this->getFormData();
        $data['no_wo'] = WorkOrder::generateNoWo();
        $data['groupedWorkOrders'] = $groupedWorkOrders;
        $data['statuses'] = $statuses;

        return view('work_orders.kanban', $data);
    }
}
