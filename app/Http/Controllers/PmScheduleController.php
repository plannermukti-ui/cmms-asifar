<?php

namespace App\Http\Controllers;

use App\Models\PmSchedule;
use App\Models\WoTask;
use App\Models\WoSubtask;
use App\Models\WorkOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PmScheduleController extends Controller
{
    public function index(Request $request)
    {
        $query = PmSchedule::with(['masterUnit', 'pmTemplate', 'site']);
        
        if ($request->filled('site_id')) {
            $query->where('site_id', $request->site_id);
        }

        $schedules = $query->orderBy('next_due_value', 'asc')->paginate(15);
        $sites = \App\Models\Site::orderBy('name')->get();

        return view('pm-schedules.index', compact('schedules', 'sites'));
    }

    public function generateWorkOrder(Request $request, PmSchedule $pmSchedule)
    {
        // Check if there's already an active Plan WO for this schedule
        $existingWo = WorkOrder::where('pm_schedule_id', $pmSchedule->id)
            ->whereNotIn('status_wo', ['Completed', 'Cancel'])
            ->first();

        if ($existingWo) {
            return back()->with('error', 'Work Order (Plan) sudah ada untuk jadwal ini dan masih aktif (WO: '.$existingWo->no_wo.').');
        }

        DB::beginTransaction();
        try {
            $pmSchedule->load('pmTemplate.tasks.subtasks');
            $template = $pmSchedule->pmTemplate;

            $wo = WorkOrder::create([
                'no_wo' => WorkOrder::generateNoWo(),
                'status_wo' => 'Open',
                'tipe_wo' => 'Plan',
                'downtime_code' => 'Schedule',
                'opportunity' => false,
                'master_unit_id' => $pmSchedule->master_unit_id,
                'site_id' => $pmSchedule->site_id,
                'pm_schedule_id' => $pmSchedule->id,
                'created_by' => Auth::id(),
            ]);

            // Copy Tasks & Subtasks
            foreach ($template->tasks as $tIndex => $tTask) {
                $woTask = WoTask::create([
                    'work_order_id' => $wo->id,
                    'problem' => $tTask->task_name,
                    'status' => 'Open',
                ]);

                foreach ($tTask->subtasks as $sIndex => $tSubtask) {
                    WoSubtask::create([
                        'wo_task_id' => $woTask->id,
                        'action' => $tSubtask->subtask_name,
                        'status' => 'Open',
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('work-orders.show', $wo->id)
                ->with('success', 'Work Order untuk Preventive Maintenance berhasil di-generate.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal generate WO: ' . $e->getMessage());
        }
    }
}
