<?php

namespace App\Http\Controllers;

use App\Models\MasterUnit;
use App\Models\PmSchedule;
use App\Models\PmTemplate;
use App\Models\PmTemplateSubtask;
use App\Models\PmTemplateTask;
use App\Models\Site;
use App\Models\UnitModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PmTemplateController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_pm_templates')->only(['index', 'show']);
        $this->middleware('permission:create_pm_templates')->only(['create', 'store', 'copy']);
        $this->middleware('permission:edit_pm_templates')->only(['edit', 'update']);
        $this->middleware('permission:delete_pm_templates')->only(['destroy']);
    }
    public function index(Request $request)
    {
        $query = PmTemplate::with(['unitModel', 'site']);

        if ($request->filled('unit_model_id')) {
            $query->where('unit_model_id', $request->unit_model_id);
        }

        $templates = $query->orderBy('created_at', 'desc')->paginate(10);
        $sites = Site::orderBy('name')->get();
        $unitModels = UnitModel::orderBy('name')->get();
        return view('pm-templates.index', compact('templates', 'sites', 'unitModels'));
    }

    public function create()
    {
        $unitModels = UnitModel::orderBy('name')->get();
        $sites = Site::orderBy('name')->get();
        $parts = \App\Models\Part::orderBy('part_number')->get();
        return view('pm-templates.create', compact('unitModels', 'sites', 'parts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'site_id' => 'nullable|exists:sites,id',
            'unit_model_id' => 'required|exists:unit_models,id',
            'name' => 'required|string|max:255',
            'interval_type' => 'required|in:hour_meter,kilometer,days',
            'interval_value' => 'required|integer|min:1',
            'opr_hrs_per_day' => 'required|numeric|min:1',
            'tasks' => 'nullable|array',
            'tasks.*.task_name' => 'required|string',
            'tasks.*.subtasks' => 'nullable|array',
            'tasks.*.subtasks.*.subtask_name' => 'required|string',
            'tasks.*.subtasks.*.parts' => 'nullable|array',
            'tasks.*.subtasks.*.parts.*' => 'exists:parts,id',
        ]);

        DB::beginTransaction();
        try {
            $template = PmTemplate::create([
                'site_id' => $request->site_id ?? Auth::user()->site_id,
                'unit_model_id' => $request->unit_model_id,
                'name' => $request->name,
                'interval_type' => $request->interval_type,
                'interval_value' => $request->interval_value,
                'opr_hrs_per_day' => $request->opr_hrs_per_day,
            ]);

            if ($request->has('tasks')) {
                foreach ($request->tasks as $tIndex => $taskData) {
                    $task = PmTemplateTask::create([
                        'pm_template_id' => $template->id,
                        'task_name' => $taskData['task_name'],
                        'sequence' => $tIndex,
                    ]);

                    if (isset($taskData['subtasks'])) {
                        foreach ($taskData['subtasks'] as $sIndex => $subtaskData) {
                            $subtask = PmTemplateSubtask::create([
                                'pm_template_task_id' => $task->id,
                                'subtask_name' => $subtaskData['subtask_name'],
                                'sequence' => $sIndex,
                            ]);
                            if (isset($subtaskData['parts'])) {
                                foreach ($subtaskData['parts'] as $partId) {
                                    $subtask->parts()->attach($partId, ['quantity' => 1]);
                                }
                            }
                        }
                    }
                }
            }

            // Auto-generate schedules for existing units of this model
            $unitsQuery = MasterUnit::where('unit_model_id', $template->unit_model_id);
            if ($template->site_id) {
                $unitsQuery->where('site_id', $template->site_id);
            }
            $units = $unitsQuery->get();

            foreach ($units as $unit) {
                // Calculate estimated next due date
                $opr_hrs = $template->opr_hrs_per_day ?? 20;
                $hrs_to_go = $template->interval_value;
                $days_to_go = $opr_hrs > 0 ? ($hrs_to_go / $opr_hrs) : 0;
                $next_due_date = \Carbon\Carbon::now()->addDays($days_to_go);

                PmSchedule::firstOrCreate([
                    'master_unit_id' => $unit->id,
                    'pm_template_id' => $template->id,
                ], [
                    'site_id' => $unit->site_id,
                    'next_due_value' => $template->interval_value,
                    'next_due_date' => $next_due_date,
                    'status_jadwal' => 'Upcoming',
                ]);
            }

            DB::commit();
            return redirect()->route('pm-templates.index')->with('success', 'PM Template berhasil dibuat dan jadwal untuk unit terkait telah diinisialisasi.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function edit(PmTemplate $pmTemplate)
    {
        $pmTemplate->load('tasks.subtasks.parts');
        $unitModels = UnitModel::orderBy('name')->get();
        $sites = Site::orderBy('name')->get();
        $parts = \App\Models\Part::orderBy('part_number')->get();
        return view('pm-templates.edit', compact('pmTemplate', 'unitModels', 'sites', 'parts'));
    }

    public function update(Request $request, PmTemplate $pmTemplate)
    {
        $request->validate([
            'site_id' => 'nullable|exists:sites,id',
            'unit_model_id' => 'required|exists:unit_models,id',
            'name' => 'required|string|max:255',
            'interval_type' => 'required|in:hour_meter,kilometer,days',
            'interval_value' => 'required|integer|min:1',
            'opr_hrs_per_day' => 'required|numeric|min:1',
            'tasks' => 'nullable|array',
            'tasks.*.task_name' => 'required|string',
            'tasks.*.subtasks' => 'nullable|array',
            'tasks.*.subtasks.*.subtask_name' => 'required|string',
            'tasks.*.subtasks.*.parts' => 'nullable|array',
            'tasks.*.subtasks.*.parts.*' => 'exists:parts,id',
        ]);

        DB::beginTransaction();
        try {
            $pmTemplate->update([
                'site_id' => $request->site_id ?? Auth::user()->site_id,
                'unit_model_id' => $request->unit_model_id,
                'name' => $request->name,
                'interval_type' => $request->interval_type,
                'interval_value' => $request->interval_value,
                'opr_hrs_per_day' => $request->opr_hrs_per_day,
            ]);

            // Re-create tasks & subtasks (simplest way to handle nested updates)
            $pmTemplate->tasks()->delete();

            if ($request->has('tasks')) {
                foreach ($request->tasks as $tIndex => $taskData) {
                    $task = PmTemplateTask::create([
                        'pm_template_id' => $pmTemplate->id,
                        'task_name' => $taskData['task_name'],
                        'sequence' => $tIndex,
                    ]);

                    if (isset($taskData['subtasks'])) {
                        foreach ($taskData['subtasks'] as $sIndex => $subtaskData) {
                            $subtask = PmTemplateSubtask::create([
                                'pm_template_task_id' => $task->id,
                                'subtask_name' => $subtaskData['subtask_name'],
                                'sequence' => $sIndex,
                            ]);
                            if (isset($subtaskData['parts'])) {
                                foreach ($subtaskData['parts'] as $partId) {
                                    $subtask->parts()->attach($partId, ['quantity' => 1]);
                                }
                            }
                        }
                    }
                }
            }

            // Optional: If unit_model_id changed, we might need to delete old schedules and create new ones.
            // Assuming unit_model_id doesn't change often, but if it does:
            $unitsQuery = MasterUnit::where('unit_model_id', $pmTemplate->unit_model_id);
            if ($pmTemplate->site_id) {
                $unitsQuery->where('site_id', $pmTemplate->site_id);
            }
            $units = $unitsQuery->get();

            foreach ($units as $unit) {
                // Calculate estimated next due date
                $opr_hrs = $pmTemplate->opr_hrs_per_day ?? 20;
                $hrs_to_go = $pmTemplate->interval_value;
                $days_to_go = $opr_hrs > 0 ? ($hrs_to_go / $opr_hrs) : 0;
                $next_due_date = \Carbon\Carbon::now()->addDays($days_to_go);

                PmSchedule::firstOrCreate([
                    'master_unit_id' => $unit->id,
                    'pm_template_id' => $pmTemplate->id,
                ], [
                    'site_id' => $unit->site_id,
                    'next_due_value' => $pmTemplate->interval_value,
                    'next_due_date' => $next_due_date,
                    'status_jadwal' => 'Upcoming',
                ]);
            }

            DB::commit();
            return redirect()->route('pm-templates.index')->with('success', 'PM Template berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(PmTemplate $pmTemplate)
    {
        try {
            $pmTemplate->delete();
            return redirect()->route('pm-templates.index')->with('success', 'PM Template berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus: ' . $e->getMessage());
        }
    }

    public function copy(Request $request, PmTemplate $pmTemplate)
    {
        $request->validate([
            'site_id' => 'nullable|exists:sites,id',
            'unit_model_id' => 'required|exists:unit_models,id',
            'name' => 'required|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            // Load relations to replicate
            $pmTemplate->load('tasks.subtasks.parts');

            $newTemplate = $pmTemplate->replicate();
            $newTemplate->site_id = $request->site_id ?? Auth::user()->site_id;
            $newTemplate->unit_model_id = $request->unit_model_id;
            $newTemplate->name = $request->name;
            $newTemplate->save();

            // Replicate tasks, subtasks, and parts
            foreach ($pmTemplate->tasks as $task) {
                $newTask = $task->replicate();
                $newTask->pm_template_id = $newTemplate->id;
                $newTask->save();

                foreach ($task->subtasks as $subtask) {
                    $newSubtask = $subtask->replicate();
                    $newSubtask->pm_template_task_id = $newTask->id;
                    $newSubtask->save();

                    // Replicate parts attachment
                    foreach ($subtask->parts as $part) {
                        $newSubtask->parts()->attach($part->id, ['quantity' => $part->pivot->quantity ?? 1]);
                    }
                }
            }

            // Auto-generate schedules for existing units of this model
            $unitsQuery = MasterUnit::where('unit_model_id', $newTemplate->unit_model_id);
            if ($newTemplate->site_id) {
                $unitsQuery->where('site_id', $newTemplate->site_id);
            }
            $units = $unitsQuery->get();

            foreach ($units as $unit) {
                PmSchedule::firstOrCreate([
                    'master_unit_id' => $unit->id,
                    'pm_template_id' => $newTemplate->id,
                ], [
                    'site_id' => $unit->site_id,
                    'next_due_value' => $newTemplate->interval_value,
                    'status_jadwal' => 'Upcoming',
                ]);
            }

            DB::commit();
            return redirect()->route('pm-templates.index')->with('success', 'PM Template berhasil disalin.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat menyalin template: ' . $e->getMessage());
        }
    }
}
