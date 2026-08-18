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
    public function __construct()
    {
        $this->middleware('permission:view_pm_schedules')->only(['index', 'show', 'historyIndex', 'allHistory']);
        $this->middleware('permission:create_pm_schedules')->only(['create', 'store', 'generateWorkOrder', 'historyStore', 'importHistory', 'downloadHistoryTemplate']);
        $this->middleware('permission:edit_pm_schedules')->only(['edit', 'update']);
        $this->middleware('permission:delete_pm_schedules')->only(['destroy']);
    }
    public function index(Request $request)
    {
        $query = PmSchedule::with(['masterUnit.latestHourMeter', 'pmTemplate', 'site', 'latestHistory']);

        if ($request->filled('site_id')) {
            $query->where('pm_schedules.site_id', $request->site_id);
        }

        if ($request->filled('unit')) {
            $unit = $request->unit;
            $query->whereHas('masterUnit', function($u) use ($unit) {
                $u->where('nomor_unit', 'like', "%{$unit}%");
            });
        }

        if ($request->filled('template')) {
            $template = $request->template;
            $query->whereHas('pmTemplate', function($t) use ($template) {
                $t->where('name', 'like', "%{$template}%");
            });
        }
        if ($request->filled('status')) {
            $query->where('status_jadwal', $request->status);
        }

        if ($request->filled('last_hm')) {
            $query->where('last_executed_value', 'like', "%{$request->last_hm}%");
        }

        if ($request->filled('last_date')) {
            $query->whereHas('latestHistory', function($q) use ($request) {
                $q->whereDate('executed_at', $request->last_date);
            });
        }

        if ($request->filled('next_due_hm')) {
            $query->where('next_due_value', 'like', "%{$request->next_due_hm}%");
        }

        if ($request->filled('next_due_date')) {
            $query->whereDate('next_due_date', $request->next_due_date)
                  ->orWhereDate('estimated_next_due_date', $request->next_due_date);
        }

        $sort = $request->get('sort', 'next_due_value');
        $direction = strtolower($request->get('direction', 'asc')) === 'desc' ? 'desc' : 'asc';

        // Apply sorting with distinct selection to avoid duplicate columns
        switch ($sort) {
            case 'unit':
                $query->distinct('pm_schedules.id')
                      ->join('master_units', 'pm_schedules.master_unit_id', '=', 'master_units.id')
                      ->orderBy('master_units.nomor_unit', $direction);
                break;
            case 'current_hm':
                $query->distinct('pm_schedules.id')
                      ->leftJoin(DB::raw('(SELECT master_unit_id, MAX(hm) as latest_hm FROM hour_meters GROUP BY master_unit_id) as hm_sub'), 'pm_schedules.master_unit_id', '=', 'hm_sub.master_unit_id')
                      ->orderBy('hm_sub.latest_hm', $direction);
                break;
            case 'template':
                $query->distinct('pm_schedules.id')
                      ->join('pm_templates', 'pm_schedules.pm_template_id', '=', 'pm_templates.id')
                      ->orderBy('pm_templates.name', $direction);
                break;
            case 'last_hm':
                $query->orderBy('pm_schedules.last_executed_value', $direction);
                break;
            case 'last_date':
                $query->distinct('pm_schedules.id')
                      ->leftJoin(DB::raw('(SELECT pm_schedule_id, MAX(executed_at) as latest_date FROM pm_schedule_histories GROUP BY pm_schedule_id) as hist_sub'), 'pm_schedules.id', '=', 'hist_sub.pm_schedule_id')
                      ->orderBy('hist_sub.latest_date', $direction);
                break;
            case 'next_due_hm':
            case 'next_due_value':
                $query->orderBy('pm_schedules.next_due_value', $direction);
                break;
            case 'next_due_date':
                $query->orderBy('pm_schedules.next_due_date', $direction);
                break;
            case 'status':
                $query->orderBy('pm_schedules.status_jadwal', $direction);
                break;
            default:
                $query->orderBy('pm_schedules.next_due_value', $direction);
                break;
        }

        $schedules = $query->paginate(15)->appends($request->query());
        $sites = \App\Models\Site::orderBy('name')->get();

        // Get distinct options for dropdown filters
        $filterUnits = \App\Models\MasterUnit::whereIn('id', PmSchedule::select('master_unit_id'))->orderBy('nomor_unit')->get();
        $filterTemplates = \App\Models\PmTemplate::whereIn('id', PmSchedule::select('pm_template_id'))->orderBy('name')->get();

        return view('pm-schedules.index', compact('schedules', 'sites', 'filterUnits', 'filterTemplates'));
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
            return redirect()->route('work-orders.show', $wo)
                ->with('success', 'Work Order untuk Preventive Maintenance berhasil di-generate.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal generate WO: ' . $e->getMessage());
        }
    }

    public function historyIndex(PmSchedule $pmSchedule)
    {
        $histories = $pmSchedule->histories()->with('creator', 'workOrder')->paginate(15);
        return view('pm-schedules.history', compact('pmSchedule', 'histories'));
    }

    public function historyStore(Request $request, PmSchedule $pmSchedule)
    {
        $request->validate([
            'hm_service' => 'required|numeric|min:0',
            'executed_at' => 'required|date',
            'work_order_no' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            // Auto-heal database schema if it doesn't exist
            if (!\Illuminate\Support\Facades\Schema::hasColumn('pm_schedule_histories', 'hm_service')) {
                \Illuminate\Support\Facades\Schema::table('pm_schedule_histories', function (\Illuminate\Database\Schema\Blueprint $table) {
                    $table->decimal('hm_service', 10, 1)->nullable()->after('pm_schedule_id');
                });
            }
            if (!\Illuminate\Support\Facades\Schema::hasColumn('pm_templates', 'opr_hrs_per_day')) {
                \Illuminate\Support\Facades\Schema::table('pm_templates', function (\Illuminate\Database\Schema\Blueprint $table) {
                    $table->decimal('opr_hrs_per_day', 10, 1)->default(20)->after('interval_value');
                });
            }
            if (!\Illuminate\Support\Facades\Schema::hasColumn('pm_schedules', 'next_due_date')) {
                \Illuminate\Support\Facades\Schema::table('pm_schedules', function (\Illuminate\Database\Schema\Blueprint $table) {
                    $table->date('next_due_date')->nullable()->after('next_due_value');
                });
            }

            $pmSchedule->histories()->create([
                'hm_service' => $request->hm_service,
                'executed_at' => $request->executed_at,
                'work_order_no' => $request->work_order_no,
                'notes' => $request->notes,
                'created_by' => Auth::id(),
            ]);

            // Update parent PmSchedule logic
            $pmSchedule->last_executed_value = $request->hm_service;

            // Recalculate next due
            if ($pmSchedule->pmTemplate) {
                $interval = $pmSchedule->pmTemplate->interval_value;
                $opr_hrs = $pmSchedule->pmTemplate->opr_hrs_per_day ?? 20;

                // Absolute interval next due HM
                $pmSchedule->next_due_value = floor($request->hm_service / $interval) * $interval + $interval;

                // Calculate next due date
                $hrs_to_go = $pmSchedule->next_due_value - $request->hm_service;
                if ($opr_hrs > 0) {
                    $days_to_go = $hrs_to_go / $opr_hrs;
                    $pmSchedule->next_due_date = \Carbon\Carbon::parse($request->executed_at)->addHours(round($days_to_go * 24));
                }
            }
            $pmSchedule->status_jadwal = 'Upcoming';
            $pmSchedule->save();

            DB::commit();
            return redirect()->route('pm-schedules.history', $pmSchedule->id)->with('success', 'History service berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal mencatat history: ' . $e->getMessage());
        }
    }

    public function allHistory(Request $request)
    {
        $query = \App\Models\PmScheduleHistory::with(['pmSchedule.masterUnit', 'pmSchedule.pmTemplate', 'creator', 'workOrder']);

        // search by unit number, template name, or work order number
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function($q) use ($s) {
                $q->whereHas('pmSchedule.masterUnit', function($u) use ($s) {
                    $u->where('nomor_unit', 'like', "%{$s}%");
                })->orWhereHas('pmSchedule.pmTemplate', function($t) use ($s) {
                    $t->where('name', 'like', "%{$s}%");
                })->orWhere('work_order_no', 'like', "%{$s}%");
            });
        }

        $histories = $query->orderBy('executed_at', 'desc')->paginate(10)->appends($request->query());
        return view('pm-schedules.all-history', compact('histories'));
    }

    public function importHistory(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:10240',
        ]);

        try {
            $import = new \App\Imports\PmScheduleHistoryImport();
            \Maatwebsite\Excel\Facades\Excel::import($import, $request->file('file'));

            $msg = "Import selesai! {$import->getImportedCount()} data riwayat servis berhasil ditambahkan dan jadwal PM telah diperbarui.";
            if ($import->getSkippedCount() > 0) {
                $msg .= " ({$import->getSkippedCount()} baris dilewati).";
            }

            $redirect = redirect()->route('pm-schedules.all-history')->with('success', $msg);
            if (!empty($import->getErrors())) {
                $redirect->with('import_errors', array_slice($import->getErrors(), 0, 5));
            }

            return $redirect;
        } catch (\Exception $e) {
            return redirect()->route('pm-schedules.all-history')->with('error', 'Gagal import history: ' . $e->getMessage());
        }
    }

    public function downloadHistoryTemplate()
    {
        $headers = [
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Content-type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename=Template_Import_PM_History.csv',
            'Expires'             => '0',
            'Pragma'              => 'public'
        ];

        $columns = ['Date', 'Unit', 'Template', 'HM', 'WO_No', 'Notes'];
        $example1 = [date('Y-m-d'), 'EX-01', 'PM 250', '12500.0', 'WO-2026-08-001', 'Periodic Service 250 Jam Selesai'];
        $example2 = [date('Y-m-d'), 'DT-05', 'PM 500', '8450.0', '', 'Service Berkala'];

        $callback = function() use($columns, $example1, $example2) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            fputcsv($file, $example1);
            fputcsv($file, $example2);
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
