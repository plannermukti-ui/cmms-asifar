@extends('layouts.tabler')

@section('title', 'KPI Master Data - CMMS Aisfar')

@section('content')
<style>
  /* ── KPI Stat Cards ── */
  .kpi-card-stat {
    border-radius: 8px;
    transition: all 0.25s ease-in-out;
    border: 1px solid rgba(0, 0, 0, 0.05);
  }
  .kpi-card-stat:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.06) !important;
  }
  .kpi-icon-avatar {
    width: 42px;
    height: 42px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  /* ── Filter Card & Unified Inputs ── */
  .filter-card {
    border: 1px solid rgba(98, 105, 118, 0.16);
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
    border-radius: 8px;
    background: #ffffff;
  }
  .filter-label {
    font-size: 0.72rem;
    font-weight: 700;
    color: #64748b;
    margin-bottom: 5px;
    text-transform: uppercase;
    letter-spacing: 0.03em;
    display: flex;
    align-items: center;
    gap: 4px;
    height: 18px;
    white-space: nowrap;
  }
  .filter-control {
    height: 36px !important;
    min-height: 36px !important;
    max-height: 36px !important;
    font-size: 0.82rem !important;
    border-radius: 6px !important;
    border: 1px solid #cbd5e1 !important;
    background-color: #ffffff !important;
    color: #1e293b !important;
    padding: 0.35rem 0.65rem !important;
    box-sizing: border-box !important;
    transition: all 0.15s ease-in-out;
  }
  .filter-btn {
    height: 36px !important;
    min-height: 36px !important;
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    font-size: 0.82rem !important;
    font-weight: 600 !important;
    border-radius: 6px !important;
    padding: 0 12px !important;
    box-sizing: border-box !important;
  }
  .filter-btn-icon {
    height: 36px !important;
    width: 36px !important;
    min-height: 36px !important;
    min-width: 36px !important;
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    border-radius: 6px !important;
    padding: 0 !important;
    box-sizing: border-box !important;
  }

  /* ── KPI Table ── */
  .table-custom th {
    font-weight: 600;
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    background: #f8fafc !important;
    color: #475569;
    border-bottom: 2px solid #e2e8f0 !important;
  }
  .table-custom td {
    vertical-align: middle;
    font-size: 0.82rem;
  }
  .table-custom tbody tr:hover {
    background-color: rgba(59, 130, 246, 0.03) !important;
  }
  .metric-badge {
    font-weight: 600;
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
    border-radius: 12px;
    letter-spacing: 0.3px;
  }

  /* ── Dark Mode Harmonization ── */
  [data-bs-theme="dark"] .filter-card {
    background: #182234 !important;
    border-color: rgba(255, 255, 255, 0.08) !important;
  }
  [data-bs-theme="dark"] .filter-label {
    color: #94a3b8 !important;
  }
  [data-bs-theme="dark"] .filter-control {
    background-color: #1d273b !important;
    border-color: #2c3b52 !important;
    color: #e2e8f0 !important;
  }
  [data-bs-theme="dark"] .kpi-card-stat {
    background-color: #182234 !important;
    border-color: rgba(255, 255, 255, 0.08) !important;
  }
  [data-bs-theme="dark"] .table-custom th {
    background-color: #131c2c !important;
    color: #cbd5e1 !important;
    border-bottom-color: rgba(255, 255, 255, 0.08) !important;
  }
  [data-bs-theme="dark"] .table-custom td {
    border-color: rgba(255, 255, 255, 0.08) !important;
  }
  [data-bs-theme="dark"] .table-custom tbody tr:hover {
    background-color: rgba(255, 255, 255, 0.03) !important;
  }
</style>

<div class="page-header d-print-none mb-3">
  <div class="row align-items-center">
    <div class="col">
      <div class="mb-1 text-muted small fw-medium">
        <span class="badge bg-blue-lt me-1">KPI Analytics</span> Key Performance Indicators
      </div>
      <h2 class="page-title fw-bold text-body">
        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-chart-histogram me-2 text-primary" width="28" height="28" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 3v18h18" /><path d="M20 18v-3" /><path d="M16 18v-6" /><path d="M12 18v-9" /><path d="M8 18v-4" /></svg>
        KPI Master Data Unit
      </h2>
    </div>
    <div class="col-auto ms-auto d-print-none">
      <span class="badge bg-surface-secondary text-secondary px-3 py-2 border shadow-sm">
        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-calendar me-1" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12z" /><path d="M16 3v4" /><path d="M8 3v4" /><path d="M4 11h16" /></svg>
        Periode: <strong>{{ $currentDateRange }}</strong>
      </span>
    </div>
  </div>
</div>

@php
  $avgPa = $units->count() > 0 ? $units->avg('pa') : 0;
  $avgMa = $units->count() > 0 ? $units->avg('ma') : 0;
  $totalOp = $units->sum('op_hrs');
  $totalBd = $units->sum('bd_hrs');
@endphp

<!-- KPI Summary Mini Cards -->
<div class="row row-cards mb-3">
  <div class="col-sm-6 col-lg-3">
    <div class="card card-sm kpi-card-stat shadow-sm">
      <div class="card-body">
        <div class="row align-items-center">
          <div class="col-auto">
            <div class="kpi-icon-avatar bg-success-lt text-success">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10" /></svg>
            </div>
          </div>
          <div class="col">
            <div class="font-weight-medium text-muted small">Rata-rata PA</div>
            <div class="h2 mb-0 fw-bold text-success">{{ number_format($avgPa, 1) }}%</div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-sm-6 col-lg-3">
    <div class="card card-sm kpi-card-stat shadow-sm">
      <div class="card-body">
        <div class="row align-items-center">
          <div class="col-auto">
            <div class="kpi-icon-avatar bg-primary-lt text-primary">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 10h3v-3l-3 3z" /><path d="M17 14h-3v3l3 -3z" /><path d="M10 14v3h3l-3 -3z" /><path d="M14 10v-3h-3l3 3z" /><path d="M12 3a9 9 0 1 0 9 9" /></svg>
            </div>
          </div>
          <div class="col">
            <div class="font-weight-medium text-muted small">Rata-rata MA</div>
            <div class="h2 mb-0 fw-bold text-primary">{{ number_format($avgMa, 1) }}%</div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-sm-6 col-lg-3">
    <div class="card card-sm kpi-card-stat shadow-sm">
      <div class="card-body">
        <div class="row align-items-center">
          <div class="col-auto">
            <div class="kpi-icon-avatar bg-azure-lt text-azure">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M12 7v5l3 3" /></svg>
            </div>
          </div>
          <div class="col">
            <div class="font-weight-medium text-muted small">Total Operating Hours (OP)</div>
            <div class="h2 mb-0 fw-bold text-azure">{{ number_format($totalOp, 1) }} <span class="fs-6 text-muted fw-normal">hrs</span></div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-sm-6 col-lg-3">
    <div class="card card-sm kpi-card-stat shadow-sm">
      <div class="card-body">
        <div class="row align-items-center">
          <div class="col-auto">
            <div class="kpi-icon-avatar bg-danger-lt text-danger">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 9v4" /><path d="M12 17h.01" /><path d="M5 19h14a2 2 0 0 0 1.84 -2.75l-7.1 -12.25a2 2 0 0 0 -3.5 0l-7.1 12.25a2 2 0 0 0 1.75 2.75" /></svg>
            </div>
          </div>
          <div class="col">
            <div class="font-weight-medium text-muted small">Total Breakdown Hours (BD)</div>
            <div class="h2 mb-0 fw-bold text-danger">{{ number_format($totalBd, 1) }} <span class="fs-6 text-muted fw-normal">hrs</span></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Filter Box -->
<div class="card filter-card mb-3">
  <div class="card-body p-2.5">
    <form action="{{ route('kpi.master-data') }}" method="GET" class="w-100">
      <div class="row g-2 align-items-end">
        <div class="col-md-2">
          <label class="filter-label">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-xs" width="14" height="14" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 11a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" /><path d="M17.657 16.657l-4.243 4.243a2 2 0 0 1 -2.827 0l-4.244 -4.243a8 8 0 1 1 11.314 0z" /></svg>
            Site
          </label>
          <select name="site_id[]" class="form-select filter-control excel-filter" multiple data-placeholder="Semua Site">
            @foreach($sites as $site)
              <option value="{{ $site->id }}" {{ is_array($siteId) && in_array($site->id, $siteId) || (!is_array($siteId) && $siteId == $site->id) ? 'selected' : '' }}>{{ $site->name }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-2">
          <label class="filter-label">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-xs" width="14" height="14" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 4h6v6h-6z" /><path d="M14 4h6v6h-6z" /><path d="M4 14h6v6h-6z" /><path d="M17 17m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" /></svg>
            Tipe Unit
          </label>
          <select name="unit_type_id[]" class="form-select filter-control excel-filter" multiple data-placeholder="Semua Tipe">
            @foreach($unitTypes as $type)
              <option value="{{ $type->id }}" {{ is_array($unitTypeId) && in_array($type->id, $unitTypeId) || (!is_array($unitTypeId) && $unitTypeId == $type->id) ? 'selected' : '' }}>{{ $type->name }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-2">
          <label class="filter-label">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-xs" width="14" height="14" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 17m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M17 17m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M5 17h-2v-11a1 1 0 0 1 1 -1h9v12m-4 0h6m4 0h2v-6h-8m0 -5h5l3 5" /></svg>
            Model Unit
          </label>
          <select name="unit_model_id[]" class="form-select filter-control excel-filter" multiple data-placeholder="Semua Model">
            @foreach($unitModels as $model)
              <option value="{{ $model->id }}" {{ is_array($unitModelId) && in_array($model->id, $unitModelId) || (!is_array($unitModelId) && $unitModelId == $model->id) ? 'selected' : '' }}>{{ $model->name }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-2">
          <label class="filter-label">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-xs" width="14" height="14" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 5m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z" /><path d="M16 3l0 4" /><path d="M8 3l0 4" /><path d="M4 11l16 0" /><path d="M8 15h2v2h-2z" /></svg>
            ISO Week
          </label>
          <input type="week" id="week_selector" class="form-control filter-control" title="Pilih Minggu (Otomatis mengisi Range Date)">
        </div>
        <div class="col-md-2">
          <label class="filter-label">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-xs" width="14" height="14" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M12 7v5l3 3" /></svg>
            Range Date BD
          </label>
          <input type="text" name="date_range" id="date_range" class="form-control filter-control" value="{{ $currentDateRange }}" placeholder="YYYY-MM-DD - YYYY-MM-DD">
        </div>
        <div class="col-md-2">
          <div class="d-flex gap-1">
            <button type="submit" class="btn btn-primary filter-btn flex-grow-1 shadow-sm">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 4h16v2.172a2 2 0 0 1 -.586 1.414l-4.414 4.414v7l-6 2v-8.5l-4.414 -4.414a2 2 0 0 1 -.586 -1.414v-2.172z" /></svg>
              Filter
            </button>
            <a href="{{ route('kpi.master-data') }}" class="btn btn-outline-secondary filter-btn-icon" data-bs-toggle="tooltip" title="Reset Filter">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm m-0" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -4v4h4" /><path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4" /></svg>
            </a>
            <button type="submit" formaction="{{ route('kpi.master-data.export') }}" class="btn btn-success filter-btn-icon shadow-sm" data-bs-toggle="tooltip" title="Download Excel">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm m-0" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><path d="M8 11h8v7h-8z" /><path d="M8 15h8" /><path d="M11 11v7" /></svg>
            </button>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Table Card -->
<div class="card filter-card">
  <div class="table-responsive">
    <table class="table table-custom card-table table-vcenter text-nowrap">
      <thead>
        <tr>
          <th class="w-1 text-center">No</th>
          <th>Unit</th>
          <th>Tipe</th>
          <th>Model</th>
          <th class="text-end" data-bs-toggle="tooltip" title="HM Awal pada tanggal filter awal">HM Awal</th>
          <th class="text-end" data-bs-toggle="tooltip" title="HM Akhir pada tanggal filter akhir">HM Akhir</th>
          <th class="text-end" data-bs-toggle="tooltip" title="Formula: HM Akhir - HM Awal">OP (hrs)</th>
          <th class="text-end" data-bs-toggle="tooltip" title="Expected Working Hours. Formula: Jumlah Hari x 24">EWH</th>
          <th class="text-center" data-bs-toggle="tooltip" title="Jumlah WO Breakdown di rentang waktu filter">Event BD</th>
          <th class="text-end" data-bs-toggle="tooltip" title="Total Durasi Breakdown yang masuk rentang filter">BD (hrs)</th>
          <th class="text-end" data-bs-toggle="tooltip" title="Formula: EWH - BD(hrs) - OP(hrs)">STB</th>
          <th class="text-center" data-bs-toggle="tooltip" title="Physical Availability. Formula: (EWH - BD) / EWH * 100">PA (%)</th>
          <th class="text-center" data-bs-toggle="tooltip" title="Mechanical Availability. Formula: OP / (OP + BD) * 100">MA (%)</th>
          <th class="text-end" data-bs-toggle="tooltip" title="Mean Time Between Failures. Formula: (EWH - BD) / Event BD">MTBF</th>
          <th class="text-end" data-bs-toggle="tooltip" title="Mean Time To Repair. Formula: BD / Event BD">MTTR</th>
          <th class="text-end" data-bs-toggle="tooltip" title="Use of Availability. Formula: OP / (EWH - BD) * 100">UA (%)</th>
          <th class="text-end" data-bs-toggle="tooltip" title="Effective Utilization. Formula: OP / EWH * 100">EU (%)</th>
        </tr>
      </thead>
      <tbody>
        @php $no = $units->firstItem() ?? 1; @endphp
        @forelse($units as $unit)
          <tr>
            <td class="text-center text-muted small">{{ $no++ }}</td>
            <td>
              <span class="fw-bold text-body">{{ $unit->nomor_unit }}</span>
            </td>
            <td><span class="badge bg-blue-lt">{{ $unit->type->name ?? '-' }}</span></td>
            <td><span class="text-secondary small">{{ $unit->model->name ?? '-' }}</span></td>
            <td class="text-end font-monospace">{{ number_format($unit->hm_awal, 1) }}</td>
            <td class="text-end font-monospace">{{ number_format($unit->hm_akhir, 1) }}</td>
            <td class="text-end font-monospace text-primary fw-bold">{{ number_format($unit->op_hrs, 1) }}</td>
            <td class="text-end font-monospace text-muted">{{ number_format($unit->ewh, 1) }}</td>
            <td class="text-center">
              <span class="badge {{ $unit->event_bd > 0 ? 'bg-orange-lt text-orange' : 'bg-secondary-lt text-muted' }}">
                {{ $unit->event_bd }}
              </span>
            </td>
            <td class="text-end font-monospace text-danger fw-bold">{{ number_format($unit->bd_hrs, 1) }}</td>
            <td class="text-end font-monospace text-muted">{{ number_format($unit->stb, 1) }}</td>
            <td class="text-center">
              <span class="metric-badge {{ $unit->pa >= 85 ? 'bg-success-lt text-success' : ($unit->pa >= 70 ? 'bg-warning-lt text-warning' : 'bg-danger-lt text-danger') }}">
                {{ number_format($unit->pa, 1) }}%
              </span>
            </td>
            <td class="text-center">
              <span class="metric-badge {{ $unit->ma >= 85 ? 'bg-success-lt text-success' : ($unit->ma >= 70 ? 'bg-warning-lt text-warning' : 'bg-danger-lt text-danger') }}">
                {{ number_format($unit->ma, 1) }}%
              </span>
            </td>
            <td class="text-end font-monospace">{{ number_format($unit->mtbf, 1) }}</td>
            <td class="text-end font-monospace">{{ number_format($unit->mttr, 1) }}</td>
            <td class="text-end font-monospace">{{ number_format($unit->ua, 1) }}%</td>
            <td class="text-end font-monospace">{{ number_format($unit->eu, 1) }}%</td>
          </tr>
        @empty
          <tr>
            <td colspan="17" class="text-center text-muted py-5">
              <div class="empty">
                <div class="empty-icon">
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-database-search text-muted" width="40" height="40" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 6c0 1.657 3.582 3 8 3s8 -1.343 8 -3s-3.582 -3 -8 -3s-8 1.343 -8 3" /><path d="M4 6v6c0 1.657 3.582 3 8 3m8 -3.5v-5.5" /><path d="M4 12v6c0 1.657 3.582 3 8 3" /><path d="M18 18m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" /><path d="M20.2 20.2l1.8 1.8" /></svg>
                </div>
                <p class="empty-title fw-bold mt-2">Data KPI Tidak Ditemukan</p>
                <p class="empty-subtitle text-muted">Silakan sesuaikan parameter pencarian atau reset filter di atas.</p>
              </div>
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($units->hasPages())
    <div class="card-footer d-flex align-items-center bg-transparent py-2 border-top">
      {{ $units->withQueryString()->links('pagination::bootstrap-5') }}
    </div>
  @endif
</div>
@endsection

@push('scripts')
<!-- Panggil library daterangepicker atau sejenisnya jika sudah ada di Tabler layout -->
<!-- Jika belum, di sini tempat untuk inisiasi plugin picker untuk input#date_range -->
<script>
  document.addEventListener("DOMContentLoaded", function() {
    // Inisiasi tooltip jika tabler menggunakan bootstrap tooltip
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Kalkulasi rentang tanggal dari ISO Week
    var weekSelector = document.getElementById('week_selector');
    var dateRangeInput = document.getElementById('date_range');
    
    if (weekSelector && dateRangeInput) {
      weekSelector.addEventListener('change', function(e) {
        if (!this.value) return;
        
        const parts = this.value.split('-W');
        if (parts.length !== 2) return;
        
        const year = parseInt(parts[0], 10);
        const week = parseInt(parts[1], 10);
        
        // Tentukan tanggal 4 Januari (selalu ada di Minggu ke-1 berdasarkan ISO 8601)
        const d = new Date(year, 0, 4);
        const day = d.getDay() || 7; // Mengubah hari ke-0 (Minggu) menjadi hari ke-7
        
        // Dapatkan Hari Senin untuk minggu ke-1
        d.setDate(d.getDate() + (1 - day));
        
        // Tambahkan minggu yang diminta
        d.setDate(d.getDate() + 7 * (week - 1));
        
        const formatDate = (date) => {
            const y = date.getFullYear();
            const m = String(date.getMonth() + 1).padStart(2, '0');
            const dt = String(date.getDate()).padStart(2, '0');
            return `${y}-${m}-${dt}`;
        };
        
        const startDate = formatDate(d);
        
        // Tambahkan 6 hari untuk mendapatkan hari Minggu
        d.setDate(d.getDate() + 6);
        const endDate = formatDate(d);
        
        dateRangeInput.value = `${startDate} - ${endDate}`;
      });
    }
  });
</script>
@endpush
