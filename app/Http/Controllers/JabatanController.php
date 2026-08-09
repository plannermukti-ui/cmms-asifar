<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class JabatanController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_jabatans')->only(['index', 'show']);
        $this->middleware('permission:create_jabatans')->only(['create', 'store']);
        $this->middleware('permission:edit_jabatans')->only(['edit', 'update']);
        $this->middleware('permission:delete_jabatans')->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $jabatans = \App\Models\Jabatan::paginate(10);
        return view('jabatans.index', compact('jabatans'));
    }

    public function create()
    {
        return view('jabatans.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_jabatan' => 'required|string|max:255',
        ]);

        \App\Models\Jabatan::create($request->all());
        return redirect()->route('jabatans.index')->with('success', 'Jabatan berhasil ditambahkan.');
    }

    public function edit(string $id)
    {
        $jabatan = \App\Models\Jabatan::findOrFail($id);
        return view('jabatans.edit', compact('jabatan'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama_jabatan' => 'required|string|max:255',
        ]);

        $jabatan = \App\Models\Jabatan::findOrFail($id);
        $jabatan->update($request->all());
        return redirect()->route('jabatans.index')->with('success', 'Jabatan berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $jabatan = \App\Models\Jabatan::findOrFail($id);
        $jabatan->delete();
        return redirect()->route('jabatans.index')->with('success', 'Jabatan berhasil dihapus.');
    }
}
