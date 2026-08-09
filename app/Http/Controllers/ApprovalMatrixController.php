<?php

namespace App\Http\Controllers;

use App\Models\ApprovalMatrix;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class ApprovalMatrixController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_approval_matrix')->only(['index']);
        $this->middleware('permission:create_approval_matrix')->only(['store']);
        $this->middleware('permission:delete_approval_matrix')->only(['destroy']);
    }

    public function index()
    {
        $matrices = ApprovalMatrix::with('role')->orderBy('module_name')->orderBy('sequence')->get();
        $roles = Role::all();
        $modules = \App\Models\Module::orderBy('name')->get();
        return view('approval-matrix.index', compact('matrices', 'roles', 'modules'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'module_name' => 'required|string|max:255',
            'sequence'    => 'required|integer|min:1',
            'role_id'     => 'required|exists:roles,id',
            'description' => 'nullable|string|max:255',
        ]);

        ApprovalMatrix::create($request->only('module_name', 'sequence', 'role_id', 'description'));
        return redirect()->route('approval-matrix.index')->with('success', 'Level approval berhasil ditambahkan.');
    }

    public function destroy($id)
    {
        ApprovalMatrix::findOrFail($id)->delete();
        return redirect()->route('approval-matrix.index')->with('success', 'Level approval berhasil dihapus.');
    }
}
