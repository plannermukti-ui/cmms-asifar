<?php

namespace App\Http\Controllers;

use App\Models\FuelDistribution;
use App\Models\FuelReceiving;
use App\Models\FuelStockLog;
use App\Models\FuelStorage;
use App\Models\FuelTruck;
use App\Models\MasterUnit;
use App\Models\Site;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class FuelReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_fuel_reports');
    }

    public function currentStock(Request $request)
    {
        $siteId = $request->get('site_id');

        $storages = FuelStorage::with('site')
            ->where('is_active', true)
            ->when($siteId, fn($q) => $q->where('site_id', $siteId))
            ->orderBy('code')
            ->get();

        $fuelTrucks = FuelTruck::with(['masterUnit', 'site'])
            ->where('is_active', true)
            ->when($siteId, fn($q) => $q->where('site_id', $siteId))
            ->get();

        $sites = Site::orderBy('name')->get();

        if ($request->get('export') === 'pdf') {
            $pdf = Pdf::loadView('fuel.reports.current_stock_pdf', compact('storages', 'fuelTrucks', 'sites', 'siteId'))
                ->setPaper('a4', 'landscape');
            return $pdf->stream('Laporan_Stok_Terkini_BBM.pdf');
        }

        return view('fuel.reports.current_stock', compact('storages', 'fuelTrucks', 'sites', 'siteId'));
    }

    public function index(Request $request)
    {
        $siteId = $request->get('site_id');
        $dateFrom = $request->get('date_from', now()->startOfMonth()->toDateString());
        $dateTo = $request->get('date_to', now()->toDateString());
        $unitId = $request->get('master_unit_id');

        // Total Inbound (Receiving)
        $totalInbound = FuelReceiving::where('status', 'Approved')
            ->whereBetween('date_receive', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])
            ->when($siteId, fn($q) => $q->where('site_id', $siteId))
            ->sum('received_volume_liters');

        // Total Outbound (Distributed to Units)
        $totalOutbound = FuelDistribution::whereBetween('dispense_time', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])
            ->when($siteId, fn($q) => $q->where('site_id', $siteId))
            ->when($unitId, fn($q) => $q->where('master_unit_id', $unitId))
            ->sum('volume_liters');

        // Distribution by Unit Breakdown
        $unitDistributions = FuelDistribution::with('masterUnit.type')
            ->selectRaw('master_unit_id, sum(volume_liters) as total_liters, count(*) as fill_count, min(meter_reading) as min_meter, max(meter_reading) as max_meter')
            ->whereBetween('dispense_time', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])
            ->when($siteId, fn($q) => $q->where('site_id', $siteId))
            ->when($unitId, fn($q) => $q->where('master_unit_id', $unitId))
            ->groupBy('master_unit_id')
            ->orderByDesc('total_liters')
            ->get();

        $sites = Site::orderBy('name')->get();
        $units = MasterUnit::where('active', 1)->orderBy('nomor_unit')->get();

        if ($request->get('export') === 'pdf') {
            $pdf = Pdf::loadView('fuel.reports.summary_pdf', compact('totalInbound', 'totalOutbound', 'unitDistributions', 'dateFrom', 'dateTo', 'siteId', 'unitId'))
                ->setPaper('a4', 'portrait');
            return $pdf->stream("Laporan_Rekap_BBM_{$dateFrom}_sd_{$dateTo}.pdf");
        }

        return view('fuel.reports.index', compact('totalInbound', 'totalOutbound', 'unitDistributions', 'dateFrom', 'dateTo', 'sites', 'units', 'siteId', 'unitId'));
    }

    public function stockCard(Request $request)
    {
        $siteId = $request->get('site_id');
        $refType = $request->get('reference_type', 'fuel_storage');
        $refId = $request->get('reference_id');
        $dateFrom = $request->get('date_from', now()->subDays(30)->toDateString());
        $dateTo = $request->get('date_to', now()->toDateString());

        $query = FuelStockLog::whereBetween('date_time', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59']);
        if ($siteId) $query->where('site_id', $siteId);
        if ($refType) $query->where('reference_type', $refType);
        if ($refId) $query->where('reference_id', $refId);

        $logs = $query->orderBy('date_time', 'desc')->orderBy('id', 'desc')->paginate(25)->withQueryString();

        $storages = FuelStorage::where('is_active', true)->orderBy('code')->get();
        $fuelTrucks = FuelTruck::with('masterUnit')->where('is_active', true)->get();
        $sites = Site::orderBy('name')->get();

        return view('fuel.reports.stock_card', compact('logs', 'storages', 'fuelTrucks', 'sites', 'siteId', 'refType', 'refId', 'dateFrom', 'dateTo'));
    }
}
