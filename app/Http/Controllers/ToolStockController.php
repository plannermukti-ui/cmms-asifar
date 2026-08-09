<?php

namespace App\Http\Controllers;

use App\Models\ToolStock;
use App\Models\Tool;
use App\Models\Mechanic;
use Illuminate\Http\Request;

class ToolStockController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_tool_stocks')->only(['index', 'show']);
        $this->middleware('permission:create_tool_stocks')->only(['create', 'store']);
        $this->middleware('permission:edit_tool_stocks')->only(['edit', 'update']);
        $this->middleware('permission:delete_tool_stocks')->only(['destroy']);
    }

    public function index(Request $request)
    {
        $query = ToolStock::with(['tool', 'mechanic']);
        if ($request->has('tool_id')) {
            $query->where('tool_id', $request->tool_id);
        }
        $stocks = $query->orderBy('tool_id')->paginate(10);
        return view('tool_stocks.index', compact('stocks'));
    }

    public function create()
    {
        $tools = Tool::orderBy('name')->get();
        $mechanics = Mechanic::orderBy('nama_lengkap')->get();
        return view('tool_stocks.create', compact('tools', 'mechanics'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tool_id' => 'required|exists:tools,id',
            'location_type' => 'required|in:ToolRoom,Mechanic',
            'mechanic_id' => 'nullable|required_if:location_type,Mechanic|exists:mechanics,id',
            'quantity' => 'required|integer|min:0',
        ]);

        // Cek jika stok sudah ada
        $existing = ToolStock::where('tool_id', $request->tool_id)
            ->where('location_type', $request->location_type)
            ->when($request->location_type === 'Mechanic', function ($q) use ($request) {
                return $q->where('mechanic_id', $request->mechanic_id);
            })
            ->first();

        if ($existing) {
            $existing->increment('quantity', $request->quantity);
            return redirect()->route('tool-stocks.index')->with('success', 'Stok tool berhasil ditambahkan ke record yang sudah ada.');
        }

        ToolStock::create($request->all());
        return redirect()->route('tool-stocks.index')->with('success', 'Data stok tool berhasil ditambahkan.');
    }

    public function edit(ToolStock $toolStock)
    {
        $tools = Tool::orderBy('name')->get();
        $mechanics = Mechanic::orderBy('nama_lengkap')->get();
        return view('tool_stocks.edit', compact('toolStock', 'tools', 'mechanics'));
    }

    public function update(Request $request, ToolStock $toolStock)
    {
        $request->validate([
            'quantity' => 'required|integer|min:0',
        ]);

        $toolStock->update(['quantity' => $request->quantity]);

        return redirect()->route('tool-stocks.index')->with('success', 'Kuantitas stok tool berhasil diupdate.');
    }

    public function destroy(ToolStock $toolStock)
    {
        $toolStock->delete();
        return redirect()->route('tool-stocks.index')->with('success', 'Data stok tool berhasil dihapus.');
    }
}
