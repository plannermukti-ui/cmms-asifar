<?php

namespace App\Http\Controllers;

use App\Models\Module;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_modules')->only(['index', 'show']);
        $this->middleware('permission:create_modules')->only(['create', 'store']);
        $this->middleware('permission:edit_modules')->only(['edit', 'update']);
        $this->middleware('permission:delete_modules')->only(['destroy']);
    }

    public function index()
    {
        $modules = Module::orderBy('name')->get();
        return view('modules.index', compact('modules'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:modules',
            'description' => 'nullable|string|max:255',
        ]);

        Module::create($request->all());
        return redirect()->route('modules.index')->with('success', 'Modul berhasil ditambahkan.');
    }

    public function update(Request $request, Module $module)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:modules,name,'.$module->id,
            'description' => 'nullable|string|max:255',
        ]);

        $module->update($request->all());
        return redirect()->route('modules.index')->with('success', 'Modul berhasil diperbarui.');
    }

    public function destroy(Module $module)
    {
        $module->delete();
        return redirect()->route('modules.index')->with('success', 'Modul berhasil dihapus.');
    }
}
