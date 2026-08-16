<?php

namespace App\Http\Controllers;

use App\Models\ToolTransaction;
use App\Models\Tool;
use App\Models\Mechanic;
use App\Models\ToolStock;
use App\Models\IncidentReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ToolTransactionController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_tool_transactions')->only(['index', 'show']);
        $this->middleware('permission:create_tool_transactions')->only(['create', 'store']);
        $this->middleware('permission:edit_tool_transactions')->only(['edit', 'update']); // for return process
        $this->middleware('permission:delete_tool_transactions')->only(['destroy']);
    }

    public function index()
    {
        $transactions = ToolTransaction::with(['tool', 'mechanic', 'admin'])->orderBy('created_at', 'desc')->paginate(10);
        $tools = Tool::whereHas('stocks', function($q) { $q->where('location_type', 'ToolRoom')->where('quantity', '>', 0); })->get();
        $mechanics = Mechanic::where('is_active', true)->orderBy('nama_lengkap')->get();
        
        $openWorkOrders = \App\Models\WorkOrder::with(['tasks.subtasks'])
            ->whereIn('status_wo', ['Open', 'Inprogress'])
            ->orderBy('id', 'desc')
            ->get();

        return view('tool_transactions.index', compact('transactions', 'tools', 'mechanics', 'openWorkOrders'));
    }

    public function create()
    {
        // Get tools that have stock in ToolRoom
        $tools = Tool::whereHas('stocks', function($q) {
            $q->where('location_type', 'ToolRoom')->where('quantity', '>', 0);
        })->get();
        
        $mechanics = Mechanic::where('is_active', true)->orderBy('nama_lengkap')->get();
        return view('tool_transactions.create', compact('tools', 'mechanics'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tool_id' => 'required|exists:tools,id',
            'mechanic_id' => 'required|exists:mechanics,id',
            'tipe_transaksi' => 'required|in:Pinjam Sementara,Pinjam Permanen',
            'borrow_qty' => 'required|integer|min:1',
            'wo_subtask_id' => 'nullable|exists:wo_subtasks,id'
        ]);

        DB::beginTransaction();
        try {
            // Check stock in ToolRoom
            $toolRoomStock = ToolStock::where('tool_id', $request->tool_id)
                ->where('location_type', 'ToolRoom')
                ->first();
                
            if (!$toolRoomStock || $toolRoomStock->quantity < $request->borrow_qty) {
                return back()->with('error', 'Stok di ToolRoom tidak mencukupi untuk jumlah yang diminta.')->withInput();
            }

            // Reduce ToolRoom Stock
            $toolRoomStock->decrement('quantity', $request->borrow_qty);

            // Add/Create Mechanic Stock
            $mechanicStock = ToolStock::firstOrCreate([
                'tool_id' => $request->tool_id,
                'location_type' => 'Mechanic',
                'mechanic_id' => $request->mechanic_id,
            ]);
            $mechanicStock->increment('quantity', $request->borrow_qty);

            // Create Transaction
            $transaction = ToolTransaction::create([
                'tool_id' => $request->tool_id,
                'mechanic_id' => $request->mechanic_id,
                'user_id' => auth()->id(),
                'tipe_transaksi' => $request->tipe_transaksi,
                'tanggal_pinjam' => now(),
                'borrow_qty' => $request->borrow_qty,
                'status' => 'Borrowed'
            ]);

            // If linked to a subtask, record it
            if ($request->filled('wo_subtask_id')) {
                \App\Models\WoSubtaskTool::create([
                    'wo_subtask_id' => $request->wo_subtask_id,
                    'tool_transaction_id' => $transaction->id
                ]);
            }

            DB::commit();
            return redirect()->route('tool-transactions.index')->with('success', 'Peminjaman tool berhasil diproses.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function edit(ToolTransaction $toolTransaction)
    {
        if ($toolTransaction->status === 'Returned') {
            return redirect()->route('tool-transactions.index')->with('error', 'Transaksi ini sudah selesai dikembalikan.');
        }
        return view('tool_transactions.edit', compact('toolTransaction'));
    }

    public function update(Request $request, ToolTransaction $toolTransaction)
    {
        if ($toolTransaction->status === 'Returned') {
            return redirect()->route('tool-transactions.index')->with('error', 'Transaksi sudah dikembalikan.');
        }

        $request->validate([
            'returned_good_qty' => 'required|integer|min:0',
            'returned_broken_qty' => 'required|integer|min:0',
            'returned_lost_qty' => 'required|integer|min:0',
            'catatan' => 'nullable|string'
        ]);

        $totalReturned = $request->returned_good_qty + $request->returned_broken_qty + $request->returned_lost_qty;
        
        if ($totalReturned != $toolTransaction->borrow_qty) {
            return back()->with('error', 'Total jumlah tool yang dikembalikan (Baik + Rusak + Hilang) harus sama dengan jumlah yang dipinjam ('.$toolTransaction->borrow_qty.').')->withInput();
        }

        DB::beginTransaction();
        try {
            // Update Transaction
            $toolTransaction->update([
                'returned_good_qty' => $request->returned_good_qty,
                'returned_broken_qty' => $request->returned_broken_qty,
                'returned_lost_qty' => $request->returned_lost_qty,
                'tanggal_kembali' => now(),
                'status' => 'Returned',
                'catatan' => $request->catatan,
            ]);

            // Adjust Mechanic Stock (Reduce)
            $mechanicStock = ToolStock::where('tool_id', $toolTransaction->tool_id)
                ->where('location_type', 'Mechanic')
                ->where('mechanic_id', $toolTransaction->mechanic_id)
                ->first();
            
            if ($mechanicStock) {
                // We reduce the total borrowed qty from mechanic stock because it's no longer with them
                $mechanicStock->decrement('quantity', $toolTransaction->borrow_qty);
            }

            // Adjust ToolRoom Stock
            $toolRoomStock = ToolStock::where('tool_id', $toolTransaction->tool_id)
                ->where('location_type', 'ToolRoom')
                ->first();
            
            if ($toolRoomStock) {
                // Add back only the good ones, and maybe broken ones? Let's say broken ones are also stored back in ToolRoom for repair.
                // Lost ones are gone.
                $returnedToRoom = $request->returned_good_qty + $request->returned_broken_qty;
                $toolRoomStock->increment('quantity', $returnedToRoom);
            }

            // Generate Incident Report if broken or lost
            if ($request->returned_broken_qty > 0 || $request->returned_lost_qty > 0) {
                IncidentReport::create([
                    'tool_transaction_id' => $toolTransaction->id,
                    'mechanic_id' => $toolTransaction->mechanic_id,
                    'kronologi' => 'Digenerate otomatis dari proses pengembalian tool. ' . 
                                   ($request->returned_broken_qty > 0 ? $request->returned_broken_qty . ' Rusak. ' : '') . 
                                   ($request->returned_lost_qty > 0 ? $request->returned_lost_qty . ' Hilang. ' : '') .
                                   'Catatan Mekanik/Admin: ' . $request->catatan,
                    'status_approval' => 'Pending'
                ]);
            }

            DB::commit();
            
            if ($request->returned_broken_qty > 0 || $request->returned_lost_qty > 0) {
                return redirect()->route('tool-transactions.index')->with('success', 'Pengembalian tool berhasil diproses. Berita Acara Kerusakan/Kehilangan otomatis dibuat.');
            }
            
            return redirect()->route('tool-transactions.index')->with('success', 'Pengembalian tool berhasil diproses.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy(ToolTransaction $toolTransaction)
    {
        DB::beginTransaction();
        try {
            if ($toolTransaction->status === 'Borrowed') {
                // Kembalikan stok dari mekanik ke ToolRoom
                $mechanicStock = ToolStock::where('tool_id', $toolTransaction->tool_id)
                    ->where('location_type', 'Mechanic')
                    ->where('mechanic_id', $toolTransaction->mechanic_id)
                    ->first();
                if ($mechanicStock) {
                    $mechanicStock->decrement('quantity', $toolTransaction->borrow_qty);
                }

                $toolRoomStock = ToolStock::where('tool_id', $toolTransaction->tool_id)
                    ->where('location_type', 'ToolRoom')
                    ->first();
                if ($toolRoomStock) {
                    $toolRoomStock->increment('quantity', $toolTransaction->borrow_qty);
                }
            } else if ($toolTransaction->status === 'Returned') {
                // Jika sudah returned, maka yang hilang belum kembali ke ToolRoom
                if ($toolTransaction->returned_lost_qty > 0) {
                    $toolRoomStock = ToolStock::where('tool_id', $toolTransaction->tool_id)
                        ->where('location_type', 'ToolRoom')
                        ->first();
                    if ($toolRoomStock) {
                        $toolRoomStock->increment('quantity', $toolTransaction->returned_lost_qty);
                    }
                }
                // Hapus incident report terkait
                IncidentReport::where('tool_transaction_id', $toolTransaction->id)->delete();
            }

            // Hapus referensi dari wo subtask jika ada
            \App\Models\WoSubtaskTool::where('tool_transaction_id', $toolTransaction->id)->delete();

            $toolTransaction->delete();
            DB::commit();
            return redirect()->route('tool-transactions.index')->with('success', 'Transaksi berhasil dihapus dan stok telah disesuaikan kembali.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('tool-transactions.index')->with('error', 'Terjadi kesalahan saat menghapus: ' . $e->getMessage());
        }
    }
}
