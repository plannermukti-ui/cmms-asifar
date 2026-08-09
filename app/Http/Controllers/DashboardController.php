<?php

namespace App\Http\Controllers;

use App\Models\WorkOrder;
use App\Models\MasterUnit;
use App\Models\HourMeter;
use App\Models\ToolTransaction;
use App\Models\User;
use App\Models\Message;
use App\Models\Site;
use App\Models\UnitType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user        = Auth::user();
        $allSites    = Site::orderBy('name')->get(['id', 'name', 'code']);

        // Filter: Admin (no site) bisa pilih site; user biasa terkunci ke sitenya sendiri
        if ($user->site_id) {
            // User terikat ke satu site — tidak bisa memilih site lain
            $filterSiteId = $user->site_id;
            $selectedSite = $allSites->firstWhere('id', $filterSiteId);
        } else {
            // Admin / superuser — bisa pilih dari semua site, atau "Semua Site"
            $siteInput = $request->input('site_id');
            if (is_array($siteInput) && count($siteInput) === 1 && strpos($siteInput[0], ',') !== false) $siteInput = explode(',', $siteInput[0]);
            if (is_array($siteInput)) { $siteInput = array_filter($siteInput); if (empty($siteInput)) $siteInput = null; }
            $filterSiteId = $siteInput ? (array) $siteInput : null;
            $selectedSite = null; // Displaying multiple sites requires adjusting the view logic
        }

        // Manual refresh: hapus cache untuk site ini
        $cacheSuffix = $filterSiteId ? implode('_', $filterSiteId) : 'all';
        if ($request->boolean('refresh')) {
            Cache::forget('dashboard_stats_' . $cacheSuffix);
            Cache::forget('dashboard_chart_' . $cacheSuffix);
            Cache::forget('dashboard_unit_comparison');
            return redirect()->route('dashboard', $filterSiteId ? ['site_id' => $filterSiteId] : [])
                ->with('success', 'Data dashboard berhasil diperbarui.');
        }

        // =========================================================
        // LAYER: CACHING STATS (refresh every 10 minutes per site)
        // =========================================================
        $cacheKey = 'dashboard_stats_' . $cacheSuffix;

        $stats = Cache::remember($cacheKey, 600, function () use ($filterSiteId) {
            $woQuery   = WorkOrder::query();
            $unitQuery = MasterUnit::query();
            $hmQuery   = HourMeter::query();
            $ttQuery   = ToolTransaction::query();

            if ($filterSiteId) {
                $woQuery->whereIn('site_id', $filterSiteId);
                $unitQuery->whereIn('site_id', $filterSiteId);
                $hmQuery->whereIn('site_id', $filterSiteId);
            }

            $now = Carbon::now();

            return [
                'wo_open'         => (clone $woQuery)->where('status_wo', 'Open')->count(),
                'wo_inprogress'   => (clone $woQuery)->where('status_wo', 'In Progress')->count(),
                'wo_pending'      => (clone $woQuery)->where('status_wo', 'Pending')->count(),
                'wo_completed'    => (clone $woQuery)->whereMonth('created_at', $now->month)->whereYear('created_at', $now->year)->whereIn('status_wo', ['Completed', 'Close'])->count(),
                'wo_total'        => (clone $woQuery)->count(),
                'wo_downtime'     => (clone $woQuery)->where('opportunity', false)->whereIn('status_wo', ['Open', 'In Progress'])->count(),
                'total_units'     => (clone $unitQuery)->count(),
                'hm_today'        => (clone $hmQuery)->whereDate('date', today())->count(),
                'tools_borrowed'  => (clone $ttQuery)->where('status', 'Borrowed')->count(),
                'pending_users'   => User::where('status', 'pending')->count(),
                'unread_messages' => Message::where('receiver_id', Auth::id())->whereNull('read_at')->count(),
            ];
        });

        // =========================================================
        // CHART DATA: Trends & Status
        // =========================================================
        $chartCacheKey = 'dashboard_chart_' . ($filterSiteId ?? 'all');

        $chartData = Cache::remember($chartCacheKey, 600, function () use ($filterSiteId) {
            $months = collect(range(5, 0))->map(fn($i) => Carbon::now()->subMonths($i));

            $woPerMonth = WorkOrder::query()
                ->when($filterSiteId, fn($q) => $q->where('site_id', $filterSiteId))
                ->selectRaw('YEAR(created_at) as yr, MONTH(created_at) as mo, COUNT(*) as total')
                ->where('created_at', '>=', Carbon::now()->subMonths(6)->startOfMonth())
                ->groupByRaw('YEAR(created_at), MONTH(created_at)')
                ->orderByRaw('YEAR(created_at), MONTH(created_at)')
                ->get()
                ->keyBy(fn($r) => $r->yr . '-' . str_pad($r->mo, 2, '0', STR_PAD_LEFT));

            $trendLabels = [];
            $trendValues = [];
            foreach ($months as $m) {
                $key = $m->format('Y-m');
                $trendLabels[] = $m->translatedFormat('M Y');
                $trendValues[] = $woPerMonth->get($key)?->total ?? 0;
            }

            $woByStatus = WorkOrder::query()
                ->when($filterSiteId, fn($q) => $q->where('site_id', $filterSiteId))
                ->selectRaw('status_wo, COUNT(*) as total')
                ->groupBy('status_wo')
                ->pluck('total', 'status_wo');

            $topUnits = WorkOrder::query()
                ->when($filterSiteId, fn($q) => $q->where('work_orders.site_id', $filterSiteId))
                ->join('master_units', 'work_orders.master_unit_id', '=', 'master_units.id')
                ->selectRaw('master_units.nomor_unit, COUNT(work_orders.id) as wo_count')
                ->groupBy('master_units.id', 'master_units.nomor_unit')
                ->orderByDesc('wo_count')
                ->limit(5)
                ->get();

            return compact('trendLabels', 'trendValues', 'woByStatus', 'topUnits');
        });

        // =========================================================
        // SITE vs SITE — Perbandingan unit per tipe per site
        // Cache 15 menit, shared across all users (bukan per-site)
        // =========================================================
        $unitComparison = Cache::remember('dashboard_unit_comparison', 900, function () {
            $sites     = Site::orderBy('name')->get(['id', 'name', 'code']);
            $unitTypes = UnitType::orderBy('name')->get(['id', 'name']);

            // Aggregate: site_id + unit_type_id => count
            $counts = MasterUnit::selectRaw('site_id, unit_type_id, COUNT(*) as total')
                ->groupBy('site_id', 'unit_type_id')
                ->get()
                ->groupBy('site_id');

            // Build matrix: unitType => [site_id => count]
            $matrix = [];
            foreach ($unitTypes as $ut) {
                $matrix[$ut->id] = [
                    'label'  => $ut->name,
                    'values' => [],
                ];
                foreach ($sites as $site) {
                    $row = $counts->get($site->id)?->firstWhere('unit_type_id', $ut->id);
                    $matrix[$ut->id]['values'][$site->id] = $row?->total ?? 0;
                }
            }

            // Site totals
            $siteTotals = [];
            foreach ($sites as $site) {
                $siteTotals[$site->id] = $counts->get($site->id)?->sum('total') ?? 0;
            }

            return compact('sites', 'unitTypes', 'matrix', 'siteTotals');
        });

        // =========================================================
        // RECENT DATA — Paginated with eager loading (NO N+1)
        // =========================================================
        $recentWo = WorkOrder::query()
            ->when($filterSiteId, fn($q) => $q->where('site_id', $filterSiteId))
            ->with(['unit:id,nomor_unit', 'creator:id,nama_lengkap'])
            ->latest()
            ->limit(8)
            ->get(['id', 'no_wo', 'status_wo', 'tipe_wo', 'master_unit_id', 'created_by', 'waktu_bd', 'created_at', 'opportunity']);

        $recentHm = HourMeter::query()
            ->when($filterSiteId, fn($q) => $q->where('site_id', $filterSiteId))
            ->with(['masterUnit:id,nomor_unit'])
            ->orderByDesc('date')
            ->limit(5)
            ->get(['id', 'master_unit_id', 'hm', 'date']);

        return view('dashboard', compact(
            'stats', 'chartData', 'recentWo', 'recentHm',
            'allSites', 'selectedSite', 'filterSiteId',
            'unitComparison'
        ));
    }
}
