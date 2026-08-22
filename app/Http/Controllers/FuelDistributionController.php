<?php

namespace App\Http\Controllers;

use App\Models\FuelDistribution;
use App\Models\FuelDistributionShift;
use App\Models\FuelTruck;
use App\Models\MasterUnit;
use App\Models\Site;
use App\Services\FuelStockService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class FuelDistributionController extends Controller
{
    protected FuelStockService $stockService;

    public function __construct(FuelStockService $stockService)
    {
        $this->stockService = $stockService;
        $this->middleware('permission:view_fuel_distributions')->only(['index', 'show', 'exportPdf']);
        $this->middleware('permission:create_fuel_distributions')->only(['create', 'store', 'storeUnitItem', 'deleteUnitItem', 'closeShift']);
    }

    public function index(Request $request)
    {
        $siteId = $request->get('site_id');
        $truckId = $request->get('fuel_truck_id');
        $status = $request->get('status');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');

        $query = FuelDistributionShift::with(['fuelTruck.masterUnit', 'distributions.masterUnit', 'creator', 'closer', 'site']);

        if ($siteId) $query->where('site_id', $siteId);
        if ($truckId) $query->where('fuel_truck_id', $truckId);
        if ($status) $query->where('status', $status);
        if ($dateFrom) $query->whereDate('date', '>=', $dateFrom);
        if ($dateTo) $query->whereDate('date', '<=', $dateTo);

        $shifts = $query->orderBy('date', 'desc')->orderBy('id', 'desc')->paginate(15)->withQueryString();

        $sites = Site::orderBy('name')->get();
        $fuelTrucks = FuelTruck::with('masterUnit')->where('is_active', true)->get();

        return view('fuel.distributions.index', compact('shifts', 'sites', 'fuelTrucks', 'siteId', 'truckId', 'status', 'dateFrom', 'dateTo'));
    }

    public function create()
    {
        $fuelTrucks = FuelTruck::with('masterUnit')->where('is_active', true)->get();
        $autoNumber = FuelDistributionShift::generateShiftDocNumber();

        return view('fuel.distributions.create', compact('fuelTrucks', 'autoNumber'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'fuel_truck_id' => 'required|exists:fuel_trucks,id',
            'date' => 'required|date',
            'shift' => 'required|in:Shift 1,Shift 2',
            'fuelman_name' => 'required|string|max:255',
            'totalizer_start' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $truck = FuelTruck::findOrFail($request->fuel_truck_id);

        $shift = FuelDistributionShift::create([
            'shift_doc_number' => FuelDistributionShift::generateShiftDocNumber(),
            'site_id' => $truck->site_id,
            'fuel_truck_id' => $request->fuel_truck_id,
            'date' => $request->date,
            'shift' => $request->shift,
            'fuelman_name' => $request->fuelman_name,
            'totalizer_start' => $request->totalizer_start,
            'status' => 'Open',
            'notes' => $request->notes,
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('fuel.distributions.show', $shift)->with('success', "Sesi distribusi shift {$shift->shift_doc_number} berhasil dibuka. Silakan input pengisian unit.");
    }

    public function show(FuelDistributionShift $distribution)
    {
        $distribution->load(['fuelTruck.masterUnit', 'distributions.masterUnit.type', 'creator', 'closer', 'site']);

        $units = MasterUnit::with('type')
            ->where('active', 1)
            ->when($distribution->site_id, fn($q) => $q->where('site_id', $distribution->site_id))
            ->orderBy('nomor_unit')
            ->get();

        return view('fuel.distributions.show', [
            'shift' => $distribution,
            'units' => $units,
        ]);
    }

    public function storeUnitItem(Request $request, FuelDistributionShift $distribution)
    {
        if ($distribution->status === 'Closed') {
            return back()->with('error', 'Sesi shift ini sudah ditutup dan tidak dapat ditambahkan pengisian baru.');
        }

        $request->validate([
            'master_unit_id' => 'required|exists:master_units,id',
            'dispense_time' => 'required|date',
            'meter_reading' => 'nullable|numeric|min:0',
            'meter_type' => 'required|in:HM,KM',
            'unit_operator_name' => 'nullable|string|max:255',
            'volume_liters' => 'required|numeric|min:0.1',
            'location' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        FuelDistribution::create([
            'fuel_distribution_shift_id' => $distribution->id,
            'fuel_truck_id' => $distribution->fuel_truck_id,
            'master_unit_id' => $request->master_unit_id,
            'site_id' => $distribution->site_id,
            'dispense_time' => $request->dispense_time,
            'meter_reading' => $request->meter_reading,
            'meter_type' => $request->meter_type,
            'unit_operator_name' => $request->unit_operator_name,
            'volume_liters' => $request->volume_liters,
            'location' => $request->location,
            'notes' => $request->notes,
            'created_by' => auth()->id(),
        ]);

        // Update running total in shift
        $tot = $distribution->distributions()->sum('volume_liters');
        $distribution->update(['total_liters_distributed' => $tot]);

        return back()->with('success', 'Pengisian unit berhasil dicatat.');
    }

    public function deleteUnitItem(FuelDistribution $item)
    {
        $shift = $item->shiftSession;
        if ($shift && $shift->status === 'Closed') {
            return back()->with('error', 'Tidak dapat menghapus item dari shift yang sudah ditutup.');
        }

        $item->delete();

        if ($shift) {
            $tot = $shift->distributions()->sum('volume_liters');
            $shift->update(['total_liters_distributed' => $tot]);
        }

        return back()->with('success', 'Baris pengisian unit berhasil dihapus.');
    }

    public function closeShift(Request $request, FuelDistributionShift $distribution)
    {
        if ($distribution->status === 'Closed') {
            return back()->with('info', 'Sesi shift ini sudah ditutup sebelumnya.');
        }

        $request->validate([
            'totalizer_end' => 'required|numeric|gte:' . $distribution->totalizer_start,
        ], [
            'totalizer_end.gte' => 'Totalizer Akhir Shift harus lebih besar atau sama dengan Totalizer Awal (' . number_format($distribution->totalizer_start, 2) . ').',
        ]);

        try {
            $this->stockService->closeDistributionShift($distribution, (float) $request->totalizer_end, auth()->id());
            return redirect()->route('fuel.distributions.show', $distribution)->with('success', 'Sesi shift berhasil ditutup dan stok Fuel Truck telah diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menutup shift: ' . $e->getMessage());
        }
    }

    public function exportPdf(FuelDistributionShift $distribution)
    {
        $distribution->load(['fuelTruck.masterUnit', 'distributions.masterUnit.type', 'creator', 'closer', 'site']);
        $pdf = Pdf::loadView('fuel.distributions.pdf', ['shift' => $distribution])->setPaper('a4', 'portrait');
        return $pdf->stream("Laporan_Distribusi_Fuel_{$distribution->shift_doc_number}.pdf");
    }

    public function reopenShift(FuelDistributionShift $distribution)
    {
        if ($distribution->status !== 'Closed') {
            return back()->with('info', 'Sesi shift ini belum ditutup.');
        }

        try {
            $this->stockService->reopenDistributionShift($distribution, auth()->id());
            return redirect()->route('fuel.distributions.show', $distribution)->with('success', "Sesi shift {$distribution->shift_doc_number} berhasil dibuka kembali (Reopen). Pemotongan stok telah dikembalikan ke Fuel Truck.");
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal membuka kembali shift: ' . $e->getMessage());
        }
    }

    public function destroy(FuelDistributionShift $distribution)
    {
        if (!auth()->user()->hasRole('Super Admin')) {
            return back()->with('error', 'Hanya Super Admin yang dapat membatalkan dan menghapus sesi shift distribusi.');
        }

        try {
            $docNum = $distribution->shift_doc_number;
            $this->stockService->rollbackAndForceDeleteDistributionShift($distribution, auth()->id());
            return redirect()->route('fuel.distributions.index')->with('success', "Sesi shift distribusi {$docNum} berhasil dibatalkan, stok Fuel Truck telah dikembalikan, dan seluruh data pengisian unit telah dihapus.");
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal membatalkan sesi shift: ' . $e->getMessage());
        }
    }
}
