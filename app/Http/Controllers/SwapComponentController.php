<?php

namespace App\Http\Controllers;

use App\Models\WoSubtaskPart;
use Illuminate\Http\Request;

class SwapComponentController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_swap_components')->only(['index', 'show']);
        $this->middleware('permission:create_swap_components')->only(['create', 'store']);
        $this->middleware('permission:edit_swap_components')->only(['edit', 'update']);
        $this->middleware('permission:delete_swap_components')->only(['destroy']);
    }
    public function index(Request $request)
    {
        $query = WoSubtaskPart::where('part_status', 'Swap / Canibal')
            ->with(['part', 'swapUnit', 'subtask.task.workOrder.unit']);

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereHas('subtask.task.workOrder', function ($q) use ($request) {
                $q->whereBetween('waktu_bd', [$request->start_date . ' 00:00:00', $request->end_date . ' 23:59:59']);
            });
        }

        if ($request->filled('status')) {
            $query->where('swap_status', $request->status);
        }

        $swaps = $query->latest('id')->paginate(15);

        return view('reports.swap_components.index', compact('swaps'));
    }

    public function edit($id)
    {
        $swap = WoSubtaskPart::where('part_status', 'Swap / Canibal')->findOrFail($id);
        $units = \App\Models\MasterUnit::orderBy('nomor_unit')->get();
        return view('reports.swap_components.edit', compact('swap', 'units'));
    }

    public function update(Request $request, $id)
    {
        $swap = WoSubtaskPart::where('part_status', 'Swap / Canibal')->findOrFail($id);

        $request->validate([
            'swap_type' => 'nullable|string',
            'swap_unit_id' => 'nullable|exists:master_units,id',
            'mol_pr' => 'nullable|string',
            'swap_status' => 'nullable|string',
            'swap_remarks' => 'nullable|string',
        ]);

        $swap->update($request->only([
            'swap_type', 'swap_unit_id', 'mol_pr', 'swap_status', 'swap_remarks'
        ]));

        return redirect()->route('swap-components.index')->with('success', 'Data Swap Component berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $swap = WoSubtaskPart::where('part_status', 'Swap / Canibal')->findOrFail($id);
        $swap->delete();

        return redirect()->route('swap-components.index')->with('success', 'Data Swap Component berhasil dihapus.');
    }
}
