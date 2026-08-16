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
        
        $user = auth()->user();
        $approversQuery = \App\Models\User::whereHas('roles', function($q) {
            $q->whereIn('name', ['Supervisor', 'Superintendent', 'Manager', 'Super Admin', 'Admin']);
        });
        if (!$user->hasRole('Super Admin')) {
            $approversQuery->where(function($q) use ($user) {
                $q->where('site_id', $user->site_id)->orWhereNull('site_id');
            })->where(function($q) use ($user) {
                $q->where('department_id', $user->department_id)->orWhereNull('department_id');
            });
        }
        $approvers = $approversQuery->get();
        
        return view('stock_opnames.create', compact('mechanics', 'stocks', 'tipe', 'mechanic_id', 'approvers'));
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
            'tools.*.stok_sistem' => 'required|integer',
            'tools.*.stok_fisik' => 'required|integer',
            'approver_id' => 'nullable|exists:users,id'
        ]);

        // Check if there is any difference
        $hasDifference = false;
        if ($request->has('tools')) {
            foreach ($request->tools as $item) {
                if ($item['stok_fisik'] != $item['stok_sistem']) {
                    $hasDifference = true;
                    break;
                }
            }
        }

        if ($hasDifference && empty($request->approver_id)) {
            return back()->with('error', 'Terdapat selisih stok! Anda wajib memilih Approver (Supervisor/Superintendent) untuk menyetujui Berita Acara.')->withInput();
        }

        DB::beginTransaction();
        try {
            $status = $hasDifference ? 'Pending Approval' : 'Approved';
            
            $opname = StockOpname::create([
                'tanggal_audit' => $request->tanggal_audit,
                'tipe_audit' => $request->tipe_audit,
                'mechanic_id' => $request->mechanic_id,
                'auditor_user_id' => auth()->id(),
                'status' => $status,
                'approver_id' => $hasDifference ? $request->approver_id : null,
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

                // If no difference in the whole opname, we can safely just adjust it (though there's no difference anyway).
                // If there IS a difference, WE DO NOT ADJUST STOCK YET. It awaits approval.
                if (!$hasDifference) {
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
            }

            DB::commit();
            if ($hasDifference) {
                return redirect()->route('stock-opnames.index')->with('warning', 'Stock Opname dicatat. Terdapat selisih stok. Harap upload PDF Berita Acara yang telah ditandatangani untuk proses approval.');
            } else {
                return redirect()->route('stock-opnames.index')->with('success', 'Stock Opname selesai. Tidak ada selisih stok yang ditemukan.');
            }

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show(StockOpname $stockOpname)
    {
        $stockOpname->load(['details.tool', 'mechanic', 'auditor', 'approver', 'approvedBy']);
        return view('stock_opnames.show', compact('stockOpname'));
    }

    public function destroy(StockOpname $stockOpname)
    {
        $stockOpname->delete();
        return redirect()->route('stock-opnames.index')->with('success', 'Riwayat Stock Opname berhasil dihapus.');
    }

    public function uploadDocument(Request $request, StockOpname $stockOpname)
    {
        $request->validate([
            'signed_document' => 'required|mimes:pdf|max:5120',
        ]);

        if ($request->hasFile('signed_document')) {
            $file = $request->file('signed_document');
            $filename = 'SO_' . $stockOpname->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/stock_opnames', $filename);
            
            $stockOpname->update([
                'signed_document' => $filename
            ]);

            return redirect()->back()->with('success', 'Berita Acara berhasil diupload, menunggu Approval.');
        }

        return redirect()->back()->with('error', 'Gagal mengupload dokumen.');
    }

    public function approve(Request $request, StockOpname $stockOpname)
    {
        if ($stockOpname->approver_id != auth()->id() && !auth()->user()->hasRole('Super Admin')) {
            abort(403);
        }

        if ($stockOpname->status != 'Pending Approval') {
            return redirect()->back()->with('error', 'Status bukan Pending Approval.');
        }

        if (!$stockOpname->signed_document) {
            return redirect()->back()->with('error', 'Dokumen Berita Acara belum diupload.');
        }

        DB::beginTransaction();
        try {
            $stockOpname->update([
                'status' => 'Approved',
                'approved_by' => auth()->id()
            ]);

            // Adjust stock
            foreach ($stockOpname->details as $item) {
                if ($item->selisih == 0) continue; // no change

                $stockQuery = ToolStock::where('tool_id', $item->tool_id)
                                     ->where('location_type', $stockOpname->tipe_audit);
                if ($stockOpname->tipe_audit === 'Mechanic') {
                    $stockQuery->where('mechanic_id', $stockOpname->mechanic_id);
                }
                
                $stockRecord = $stockQuery->first();
                if ($stockRecord) {
                    $stockRecord->update(['quantity' => $item->stok_fisik]);
                } else if ($item->stok_fisik > 0) {
                    ToolStock::create([
                        'tool_id' => $item->tool_id,
                        'location_type' => $stockOpname->tipe_audit,
                        'mechanic_id' => $stockOpname->mechanic_id,
                        'quantity' => $item->stok_fisik
                    ]);
                }
            }

            DB::commit();
            return redirect()->back()->with('success', 'Stock Opname disetujui. Stok sistem telah disesuaikan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function reject(Request $request, StockOpname $stockOpname)
    {
        if ($stockOpname->approver_id != auth()->id() && !auth()->user()->hasRole('Super Admin')) {
            abort(403);
        }

        if ($stockOpname->status != 'Pending Approval') {
            return redirect()->back()->with('error', 'Status bukan Pending Approval.');
        }

        $stockOpname->update([
            'status' => 'Rejected',
            'approved_by' => auth()->id()
        ]);

        return redirect()->back()->with('success', 'Stock Opname ditolak. Stok tidak berubah.');
    }
}
