@extends('layouts.tabler')

@section('title', 'Report Breakdown - CMMS Aisfar')

@push('styles')
<style>
  /* ── Page & Filter ─────────────────────────────────── */
  .report-filter-card {
    border-radius: 12px;
    border: 1px solid rgba(0,0,0,.07);
    box-shadow: 0 2px 8px rgba(0,0,0,.04);
  }
  .filter-label {
    font-size: 0.72rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: .5px;
    color: #64748b;
    margin-bottom: 4px;
    display: block;
  }

  /* ── Report Card (Staggered z-index & visible overflow) ────────────────────── */
  .report-card {
    border-radius: 12px;
    border: 1px solid rgba(0,0,0,.08);
    box-shadow: 0 2px 10px rgba(0,0,0,.04);
    position: relative;
    background: #ffffff;
  }
  [data-bs-theme="dark"] .report-card {
    background: #1e293b;
    border-color: rgba(245,158,11,.2);
  }

  /* Staggered z-index stacking */
  .report-card-1 { z-index: 30; }
  .report-card-2 { z-index: 20; }
  .report-card-3 { z-index: 10; }

  .report-card .card-header {
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border-bottom: 1px solid #e2e8f0;
    padding: 12px 18px;
    border-top-left-radius: 12px;
    border-top-right-radius: 12px;
    position: relative;
    overflow: visible !important;
  }
  [data-bs-theme="dark"] .report-card .card-header {
    background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
    border-bottom-color: rgba(245,158,11,.2);
  }

  .card-number-badge {
    width: 28px; height: 28px;
    border-radius: 8px;
    background: linear-gradient(135deg, #206bc4 0%, #1a569f 100%);
    color: #fff;
    font-size: 0.8rem;
    font-weight: 700;
    display: inline-flex; align-items: center; justify-content: center;
    flex-shrink: 0;
    box-shadow: 0 2px 5px rgba(32,107,196,.3);
  }

  /* ── KPI Table ──────────────────────────────────────── */
  .kpi-table th {
    font-size: 0.72rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: .4px;
    background: #f8fafc !important;
    color: #475569;
    border-bottom: 2px solid #e2e8f0 !important;
    white-space: nowrap;
    padding: 8px 10px;
  }
  [data-bs-theme="dark"] .kpi-table th {
    background: #0f172a !important;
    color: #94a3b8;
    border-bottom-color: rgba(245,158,11,.3) !important;
  }
  .kpi-table td {
    font-size: 0.82rem;
    vertical-align: middle;
    padding: 7px 10px;
    white-space: nowrap;
  }
  .kpi-table tbody tr:hover { background: rgba(59,130,246,.04) !important; }

  /* ── BD Types Table ─────────────────────────────────── */
  .bd-table th {
    font-size: 0.72rem; font-weight: 600;
    text-transform: uppercase; letter-spacing: .4px;
    background: #f8fafc !important; color: #475569;
    border-bottom: 2px solid #e2e8f0 !important;
    white-space: nowrap; padding: 8px 10px;
  }
  [data-bs-theme="dark"] .bd-table th {
    background: #0f172a !important;
    color: #94a3b8;
    border-bottom-color: rgba(245,158,11,.3) !important;
  }
  .bd-table td { font-size: 0.82rem; vertical-align: middle; padding: 6px 10px; }

  /* ── Metric badges ──────────────────────────────────── */
  .metric-badge {
    font-size: 0.73rem; font-weight: 600;
    padding: 3px 8px; border-radius: 20px;
    display: inline-block;
  }

  /* ── Section label ─────────────────────────────────── */
  .section-label {
    font-size: 0.68rem; font-weight: 700;
    text-transform: uppercase; letter-spacing: .6px;
    color: #64748b; margin-bottom: 8px;
    display: flex; align-items: center;
  }

  /* ── Empty state ────────────────────────────────────── */
  .card-placeholder {
    padding: 56px 24px;
    text-align: center;
  }
  .card-placeholder .icon-wrapper {
    width: 64px; height: 64px; border-radius: 16px;
    background: #f1f5f9;
    display: inline-flex; align-items: center; justify-content: center;
    margin-bottom: 14px;
  }
  [data-bs-theme="dark"] .card-placeholder .icon-wrapper {
    background: #0f172a;
  }

  /* ── Virtual Select Custom Styling ───────────────────── */
  .vscomp-wrapper {
    font-size: 0.82rem !important;
    font-family: inherit !important;
  }
  .vscomp-ele {
    min-height: 33px !important;
    padding: 2px 8px !important;
    border-radius: 6px !important;
    border: 1px solid #cbd5e1 !important;
    background-color: #ffffff !important;
    transition: all 0.2s ease;
  }
  .vscomp-ele:hover {
    border-color: #94a3b8 !important;
  }
  .vscomp-value {
    color: #334155 !important;
    font-weight: 500 !important;
  }
  .vscomp-dropbox-container {
    z-index: 99999 !important;
  }
  .vscomp-dropbox {
    border-radius: 8px !important;
    border: 1px solid #cbd5e1 !important;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15) !important;
    padding: 6px !important;
    background-color: #ffffff !important;
  }

  /* Dark mode virtual select fixes */
  [data-bs-theme="dark"] .vscomp-ele {
    background-color: #0f172a !important;
    border-color: rgba(245,158,11,.3) !important;
  }
  [data-bs-theme="dark"] .vscomp-value {
    color: #f8fafc !important;
  }
  [data-bs-theme="dark"] .vscomp-dropbox {
    background-color: #1e293b !important;
    border-color: rgba(245,158,11,.4) !important;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.6) !important;
  }
  [data-bs-theme="dark"] .vscomp-option {
    color: #cbd5e1 !important;
  }
  [data-bs-theme="dark"] .vscomp-option.focused,
  [data-bs-theme="dark"] .vscomp-option:hover {
    background-color: rgba(245,158,11,.15) !important;
    color: #fbbf24 !important;
  }
  [data-bs-theme="dark"] .vscomp-option.selected {
    background-color: rgba(245,158,11,.25) !important;
    color: #fbbf24 !important;
  }
  [data-bs-theme="dark"] .vscomp-search-input {
    background-color: #0f172a !important;
    color: #f8fafc !important;
    border-color: rgba(245,158,11,.3) !important;
  }

  /* ── Chart section ──────────────────────────────────── */
  .chart-wrapper {
    border-radius: 10px;
    border: 1px solid #e2e8f0;
    background: #fff;
    overflow: hidden;
  }
  [data-bs-theme="dark"] .chart-wrapper { background: #0f172a; border-color: rgba(255,255,255,.08); }
</style>
@endpush

@section('content')
<div class="page-header d-print-none mb-3">
  <div class="row align-items-center">
    <div class="col">
      <div class="mb-1">
        <span class="badge bg-blue-lt me-1">KPI Analytics</span>
        <span class="text-muted small">Breakdown Reporting</span>
      </div>
      <h2 class="page-title fw-bold mb-0">
        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2 text-primary" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 3v18h18" /><path d="M20 18v-3" /><path d="M16 18v-6" /><path d="M12 18v-9" /><path d="M8 18v-4" /></svg>
        Report Breakdown
      </h2>
    </div>
    @if($generated)
    <div class="col-auto">
      <span class="badge bg-surface-secondary text-secondary px-3 py-2 border shadow-sm">
        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm me-1" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12z" /><path d="M16 3v4" /><path d="M8 3v4" /><path d="M4 11h16" /></svg>
        {{ $currentDateRange }}
      </span>
    </div>
    @endif
  </div>
</div>

<!-- ══════════════════════════════════════════════════════
     GLOBAL FILTER CARD
════════════════════════════════════════════════════════ -->
<div class="card report-filter-card mb-3" style="z-index: 50; position: relative;">
  <div class="card-body py-3">
    <form method="GET" action="{{ route('reports.breakdown') }}" id="global-filter-form">
      <input type="hidden" name="_generate" value="1">

      <div class="row g-2 align-items-end">
        {{-- Site --}}
        <div class="col-md-2 col-sm-6">
          <label class="filter-label">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-xs me-1" width="14" height="14" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 11a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" /><path d="M17.657 16.657l-4.243 4.243a2 2 0 0 1 -2.827 0l-4.244 -4.243a8 8 0 1 1 11.314 0z" /></svg>
            Site
          </label>
          <select name="site_id" class="form-select form-select-sm">
            <option value="">Semua Site</option>
            @foreach($sites as $site)
              <option value="{{ $site->id }}" {{ $siteId == $site->id ? 'selected' : '' }}>{{ $site->name }}</option>
            @endforeach
          </select>
        </div>

        {{-- Tipe Unit (global reference) --}}
        <div class="col-md-2 col-sm-6">
          <label class="filter-label">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-xs me-1" width="14" height="14" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 4h6v6h-6z" /><path d="M14 4h6v6h-6z" /><path d="M4 14h6v6h-6z" /><path d="M17 17m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" /></svg>
            Tipe Unit Default
          </label>
          <select name="unit_type_id" class="form-select form-select-sm">
            <option value="">Semua Tipe</option>
            @foreach($unitTypes as $ut)
              <option value="{{ $ut->id }}" {{ $globalUnitTypeId == $ut->id ? 'selected' : '' }}>{{ $ut->name }}</option>
            @endforeach
          </select>
        </div>

        {{-- ISO Week --}}
        <div class="col-md-2 col-sm-6">
          <label class="filter-label">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-xs me-1" width="14" height="14" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 5m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z" /><path d="M16 3l0 4" /><path d="M8 3l0 4" /><path d="M4 11l16 0" /><path d="M8 15h2v2h-2z" /></svg>
            ISO Week
          </label>
          <input type="week" id="week_selector" class="form-control form-control-sm" title="Pilih minggu ISO">
        </div>

        {{-- Range Date --}}
        <div class="col-md-3 col-sm-6">
          <label class="filter-label">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-xs me-1" width="14" height="14" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12z" /><path d="M16 3v4" /><path d="M8 3v4" /><path d="M4 11h16" /></svg>
            Range Date BD
          </label>
          <div class="input-icon input-icon-sm">
            <input type="text" name="date_range" id="datepicker-range" class="form-control form-control-sm" placeholder="YYYY-MM-DD - YYYY-MM-DD" value="{{ $currentDateRange }}">
            <span class="input-icon-addon">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12z" /><path d="M16 3v4" /><path d="M8 3v4" /><path d="M4 11h16" /><path d="M11 15h1" /><path d="M12 15v3" /></svg>
            </span>
          </div>
        </div>

        {{-- Buttons --}}
        <div class="col-md-3 col-sm-12">
          <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary btn-sm flex-grow-1 shadow-sm fw-bold">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 4h16v2.172a2 2 0 0 1 -.586 1.414l-4.414 4.414v7l-6 2v-8.5l-4.414 -4.414a2 2 0 0 1 -.586 -1.414v-2.172z" /></svg>
              Set Filter Utama
            </button>
            <a href="{{ route('reports.breakdown') }}" class="btn btn-outline-secondary btn-sm" title="Reset Filter">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm m-0" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -4v4h4" /><path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4" /></svg>
            </a>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- ══════════════════════════════════════════════════════
     3 REPORT CARDS
════════════════════════════════════════════════════════ -->
@php
  $cardConfigs = [
    ['num' => 1, 'data' => $card1, 'selectedTypes' => $cardUnitTypes1],
    ['num' => 2, 'data' => $card2, 'selectedTypes' => $cardUnitTypes2],
    ['num' => 3, 'data' => $card3, 'selectedTypes' => $cardUnitTypes3],
  ];
@endphp

@foreach($cardConfigs as $cc)
@php $n = $cc['num']; $card = $cc['data']; @endphp
<div class="card report-card report-card-{{ $n }} mb-3">
  {{-- Card Header with local filter --}}
  <div class="card-header">
    <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap">
      <div class="d-flex align-items-center gap-2">
        <span class="card-number-badge">{{ $n }}</span>
        <div>
          <div class="fw-bold text-dark" style="font-size:.92rem;">Laporan Breakdown — Card {{ $n }}</div>
          @if($card)
            <div class="text-muted" style="font-size:.73rem;">
              Total BD: <strong class="text-danger">{{ number_format($card['total_jam_bd'], 1) }} hrs</strong>
              &nbsp;·&nbsp; {{ count($card['unit_rows']) }} unit
            </div>
          @else
            <div class="text-muted small" style="font-size:.73rem;">
              <span class="badge bg-warning-lt text-warning">Belum Ada Filter Tipe Unit</span>
            </div>
          @endif
        </div>
      </div>

      {{-- Local card filter (multi-select, compact) --}}
      <form method="GET" action="{{ route('reports.breakdown') }}" id="card-form-{{ $n }}" class="d-flex align-items-center gap-2 flex-wrap">
        <input type="hidden" name="_generate" value="1">
        <input type="hidden" name="site_id" value="{{ request('site_id') }}">
        <input type="hidden" name="unit_type_id" value="{{ request('unit_type_id') }}">
        <input type="hidden" name="date_range" value="{{ request('date_range', $currentDateRange) }}">
        <input type="hidden" name="iso_week" value="{{ request('iso_week') }}">

        {{-- Preserve other cards' filter selections --}}
        @foreach($cardConfigs as $other)
          @if($other['num'] != $n)
            @foreach($other['selectedTypes'] as $tv)
              <input type="hidden" name="card_unit_type_{{ $other['num'] }}[]" value="{{ $tv }}">
            @endforeach
          @endif
        @endforeach

        <label class="filter-label mb-0 me-1" style="white-space:nowrap;">Pilih Tipe Unit (Card {{ $n }}):</label>
        <div style="min-width:210px; max-width:300px;">
          <select name="card_unit_type_{{ $n }}[]"
                  class="form-select form-select-sm excel-filter-card-{{ $n }}"
                  multiple
                  data-placeholder="Pilih Tipe Unit Card {{ $n }}...">
            @foreach($unitTypes as $ut)
              <option value="{{ $ut->id }}"
                {{ in_array($ut->id, $cc['selectedTypes']) ? 'selected' : '' }}>
                {{ $ut->name }}
              </option>
            @endforeach
          </select>
        </div>
        <button type="submit" class="btn btn-sm btn-primary shadow-sm px-2.5" title="Terapkan filter Card {{ $n }}">
          <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm me-1 m-0" width="14" height="14" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 4h16v2.172a2 2 0 0 1 -.586 1.414l-4.414 4.414v7l-6 2v-8.5l-4.414 -4.414a2 2 0 0 1 -.586 -1.414v-2.172z" /></svg>
          <span class="small fw-semibold">Terapkan</span>
        </button>
      </form>
    </div>
  </div>

  {{-- Card Body --}}
  <div class="card-body p-0">
    @if(!$card)
      {{-- Clean Placeholder: No filter selected for this card yet --}}
      <div class="card-placeholder">
        <div class="icon-wrapper mx-auto">
          <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-lg text-primary" width="32" height="32" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 4h16v2.172a2 2 0 0 1 -.586 1.414l-4.414 4.414v7l-6 2v-8.5l-4.414 -4.414a2 2 0 0 1 -.586 -1.414v-2.172z" /></svg>
        </div>
        <h4 class="fw-bold mb-1">Filter Card {{ $n }} Belum Dipilih</h4>
        <p class="text-muted small mb-0" style="max-width: 480px; margin: 0 auto;">
          Silakan pilih satu atau beberapa <strong>Tipe Unit</strong> pada dropdown di pojok kanan atas <strong>Card {{ $n }}</strong> lalu klik <strong>Terapkan</strong> untuk menampilkan laporan data.
        </p>
      </div>

    @else
      {{-- ─────────────────────────────────────────────────
           ROW 1: KPI Master Data Table (full-width)
      ───────────────────────────────────────────────── --}}
      <div class="p-3 border-bottom">
        <div class="section-label mb-2">
          <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-xs me-1 text-primary" width="14" height="14" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2" /><path d="M9 3m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z" /></svg>
          KPI Per Unit
        </div>
        <div class="table-responsive">
          <table class="table table-bordered kpi-table card-table mb-0">
            <thead>
              <tr>
                <th class="text-center w-1">No</th>
                <th>Unit</th>
                <th>Tipe</th>
                <th>Model</th>
                <th class="text-end" title="HM Awal pada tanggal filter awal">HM Awal</th>
                <th class="text-end" title="HM Akhir pada tanggal filter akhir">HM Akhir</th>
                <th class="text-end" title="OP Hrs = HM Akhir - HM Awal">OP (hrs)</th>
                <th class="text-end" title="Expected Working Hours = Hari × 24">EWH</th>
                <th class="text-center" title="Jumlah event Breakdown">Ev BD</th>
                <th class="text-end" title="Total jam BD (overlap)">BD (hrs)</th>
                <th class="text-end" title="STB = EWH - BD - OP">STB</th>
                <th class="text-center" title="PA = (EWH-BD)/EWH × 100">PA (%)</th>
                <th class="text-center" title="MA = OP/(OP+BD) × 100">MA (%)</th>
                <th class="text-end" title="MTBF = (EWH-BD)/EventBD">MTBF</th>
                <th class="text-end" title="MTTR = BD/EventBD">MTTR</th>
                <th class="text-end" title="UA = OP/(EWH-BD) × 100">UA (%)</th>
                <th class="text-end" title="EU = OP/EWH × 100">EU (%)</th>
              </tr>
            </thead>
            <tbody>
              @forelse($card['unit_rows'] as $i => $u)
                <tr>
                  <td class="text-center text-muted small">{{ $i + 1 }}</td>
                  <td><span class="fw-bold">{{ $u['nomor_unit'] }}</span></td>
                  <td><span class="badge bg-blue-lt text-blue">{{ $u['type_name'] }}</span></td>
                  <td><span class="text-muted small">{{ $u['model_name'] }}</span></td>
                  <td class="text-end font-monospace">{{ number_format($u['hm_awal'], 1) }}</td>
                  <td class="text-end font-monospace">{{ number_format($u['hm_akhir'], 1) }}</td>
                  <td class="text-end font-monospace text-primary fw-bold">{{ number_format($u['op_hrs'], 1) }}</td>
                  <td class="text-end font-monospace text-muted">{{ number_format($u['ewh'], 1) }}</td>
                  <td class="text-center">
                    <span class="badge {{ $u['event_bd'] > 0 ? 'bg-orange-lt text-orange' : 'bg-secondary-lt text-muted' }}">
                      {{ $u['event_bd'] }}
                    </span>
                  </td>
                  <td class="text-end font-monospace text-danger fw-bold">{{ number_format($u['bd_hrs'], 1) }}</td>
                  <td class="text-end font-monospace text-muted">{{ number_format($u['stb'], 1) }}</td>
                  <td class="text-center">
                    <span class="metric-badge {{ $u['pa'] >= 85 ? 'bg-success-lt text-success' : ($u['pa'] >= 70 ? 'bg-warning-lt text-warning' : 'bg-danger-lt text-danger') }}">
                      {{ number_format($u['pa'], 1) }}%
                    </span>
                  </td>
                  <td class="text-center">
                    <span class="metric-badge {{ $u['ma'] >= 85 ? 'bg-success-lt text-success' : ($u['ma'] >= 70 ? 'bg-warning-lt text-warning' : 'bg-danger-lt text-danger') }}">
                      {{ number_format($u['ma'], 1) }}%
                    </span>
                  </td>
                  <td class="text-end font-monospace">{{ number_format($u['mtbf'], 1) }}</td>
                  <td class="text-end font-monospace">{{ number_format($u['mttr'], 1) }}</td>
                  <td class="text-end font-monospace">{{ number_format($u['ua'], 1) }}%</td>
                  <td class="text-end font-monospace">{{ number_format($u['eu'], 1) }}%</td>
                </tr>
              @empty
                <tr>
                  <td colspan="17" class="py-4 text-center text-muted">
                    <div class="empty py-2">
                      <div class="empty-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-lg text-muted" width="36" height="36" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 6c0 1.657 3.582 3 8 3s8 -1.343 8 -3s-3.582 -3 -8 -3s-8 1.343 -8 3" /><path d="M4 6v6c0 1.657 3.582 3 8 3m8 -3.5v-5.5" /><path d="M4 12v6c0 1.657 3.582 3 8 3" /><path d="M18 18m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" /><path d="M20.2 20.2l1.8 1.8" /></svg>
                      </div>
                      <p class="empty-title mt-2">Tidak ada unit ditemukan</p>
                      <p class="empty-subtitle text-muted small">Sesuaikan filter atau pilih tipe unit yang berbeda.</p>
                    </div>
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>

      {{-- ─────────────────────────────────────────────────
           ROW 2: 3 Analytics Columns:
           1. Breakdown Types Table & Donut Chart
           2. Component Group Bar Chart
           3. Downtime Code Chart
      ───────────────────────────────────────────────── --}}
      <div class="p-3">
        <div class="row g-3">

          {{-- Column 1: Breakdown per Tipe (Table & Donut Chart) --}}
          <div class="col-lg-4">
            <div class="section-label mb-2">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-xs me-1 text-primary" width="14" height="14" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 19l16 0" /><path d="M4 15l4 -6l4 2l4 -5l4 4" /></svg>
              Breakdown per Tipe
            </div>
            <div class="table-responsive mb-3" style="max-height: 220px; overflow-y: auto;">
              <table class="table table-bordered bd-table mb-0">
                <thead>
                  <tr>
                    <th style="width:12%">Kode</th>
                    <th>Deskripsi</th>
                    <th class="text-end" style="width:20%">Total (hrs)</th>
                    <th class="text-end" style="width:15%">% BD</th>
                  </tr>
                </thead>
                <tbody>
                  @php $grandRight = 0; @endphp
                  @foreach($card['bd_type_totals'] as $bdt)
                    <tr>
                      <td><span class="badge bg-secondary-lt text-secondary font-monospace">{{ $bdt['code'] }}</span></td>
                      <td class="text-truncate" style="max-width:140px" title="{{ $bdt['name'] }}">{{ $bdt['name'] }}</td>
                      <td class="text-end font-monospace {{ $bdt['total_jam'] > 0 ? 'text-danger fw-bold' : 'text-muted' }}">
                        {{ number_format($bdt['total_jam'], 1) }}
                      </td>
                      <td class="text-end">
                        <span class="{{ $bdt['percentage'] > 0 ? 'text-danger fw-semibold' : 'text-muted' }}" style="font-size:.8rem;">
                          {{ number_format($bdt['percentage'], 1) }}%
                        </span>
                      </td>
                    </tr>
                    @php $grandRight += $bdt['total_jam']; @endphp
                  @endforeach
                  <tr class="table-dark">
                    <td colspan="2" class="fw-bold small">Total</td>
                    <td class="fw-bold text-end font-monospace">{{ number_format($grandRight, 1) }}</td>
                    <td class="fw-bold text-end" style="font-size:.8rem;">100.0%</td>
                  </tr>
                </tbody>
              </table>
            </div>

            {{-- Chart Breakdown Types (ALWAYS Rendered even if 0) --}}
            <div class="chart-wrapper p-2">
              <div class="text-center small text-muted fw-semibold mb-1">Distribusi Breakdown Type</div>
              <div id="chart-bd-type-{{ $n }}" style="min-height:210px;"></div>
            </div>
          </div>

          {{-- Column 2: Component Group Bar Chart --}}
          <div class="col-lg-4">
            <div class="section-label mb-2">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-xs me-1 text-primary" width="14" height="14" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 3v18h18" /><path d="M8 12h8" /><path d="M8 8h10" /><path d="M8 16h6" /></svg>
              Breakdown (Hrs) per Component Group
            </div>
            <div class="chart-wrapper p-2 h-100 d-flex flex-column">
              <div class="text-center small text-muted fw-semibold mb-1">Total Jam BD per Component Group</div>
              <div id="chart-comp-group-{{ $n }}" class="flex-grow-1" style="min-height:360px;"></div>
            </div>
          </div>

          {{-- Column 3: Downtime Code Chart --}}
          <div class="col-lg-4">
            <div class="section-label mb-2">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-xs me-1 text-primary" width="14" height="14" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M12 7v5l3 3" /></svg>
              Breakdown (Hrs) per Downtime Code
            </div>
            <div class="chart-wrapper p-2 h-100 d-flex flex-column">
              <div class="text-center small text-muted fw-semibold mb-1">Total Jam BD per Downtime Code</div>
              <div id="chart-downtime-code-{{ $n }}" class="flex-grow-1" style="min-height:360px;"></div>
            </div>
          </div>

        </div>
      </div>
    @endif
  </div>
</div>
@endforeach

@endsection

@push('scripts')
<script src="{{ asset('dist/libs/litepicker/dist/litepicker.js?1692870487') }}" defer></script>
<script src="{{ asset('dist/libs/apexcharts/dist/apexcharts.min.js?1692870487') }}" defer></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
  // ── Virtual Select for Card Filters ─────────────────
  [1, 2, 3].forEach(function(n) {
    var el = document.querySelector('.excel-filter-card-' + n);
    if (!el) return;

    VirtualSelect.init({
      ele: el,
      multiple: true,
      search: true,
      placeholder: 'Pilih Tipe Unit Card ' + n + '...',
      selectAllText: 'Pilih Semua',
      searchPlaceholderText: 'Cari tipe...',
      optionsSelectedText: ' terpilih',
      optionSelectedText: ' terpilih',
      allOptionsSelectedText: 'Semua Tipe',
      hideClearButton: false,
      dropboxDirection: 'bottom',
      zIndex: 1050,
      showValueAsTags: false,
      dropboxWidth: '260px',
      maxWidth: '300px',
    });

    // Auto-submit when user closes dropdown after making selections
    el.addEventListener('afterClose', function () {
      var form = document.getElementById('card-form-' + n);
      if (form) {
        form.submit();
      }
    });
  });

  // ── Litepicker date range ────────────────────────────
  if (window.Litepicker) {
    new Litepicker({
      element: document.getElementById('datepicker-range'),
      singleMode: false,
      format: 'YYYY-MM-DD',
      buttonText: {
        previousMonth: '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M15 6l-6 6l6 6" /></svg>',
        nextMonth: '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 6l6 6l-6 6" /></svg>',
      },
    });
  }

  // ── ISO Week → date range ────────────────────────────
  var weekSel  = document.getElementById('week_selector');
  var drInput  = document.getElementById('datepicker-range');
  if (weekSel && drInput) {
    weekSel.addEventListener('change', function () {
      var parts = this.value.split('-W');
      if (parts.length !== 2) return;
      var year = parseInt(parts[0], 10);
      var week = parseInt(parts[1], 10);
      var d = new Date(year, 0, 4);
      var day = d.getDay() || 7;
      d.setDate(d.getDate() + (1 - day) + 7 * (week - 1));
      var fmt = function(dt) {
        return dt.getFullYear() + '-' +
               String(dt.getMonth()+1).padStart(2,'0') + '-' +
               String(dt.getDate()).padStart(2,'0');
      };
      var s = fmt(d);
      d.setDate(d.getDate() + 6);
      drInput.value = s + ' - ' + fmt(d);
    });
  }

  // ── ApexCharts rendering for active cards ────────────
  var chartColors = [
    '#206bc4','#2fb344','#f59f00','#d63939','#ae3ec9',
    '#4263eb','#0ca678','#f76707','#e64980','#1c7ed6'
  ];

  @foreach($cardConfigs as $cc)
  @php $cardJs = $cc['data']; $nJs = $cc['num']; @endphp
  @if($cardJs)
  (function() {
    // 1. Breakdown Types Donut Chart (ALWAYS rendered even if 0)
    var rawBt = @json($cardJs['chart_bd_types']);
    var elBt  = document.getElementById('chart-bd-type-{{ $nJs }}');
    if (elBt && Array.isArray(rawBt)) {
      var filteredBt = rawBt.filter(function(d){ return d.value > 0; });
      var hasDataBt = filteredBt.length > 0;
      
      new ApexCharts(elBt, {
        chart: { type: 'donut', height: 220, fontFamily: 'inherit', animations: { enabled: true } },
        series: hasDataBt ? filteredBt.map(function(d){ return Number(d.value); }) : [1],
        labels: hasDataBt ? filteredBt.map(function(d){ return d.name; }) : ['Belum Ada Breakdown'],
        colors: hasDataBt ? chartColors.slice(0, filteredBt.length) : ['#cbd5e1'],
        dataLabels: {
          enabled: hasDataBt,
          formatter: function(v){ return Number(v).toFixed(1) + '%'; },
          style: { fontSize: '11px' },
          dropShadow: { enabled: false }
        },
        legend: { show: true, position: 'bottom', fontSize: '11px', itemMargin: { horizontal: 5, vertical: 2 } },
        tooltip: {
          theme: 'dark',
          y: {
            formatter: function(v, opts){
              if (!hasDataBt) return '0.0 hrs';
              var hrs = filteredBt[opts.seriesIndex] ? filteredBt[opts.seriesIndex].hrs : 0;
              return Number(v).toFixed(1) + '% (' + hrs + ' hrs)';
            }
          }
        },
        plotOptions: {
          pie: { donut: {
            size: '56%',
            labels: {
              show: true,
              total: {
                show: true, label: 'Total BD',
                formatter: function(w){
                  var total = Number(@json($cardJs['total_jam_bd']));
                  return total.toFixed(1) + ' hrs';
                }
              }
            }
          }}
        },
        stroke: { width: 1 },
      }).render();
    }

    // 2. Component Group Horizontal Bar Chart
    var rawCg = @json($cardJs['comp_group_chart']);
    var elCg  = document.getElementById('chart-comp-group-{{ $nJs }}');
    if (elCg && Array.isArray(rawCg)) {
      if (rawCg.length === 0) {
        elCg.innerHTML = '<div style="display:flex;align-items:center;justify-content:center;height:100%;min-height:280px;" class="text-muted small text-center p-3">Kosong / Tidak ada Component Group pada WO.</div>';
      } else {
        var categoriesCg = rawCg.map(function(d){ return d.name; });
        var valuesCg     = rawCg.map(function(d){ return Number(d.value); });

        new ApexCharts(elCg, {
          chart: { type: 'bar', height: Math.max(300, categoriesCg.length * 35), fontFamily: 'inherit', toolbar: { show: false } },
          plotOptions: {
            bar: { horizontal: true, borderRadius: 4, barHeight: '55%', distributed: true }
          },
          colors: chartColors,
          series: [{ name: 'Total BD (hrs)', data: valuesCg }],
          xaxis: { categories: categoriesCg, labels: { style: { fontSize: '11px' } } },
          yaxis: { labels: { style: { fontSize: '11px' } } },
          dataLabels: {
            enabled: true,
            formatter: function(v){ return Number(v).toFixed(1) + ' hrs'; },
            style: { fontSize: '11px', colors: ['#fff'] }
          },
          tooltip: { theme: 'dark', y: { formatter: function(v){ return Number(v).toFixed(1) + ' hrs'; } } },
          legend: { show: false },
        }).render();
      }
    }

    // 3. Downtime Code Chart (Donut / Bar)
    var rawDt = @json($cardJs['downtime_code_chart']);
    var elDt  = document.getElementById('chart-downtime-code-{{ $nJs }}');
    if (elDt && Array.isArray(rawDt)) {
      if (rawDt.length === 0) {
        elDt.innerHTML = '<div style="display:flex;align-items:center;justify-content:center;height:100%;min-height:280px;" class="text-muted small text-center p-3">Tidak ada data Downtime Code.</div>';
      } else {
        var labelsDt = rawDt.map(function(d){ return d.name; });
        var seriesDt = rawDt.map(function(d){ return Number(d.value); });

        new ApexCharts(elDt, {
          chart: { type: 'donut', height: 300, fontFamily: 'inherit' },
          series: seriesDt,
          labels: labelsDt,
          colors: ['#f59f00', '#d63939', '#206bc4', '#ae3ec9', '#2fb344'],
          dataLabels: {
            enabled: true,
            formatter: function(v){ return Number(v).toFixed(1) + '%'; },
            style: { fontSize: '11px' }
          },
          legend: { show: true, position: 'bottom', fontSize: '11px' },
          tooltip: { theme: 'dark', y: { formatter: function(v){ return Number(v).toFixed(1) + ' hrs'; } } },
          plotOptions: {
            pie: { donut: {
              size: '56%',
              labels: {
                show: true,
                total: {
                  show: true, label: 'Downtime',
                  formatter: function(w){
                    var total = seriesDt.reduce(function(a,b){ return a+b; }, 0);
                    return total.toFixed(1) + ' hrs';
                  }
                }
              }
            }}
          }
        }).render();
      }
    }
  })();
  @endif
  @endforeach

  // ── Tooltips ─────────────────────────────────────────
  document.querySelectorAll('[title]').forEach(function(el) {
    new bootstrap.Tooltip(el, { trigger: 'hover' });
  });
});
</script>
@endpush
