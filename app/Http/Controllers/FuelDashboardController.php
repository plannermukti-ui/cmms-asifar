<?php

namespace App\Http\Controllers;

use App\Models\FuelDistribution;
use App\Models\FuelReceiving;
use App\Models\FuelStockLog;
use App\Models\FuelStorage;
use App\Models\FuelTruck;
use App\Models\Site;
use Illuminate\Http\Request;

class FuelDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_fuel_management');
    }

    public function index(Request $request)
    {
        $siteId = $request->get('site_id');

        // Fuel Storages
        $storagesQuery = FuelStorage::where('is_active', true);
        if ($siteId) {
            $storagesQuery->where('site_id', $siteId);
        }
        $storages = $storagesQuery->orderBy('code')->get();

        // Fuel Trucks
        $trucksQuery = FuelTruck::with('masterUnit')->where('is_active', true);
        if ($siteId) {
            $trucksQuery->where('site_id', $siteId);
        }
        $trucks = $trucksQuery->get();

        // Total Stock Stats
        $totalStorageStock = $storages->sum('current_stock');
        $totalStorageCapacity = $storages->sum('capacity');
        $totalTruckStock = $trucks->sum('current_stock');
        $totalTruckCapacity = $trucks->sum('capacity');
        $overallTotalStock = $totalStorageStock + $totalTruckStock;

        // Today's Inbound & Outbound
        $today = now()->toDateString();
        $todayReceived = FuelReceiving::whereDate('date_receive', $today)
            ->where('status', 'Approved')
            ->when($siteId, fn($q) => $q->where('site_id', $siteId))
            ->sum('received_volume_liters');

        $todayDistributed = FuelDistribution::whereDate('dispense_time', $today)
            ->when($siteId, fn($q) => $q->where('site_id', $siteId))
            ->sum('volume_liters');

        // Recent Transactions Log
        $recentLogs = FuelStockLog::when($siteId, fn($q) => $q->where('site_id', $siteId))
            ->orderBy('date_time', 'desc')
            ->orderBy('id', 'desc')
            ->limit(10)
            ->get();

        // Monthly Stats (Last 7 Days Chart)
        $chartDates = [];
        $chartInbound = [];
        $chartOutbound = [];
        for ($i = 6; $i >= 0; $i--) {
            $d = now()->subDays($i)->toDateString();
            $dLabel = now()->subDays($i)->format('d M');
            $chartDates[] = $dLabel;

            $in = FuelReceiving::whereDate('date_receive', $d)
                ->where('status', 'Approved')
                ->when($siteId, fn($q) => $q->where('site_id', $siteId))
                ->sum('received_volume_liters');

            $out = FuelDistribution::whereDate('dispense_time', $d)
                ->when($siteId, fn($q) => $q->where('site_id', $siteId))
                ->sum('volume_liters');

            $chartInbound[] = (float) $in;
            $chartOutbound[] = (float) $out;
        }

        $sites = Site::orderBy('name')->get();

        return view('fuel.dashboard', compact(
            'storages',
            'trucks',
            'totalStorageStock',
            'totalStorageCapacity',
            'totalTruckStock',
            'totalTruckCapacity',
            'overallTotalStock',
            'todayReceived',
            'todayDistributed',
            'recentLogs',
            'chartDates',
            'chartInbound',
            'chartOutbound',
            'sites',
            'siteId'
        ));
    }
}
