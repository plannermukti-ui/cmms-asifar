<?php

namespace App\Http\Controllers;

use App\Models\FuelReceiving;
use App\Models\FuelStorage;
use App\Models\FuelSupplierTruck;
use App\Models\Site;
use App\Models\User;
use App\Models\Vendor;
use App\Services\FuelStockService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FuelReceivingController extends Controller
{
    protected FuelStockService $stockService;

    public function __construct(FuelStockService $stockService)
    {
        $this->stockService = $stockService;
        $this->middleware('permission:view_fuel_receivings')->only(['index', 'show', 'exportPdf']);
        $this->middleware('permission:create_fuel_receivings')->only(['create', 'store']);
        $this->middleware('permission:approve_fuel_receivings')->only(['approve', 'reject']);
        $this->middleware('permission:delete_fuel_receivings')->only(['destroy']);
    }

    public function index(Request $request)
    {
        $siteId = $request->get('site_id');
        $status = $request->get('status');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');

        $query = FuelReceiving::with(['storage', 'vendor', 'supplierTruck', 'receiver', 'intendedApprover', 'approver', 'site']);

        if ($siteId) $query->where('site_id', $siteId);
        if ($status) $query->where('status', $status);
        if ($dateFrom) $query->whereDate('date_receive', '>=', $dateFrom);
        if ($dateTo) $query->whereDate('date_receive', '<=', $dateTo);

        $receivings = $query->orderBy('date_receive', 'desc')->paginate(15)->withQueryString();

        $sites = Site::orderBy('name')->get();
        $storages = FuelStorage::where('is_active', true)->orderBy('code')->get();

        return view('fuel.receivings.index', compact('receivings', 'sites', 'storages', 'siteId', 'status', 'dateFrom', 'dateTo'));
    }

    public function create()
    {
        $storages = FuelStorage::where('is_active', true)->orderBy('code')->get();
        $vendors = Vendor::orderBy('name')->get();
        $supplierTrucks = FuelSupplierTruck::with('vendor')->where('is_active', true)->get();
        $sites = Site::orderBy('name')->get();
        $approverUsers = User::where('status', 'active')->orderBy('nama_lengkap')->get();
        $autoNumber = FuelReceiving::generateReceivingNumber();

        return view('fuel.receivings.create', compact('storages', 'vendors', 'supplierTrucks', 'sites', 'approverUsers', 'autoNumber'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'fuel_storage_id' => 'required|exists:fuel_storages,id',
            'vendor_id' => 'required|exists:vendors,id',
            'fuel_supplier_truck_id' => 'nullable|exists:fuel_supplier_trucks,id',
            'delivery_order_number' => 'required|string|max:100',
            'po_number' => 'nullable|string|max:100',
            'date_receive' => 'required|date',
            'truck_plat_nomor' => 'nullable|string|max:50',
            'driver_name' => 'nullable|string|max:255',
            'sonding_awal_cm' => 'nullable|numeric|min:0',
            'sonding_akhir_cm' => 'nullable|numeric|min:0',
            'density' => 'nullable|numeric|min:0',
            'temperature' => 'nullable|numeric',
            'do_volume_liters' => 'required|numeric|min:1',
            'received_volume_liters' => 'required|numeric|min:1',
            'totalizer_before' => 'nullable|numeric|min:0',
            'totalizer_after' => 'nullable|numeric|min:0',
            'intended_approver_id' => 'required|exists:users,id',
            'document_scan' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'notes' => 'nullable|string',
        ]);

        $storage = FuelStorage::find($request->fuel_storage_id);
        $supplierTruck = $request->fuel_supplier_truck_id ? FuelSupplierTruck::find($request->fuel_supplier_truck_id) : null;

        $docPath = null;
        if ($request->hasFile('document_scan')) {
            $docPath = $request->file('document_scan')->store('fuel_documents/receivings', 'public');
        }

        $doVol = (float) $request->do_volume_liters;
        $recVol = (float) $request->received_volume_liters;
        $losses = $recVol - $doVol;

        $receiving = FuelReceiving::create([
            'receiving_number' => FuelReceiving::generateReceivingNumber(),
            'site_id' => $storage ? $storage->site_id : null,
            'fuel_storage_id' => $request->fuel_storage_id,
            'vendor_id' => $request->vendor_id,
            'fuel_supplier_truck_id' => $request->fuel_supplier_truck_id,
            'delivery_order_number' => $request->delivery_order_number,
            'po_number' => $request->po_number,
            'date_receive' => $request->date_receive,
            'truck_plat_nomor' => $supplierTruck ? $supplierTruck->truck_plat_nomor : $request->truck_plat_nomor,
            'driver_name' => $supplierTruck ? $supplierTruck->driver_name : $request->driver_name,
            'sonding_awal_cm' => $request->sonding_awal_cm,
            'sonding_akhir_cm' => $request->sonding_akhir_cm,
            'density' => $request->density,
            'temperature' => $request->temperature,
            'do_volume_liters' => $doVol,
            'received_volume_liters' => $recVol,
            'losses_volume_liters' => $losses,
            'totalizer_before' => $request->totalizer_before,
            'totalizer_after' => $request->totalizer_after,
            'intended_approver_id' => $request->intended_approver_id,
            'document_scan' => $docPath,
            'notes' => $request->notes,
            'status' => 'Submitted',
            'received_by' => auth()->id(),
        ]);

        return redirect()->route('fuel.receivings.show', $receiving)->with('success', "Penerimaan BBM {$receiving->receiving_number} berhasil dibuat dan menunggu approval dari atasan yang dipilih.");
    }

    public function show(FuelReceiving $receiving)
    {
        $receiving->load(['storage.site', 'vendor', 'supplierTruck', 'receiver', 'intendedApprover', 'approver']);
        return view('fuel.receivings.show', compact('receiving'));
    }

    public function approve(FuelReceiving $receiving)
    {
        if ($receiving->status === 'Approved') {
            return back()->with('info', 'Penerimaan BBM ini sudah pernah disetujui sebelumnya.');
        }

        try {
            $this->stockService->approveReceiving($receiving, auth()->id());
            return redirect()->route('fuel.receivings.show', $receiving)->with('success', 'Penerimaan BBM berhasil disetujui dan stok tangki telah diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memproses approval: ' . $e->getMessage());
        }
    }

    public function reject(Request $request, FuelReceiving $receiving)
    {
        $request->validate(['rejected_reason' => 'required|string|max:500']);

        $this->stockService->rejectReceiving($receiving, auth()->id(), $request->rejected_reason);
        return redirect()->route('fuel.receivings.show', $receiving)->with('warning', 'Penerimaan BBM telah ditolak.');
    }

    public function exportPdf(FuelReceiving $receiving)
    {
        $receiving->load(['storage.site', 'vendor', 'supplierTruck', 'receiver', 'intendedApprover', 'approver']);
        $pdf = Pdf::loadView('fuel.receivings.pdf', compact('receiving'))->setPaper('a4', 'portrait');
        return $pdf->stream("Berita_Acara_Penerimaan_BBM_{$receiving->receiving_number}.pdf");
    }

    public function destroy(FuelReceiving $receiving)
    {
        $isSuperAdmin = auth()->user()->hasRole('Super Admin');

        if ($receiving->status === 'Approved' && !$isSuperAdmin) {
            return back()->with('error', 'Penerimaan BBM yang sudah disetujui (Approved) hanya dapat dibatalkan & dihapus oleh Super Admin.');
        }

        try {
            $recNumber = $receiving->receiving_number;
            $wasApproved = $receiving->status === 'Approved';

            $this->stockService->rollbackAndForceDeleteReceiving($receiving, auth()->id());

            $msg = $wasApproved
                ? "Transaksi Penerimaan BBM {$recNumber} berhasil dibatalkan, penambahan stok tangki telah dikembalikan (rollback), dan seluruh data transaksi terkait telah dihapus otomatis."
                : "Penerimaan BBM {$recNumber} berhasil dihapus.";

            return redirect()->route('fuel.receivings.index')->with('success', $msg);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal membatalkan transaksi: ' . $e->getMessage());
        }
    }
}
