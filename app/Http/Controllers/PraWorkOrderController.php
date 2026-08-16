<?php

namespace App\Http\Controllers;

use App\Models\PraWorkOrder;
use App\Models\MasterUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PraWorkOrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_pra_work_orders')->only(['index', 'show']);
        $this->middleware('permission:create_pra_work_orders')->only(['create', 'store', 'generate']);
        $this->middleware('permission:edit_pra_work_orders')->only(['edit', 'update', 'cancel']);
        $this->middleware('permission:delete_pra_work_orders')->only(['destroy']);
    }
    public function index(Request $request)
    {
        $query = PraWorkOrder::with(['masterUnit', 'creator', 'workOrder']);

        if ($request->filled('id_request')) {
            $query->where('id', 'like', '%' . str_replace('REQ-', '', ltrim($request->id_request, '0')) . '%');
        }
        if ($request->filled('nomor_unit')) {
            $query->whereHas('masterUnit', function($q) use ($request) {
                $q->where('nomor_unit', 'like', '%' . $request->nomor_unit . '%');
            });
        }
        if ($request->filled('waktu_bd')) {
            $query->whereDate('waktu_bd', $request->waktu_bd);
        }
        if ($request->filled('lokasi_kerusakan')) {
            $query->where('lokasi_kerusakan', 'like', '%' . $request->lokasi_kerusakan . '%');
        }
        if ($request->filled('problem')) {
            $query->where('problem', 'like', '%' . $request->problem . '%');
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $praWorkOrders = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
            
        $units = MasterUnit::all();

        return view('pra_work_orders.index', compact('praWorkOrders', 'units'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'master_unit_id' => 'required|exists:master_units,id',
            'waktu_bd' => 'required|date',
            'hours_meter' => 'nullable|numeric',
            'lokasi_kerusakan' => 'required|string',
            'problem' => 'required|string',
        ]);

        $unit = MasterUnit::findOrFail($request->master_unit_id);

        $activeWO = \App\Models\WorkOrder::where('master_unit_id', $unit->id)
            ->whereIn('status_wo', ['Open', 'Inprogress'])
            ->first();

        if ($activeWO) {
            return back()->with('error_popup', "Gagal! Unit ini masih dalam penanganan dengan No WO: " . $activeWO->no_wo);
        }

        PraWorkOrder::create([
            'site_id' => $unit->site_id,
            'master_unit_id' => $unit->id,
            'waktu_bd' => $request->waktu_bd,
            'hours_meter' => $request->hours_meter,
            'lokasi_kerusakan' => $request->lokasi_kerusakan,
            'problem' => $request->problem,
            'status' => 'Pending',
            'created_by' => Auth::id(),
        ]);

        $waMessage = "🚨 *Laporan Kerusakan Unit (Pra-WO)* 🚨\n"
                   . "Unit: *" . $unit->nomor_unit . " (" . ($unit->model->name ?? '-') . ")*\n"
                   . "Lokasi: " . $request->lokasi_kerusakan . "\n"
                   . "Waktu BD: " . \Carbon\Carbon::parse($request->waktu_bd)->format('d/m/Y H:i') . "\n"
                   . "HM: " . ($request->hours_meter ?? '-') . "\n"
                   . "Problem: \n" . $request->problem . "\n\n"
                   . "Mohon segera diverifikasi. Terima kasih.";

        return back()->with('success', 'Pra-Work Order berhasil dibuat.')->with('wa_message', $waMessage);
    }

    public function cancel(PraWorkOrder $praWorkOrder)
    {
        $praWorkOrder->update([
            'status' => 'Cancelled'
        ]);

        return back()->with('success', 'Pra-Work Order dibatalkan.');
    }

    public function generate(PraWorkOrder $praWorkOrder)
    {
        if ($praWorkOrder->status !== 'Pending') {
            return back()->with('error_popup', 'Request ini tidak dapat digenerate karena statusnya sudah ' . $praWorkOrder->status . '.');
        }

        if (!auth()->user()->can('create_work_orders')) {
            return back()->with('error_popup', 'Anda tidak memiliki hak akses untuk membuat Work Order. Hubungi Administrator untuk mendapatkan akses Create Work Order.');
        }

        $activeWO = \App\Models\WorkOrder::where('master_unit_id', $praWorkOrder->master_unit_id)
            ->whereIn('status_wo', ['Open', 'Inprogress'])
            ->first();

        if ($activeWO) {
            return back()->with('error_popup', "Gagal! Unit ini masih dalam penanganan dengan No WO: " . $activeWO->no_wo . ". Selesaikan WO tersebut terlebih dahulu!");
        }

        // This will redirect to the create WO page, bringing along the pra_work_order ID
        // so we can prefill the form and later link them.
        return redirect()->route('work-orders.create', ['pra_work_order_id' => $praWorkOrder->id]);
    }
}
