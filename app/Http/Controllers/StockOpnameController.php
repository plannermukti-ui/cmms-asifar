<?php

namespace App\Http\Controllers;

use App\Models\StockOpname;
use App\Models\StockOpnameDetail;
use App\Models\ToolStock;
use App\Models\Mechanic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockOpnameController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_stock_opnames')->only(['index', 'show']);
        $this->middleware('permission:create_stock_opnames')->only(['create', 'store']);
        $this->middleware('permission:edit_stock_opnames')->only(['edit', 'update']);
        $this->middleware('permission:delete_stock_opnames')->only(['destroy']);
    }

    public function index()
    {
        $opnames = StockOpname::with(['mechanic', 'auditor'])->orderBy('tanggal_audit', 'desc')->paginate(10);
        return view('stock_opnames.index', compact('opnames'));
    }

    public function create(Request $request)
    {
        $tipe = $request->get('tipe_audit', 'ToolRoom');
        $mechanic_id = $request->get('mechanic_id');
        
        $mechanics = Mechanic::where('is_active', true)->orderBy('nama_lengkap')->get();
        
        $query = ToolStock::with('tool')->where('location_type', $tipe);
        if ($tipe === 'Mechanic' && $mechanic_id) {
            $query->where('mechanic_id', $mechanic_id);
        } else if ($tipe === 'Mechanic' && !$mechanic_id) {
            // If mechanic type but no mechanic selected, don't load stocks yet
            $stocks = collect();
            return view('stock_opnames.create', compact('mechanics', 'stocks', 'tipe', 'mechanic_id'));
        }
        
        $stocks = $query->get();
        
        return view('stock_opnames.create', compact('mechanics', 'stocks', 'tipe', 'mechanic_id'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tipe_audit' => 'required|in:ToolRoom,Mechanic',
            'mechanic_id' => 'nullable|required_if:tipe_audit,Mechanic|exists:mechanics,id',
            'tanggal_audit' => 'required|date',
            'tools' => 'required|array',
            'tools.*.tool_id' => 'required|exists:tools,id',
            'tools.*.stok_sistem' => 'required|integer',
            'tools.*.stok_fisik' => 'required|integer',
        ]);

        DB::beginTransaction();
        try {
            $opname = StockOpname::create([
                'tanggal_audit' => $request->tanggal_audit,
                'tipe_audit' => $request->tipe_audit,
                'mechanic_id' => $request->mechanic_id,
                'auditor_user_id' => auth()->id(),
            ]);

            foreach ($request->tools as $item) {
                $selisih = $item['stok_fisik'] - $item['stok_sistem'];
                
                StockOpnameDetail::create([
                    'stock_opname_id' => $opname->id,
                    'tool_id' => $item['tool_id'],
                    'stok_sistem' => $item['stok_sistem'],
                    'stok_fisik' => $item['stok_fisik'],
                    'selisih' => $selisih
                ]);

                // Adjust the stock to the physical count
                $stockQuery = ToolStock::where('tool_id', $item['tool_id'])
                                     ->where('location_type', $request->tipe_audit);
                if ($request->tipe_audit === 'Mechanic') {
                    $stockQuery->where('mechanic_id', $request->mechanic_id);
                }
                
                $stockRecord = $stockQuery->first();
                if ($stockRecord) {
                    $stockRecord->update(['quantity' => $item['stok_fisik']]);
                } else if ($item['stok_fisik'] > 0) {
                    ToolStock::create([
                        'tool_id' => $item['tool_id'],
                        'location_type' => $request->tipe_audit,
                        'mechanic_id' => $request->mechanic_id,
                        'quantity' => $item['stok_fisik']
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('stock-opnames.index')->with('success', 'Stock Opname berhasil disimpan dan stok sistem telah disesuaikan dengan stok fisik.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show(StockOpname $stockOpname)
    {
        $stockOpname->load(['details.tool', 'mechanic', 'auditor']);
        return view('stock_opnames.show', compact('stockOpname'));
    }

    public function destroy(StockOpname $stockOpname)
    {
        $stockOpname->delete();
        return redirect()->route('stock-opnames.index')->with('success', 'Riwayat Stock Opname berhasil dihapus.');
    }
}
