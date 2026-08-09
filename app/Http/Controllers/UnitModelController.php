<?php

namespace App\Http\Controllers;

use App\Models\UnitModel;
use App\Models\UnitType;
use Illuminate\Http\Request;

class UnitModelController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_unit_models')->only(['index', 'show']);
        $this->middleware('permission:create_unit_models')->only(['create', 'store']);
        $this->middleware('permission:edit_unit_models')->only(['edit', 'update']);
        $this->middleware('permission:delete_unit_models')->only(['destroy']);
    }

    public function index()
    {
        $unitModels = UnitModel::with('type')->orderBy('name')->get();
        $unitTypes = UnitType::orderBy('name')->get();
        return view('unit-models.index', compact('unitModels', 'unitTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'unit_type_id' => 'required|exists:unit_types,id',
            'name' => 'required|string|max:255',
        ]);

        $unitModel = UnitModel::create($request->all());

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'data' => $unitModel]);
        }

        return redirect()->route('unit-models.index')->with('success', 'Model Unit berhasil ditambahkan.');
    }

    public function update(Request $request, UnitModel $unitModel)
    {
        $request->validate([
            'unit_type_id' => 'required|exists:unit_types,id',
            'name' => 'required|string|max:255',
        ]);

        $unitModel->update($request->all());
        return redirect()->route('unit-models.index')->with('success', 'Model Unit berhasil diperbarui.');
    }

    public function destroy(UnitModel $unitModel)
    {
        $unitModel->delete();
        return redirect()->route('unit-models.index')->with('success', 'Model Unit berhasil dihapus.');
    }
}
