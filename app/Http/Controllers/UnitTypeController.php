<?php

namespace App\Http\Controllers;

use App\Models\UnitType;
use Illuminate\Http\Request;

class UnitTypeController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_unit_types')->only(['index', 'show']);
        $this->middleware('permission:create_unit_types')->only(['create', 'store']);
        $this->middleware('permission:edit_unit_types')->only(['edit', 'update']);
        $this->middleware('permission:delete_unit_types')->only(['destroy']);
    }

    public function index()
    {
        $unitTypes = UnitType::orderBy('name')->get();
        return view('unit-types.index', compact('unitTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:unit_types',
        ]);

        $unitType = UnitType::create($request->all());

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'data' => $unitType]);
        }

        return redirect()->route('unit-types.index')->with('success', 'Tipe Unit berhasil ditambahkan.');
    }

    public function update(Request $request, UnitType $unitType)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:unit_types,name,'.$unitType->id,
        ]);

        $unitType->update($request->all());
        return redirect()->route('unit-types.index')->with('success', 'Tipe Unit berhasil diperbarui.');
    }

    public function destroy(UnitType $unitType)
    {
        $unitType->delete();
        return redirect()->route('unit-types.index')->with('success', 'Tipe Unit berhasil dihapus.');
    }
}
