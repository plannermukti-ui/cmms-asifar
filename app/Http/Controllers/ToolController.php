<?php

namespace App\Http\Controllers;

use App\Models\Tool;
use App\Models\ToolCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ToolController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_tools')->only(['index', 'show']);
        $this->middleware('permission:create_tools')->only(['create', 'store']);
        $this->middleware('permission:edit_tools')->only(['edit', 'update']);
        $this->middleware('permission:delete_tools')->only(['destroy']);
    }

    public function index()
    {
        $tools = Tool::with('category')->orderBy('name')->paginate(10);
        $categories = ToolCategory::orderBy('name')->get();
        $sites = \App\Models\Site::orderBy('name')->get();

        // Calculations for Dashboard Cards
        // 1. Total Tool (Room + Mechanic)
        $stocks = \App\Models\ToolStock::with('tool')->get();
        
        $totalToolCost = 0;
        $totalToolQty = 0;
        $mechanicCost = 0;
        $mechanicQty = 0;
        $roomCost = 0;
        $roomQty = 0;

        foreach ($stocks as $stock) {
            $cost = ($stock->tool->price ?? 0) * $stock->quantity;
            $qty = $stock->quantity;

            $totalToolCost += $cost;
            $totalToolQty += $qty;

            if ($stock->location_type === 'Mechanic') {
                $mechanicCost += $cost;
                $mechanicQty += $qty;
            } elseif ($stock->location_type === 'ToolRoom') {
                $roomCost += $cost;
                $roomQty += $qty;
            }
        }

        // 4. Damaged/Missing
        $transactions = \App\Models\ToolTransaction::with('tool')->where(function($q) {
            $q->where('returned_broken_qty', '>', 0)->orWhere('returned_lost_qty', '>', 0);
        })->get();

        $damagedCost = 0;
        $damagedQty = 0;

        foreach ($transactions as $tx) {
            $qty = $tx->returned_broken_qty + $tx->returned_lost_qty;
            $cost = ($tx->tool->price ?? 0) * $qty;
            $damagedQty += $qty;
            $damagedCost += $cost;
        }

        return view('tools.index', compact('tools', 'categories', 'sites', 'totalToolCost', 'totalToolQty', 'mechanicCost', 'mechanicQty', 'roomCost', 'roomQty', 'damagedCost', 'damagedQty'));
    }

    public function create()
    {
        $categories = ToolCategory::orderBy('name')->get();
        $sites = \App\Models\Site::orderBy('name')->get();
        return view('tools.create', compact('categories', 'sites'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'site_id' => 'nullable|exists:sites,id',
            'name' => 'required|string|max:255',
            'tool_category_id' => 'required|exists:tool_categories,id',
            'spesifikasi' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
            'foto' => 'nullable|image|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('tools', 'public');
            $data['foto'] = $path;
        }

        Tool::create($data);

        return redirect()->route('tools.index')->with('success', 'Tool berhasil ditambahkan.');
    }

    public function edit(Tool $tool)
    {
        $categories = ToolCategory::orderBy('name')->get();
        $sites = \App\Models\Site::orderBy('name')->get();
        return view('tools.edit', compact('tool', 'categories', 'sites'));
    }

    public function update(Request $request, Tool $tool)
    {
        $request->validate([
            'site_id' => 'nullable|exists:sites,id',
            'name' => 'required|string|max:255',
            'tool_category_id' => 'required|exists:tool_categories,id',
            'spesifikasi' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
            'foto' => 'nullable|image|max:2048',
        ]);

        $data = $request->except('foto');

        if ($request->hasFile('foto')) {
            // Delete old photo
            if ($tool->foto && Storage::disk('public')->exists($tool->foto)) {
                Storage::disk('public')->delete($tool->foto);
            }
            $path = $request->file('foto')->store('tools', 'public');
            $data['foto'] = $path;
        }

        $tool->update($data);

        return redirect()->route('tools.index')->with('success', 'Tool berhasil diperbarui.');
    }

    public function destroy(Tool $tool)
    {
        if ($tool->stocks()->count() > 0 || $tool->foto) {
            if ($tool->foto && Storage::disk('public')->exists($tool->foto)) {
                Storage::disk('public')->delete($tool->foto);
            }
        }
        $tool->delete();
        return redirect()->route('tools.index')->with('success', 'Tool berhasil dihapus.');
    }
}
