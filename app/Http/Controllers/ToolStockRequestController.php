<?php

namespace App\Http\Controllers;

use App\Models\ToolStockRequest;
use App\Models\ToolStockRequestItem;
use App\Models\ToolStock;
use App\Models\User;
use App\Notifications\ToolStockRequestNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ToolStockRequestController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = ToolStockRequest::with(['requester', 'approver'])
            ->orderBy('created_at', 'desc');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('requester', function($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%");
            })->orWhereHas('approver', function($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%");
            })->orWhere('id', 'like', "%{$search}%");
        }

        $requests = $query->paginate(15)->withQueryString();
            
        return view('tool_stock_requests.index', compact('requests'));
    }

    public function show(ToolStockRequest $toolStockRequest)
    {

        $toolStockRequest->load(['items.tool', 'items.mechanic', 'requester', 'approver']);
        
        // Mark related notifications as read
        auth()->user()->unreadNotifications->where('type', 'App\Notifications\ToolStockRequestNotification')->each(function($notification) use ($toolStockRequest) {
            if(isset($notification->data['url']) && str_contains($notification->data['url'], route('tool-stock-requests.show', $toolStockRequest->id))) {
                $notification->markAsRead();
            }
        });
        
        return view('tool_stock_requests.show', compact('toolStockRequest'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'approver_id' => 'required|exists:users,id',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.tool_id' => 'required|exists:tools,id',
            'items.*.location_type' => 'required|in:ToolRoom,Mechanic',
            'items.*.mechanic_id' => 'nullable|required_if:items.*.location_type,Mechanic|exists:mechanics,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            $stockRequest = ToolStockRequest::create([
                'requester_id' => auth()->id(),
                'approver_id' => $request->approver_id,
                'status' => 'Pending',
                'notes' => $request->notes,
            ]);

            foreach ($request->items as $item) {
                ToolStockRequestItem::create([
                    'tool_stock_request_id' => $stockRequest->id,
                    'tool_id' => $item['tool_id'],
                    'location_type' => $item['location_type'],
                    'mechanic_id' => $item['mechanic_id'] ?? null,
                    'quantity' => $item['quantity'],
                ]);
            }

            // Notify approver
            $approver = User::find($request->approver_id);
            if ($approver) {
                $approver->notify(new ToolStockRequestNotification(
                    $stockRequest,
                    'Permintaan Penambahan Stok Tool',
                    auth()->user()->name . ' meminta persetujuan penambahan stok tool.'
                ));
            }

            DB::commit();
            return redirect()->route('tool-stocks.index')->with('success', 'Permintaan penambahan stok berhasil dikirim dan menunggu persetujuan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function approve(Request $request, ToolStockRequest $toolStockRequest)
    {
        if ($toolStockRequest->approver_id != auth()->id() && !auth()->user()->hasRole('Super Admin')) {
            abort(403);
        }

        if ($toolStockRequest->status != 'Pending') {
            return redirect()->back()->with('error', 'Request sudah diproses sebelumnya.');
        }

        DB::beginTransaction();
        try {
            $toolStockRequest->update(['status' => 'Approved']);

            // Process items
            foreach ($toolStockRequest->items as $item) {
                $existing = ToolStock::where('tool_id', $item->tool_id)
                    ->where('location_type', $item->location_type)
                    ->when($item->location_type === 'Mechanic', function ($q) use ($item) {
                        return $q->where('mechanic_id', $item->mechanic_id);
                    })
                    ->first();

                if ($existing) {
                    $existing->increment('quantity', $item->quantity);
                } else {
                    ToolStock::create([
                        'tool_id' => $item->tool_id,
                        'location_type' => $item->location_type,
                        'mechanic_id' => $item->mechanic_id,
                        'quantity' => $item->quantity,
                    ]);
                }
            }

            // Notify requester
            $toolStockRequest->requester->notify(new ToolStockRequestNotification(
                $toolStockRequest,
                'Permintaan Stok Disetujui',
                'Permintaan penambahan stok tool Anda telah disetujui.'
            ));

            DB::commit();
            return redirect()->route('tool-stock-requests.show', $toolStockRequest)->with('success', 'Permintaan disetujui, stok telah ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function reject(Request $request, ToolStockRequest $toolStockRequest)
    {
        if ($toolStockRequest->approver_id != auth()->id() && !auth()->user()->hasRole('Super Admin')) {
            abort(403);
        }

        if ($toolStockRequest->status != 'Pending') {
            return redirect()->back()->with('error', 'Request sudah diproses sebelumnya.');
        }

        $toolStockRequest->update(['status' => 'Rejected']);

        // Notify requester
        $toolStockRequest->requester->notify(new ToolStockRequestNotification(
            $toolStockRequest,
            'Permintaan Stok Ditolak',
            'Permintaan penambahan stok tool Anda telah ditolak.'
        ));

        return redirect()->route('tool-stock-requests.show', $toolStockRequest)->with('success', 'Permintaan berhasil ditolak.');
    }
}
