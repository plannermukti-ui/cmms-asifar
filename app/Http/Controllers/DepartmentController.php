<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_departments')->only(['index', 'show']);
        $this->middleware('permission:create_departments')->only(['create', 'store']);
        $this->middleware('permission:edit_departments')->only(['edit', 'update']);
        $this->middleware('permission:delete_departments')->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $departments = \App\Models\Department::paginate(10);
        return view('departments.index', compact('departments'));
    }

    public function create()
    {
        return view('departments.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_department' => 'required|string|max:255',
        ]);

        \App\Models\Department::create($request->all());
        return redirect()->route('departments.index')->with('success', 'Departemen berhasil ditambahkan.');
    }

    public function edit(string $id)
    {
        $department = \App\Models\Department::findOrFail($id);
        return view('departments.edit', compact('department'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama_department' => 'required|string|max:255',
        ]);

        $department = \App\Models\Department::findOrFail($id);
        $department->update($request->all());
        return redirect()->route('departments.index')->with('success', 'Departemen berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $department = \App\Models\Department::findOrFail($id);
        $department->delete();
        return redirect()->route('departments.index')->with('success', 'Departemen berhasil dihapus.');
    }
}
