<?php

namespace App\Http\Controllers;

use App\Models\Part;
use Illuminate\Http\Request;

class PartController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_parts')->only(['index', 'show']);
        $this->middleware('permission:create_parts')->only(['create', 'store']);
        $this->middleware('permission:edit_parts')->only(['edit', 'update']);
        $this->middleware('permission:delete_parts')->only(['destroy']);
    }

    public function index()
    {
        $parts = Part::orderBy('part_number')->paginate(15);
        $sites = \App\Models\Site::orderBy('name')->get();
        $categories = \App\Models\PartCategory::all()->groupBy('type');
        return view('parts.index', compact('parts', 'sites', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'site_id' => 'nullable|exists:sites,id',
            'part_number' => 'required|string|max:100|unique:parts,part_number',
            'part_description' => 'required|string|max:255',
            'satuan' => 'nullable|string|max:50',
            'cost' => 'nullable|numeric|min:0',
            'expenditure_type' => 'nullable|string|in:Capex,Opex',
            'kategori_1_id' => 'nullable|exists:part_categories,id',
            'kategori_2_id' => 'nullable|exists:part_categories,id',
            'kategori_3_id' => 'nullable|exists:part_categories,id',
            'kategori_4_id' => 'nullable|exists:part_categories,id',
        ]);

        Part::create($request->all());
        return redirect()->route('parts.index')->with('success', 'Part berhasil ditambahkan.');
    }

    public function edit(Part $part)
    {
        $sites = \App\Models\Site::orderBy('name')->get();
        $categories = \App\Models\PartCategory::all()->groupBy('type');
        return view('parts.edit', compact('part', 'sites', 'categories'));
    }

    public function update(Request $request, Part $part)
    {
        $request->validate([
            'site_id' => 'nullable|exists:sites,id',
            'part_number' => 'required|string|max:100|unique:parts,part_number,' . $part->id,
            'part_description' => 'required|string|max:255',
            'satuan' => 'nullable|string|max:50',
            'cost' => 'nullable|numeric|min:0',
            'expenditure_type' => 'nullable|string|in:Capex,Opex',
            'kategori_1_id' => 'nullable|exists:part_categories,id',
            'kategori_2_id' => 'nullable|exists:part_categories,id',
            'kategori_3_id' => 'nullable|exists:part_categories,id',
            'kategori_4_id' => 'nullable|exists:part_categories,id',
        ]);

        $part->update($request->all());
        return redirect()->route('parts.index')->with('success', 'Part berhasil diperbarui.');
    }

    public function destroy(Part $part)
    {
        $part->delete();
        return redirect()->route('parts.index')->with('success', 'Part berhasil dihapus.');
    }

    public function storeCategory(Request $request)
    {
        $request->validate([
            'type' => 'required|string|in:kategori_1,kategori_2,kategori_3,kategori_4',
            'name' => 'required|string|max:255',
        ]);

        $category = \App\Models\PartCategory::firstOrCreate([
            'type' => $request->type,
            'name' => $request->name,
        ]);

        return response()->json([
            'success' => true,
            'category' => $category
        ]);
    }
}
