<?php

namespace App\Http\Controllers;

use App\Models\Mechanic;
use App\Models\Jabatan;
use Illuminate\Http\Request;

class MechanicController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_mechanics')->only(['index', 'show']);
        $this->middleware('permission:create_mechanics')->only(['create', 'store']);
        $this->middleware('permission:edit_mechanics')->only(['edit', 'update']);
        $this->middleware('permission:delete_mechanics')->only(['destroy']);
    }

    public function index()
    {
        $mechanics = Mechanic::with(['jabatan', 'site'])->orderBy('nama_lengkap', 'asc')->paginate(10);
        $mechIds = $mechanics->pluck('id');

        // Batch load tool stocks for current page mechanics
        $allToolsAllocated = \App\Models\ToolStock::with('tool.category')
            ->where('location_type', 'Mechanic')
            ->whereIn('mechanic_id', $mechIds)
            ->get()
            ->groupBy('mechanic_id');

        // Batch load WO counts and duration
        $allWoCounts = \App\Models\WoSubtaskManpower::whereIn('mechanic_id', $mechIds)
            ->join('wo_subtasks', 'wo_subtask_manpower.wo_subtask_id', '=', 'wo_subtasks.id')
            ->join('wo_tasks', 'wo_subtasks.wo_task_id', '=', 'wo_tasks.id')
            ->selectRaw('mechanic_id, count(distinct wo_tasks.work_order_id) as total_wo, sum(wo_subtasks.duration_hours) as total_duration')
            ->groupBy('mechanic_id')
            ->get()
            ->keyBy('mechanic_id');

        foreach ($mechanics as $mech) {
            $woData = $allWoCounts->get($mech->id);
            $mech->total_wo = $woData ? $woData->total_wo : 0;
            $mech->total_duration = $woData ? ($woData->total_duration ?? 0) : 0;
            $mech->tools_allocated = $allToolsAllocated->get($mech->id, collect());
            $mech->total_tools = $mech->tools_allocated->sum('quantity');
        }

        $jabatans = Jabatan::all();
        $sites = \App\Models\Site::orderBy('name')->get();
        return view('mechanics.index', compact('mechanics', 'jabatans', 'sites'));
    }

    public function create()
    {
        $jabatans = Jabatan::all();
        $sites = \App\Models\Site::orderBy('name')->get();
        return view('mechanics.create', compact('jabatans', 'sites'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'site_id' => 'nullable|exists:sites,id',
            'nama_lengkap' => 'required|string|max:255',
            'jabatan_id' => 'nullable|exists:jabatans,id',
            'is_active' => 'required|boolean',
        ]);

        Mechanic::create($request->all());

        return redirect()->route('mechanics.index')->with('success', 'Data mekanik berhasil ditambahkan.');
    }

    public function edit(Mechanic $mechanic)
    {
        $jabatans = Jabatan::all();
        $sites = \App\Models\Site::orderBy('name')->get();
        return view('mechanics.edit', compact('mechanic', 'jabatans', 'sites'));
    }

    public function update(Request $request, Mechanic $mechanic)
    {
        $request->validate([
            'site_id' => 'nullable|exists:sites,id',
            'nama_lengkap' => 'required|string|max:255',
            'jabatan_id' => 'nullable|exists:jabatans,id',
            'is_active' => 'required|boolean',
        ]);

        $mechanic->update($request->all());

        return redirect()->route('mechanics.index')->with('success', 'Data mekanik berhasil diperbarui.');
    }

    public function show(Mechanic $mechanic)
    {
        // Calculate Total Work Orders
        $totalWO = \App\Models\WoSubtaskManpower::where('mechanic_id', $mechanic->id)
            ->join('wo_subtasks', 'wo_subtask_manpower.wo_subtask_id', '=', 'wo_subtasks.id')
            ->join('wo_tasks', 'wo_subtasks.wo_task_id', '=', 'wo_tasks.id')
            ->distinct('wo_tasks.work_order_id')
            ->count('wo_tasks.work_order_id');

        // Calculate Total Duration
        $totalDuration = \App\Models\WoSubtaskManpower::where('mechanic_id', $mechanic->id)
            ->join('wo_subtasks', 'wo_subtask_manpower.wo_subtask_id', '=', 'wo_subtasks.id')
            ->sum('wo_subtasks.duration_hours');

        // Get Tools Allocated
        $toolsAllocated = \App\Models\ToolStock::with('tool.category')
            ->where('location_type', 'Mechanic')
            ->where('mechanic_id', $mechanic->id)
            ->get();
            
        $totalTools = $toolsAllocated->sum('quantity');

        return view('mechanics.show', compact('mechanic', 'totalWO', 'totalDuration', 'toolsAllocated', 'totalTools'));
    }

    public function destroy(Mechanic $mechanic)
    {
        $mechanic->delete();
        return redirect()->route('mechanics.index')->with('success', 'Data mekanik berhasil dihapus.');
    }
}
