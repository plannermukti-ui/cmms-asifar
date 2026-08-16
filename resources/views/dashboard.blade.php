@extends('layouts.tabler')

@section('title', 'Dashboard - CMMS Aisfar')

@section('content')

<style>
  /* Custom Dashboard Modern Aesthetics */
  .dash-hero-banner {
    background: linear-gradient(135deg, rgba(30, 41, 59, 0.9) 0%, rgba(15, 23, 42, 0.95) 100%);
    border: 1px solid rgba(245, 158, 11, 0.25);
    border-radius: 20px;
    padding: 1.5rem 2rem;
    position: relative;
    z-index: 99;
    overflow: visible;
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
    margin-bottom: 1.5rem;
    backdrop-filter: blur(12px);
  }

  [data-bs-theme="light"] .dash-hero-banner {
    background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
    border: 1px solid rgba(226, 232, 240, 0.8);
    box-shadow: 0 10px 25px rgba(15, 23, 42, 0.05);
  }

  .dash-hero-banner::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -10%;
    width: 300px;
    height: 300px;
    background: radial-gradient(circle, rgba(245, 158, 11, 0.12), transparent 70%);
    pointer-events: none;
    border-radius: 50%;
  }

  .stat-card-modern {
    border-radius: 16px !important;
    border: 1px solid rgba(245, 158, 11, 0.18) !important;
    background: rgba(30, 41, 59, 0.75) !important;
    backdrop-filter: blur(16px);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
  }

  [data-bs-theme="light"] .stat-card-modern {
    background: #ffffff !important;
    border: 1px solid rgba(226, 232, 240, 0.9) !important;
  }

  .stat-card-modern:hover {
    transform: translateY(-4px);
    box-shadow: 0 18px 35px -10px rgba(0, 0, 0, 0.4) !important;
    border-color: rgba(245, 158, 11, 0.45) !important;
  }

  .stat-icon-wrapper {
    width: 48px;
    height: 48px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.35rem;
    box-shadow: 0 8px 18px rgba(0, 0, 0, 0.2);
  }

  .pulse-indicator {
    display: inline-block;
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background-color: #22c55e;
    box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.7);
    animation: pulseGlow 2s infinite;
  }

  @keyframes pulseGlow {
    0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.7); }
    70% { transform: scale(1); box-shadow: 0 0 0 6px rgba(34, 197, 94, 0); }
    100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(34, 197, 94, 0); }
  }

  .table-modern tbody tr {
    transition: background-color 0.2s ease;
  }

  .table-modern tbody tr:hover {
    background-color: rgba(245, 158, 11, 0.05) !important;
  }

  .btn-refresh-spin:hover svg {
    animation: spinOnce 0.6s ease-in-out;
  }

  @keyframes spinOnce {
    100% { transform: rotate(360deg); }
  }
</style>

{{-- ================================================================== --}}
{{-- HERO BANNER + QUICK METRICS + FILTER --}}
{{-- ================================================================== --}}
<div class="dash-hero-banner">
  <div class="row align-items-center g-3">
    <div class="col-md-7">
      <div class="d-flex align-items-center gap-2 mb-2">
        <span class="pulse-indicator"></span>
        <span class="badge bg-warning-lt text-warning fw-bold px-2.5 py-1" style="font-size:0.75rem; letter-spacing:0.5px; border-radius:30px;">
          SYSTEM CMMS OPERATIONAL
        </span>
        @if($selectedSite)
          <span class="badge bg-blue text-white px-2.5 py-1" style="border-radius:30px;">📍 Site {{ $selectedSite->name }} ({{ $selectedSite->code }})</span>
        @else
          <span class="badge bg-purple-lt text-purple px-2.5 py-1" style="border-radius:30px;">🌐 Monitoring Multi Site</span>
        @endif
      </div>

      <h2 class="fw-bold mb-1" style="font-size: 1.6rem; letter-spacing: -0.5px;">
        Halo, <span class="text-warning">{{ Auth::user()->nama_lengkap }}</span>! 👋
      </h2>
      <p class="text-secondary mb-0" style="font-size: 0.88rem;">
        Berikut ringkasan performa pemeliharaan unit alat berat & aktivitas operasional secara real-time.
      </p>
    </div>

    <div class="col-md-5">
      <div class="d-flex align-items-center justify-content-md-end gap-2 flex-wrap">
        {{-- Site Selector --}}
        @if(!Auth::user()->site_id)
        <form method="GET" action="{{ route('dashboard') }}" id="siteFilterForm" class="d-flex align-items-center gap-2">
          <div class="input-icon">
            <span class="input-icon-addon">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon text-warning" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M12 2a15.3 15.3 0 0 1 4 10a15.3 15.3 0 0 1 -4 10a15.3 15.3 0 0 1 -4 -10a15.3 15.3 0 0 1 4 -10z" /><path d="M2.5 9h19" /><path d="M2.5 15h19" /></svg>
            </span>
            <select name="site_id[]" class="form-select form-select-sm fw-bold border-warning-subtle excel-filter" multiple style="min-width: 190px;" data-placeholder="🌐 Semua Site">
              @foreach($allSites as $site)
                <option value="{{ $site->id }}" {{ is_array($filterSiteId) && in_array($site->id, $filterSiteId) ? 'selected' : '' }}>
                  📍 {{ $site->name }} ({{ $site->code }})
                </option>
              @endforeach
            </select>
          </div>
          <button type="submit" class="btn btn-sm btn-primary">Filter</button>
        </form>
        @endif

        {{-- Refresh Button --}}
        <a href="{{ route('dashboard', array_filter(array_merge(request()->query(), ['refresh' => 1]))) }}"
           class="btn btn-sm btn-warning text-dark font-weight-bold btn-refresh-spin"
           style="border-radius: 10px; padding: 0.45rem 0.9rem;"
           title="Perbarui Data Sekarang (Cache Reset)">
          <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="18" height="18" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -4v4h4"/><path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4"/></svg>
          Refresh Data
        </a>
      </div>
    </div>
  </div>
</div>

{{-- ================================================================== --}}
{{-- ROW 1: SUMMARY METRIC CARDS --}}
{{-- ================================================================== --}}
<div class="row row-deck row-cards mb-4">

  {{-- WO Aktif --}}
  <div class="col-sm-6 col-xl-3">
    <div class="card card-sm stat-card-modern">
      <div class="card-body">
        <div class="d-flex align-items-center justify-content-between mb-2">
          <span class="text-secondary fw-semibold" style="font-size:0.83rem;">WORK ORDER AKTIF</span>
          <div class="stat-icon-wrapper bg-warning text-dark">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2" /><path d="M9 3m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z" /><path d="M9 14l2 2l4 -4" /></svg>
          </div>
        </div>
        <div class="h1 mb-2 fw-bold text-warning" style="font-size:2.2rem; line-height:1;">
          {{ $stats['wo_open'] + $stats['wo_inprogress'] }}
        </div>
        <div class="d-flex align-items-center justify-content-between pt-2 border-top" style="border-color: rgba(245,158,11,0.15) !important; font-size:0.78rem;">
          <span class="text-secondary">Open: <strong class="text-warning">{{ $stats['wo_open'] }}</strong></span>
          <span class="text-secondary">In Progress: <strong class="text-info">{{ $stats['wo_inprogress'] }}</strong></span>
        </div>
      </div>
    </div>
  </div>

  {{-- WO Breakdown --}}
  <div class="col-sm-6 col-xl-3">
    <div class="card card-sm stat-card-modern">
      <div class="card-body">
        <div class="d-flex align-items-center justify-content-between mb-2">
          <span class="text-secondary fw-semibold" style="font-size:0.83rem;">UNIT BREAKDOWN</span>
          <div class="stat-icon-wrapper bg-danger text-white">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 9v4"/><path d="M10.363 3.591l-8.106 13.534a1.914 1.914 0 0 0 1.636 2.871h16.214a1.914 1.914 0 0 0 1.636 -2.87l-8.106 -13.536a1.914 1.914 0 0 0 -3.274 0z"/><path d="M12 16h.01"/></svg>
          </div>
        </div>
        <div class="h1 mb-2 fw-bold text-danger" style="font-size:2.2rem; line-height:1;">
          {{ $stats['wo_downtime'] }}
        </div>
        <div class="pt-2 border-top" style="border-color: rgba(239,68,68,0.15) !important; font-size:0.78rem;">
          <span class="text-secondary">Butuh penanganan perbaikan aktif</span>
        </div>
      </div>
    </div>
  </div>

  {{-- Selesai Bulan Ini --}}
  <div class="col-sm-6 col-xl-3">
    <div class="card card-sm stat-card-modern">
      <div class="card-body">
        <div class="d-flex align-items-center justify-content-between mb-2">
          <span class="text-secondary fw-semibold" style="font-size:0.83rem;">SELESAI BULAN INI</span>
          <div class="stat-icon-wrapper bg-success text-white">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10"/></svg>
          </div>
        </div>
        <div class="h1 mb-2 fw-bold text-success" style="font-size:2.2rem; line-height:1;">
          {{ $stats['wo_completed'] }}
        </div>
        <div class="pt-2 border-top" style="border-color: rgba(34,197,94,0.15) !important; font-size:0.78rem;">
          <span class="text-secondary">Status Completed & Closed</span>
        </div>
      </div>
    </div>
  </div>

  {{-- Total Unit --}}
  <div class="col-sm-6 col-xl-3">
    <div class="card card-sm stat-card-modern">
      <div class="card-body">
        <div class="d-flex align-items-center justify-content-between mb-2">
          <span class="text-secondary fw-semibold" style="font-size:0.83rem;">POPULASI UNIT</span>
          <div class="stat-icon-wrapper bg-info text-white">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 17m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"/><path d="M17 17m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"/><path d="M5 17h-2v-6l2 -5h9l4 5h1a2 2 0 0 1 2 2v4h-2m-4 0h-6m-6 -6h15m-6 0v-5"/></svg>
          </div>
        </div>
        <div class="h1 mb-2 fw-bold text-info" style="font-size:2.2rem; line-height:1;">
          {{ $stats['total_units'] }}
        </div>
        <div class="pt-2 border-top" style="border-color: rgba(56,189,248,0.15) !important; font-size:0.78rem;">
          <span class="text-secondary">Update HM Hari Ini: <strong class="text-info">{{ $stats['hm_today'] }}</strong> unit</span>
        </div>
      </div>
    </div>
  </div>

</div>

{{-- ================================================================== --}}
{{-- ROW 2: CHARTS TREN & DONUT STATUS --}}
{{-- ================================================================== --}}
<div class="row row-cards mb-4">
  <div class="col-lg-7">
    <div class="card stat-card-modern h-100">
      <div class="card-header py-2 px-3 d-flex align-items-center justify-content-between">
        <h3 class="card-title text-warning m-0 d-flex align-items-center" style="font-size: 0.95rem;">
          <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2 text-warning" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 12m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v6a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" /><path d="M9 8m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v10a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" /><path d="M15 4m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v14a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" /></svg>
          Tren Work Order (6 Bulan Terakhir)
        </h3>
        <span class="badge bg-warning-lt" style="font-size:0.7rem;">Grafik Aktivitas</span>
      </div>
      <div class="card-body p-3">
        <div style="position: relative; height: 190px; width: 100%;">
          <canvas id="chartWoTrend"></canvas>
        </div>
      </div>
    </div>
  </div>

  <div class="col-lg-5">
    <div class="card stat-card-modern h-100">
      <div class="card-header py-2 px-3 d-flex align-items-center justify-content-between">
        <h3 class="card-title text-warning m-0 d-flex align-items-center" style="font-size: 0.95rem;">
          <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2 text-warning" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M12 12l8 -4.5" /><path d="M12 12v9" /><path d="M12 12l-8 -4.5" /></svg>
          Distribusi Status WO
        </h3>
      </div>
      <div class="card-body p-3 d-flex align-items-center justify-content-center">
        <div style="position: relative; height: 190px; width: 100%; display: flex; justify-content: center; align-items: center;">
          <canvas id="chartWoStatus" style="max-height: 180px; max-width: 100%;"></canvas>
        </div>
      </div>
    </div>
  </div>
</div>

{{-- ================================================================== --}}
{{-- ROW 3: TREN DURASI SUMMARY --}}
{{-- ================================================================== --}}
<div class="row row-cards mb-4">
  <div class="col-lg-6">
    <div class="card stat-card-modern h-100">
      <div class="card-header py-2 px-3 d-flex align-items-center justify-content-between">
        <h3 class="card-title text-warning m-0 d-flex align-items-center" style="font-size: 0.95rem;">
          <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2 text-warning" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 12m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v6a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" /><path d="M9 8m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v10a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" /><path d="M15 4m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v14a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" /></svg>
          Tren Ringkasan Durasi & Waktu Tanggap (4 Minggu Terakhir)
        </h3>
        <span class="badge bg-warning-lt" style="font-size:0.7rem;">Average per Minggu</span>
      </div>
      <div class="card-body p-3">
        <div style="position: relative; height: 250px; width: 100%;">
          <canvas id="chartDurationTrend"></canvas>
        </div>
      </div>
    </div>
  </div>

  <div class="col-lg-6">
    <div class="card stat-card-modern h-100">
      <div class="card-header py-2 px-3 d-flex align-items-center justify-content-between">
        <h3 class="card-title text-success m-0 d-flex align-items-center" style="font-size: 0.95rem;">
          <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2 text-success" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 12m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v6a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" /><path d="M9 8m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v10a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" /><path d="M15 4m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v14a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" /></svg>
          Plan Achievement Trend (6 Bulan Terakhir)
        </h3>
        <span class="badge bg-success-lt" style="font-size:0.7rem;">Achievement Plan</span>
      </div>
      <div class="card-body p-3">
        <div style="position: relative; height: 250px; width: 100%;">
          <canvas id="chartPlanAchievement"></canvas>
        </div>
      </div>
    </div>
  </div>
</div>


{{-- ================================================================== --}}
{{-- ROW 4: TOP UNITS & RECENT WORK ORDERS --}}
{{-- ================================================================== --}}
<div class="row row-cards mb-4">
  <div class="col-lg-5">
    <div class="card stat-card-modern h-100">
      <div class="card-header py-2 px-3">
        <h3 class="card-title text-warning m-0 d-flex align-items-center" style="font-size: 0.95rem;">
          <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2 text-warning" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 10h3v-3l-3.5 -3.5a6 6 0 0 1 8 8l6 6a2 2 0 0 1 -3 3l-6 -6a6 6 0 0 1 -8 -8l3.5 3.5" /></svg>
          Top 5 Unit — WO Terbanyak
        </h3>
      </div>
      <div class="card-body p-3">
        <div style="position: relative; height: 190px; width: 100%;">
          <canvas id="chartTopUnits"></canvas>
        </div>
      </div>
    </div>
  </div>

  <div class="col-lg-7">
    <div class="card stat-card-modern h-100">
      <div class="card-header py-2 px-3 d-flex justify-content-between align-items-center">
        <h3 class="card-title text-warning m-0 d-flex align-items-center" style="font-size: 0.95rem;">
          <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2 text-warning" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><path d="M9 9l1 0" /><path d="M9 13l6 0" /><path d="M9 17l6 0" /></svg>
          Work Order Terbaru
        </h3>
        <a href="{{ route('work-orders.index') }}" class="btn btn-sm btn-warning text-dark font-weight-bold py-1 px-2" style="border-radius:6px; font-size:0.75rem;">Detail Semua WO</a>
      </div>
      <div class="table-responsive">
        <table class="table table-vcenter table-modern table-sm card-table">
          <thead>
            <tr>
              <th>No WO</th>
              <th>Unit</th>
              <th>Tipe</th>
              <th>Status</th>
              <th>Tanggal</th>
            </tr>
          </thead>
          <tbody>
            @forelse($recentWo as $wo)
            <tr>
              <td class="fw-bold">
                <a href="{{ route('work-orders.show', $wo) }}" class="text-warning text-decoration-none">{{ $wo->no_wo }}</a>
              </td>
              <td class="fw-semibold text-white">{{ $wo->unit?->nomor_unit ?? '-' }}</td>
              <td>
                @if($wo->opportunity)
                  <span class="badge bg-blue-lt text-blue rounded-pill">Opportunity</span>
                @else
                  <span class="badge bg-red-lt text-red rounded-pill">Breakdown</span>
                @endif
              </td>
              <td>
                @php
                  $statusColors = ['Open'=>'warning','In Progress'=>'blue','Pending'=>'orange','Completed'=>'success','Close'=>'secondary'];
                  $color = $statusColors[$wo->status_wo] ?? 'secondary';
                @endphp
                <span class="badge bg-{{ $color }}-lt text-{{ $color }} rounded-pill">{{ $wo->status_wo }}</span>
              </td>
              <td class="text-secondary" style="font-size:0.8rem;">{{ $wo->created_at->format('d/m/Y') }}</td>
            </tr>
            @empty
            <tr><td colspan="5" class="text-center text-secondary py-4">Belum ada Work Order</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

{{-- ================================================================== --}}
{{-- ROW 5: SYSTEM NOTIFICATIONS & RECENT HOUR METERS --}}
{{-- ================================================================== --}}
<div class="row row-cards">
  <div class="col-lg-4">
    <div class="card stat-card-modern h-100">
      <div class="card-header py-2 px-3">
        <h3 class="card-title text-warning m-0 d-flex align-items-center" style="font-size: 0.95rem;">
          <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2 text-warning" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 5a2 2 0 1 1 4 0a7 7 0 0 1 4 6v3a4 4 0 0 0 2 3h-16a4 4 0 0 0 2 -3v-3a7 7 0 0 1 4 -6" /><path d="M9 17v1a3 3 0 0 0 6 0v-1" /></svg>
          Notifikasi Sistem
        </h3>
      </div>
      <div class="list-group list-group-flush">

        <div class="list-group-item d-flex align-items-center py-2.5 bg-transparent">
          <span class="avatar avatar-sm me-3 rounded-circle {{ $stats['pending_users'] > 0 ? 'bg-warning text-dark' : 'bg-secondary-lt text-secondary' }}">
            {{ $stats['pending_users'] > 0 ? $stats['pending_users'] : '✓' }}
          </span>
          <div>
            <div class="fw-bold" style="font-size:0.85rem;">Pendaftaran User Baru</div>
            <div class="text-secondary" style="font-size:0.78rem;">
              @if($stats['pending_users'] > 0)
                <a href="{{ route('users.index') }}" class="text-warning fw-bold">{{ $stats['pending_users'] }} user menunggu persetujuan →</a>
              @else Tidak ada pendaftaran baru @endif
            </div>
          </div>
        </div>

        <div class="list-group-item d-flex align-items-center py-2.5 bg-transparent">
          <span class="avatar avatar-sm me-3 rounded-circle {{ $stats['unread_messages'] > 0 ? 'bg-info text-white' : 'bg-secondary-lt text-secondary' }}">
            {{ $stats['unread_messages'] > 0 ? $stats['unread_messages'] : '✓' }}
          </span>
          <div>
            <div class="fw-bold" style="font-size:0.85rem;">Pesan Belum Dibaca</div>
            <div class="text-secondary" style="font-size:0.78rem;">
              @if($stats['unread_messages'] > 0)
                <a href="{{ route('chat.index') }}" class="text-info fw-bold">Buka pesan sekarang →</a>
              @else Semua pesan sudah dibaca @endif
            </div>
          </div>
        </div>

        <div class="list-group-item d-flex align-items-center py-2.5 bg-transparent">
          <span class="avatar avatar-sm me-3 rounded-circle {{ $stats['tools_borrowed'] > 0 ? 'bg-orange text-white' : 'bg-secondary-lt text-secondary' }}">
            {{ $stats['tools_borrowed'] > 0 ? $stats['tools_borrowed'] : '✓' }}
          </span>
          <div>
            <div class="fw-bold" style="font-size:0.85rem;">Tool Sedang Dipinjam</div>
            <div class="text-secondary" style="font-size:0.78rem;">
              @if($stats['tools_borrowed'] > 0)
                <a href="{{ route('tool-transactions.index') }}" class="text-orange fw-bold">{{ $stats['tools_borrowed'] }} tool aktif dipinjam →</a>
              @else Tidak ada peminjaman aktif @endif
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>

  <div class="col-lg-8">
    <div class="card stat-card-modern h-100">
      <div class="card-header py-2 px-3 d-flex justify-content-between align-items-center">
        <h3 class="card-title text-warning m-0 d-flex align-items-center" style="font-size: 0.95rem;">
          <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2 text-warning" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M12 7v5l3 3" /></svg>
          Hour Meter Terkini
        </h3>
        <a href="{{ route('hour-meters.index') }}" class="btn btn-sm btn-outline-warning py-1 px-2" style="border-radius:6px; font-size:0.75rem;">Detail Semua HM</a>
      </div>
      <div class="table-responsive">
        <table class="table table-vcenter table-modern table-sm card-table">
          <thead>
            <tr><th>Unit</th><th>HM (Jam)</th><th>Tanggal Update</th></tr>
          </thead>
          <tbody>
            @forelse($recentHm as $hm)
            <tr>
              <td class="fw-bold text-white">{{ $hm->masterUnit?->nomor_unit ?? '-' }}</td>
              <td><span class="badge bg-info-lt text-info fw-bold rounded-pill px-2.5">{{ number_format($hm->hm, 1) }} HM</span></td>
              <td class="text-secondary">{{ \Carbon\Carbon::parse($hm->date)->format('d M Y') }}</td>
            </tr>
            @empty
            <tr><td colspan="3" class="text-center text-secondary py-4">Belum ada data Hour Meter</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
  const isDark    = document.body.getAttribute('data-bs-theme') === 'dark';
  const gridColor = isDark ? 'rgba(255,255,255,0.06)' : 'rgba(0,0,0,0.06)';
  const lblColor  = isDark ? '#94a3b8' : '#64748b';
  Chart.defaults.color       = lblColor;
  Chart.defaults.borderColor = gridColor;

  // 1. Smooth Curved Trend Line Chart with Gradient
  const trendCanvas = document.getElementById('chartWoTrend');
  const trendCtx    = trendCanvas.getContext('2d');

  const gradientYellow = trendCtx.createLinearGradient(0, 0, 0, 180);
  gradientYellow.addColorStop(0, 'rgba(245, 158, 11, 0.35)');
  gradientYellow.addColorStop(1, 'rgba(245, 158, 11, 0.0)');

  new Chart(trendCtx, {
    type: 'line',
    data: {
      labels: @json($chartData['trendLabels']),
      datasets: [{
        label: 'Jumlah WO',
        data: @json($chartData['trendValues']),
        backgroundColor: gradientYellow,
        borderColor: '#f59e0b',
        borderWidth: 2.5,
        pointBackgroundColor: '#f59e0b',
        pointBorderColor: isDark ? '#1e293b' : '#fff',
        pointBorderWidth: 2,
        pointRadius: 4,
        pointHoverRadius: 6,
        tension: 0.35,
        fill: true,
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: { display: false },
        tooltip: {
          backgroundColor: isDark ? '#0f172a' : '#ffffff',
          titleColor: '#f59e0b',
          bodyColor: isDark ? '#ffffff' : '#0f172a',
          borderColor: 'rgba(245,158,11,0.3)',
          borderWidth: 1,
          padding: 8,
          boxPadding: 4,
        }
      },
      scales: {
        y: { beginAtZero: true, grid: { color: gridColor }, ticks: { color: lblColor, stepSize: 1, font: { size: 11 } } },
        x: { grid: { display: false }, ticks: { color: lblColor, font: { size: 11 } } }
      }
    }
  });

  // Center text plugin for doughnut chart
  const centerTextPlugin = {
    id: 'centerText',
    beforeDraw(chart) {
      if (chart.config.type !== 'doughnut') return;
      const { width, height, ctx } = chart;
      const dataset = chart.data.datasets[0];
      if (!dataset) return;
      const total = dataset.data.reduce((a, b) => a + Number(b), 0);
      
      ctx.save();
      ctx.textAlign = 'center';
      ctx.textBaseline = 'middle';
      const legendHeight = chart.legend ? chart.legend.height : 0;
      const centerX = width / 2;
      const centerY = (height - legendHeight) / 2 + 5;
      
      ctx.font = 'bold 1.1rem sans-serif';
      ctx.fillStyle = isDark ? '#f8fafc' : '#0f172a';
      ctx.fillText(total.toString(), centerX, centerY - 7);
      
      ctx.font = '500 0.7rem sans-serif';
      ctx.fillStyle = lblColor;
      ctx.fillText('Total WO', centerX, centerY + 9);
      ctx.restore();
    }
  };

  // 2. Status Donut Chart
  const statusData   = @json($chartData['woByStatus']);
  const statusColors = { 'Open':'#f59e0b','In Progress':'#38bdf8','Pending':'#f97316','Completed':'#22c55e','Close':'#94a3b8' };
  new Chart(document.getElementById('chartWoStatus'), {
    type: 'doughnut',
    data: {
      labels: Object.keys(statusData),
      datasets: [{
        data: Object.values(statusData),
        backgroundColor: Object.keys(statusData).map(l => statusColors[l] ?? '#64748b'),
        borderColor: isDark ? '#1e293b' : '#fff',
        borderWidth: 2,
        hoverOffset: 5,
      }]
    },
    plugins: [centerTextPlugin],
    options: {
      responsive: true,
      maintainAspectRatio: true,
      cutout: '72%',
      plugins: {
        legend: {
          position: 'bottom',
          labels: {
            color: lblColor,
            padding: 8,
            boxWidth: 8,
            boxHeight: 8,
            usePointStyle: true,
            pointStyle: 'circle',
            font: { size: 11 }
          }
        },
        tooltip: {
          backgroundColor: isDark ? '#0f172a' : '#ffffff',
          bodyColor: isDark ? '#ffffff' : '#0f172a',
          borderColor: 'rgba(245,158,11,0.3)',
          borderWidth: 1,
          padding: 8
        }
      }
    }
  });

  // 3. Top Units Horizontal Bar Chart
  const topUnits = @json($chartData['topUnits']);
  new Chart(document.getElementById('chartTopUnits'), {
    type: 'bar',
    data: {
      labels: topUnits.map(u => u.nomor_unit),
      datasets: [{
        label: 'Jumlah WO',
        data: topUnits.map(u => u.wo_count),
        backgroundColor: ['#f59e0b','#38bdf8','#22c55e','#f97316','#ef4444'],
        borderRadius: 4,
      }]
    },
    options: {
      indexAxis: 'y',
      responsive: true,
      maintainAspectRatio: false,
      plugins: { legend: { display: false } },
      scales: {
        x: { beginAtZero: true, grid: { color: gridColor }, ticks: { color: lblColor, stepSize: 1, font: { size: 11 } } },
        y: { grid: { display: false }, ticks: { color: lblColor, font: { size: 11 } } }
      }
    }
  });

  // 4. Duration Summary Trend Chart (4 Weeks)
  const durationCanvas = document.getElementById('chartDurationTrend');
  if (durationCanvas) {
    const durationCtx = durationCanvas.getContext('2d');
    new Chart(durationCtx, {
      type: 'line',
      data: {
        labels: @json($chartData['durationTrendLabels']),
        datasets: [
          {
            label: 'Respontime (Avg Hrs)',
            data: @json($chartData['durationTrendRespon'] ?? []),
            borderColor: '#38bdf8',
            backgroundColor: '#38bdf8',
            borderWidth: 2.5,
            pointBackgroundColor: '#38bdf8',
            pointBorderColor: isDark ? '#1e293b' : '#fff',
            pointBorderWidth: 2,
            pointRadius: 4,
            tension: 0.3
          },
          {
            label: 'Total Durasi Subtask (Avg Hrs)',
            data: @json($chartData['durationTrendSubtask'] ?? []),
            borderColor: '#f59e0b',
            backgroundColor: '#f59e0b',
            borderWidth: 2.5,
            pointBackgroundColor: '#f59e0b',
            pointBorderColor: isDark ? '#1e293b' : '#fff',
            pointBorderWidth: 2,
            pointRadius: 4,
            tension: 0.3
          },
          {
            label: 'No Action (Avg Hrs)',
            data: @json($chartData['durationTrendNoAction'] ?? []),
            borderColor: '#ef4444',
            backgroundColor: '#ef4444',
            borderWidth: 2.5,
            pointBackgroundColor: '#ef4444',
            pointBorderColor: isDark ? '#1e293b' : '#fff',
            pointBorderWidth: 2,
            pointRadius: 4,
            tension: 0.3
          }
        ]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { 
            display: true, 
            position: 'top',
            labels: { color: lblColor, font: { size: 11 }, usePointStyle: true, boxWidth: 8 }
          },
          tooltip: {
            backgroundColor: isDark ? '#0f172a' : '#ffffff',
            titleColor: isDark ? '#ffffff' : '#0f172a',
            bodyColor: isDark ? '#ffffff' : '#0f172a',
            borderColor: 'rgba(245,158,11,0.3)',
            borderWidth: 1,
            padding: 8
          }
        },
        scales: {
          y: { beginAtZero: true, grid: { color: gridColor }, ticks: { color: lblColor, font: { size: 11 } } },
          x: { grid: { display: false }, ticks: { color: lblColor, font: { size: 11 } } }
        }
      }
    });
  }

  // 5. Plan Achievement Trend Chart (Last 6 Months)
  const planAchievementCanvas = document.getElementById('chartPlanAchievement');
  if (planAchievementCanvas) {
    const planCtx = planAchievementCanvas.getContext('2d');
    new Chart(planCtx, {
      type: 'bar',
      data: {
        labels: @json($chartData['planTrendLabels']),
        datasets: [
          {
            label: 'Completed (Actual)',
            data: @json($chartData['planTrendCompleted'] ?? []),
            backgroundColor: '#22c55e',
            borderRadius: 4,
          },
          {
            label: 'In Progress (Masih Plan)',
            data: @json($chartData['planTrendInProgress'] ?? []),
            backgroundColor: '#38bdf8',
            borderRadius: 4,
          },
          {
            label: 'Cancel (Plan Gagal)',
            data: @json($chartData['planTrendCancel'] ?? []),
            backgroundColor: '#ef4444',
            borderRadius: 4,
          }
        ]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { 
            display: true, 
            position: 'top',
            labels: { color: lblColor, font: { size: 11 }, usePointStyle: true, boxWidth: 8 }
          },
          tooltip: {
            backgroundColor: isDark ? '#0f172a' : '#ffffff',
            titleColor: isDark ? '#ffffff' : '#0f172a',
            bodyColor: isDark ? '#ffffff' : '#0f172a',
            borderColor: 'rgba(34,197,94,0.3)',
            borderWidth: 1,
            padding: 8
          }
        },
        scales: {
          y: { 
            stacked: true,
            beginAtZero: true, 
            grid: { color: gridColor }, 
            ticks: { color: lblColor, font: { size: 11 }, stepSize: 1 } 
          },
          x: { 
            stacked: true,
            grid: { display: false }, 
            ticks: { color: lblColor, font: { size: 11 } } 
          }
        }
      }
    });
  }
</script>
@endpush

