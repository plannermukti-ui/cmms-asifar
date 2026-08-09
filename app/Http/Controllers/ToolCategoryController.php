<?php

namespace App\Http\Controllers;

use App\Models\ToolCategory;
use Illuminate\Http\Request;

class ToolCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_tool_categories')->only(['index', 'show']);
        $this->middleware('permission:create_tool_categories')->only(['create', 'store']);
        $this->middleware('permission:edit_tool_categories')->only(['edit', 'update']);
        $this->middleware('permission:delete_tool_categories')->only(['destroy']);
    }

    public function index()
    {
        $categories = ToolCategory::orderBy('name')->paginate(10);
        return view('tool_categories.index', compact('categories'));
    }

    public function create()
    {
        return view('tool_categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:tool_categories,name',
            'description' => 'nullable|string',
        ]);

        ToolCategory::create($request->all());

        return redirect()->route('tool-categories.index')->with('success', 'Kategori Tool berhasil ditambahkan.');
    }

    public function edit(ToolCategory $toolCategory)
    {
        return view('tool_categories.edit', compact('toolCategory'));
    }

    public function update(Request $request, ToolCategory $toolCategory)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:tool_categories,name,'.$toolCategory->id,
            'description' => 'nullable|string',
        ]);

        $toolCategory->update($request->all());

        return redirect()->route('tool-categories.index')->with('success', 'Kategori Tool berhasil diperbarui.');
    }

    public function destroy(ToolCategory $toolCategory)
    {
        if ($toolCategory->tools()->count() > 0) {
            return redirect()->route('tool-categories.index')->with('error', 'Kategori ini tidak bisa dihapus karena masih digunakan oleh Tool.');
        }

        $toolCategory->delete();
        return redirect()->route('tool-categories.index')->with('success', 'Kategori Tool berhasil dihapus.');
    }
}
