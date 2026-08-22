<?php

namespace App\Http\Controllers;

use App\Models\FuelFlowmeterAdjustment;
use App\Models\FuelStorage;
use App\Models\FuelTruck;
use App\Models\Site;
use App\Models\User;
use App\Services\FuelStockService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FuelFlowmeterAdjustmentController extends Controller
{
    protected FuelStockService $stockService;

    public function __construct(FuelStockService $stockService)
    {
        $this->stockService = $stockService;
        $this->middleware('permission:view_fuel_flowmeter_adjustments')->only(['index', 'show', 'exportPdf']);
        $this->middleware('permission:create_fuel_flowmeter_adjustments')->only(['create', 'store']);
        $this->middleware('permission:approve_flowmeter_adjustments')->only(['approve']);
    }

    public function index(Request $request)
    {
        $siteId = $request->get('site_id');
        $adjustments = FuelFlowmeterAdjustment::with(['site', 'creator', 'managerUser'])
            ->when($siteId, fn($q) => $q->where('site_id', $siteId))
            ->orderBy('incident_date', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(15)
            ->withQueryString();

        $sites = Site::orderBy('name')->get();

        return view('fuel.flowmeter_adjustments.index', compact('adjustments', 'sites', 'siteId'));
    }

    public function create()
    {
        $storages = FuelStorage::where('is_active', true)->orderBy('code')->get();
        $fuelTrucks = FuelTruck::with('masterUnit')->where('is_active', true)->get();
        $managers = User::where('status', 'active')->orderBy('nama_lengkap')->get();
        $autoNumber = FuelFlowmeterAdjustment::generateAdjustmentNumber();

        return view('fuel.flowmeter_adjustments.create', compact('storages', 'fuelTrucks', 'managers', 'autoNumber'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'device_type' => 'required|in:fuel_storage,fuel_truck',
            'device_id' => 'required|integer',
            'incident_type' => 'required|string|in:Replacement,Damage / Breakdown,Recalibration / Adjustment',
            'incident_date' => 'required|date',
            'old_flowmeter_serial' => 'nullable|string|max:100',
            'old_totalizer_final' => 'required|numeric|min:0',
            'new_flowmeter_serial' => 'nullable|string|max:100',
            'new_totalizer_initial' => 'required|numeric|min:0',
            'reason' => 'required|string',
            'signed_by_manager_name' => 'required|string|max:255',
            'manager_user_id' => 'nullable|exists:users,id',
            'document_scan' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $siteId = null;
        if ($request->device_type === 'fuel_storage') {
            $s = FuelStorage::findOrFail($request->device_id);
            $siteId = $s->site_id;
        } else {
            $t = FuelTruck::findOrFail($request->device_id);
            $siteId = $t->site_id;
        }

        $docPath = null;
        if ($request->hasFile('document_scan')) {
            $docPath = $request->file('document_scan')->store('fuel_documents/flowmeters', 'public');
        }

        $adj = FuelFlowmeterAdjustment::create([
            'adjustment_number' => FuelFlowmeterAdjustment::generateAdjustmentNumber(),
            'site_id' => $siteId,
            'device_type' => $request->device_type,
            'device_id' => $request->device_id,
            'incident_type' => $request->incident_type,
            'incident_date' => $request->incident_date,
            'old_flowmeter_serial' => $request->old_flowmeter_serial,
            'old_totalizer_final' => $request->old_totalizer_final,
            'new_flowmeter_serial' => $request->new_flowmeter_serial,
            'new_totalizer_initial' => $request->new_totalizer_initial,
            'reason' => $request->reason,
            'document_scan' => $docPath,
            'signed_by_manager_name' => $request->signed_by_manager_name,
            'manager_user_id' => $request->manager_user_id,
            'signed_at' => now(),
            'status' => 'Approved',
            'created_by' => auth()->id(),
        ]);

        // Terapkan perubahan totalizer ke device terkait
        $this->stockService->applyFlowmeterAdjustment($adj, auth()->id());

        return redirect()->route('fuel.flowmeter-adjustments.show', $adj)->with('success', "Berita Acara Flowmeter {$adj->adjustment_number} berhasil disimpan dan totalizer telah disesuaikan.");
    }

    public function show(FuelFlowmeterAdjustment $flowmeterAdjustment)
    {
        $flowmeterAdjustment->load(['site', 'creator', 'managerUser']);
        $device = null;
        if ($flowmeterAdjustment->device_type === 'fuel_storage') {
            $device = FuelStorage::find($flowmeterAdjustment->device_id);
        } else {
            $device = FuelTruck::with('masterUnit')->find($flowmeterAdjustment->device_id);
        }

        return view('fuel.flowmeter_adjustments.show', [
            'adjustment' => $flowmeterAdjustment,
            'device' => $device,
        ]);
    }

    public function exportPdf(FuelFlowmeterAdjustment $flowmeterAdjustment)
    {
        $flowmeterAdjustment->load(['site', 'creator', 'managerUser']);
        $device = null;
        if ($flowmeterAdjustment->device_type === 'fuel_storage') {
            $device = FuelStorage::find($flowmeterAdjustment->device_id);
        } else {
            $device = FuelTruck::with('masterUnit')->find($flowmeterAdjustment->device_id);
        }

        $pdf = Pdf::loadView('fuel.flowmeter_adjustments.pdf', [
            'adjustment' => $flowmeterAdjustment,
            'device' => $device,
        ])->setPaper('a4', 'portrait');

        return $pdf->stream("Berita_Acara_Flowmeter_{$flowmeterAdjustment->adjustment_number}.pdf");
    }
}
