<?php

namespace App\Http\Controllers;

use App\Models\Site;
use Illuminate\Http\Request;

class SiteController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_settings')->only(['index', 'show']);
        $this->middleware('permission:edit_settings')->only(['create', 'store', 'edit', 'update', 'destroy']);
    }

    public function index()
    {
        $sites = Site::all();
        return view('sites.index', compact('sites'));
    }

    public function create()
    {
        return view('sites.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:sites',
            'code' => 'nullable|string|max:50|unique:sites',
            'description' => 'nullable|string'
        ]);

        Site::create($request->all());

        return redirect()->route('sites.index')->with('success', 'Site berhasil ditambahkan.');
    }

    public function edit(Site $site)
    {
        return view('sites.edit', compact('site'));
    }

    public function update(Request $request, Site $site)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:sites,name,' . $site->id,
            'code' => 'nullable|string|max:50|unique:sites,code,' . $site->id,
            'description' => 'nullable|string'
        ]);

        $site->update($request->all());

        return redirect()->route('sites.index')->with('success', 'Site berhasil diperbarui.');
    }

    public function destroy(Site $site)
    {
        // Simple protection against deleting sites if they have associated data
        // For now just basic delete
        try {
            $site->delete();
            return redirect()->route('sites.index')->with('success', 'Site berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('sites.index')->with('error', 'Gagal menghapus Site. Site ini mungkin sedang digunakan.');
        }
    }
}
