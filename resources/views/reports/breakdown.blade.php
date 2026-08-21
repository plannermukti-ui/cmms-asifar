@extends('layouts.tabler')

@section('title', 'Report Breakdown - CMMS Aisfar')

@push('styles')
<style>
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

  /* ── Page & Filter ─────────────────────────────────── */
  .report-filter-card {
    border-radius: 12px;
    border: 1px solid rgba(0,0,0,.07);
    box-shadow: 0 2px 8px rgba(0,0,0,.04);
  }
  .report-intro-card {
    border-radius: 12px;
    border: 1px solid rgba(32,107,196,.12);
    background: linear-gradient(135deg, #f8fbff 0%, #eef4ff 100%);
  }
  [data-bs-theme="dark"] .report-intro-card {
    background: linear-gradient(135deg, #182234 0%, #131c2c 100%) !important;
    border-color: rgba(255, 255, 255, 0.08) !important;
  }

  /* ── Report Card ────────────────────────────────────── */
  .report-card {
    border-radius: 12px;
    border: 1px solid rgba(0,0,0,.08);
    box-shadow: 0 2px 10px rgba(0,0,0,.04);
    position: relative;
    background: #ffffff;
  }

  .report-print-header {
    display: none;
  }
  .report-print-card {
    border: 1px solid #cbd5e1;
    box-shadow: none;
    background: #fff;
  }
  .report-print-title {
    font-size: 1.2rem;
    font-weight: 800;
    letter-spacing: .04em;
    text-transform: uppercase;
  }
  [data-bs-theme="dark"] .report-card {
    background: #1e293b;
    border-color: rgba(245,158,11,.2);
  }

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

  /* ── PRINT STYLES ────────────────────────────────────── */
  @media print {
    * {
      box-shadow: none !important;
      text-shadow: none !important;
      animation: none !important;
      transition: none !important;
    }
    body {
      background: #fff !important;
      color: #000 !important;
      font-size: 9.5px !important;
      margin: 0 !important;
      padding: 4mm !important;
    }

    /* Hide screen-only elements */
    .page-header, .navbar, .footer, .d-print-none,
    .report-filter-card, .report-intro-card,
    .report-card .card-header .d-print-none,
    .report-card .card-body .btn,
    .report-card .card-body .form-select,
    .report-card .card-body .form-control,
    .report-card .card-body .card-placeholder,
    .d-flex.gap-2.d-print-none,
    .report-card .card-header form {
      display: none !important;
    }

    /* Print header - tampil di setiap halaman */
    .report-print-header {
      display: block !important;
      margin-bottom: 6px !important;
      padding-bottom: 4px !important;
      border-bottom: 2px solid #206bc4 !important;
    }

    /* Print tag for each card */
    .report-card-print-tag {
      display: block !important;
      font-size: 9px !important;
      font-weight: 700 !important;
      text-transform: uppercase;
      letter-spacing: .04em;
      color: #206bc4 !important;
      background: #eef4ff !important;
      border: 1px solid #cbd5e1 !important;
      border-bottom: none !important;
      padding: 3px 8px !important;
      border-radius: 3px 3px 0 0 !important;
    }

    /* Card styling for print - compact */
    .report-card {
      border: 1px solid #cbd5e1 !important;
      box-shadow: none !important;
      break-inside: avoid !important;
      page-break-inside: avoid !important;
      margin-bottom: 6px !important;
      border-radius: 3px !important;
      background: #fff !important;
    }

    /* Card header tetap terlihat di print */
    .report-card .card-header {
      display: block !important;
      background: #f8fafc !important;
      border-bottom: 1px solid #cbd5e1 !important;
      padding: 4px 8px !important;
      border-radius: 0 !important;
    }
    .report-card .card-header .fw-bold {
      font-size: 10px !important;
    }
    .report-card .card-header .text-muted {
      font-size: 8px !important;
    }
    .report-card .card-header .card-number-badge {
      width: 20px !important;
      height: 20px !important;
      font-size: 0.65rem !important;
    }

    /* Card body compact */
    .report-card .card-body {
      padding: 4px !important;
    }
    .report-card .card-body > .p-3 {
      padding: 4px !important;
    }
    .report-card .card-body > .p-3.border-bottom {
      border-bottom: 1px solid #e2e8f0 !important;
      padding-bottom: 4px !important;
      margin-bottom: 4px !important;
    }

    /* Section label compact */
    .section-label {
      font-size: 7px !important;
      margin-bottom: 2px !important;
    }
    .section-label svg {
      width: 10px !important;
      height: 10px !important;
    }

    /* ── TABLES ── */
    .table-responsive {
      max-height: none !important;
      overflow: visible !important;
    }
    .kpi-table th, .kpi-table td,
    .bd-table th, .bd-table td {
      padding: 2px 4px !important;
      font-size: 7.5px !important;
      line-height: 1.2 !important;
    }
    .kpi-table th {
      font-size: 6.8px !important;
      padding: 2px 4px !important;
    }
    .kpi-table td .badge {
      font-size: 6.5px !important;
      padding: 1px 4px !important;
    }
    .metric-badge {
      font-size: 6.5px !important;
      padding: 1px 4px !important;
    }
    .font-monospace {
      font-size: 7px !important;
    }

    /* ── 3-COLUMN ANALYTICS ROW ── */
    .analytics-row {
      display: flex !important;
      flex-wrap: nowrap !important;
      gap: 4px !important;
      margin: 0 !important;
    }
    .analytics-row > [class*="col-"] {
      flex: 0 0 33.333% !important;
      max-width: 33.333% !important;
      width: 33.333% !important;
      padding-left: 2px !important;
      padding-right: 2px !important;
    }

    /* ── CHARTS ── */
    .chart-wrapper {
      border: 1px solid #cbd5e1 !important;
      background: #fff !important;
      padding: 3px !important;
      margin-top: 3px !important;
    }
    .chart-wrapper .text-center {
      font-size: 7px !important;
    }
    .chart-wrapper .text-center .fw-semibold {
      font-size: 7px !important;
    }
    .simple-chart-donut {
      width: 70px !important;
      height: 70px !important;
    }
    .simple-chart-donut-center {
      width: 38px !important;
      height: 38px !important;
      font-size: 7px !important;
    }
    .simple-chart-donut-center .fw-bold {
      font-size: 9px !important;
    }
    .simple-chart-donut-center .small {
      font-size: 6px !important;
    }
    .simple-chart-list-item {
      font-size: 6.5px !important;
      padding: 1px 3px !important;
      gap: 4px !important;
      margin-top: 1px !important;
    }
    .simple-chart-list-item .simple-chart-swatch {
      width: 6px !important;
      height: 6px !important;
    }
    .simple-chart-stack {
      gap: 4px !important;
      padding: 2px !important;
    }
    .simple-chart-row {
      padding: 1px 0 !important;
      gap: 4px !important;
    }
    .simple-chart-row .small {
      font-size: 6.5px !important;
      width: 70px !important;
    }
    .simple-chart-row .simple-chart-bar-track {
      height: 6px !important;
    }
    .simple-chart-row .fw-semibold {
      font-size: 6.5px !important;
    }

    /* ── BD TABLE INSIDE ── */
    .bd-table th, .bd-table td {
      padding: 2px 4px !important;
      font-size: 7px !important;
    }
    .bd-table th {
      font-size: 6.5px !important;
    }
    .bd-table .badge {
      font-size: 6px !important;
      padding: 1px 3px !important;
    }

    /* ── TREND ROW ── */
    .trend-row {
      display: flex !important;
      flex-wrap: nowrap !important;
      gap: 4px !important;
      margin-top: 0 !important;
      page-break-before: avoid !important;
      break-before: avoid !important;
    }
    .trend-row > [class*="col-"] {
      flex: 0 0 50% !important;
      max-width: 50% !important;
      width: 50% !important;
      padding-left: 2px !important;
      padding-right: 2px !important;
    }
    .trend-row .card {
      margin-bottom: 4px !important;
    }
    .trend-row .card-header {
      padding: 3px 8px !important;
    }
    .trend-row .card-header .fw-bold {
      font-size: 9px !important;
    }
    .trend-row .card-header .small {
      font-size: 7px !important;
    }
    .trend-row .card-body {
      padding: 4px !important;
    }
    .trend-chart-shell {
      height: 150px !important;
      padding: 4px !important;
    }
    .trend-chart-svg {
      height: 150px !important;
    }
    .trend-chart-svg text {
      font-size: 7px !important;
    }

    /* ── HINDARI CARD BARU DI HALAMAN BARU ── */
    /* Card mengalir normal tanpa paksa halaman baru */
    .report-card {
      break-inside: avoid !important;
      page-break-inside: avoid !important;
    }

    /* Tabel tidak terpotong */
    .kpi-table, .bd-table {
      break-inside: avoid !important;
      page-break-inside: avoid !important;
    }

    /* Header print berulang di setiap halaman */
    .report-print-header {
      position: running(header);
    }

    @page {
      size: A4 landscape;
      margin: 5mm 5mm 5mm 5mm;
    }
  }

  /* ── Screen chart styles ── */
  .simple-chart-donut {
    width: 140px;
    height: 140px;
    border-radius: 50%;
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
  }
  .simple-chart-donut-center {
    width: 78px;
    height: 78px;
    border-radius: 50%;
    background: #fff;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    box-shadow: inset 0 0 0 1px #e2e8f0;
    text-align: center;
    line-height: 1.1;
  }
  [data-bs-theme="dark"] .simple-chart-donut-center {
    background: #0f172a;
    box-shadow: inset 0 0 0 1px rgba(255,255,255,.08);
  }
  .simple-chart-list-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.78rem;
    margin-top: 6px;
  }
  .simple-chart-swatch {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    display: inline-block;
    flex-shrink: 0;
  }
  .simple-chart-stack {
    display: flex;
    flex-direction: column;
    gap: 10px;
    padding: 6px 4px;
  }
  .simple-chart-row {
    display: flex;
    align-items: center;
    gap: 8px;
  }
  .simple-chart-bar-track {
    flex: 1;
    height: 10px;
    background: #e2e8f0;
    border-radius: 999px;
    overflow: hidden;
  }
  .simple-chart-bar-fill {
    height: 100%;
    border-radius: 999px;
    transition: transform 0.2s ease, filter 0.2s ease, opacity 0.2s ease;
  }
  [data-bs-theme="dark"] .simple-chart-bar-track {
    background: rgba(255,255,255,.12);
  }

  .trend-chart-shell {
    position: relative;
    border-radius: 16px;
    padding: 14px;
    background: linear-gradient(135deg, #f8fbff 0%, #eef4ff 100%);
    border: 1px solid rgba(32,107,196,.12);
    box-shadow: inset 0 1px 0 rgba(255,255,255,.8), 0 10px 25px rgba(15,23,42,.04);
    transition: box-shadow 0.2s ease, transform 0.2s ease;
  }
  .trend-chart-shell:hover {
    transform: translateY(-1px);
    box-shadow: 0 16px 30px rgba(15,23,42,.08);
  }
  [data-bs-theme="dark"] .trend-chart-shell {
    background: linear-gradient(135deg, rgba(15,23,42,.95), rgba(30,41,59,.9));
    border-color: rgba(245,158,11,.16);
  }
  .trend-chart-svg {
    width: 100%; height: 100%;
  }
  .trend-point {
    cursor: pointer;
    transition: transform 0.2s ease, r 0.2s ease, filter 0.2s ease;
    transform-origin: center;
  }
  .trend-point:hover, .trend-point.is-active {
    transform: scale(1.12);
    filter: drop-shadow(0 3px 6px rgba(0,0,0,.18));
  }
  .trend-tooltip {
    position: absolute;
    z-index: 20;
    min-width: 152px;
    max-width: 230px;
    padding: 10px 12px;
    border-radius: 12px;
    background: linear-gradient(135deg, rgba(2,6,23,.97), rgba(15,23,42,.95));
    color: #f8fafc;
    border: 1px solid rgba(148,163,184,.25);
    box-shadow: 0 16px 35px rgba(15,23,42,.28);
    font-size: 0.78rem;
    line-height: 1.4;
    pointer-events: none;
    opacity: 0;
    transform: translateY(8px) scale(0.97);
    transition: opacity 0.18s ease, transform 0.18s ease;
    backdrop-filter: blur(8px);
  }
  .trend-tooltip.show {
    opacity: 1;
    transform: translateY(0) scale(1);
  }
  .trend-tooltip .title {
    font-weight: 800;
    margin-bottom: 6px;
    color: #ffffff;
  }
  .trend-tooltip .tip-row {
    display: flex;
    justify-content: space-between;
    gap: 8px;
    font-size: 0.76rem;
    color: #cbd5e1;
  }
  .trend-tooltip .tip-row strong {
    color: #eff6ff;
  }
  .simple-chart-row {
    padding: 5px 0;
    border-radius: 8px;
    transition: background-color 0.2s ease, transform 0.2s ease;
  }
  .simple-chart-row:hover {
    background: rgba(32,107,196,.06);
    transform: translateX(2px);
  }
  .simple-chart-list-item {
    padding: 4px 6px;
    border-radius: 8px;
    transition: background-color 0.2s ease, transform 0.2s ease;
  }
  .simple-chart-list-item:hover {
    background: rgba(32,107,196,.06);
    transform: translateX(2px);
  }
</style>
@endpush

@section('content')
<div id="report-export-content">
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
<div class="page-header d-print-none mb-3">
  <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
    <div>
      <h2 class="page-title">Laporan Breakdown Unit</h2>
      <div class="text-muted small mt-1">Analisa breakdown, Pareto, MTBF, MTTR, dan KPI operasional.</div>
    </div>
    <div class="d-flex flex-wrap gap-2">
      <button type="button" class="btn btn-success btn-sm" onclick="window.print();">
        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2"/><path d="M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4"/><path d="M7 13m0 2a2 2 0 0 1 2 -2h6a2 2 0 0 1 2 2v4a2 2 0 0 1 -2 2h-6a2 2 0 0 1 -2 -2z"/></svg>
        PDF
      </button>
      <button type="button" class="btn btn-primary btn-sm" onclick="downloadWordReport();">
        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4"/><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z"/><path d="M8 13h2"/><path d="M12 13h2"/><path d="M8 17h2"/><path d="M12 17h2"/></svg>
        Word
      </button>
      <a href="{{ route('reports.breakdown') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
    </div>
  </div>
</div>

<!-- PRINT HEADER - akan tampil di setiap halaman print -->
<div class="report-print-header mb-2 border-bottom pb-2">
  @php
    $appLogo = \App\Models\AppSetting::where('key', 'app_logo')->first()?->value;
    $appName = \App\Models\AppSetting::where('key', 'app_name')->first()?->value ?? 'CMMS AISFAR';
    $appAddress = \App\Models\AppSetting::where('key', 'app_address')->first()?->value ?? '';
    if ($siteId) {
      $siteLabel = \App\Models\Site::find($siteId)?->name;
      if ($siteLabel) {
        $appName .= ' - ' . $siteLabel;
      }
    }
  @endphp
  <div class="d-flex align-items-center justify-content-between gap-3">
    <div class="d-flex align-items-center gap-3">
      @if($appLogo)
        <img src="{{ asset('storage/logos/' . $appLogo) }}" alt="Logo" style="max-height: 32px;">
      @endif
      <div>
        <div class="report-print-title text-body" style="font-size:14px;">{{ $appName }}</div>
        @if($appAddress)
          <div class="small text-muted" style="font-size:8px;">{{ $appAddress }}</div>
        @endif
        <div class="small text-muted" style="font-size:8px;">BREAKDOWN REPORTING · {{ $currentDateRange }}</div>
      </div>
    </div>
    <div class="text-end">
      <div class="fw-bold" style="font-size:10px;">Tanggal Cetak: {{ date('d M Y H:i') }}</div>
      <div class="small text-muted" style="font-size:8px;">Dibuat dari halaman Report Breakdown</div>
    </div>
  </div>
</div>

<div class="card report-intro-card mb-3 d-print-none">
  <div class="card-body py-3">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
      <div>
        <div class="fw-bold text-primary mb-1">Key Performance Indicator Report</div>
        <div class="text-muted small mb-0">Breakdown analysis with flexible card comparison and trend insight.</div>
      </div>
      <div class="d-flex flex-wrap align-items-center gap-2">
        <span class="badge bg-primary-subtle text-primary">KPI</span>
        <span class="badge bg-success-subtle text-success">Breakdown</span>
        <span class="badge bg-warning-subtle text-warning">Trend</span>
      </div>
    </div>
  </div>
</div>

<div class="card filter-card mb-3" style="z-index: 50; position: relative;">
  <div class="card-body p-2.5">
    <form method="GET" action="{{ route('reports.breakdown') }}" id="global-filter-form">
      <input type="hidden" name="_generate" value="1">

      <div class="row g-2 align-items-end">
        {{-- Site --}}
        <div class="col-md-2 col-sm-6">
          <label class="filter-label">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-xs" width="14" height="14" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 11a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" /><path d="M17.657 16.657l-4.243 4.243a2 2 0 0 1 -2.827 0l-4.244 -4.243a8 8 0 1 1 11.314 0z" /></svg>
            Site
          </label>
          <select name="site_id" class="form-select filter-control">
            <option value="">Semua Site</option>
            @foreach($sites as $site)
              <option value="{{ $site->id }}" {{ $siteId == $site->id ? 'selected' : '' }}>{{ $site->name }}</option>
            @endforeach
          </select>
        </div>

        {{-- Tipe Unit (global reference) --}}
        <div class="col-md-2 col-sm-6">
          <label class="filter-label">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-xs" width="14" height="14" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 4h6v6h-6z" /><path d="M14 4h6v6h-6z" /><path d="M4 14h6v6h-6z" /><path d="M17 17m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" /></svg>
            Tipe Unit
          </label>
          <select id="global-unit-type-select" name="unit_type_id[]" class="form-select filter-control" multiple size="4" aria-label="Pilih tipe unit default">
            <option value="">Semua Tipe</option>
            @foreach($unitTypes as $ut)
              <option value="{{ $ut->id }}" {{ in_array($ut->id, $globalUnitTypeIds) ? 'selected' : '' }}>{{ $ut->name }}</option>
            @endforeach
          </select>
        </div>

        {{-- ISO Week --}}
        <div class="col-md-2 col-sm-6">
          <label class="filter-label">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-xs" width="14" height="14" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 5m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z" /><path d="M16 3l0 4" /><path d="M8 3l0 4" /><path d="M4 11l16 0" /><path d="M8 15h2v2h-2z" /></svg>
            Week
          </label>
          <input type="week" id="week_selector" class="form-control filter-control" title="Pilih minggu ISO">
        </div>

        {{-- Range Date --}}
        <div class="col-md-3 col-sm-6">
          <label class="filter-label">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-xs" width="14" height="14" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12z" /><path d="M16 3v4" /><path d="M8 3v4" /><path d="M4 11h16" /></svg>
            Range Date BD
          </label>
          <input type="text" name="date_range" id="datepicker-range" class="form-control filter-control" placeholder="YYYY-MM-DD - YYYY-MM-DD" value="{{ $currentDateRange }}">
        </div>

        {{-- Buttons --}}
        <div class="col-md-3 col-sm-12">
          <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary filter-btn flex-grow-1 shadow-sm">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 4h16v2.172a2 2 0 0 1 -.586 1.414l-4.414 4.414v7l-6 2v-8.5l-4.414 -4.414a2 2 0 0 1 -.586 -1.414v-2.172z" /></svg>
              Set Filter
            </button>
            <a href="{{ route('reports.breakdown') }}" class="btn btn-outline-secondary filter-btn-icon" title="Reset Filter">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm m-0" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -4v4h4" /><path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4" /></svg>
            </a>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- ══════════════════════════════════════════════════════
     REPORT CARDS
════════════════════════════════════════════════════════ -->
@php
  $cardConfigs = $cardConfigs ?? [];
@endphp

<div class="d-flex justify-content-between align-items-center mb-3 d-print-none">
  <div>
    <h3 class="fw-bold mb-1">Key Performance Indicator</h3>
    <p class="text-muted small mb-0">Ringkasan performa breakdown per kartu komparatif dan tren periode.</p>
  </div>
  <form method="GET" action="{{ route('reports.breakdown') }}" class="d-flex gap-2 align-items-center">
    <input type="hidden" name="_generate" value="1">
    <input type="hidden" name="site_id" value="{{ request('site_id') }}">
    @foreach(($globalUnitTypeIds ?? []) as $tv)
      <input type="hidden" name="unit_type_id[]" value="{{ $tv }}">
    @endforeach
    <input type="hidden" name="date_range" value="{{ request('date_range', $currentDateRange) }}">
    <input type="hidden" name="iso_week" value="{{ request('iso_week') }}">
    @foreach($cardConfigs as $existing)
      @foreach($existing['selectedTypes'] as $tv)
        <input type="hidden" name="card_unit_type_{{ $existing['num'] }}[]" value="{{ $tv }}">
      @endforeach
    @endforeach
    <input type="hidden" name="card_unit_type_new" value="1">
    <button type="submit" class="btn btn-outline-primary btn-sm">Tambah Card KPI</button>
  </form>
</div>

@foreach($cardConfigs as $cc)
@php $n = $cc['num']; $card = $cc['data']; @endphp
<div class="card report-card report-card-{{ $n }} mb-2">
  {{-- Print-only label --}}
  <div class="report-card-print-tag d-none d-print-block">Card {{ $n }} — Laporan Breakdown</div>

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

      {{-- Local card filter (hidden in print) --}}
      <form method="GET" action="{{ route('reports.breakdown') }}" id="card-form-{{ $n }}" class="d-flex align-items-center gap-2 flex-wrap d-print-none">
        <input type="hidden" name="_generate" value="1">
        <input type="hidden" name="site_id" value="{{ request('site_id') }}">
        @foreach(($globalUnitTypeIds ?? []) as $tv)
          <input type="hidden" name="unit_type_id[]" value="{{ $tv }}">
        @endforeach
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
           ROW 1: KPI Master Data Table
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
           ROW 2: 3 Analytics Columns
      ───────────────────────────────────────────────── --}}
      <div class="p-3">
        <div class="row g-3 analytics-row">

          {{-- Column 1: Breakdown per Tipe --}}
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

            {{-- Chart Breakdown Types --}}
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

@if($generated)
{{-- Print header untuk trend (akan muncul di setiap halaman) --}}
<div class="report-print-header report-print-header-repeat mb-2 d-none d-print-block">
  <div class="report-print-title text-body" style="font-size:12px;">Analisa Tren PA</div>
  <div class="small text-muted" style="font-size:8px;">Trend Performance Availability mingguan &amp; bulanan</div>
</div>

<div class="row g-3 mt-1 trend-row">
  <div class="col-xl-6">
    <div class="card report-card mb-2">
      <div class="card-header">
        <div class="fw-bold text-dark">Trend PA Weekly</div>
        <div class="text-muted small">Berdasarkan tipe unit pada filter utama</div>
      </div>
      <div class="card-body">
        @if(!empty($weeklyTrend))
          <div id="chart-trend-weekly" style="height: 250px;"></div>
          <script>
            document.addEventListener("DOMContentLoaded", function () {
              var theme = document.body.getAttribute('data-bs-theme') || 'light';
              var options = {
                series: [
                  @foreach($weeklyTrend as $series)
                  {
                    name: '{{ $series['name'] }}',
                    data: [
                      @foreach($series['points'] as $point)
                        {{ $point['pa'] }},
                      @endforeach
                    ]
                  },
                  @endforeach
                ],
                chart: {
                  type: 'area',
                  height: 250,
                  parentHeightOffset: 0,
                  toolbar: { show: false },
                  background: 'transparent'
                },
                colors: ['#206bc4', '#2fb344', '#f59f00', '#d63939', '#ae3ec9'],
                dataLabels: { enabled: false },
                stroke: { width: 2.5, curve: 'smooth' },
                xaxis: {
                  categories: [
                    @foreach($weeklyTrend[0]['points'] ?? [] as $point)
                      '{{ $point['label'] }}',
                    @endforeach
                  ],
                  labels: { style: { colors: theme === 'dark' ? '#94a3b8' : '#64748b' } },
                  axisBorder: { show: false }
                },
                yaxis: {
                  min: 0,
                  max: 100,
                  labels: {
                    formatter: function (val) { return val + "%"; },
                    style: { colors: theme === 'dark' ? '#94a3b8' : '#64748b' }
                  }
                },
                grid: {
                  borderColor: theme === 'dark' ? 'rgba(255,255,255,0.08)' : '#e2e8f0',
                  strokeDashArray: 4,
                },
                fill: {
                  type: 'gradient',
                  gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.4,
                    opacityTo: 0.05,
                    stops: [0, 90, 100]
                  }
                },
                tooltip: {
                  theme: theme,
                  y: { formatter: function (val) { return val + "%" } }
                },
                legend: {
                  labels: { colors: theme === 'dark' ? '#cbd5e1' : '#334155' }
                }
              };
              var chart = new ApexCharts(document.querySelector("#chart-trend-weekly"), options);
              chart.render();
            });
          </script>
        @else
          <div class="text-muted small">Tidak ada data trend untuk periode ini.</div>
        @endif
      </div>
    </div>
  </div>
  <div class="col-xl-6">
    <div class="card report-card mb-2">
      <div class="card-header">
        <div class="fw-bold text-dark">Trend PA Monthly</div>
        <div class="text-muted small">Berdasarkan tipe unit pada filter utama</div>
      </div>
      <div class="card-body">
        @if(!empty($monthlyTrend))
          <div id="chart-trend-monthly" style="height: 250px;"></div>
          <script>
            document.addEventListener("DOMContentLoaded", function () {
              var theme = document.body.getAttribute('data-bs-theme') || 'light';
              var options = {
                series: [
                  @foreach($monthlyTrend as $series)
                  {
                    name: '{{ $series['name'] }}',
                    data: [
                      @foreach($series['points'] as $point)
                        {{ $point['pa'] }},
                      @endforeach
                    ]
                  },
                  @endforeach
                ],
                chart: {
                  type: 'area',
                  height: 250,
                  parentHeightOffset: 0,
                  toolbar: { show: false },
                  background: 'transparent'
                },
                colors: ['#206bc4', '#2fb344', '#f59f00', '#d63939', '#ae3ec9'],
                dataLabels: { enabled: false },
                stroke: { width: 2.5, curve: 'smooth' },
                xaxis: {
                  categories: [
                    @foreach($monthlyTrend[0]['points'] ?? [] as $point)
                      '{{ $point['label'] }}',
                    @endforeach
                  ],
                  labels: { style: { colors: theme === 'dark' ? '#94a3b8' : '#64748b' } },
                  axisBorder: { show: false }
                },
                yaxis: {
                  min: 0,
                  max: 100,
                  labels: {
                    formatter: function (val) { return val + "%"; },
                    style: { colors: theme === 'dark' ? '#94a3b8' : '#64748b' }
                  }
                },
                grid: {
                  borderColor: theme === 'dark' ? 'rgba(255,255,255,0.08)' : '#e2e8f0',
                  strokeDashArray: 4,
                },
                fill: {
                  type: 'gradient',
                  gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.4,
                    opacityTo: 0.05,
                    stops: [0, 90, 100]
                  }
                },
                tooltip: {
                  theme: theme,
                  y: { formatter: function (val) { return val + "%" } }
                },
                legend: {
                  labels: { colors: theme === 'dark' ? '#cbd5e1' : '#334155' }
                }
              };
              var chart = new ApexCharts(document.querySelector("#chart-trend-monthly"), options);
              chart.render();
            });
          </script>
        @else
          <div class="text-muted small">Tidak ada data trend untuk periode ini.</div>
        @endif
      </div>
    </div>
  </div>
</div>
@endif

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script src="{{ asset('dist/libs/litepicker/dist/litepicker.js?1692870487') }}" defer></script>
<script>
function downloadWordReport() {
  var exportContent = document.getElementById('report-export-content');
  if (!exportContent) return;
  var html = '<!DOCTYPE html><html><head><meta charset="utf-8"><title>Key Performance Indicator Report</title><style>body{font-family:Arial, sans-serif; font-size:10pt; color:#111; padding:5mm;} .report-print-header{margin-bottom:8px; padding-bottom:6px; border-bottom:2px solid #206bc4;} .report-card{border:1px solid #cbd5e1; border-radius:4px; padding:6px; margin-bottom:6px; page-break-inside:avoid;} .report-card-print-tag{font-size:8pt; font-weight:700; text-transform:uppercase; color:#206bc4; background:#eef4ff; border:1px solid #cbd5e1; border-bottom:none; padding:2px 6px;} .kpi-table,.bd-table{border-collapse:collapse; width:100%; font-size:8pt;} .kpi-table th,.kpi-table td,.bd-table th,.bd-table td{border:1px solid #cbd5e1; padding:2px 4px;} .badge{display:inline-block; padding:1px 4px; border-radius:999px; background:#eff6ff; color:#1d4ed8; margin-right:2px; font-size:7pt;} .text-muted{color:#64748b;} .fw-bold{font-weight:700;} .fw-semibold{font-weight:600;} .small{font-size:8pt;} .mb-2{margin-bottom:6px;} .mb-3{margin-bottom:8px;} .row{display:flex; flex-wrap:wrap; gap:4px;} .col-lg-4{flex:0 0 33.333%; max-width:33.333%;} .col-xl-6{flex:0 0 50%; max-width:50%;} .d-flex{display:flex;} .justify-content-between{justify-content:space-between;} .align-items-center{align-items:center;} .gap-2{gap:6px;} .gap-3{gap:8px;} .me-2{margin-right:6px;} .text-primary{color:#2563eb;} .text-success{color:#16a34a;} .text-warning{color:#d97706;} .text-danger{color:#dc2626;} .font-monospace{font-family:monospace;} .table-responsive{overflow:visible;} .chart-wrapper{border:1px solid #cbd5e1; padding:4px; margin-top:4px;} .section-label{font-size:7pt; font-weight:700; text-transform:uppercase; color:#64748b; margin-bottom:3px;} .metric-badge{font-size:7pt; padding:1px 4px; border-radius:999px; display:inline-block;} .trend-chart-shell{height:160px; padding:4px;} .trend-chart-svg{width:100%; height:100%;} .analytics-row{display:flex; flex-wrap:nowrap; gap:4px;} .report-print-header-repeat{margin-top:6px; padding-top:6px; border-top:2px solid #206bc4;} .simple-chart-donut{width:80px; height:80px; border-radius:50%; margin:0 auto;} .simple-chart-donut-center{width:44px; height:44px; border-radius:50%; background:#fff; display:flex; flex-direction:column; align-items:center; justify-content:center; text-align:center;} .simple-chart-list-item{font-size:7pt; display:flex; align-items:center; gap:4px; margin-top:2px;} .simple-chart-swatch{width:8px; height:8px; border-radius:50%; display:inline-block;} .simple-chart-stack{display:flex; flex-direction:column; gap:4px;} .simple-chart-row{display:flex; align-items:center; gap:4px;} .simple-chart-bar-track{flex:1; height:8px; background:#e2e8f0; border-radius:999px; overflow:hidden;} .simple-chart-bar-fill{height:100%; border-radius:999px;} .trend-tooltip{display:none;} @page{size:A4 landscape; margin:4mm;}</style></head><body>' + exportContent.innerHTML + '</body></html>';
  var blob = new Blob([html], { type: 'application/msword' });
  var url = URL.createObjectURL(blob);
  var link = document.createElement('a');
  link.href = url;
  link.download = 'kpi-breakdown-report.doc';
  document.body.appendChild(link);
  link.click();
  document.body.removeChild(link);
  URL.revokeObjectURL(url);
}

document.addEventListener("DOMContentLoaded", function () {
  // ── Virtual Select ──────────────────────────────────
  var globalUnitTypeSelect = document.getElementById('global-unit-type-select');
  if (globalUnitTypeSelect) {
    VirtualSelect.init({
      ele: globalUnitTypeSelect,
      multiple: true,
      search: true,
      placeholder: 'Pilih tipe unit default...',
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
  }

  document.querySelectorAll('select[name^="card_unit_type_"][name$="[]"]').forEach(function(el) {
    var match = el.name.match(/card_unit_type_(\d+)/);
    var n = match ? match[1] : '';
    if (!n) return;

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

  // ── Custom chart rendering ────────────────────────────
  var chartColors = [
    '#206bc4','#2fb344','#f59f00','#d63939','#ae3ec9',
    '#4263eb','#0ca678','#f76707','#e64980','#1c7ed6'
  ];

  function renderSimpleChart(container, type, labels, values, colors, totalValue) {
    if (!container) return;

    var safeLabels = labels.filter(function(label, index) {
      return Number(values[index] || 0) > 0;
    });
    var safeValues = values.filter(function(value, index) {
      return Number(value || 0) > 0;
    });

    if (!safeLabels.length || safeValues.reduce(function(a, b) { return a + Number(b); }, 0) <= 0) {
      container.innerHTML = '<div class="text-muted small text-center p-3">Tidak ada data BD untuk kategori ini.</div>';
      return;
    }

    if (type === 'donut') {
      var total = safeValues.reduce(function(a, b) { return a + Number(b); }, 0);
      var cumulative = 0;
      var gradientParts = [];

      safeValues.forEach(function(v, i) {
        var pct = (Number(v) / total) * 100;
        var start = cumulative;
        cumulative += pct;
        gradientParts.push(colors[i % colors.length] + ' ' + start.toFixed(2) + '% ' + cumulative.toFixed(2) + '%');
      });

      container.innerHTML = [
        '<div class="d-flex flex-column align-items-center gap-2">',
        '  <div class="simple-chart-donut" style="background: conic-gradient(' + gradientParts.join(', ') + ')">',
        '    <div class="simple-chart-donut-center">',
        '      <div class="fw-bold">' + Number(totalValue || total).toFixed(1) + '</div>',
        '      <div class="small text-muted">hrs</div>',
        '    </div>',
        '  </div>',
        '  <div class="w-100">',
        safeLabels.map(function(label, i) {
          return '    <div class="simple-chart-list-item" data-label="' + label + '" data-value="' + Number(safeValues[i]).toFixed(1) + '" role="button" tabindex="0">' +
            '<span class="simple-chart-swatch" style="background:' + colors[i % colors.length] + '"></span>' +
            '<span class="text-truncate">' + label + '</span>' +
            '<span class="ms-auto fw-semibold">' + Number(safeValues[i]).toFixed(1) + ' hrs</span>' +
            '</div>';
        }).join(''),
        '  </div>',
        '</div>'
      ].join('');
      return;
    }

    var max = Math.max.apply(null, safeValues.concat([1]));
    container.innerHTML = [
      '<div class="simple-chart-stack">',
      safeLabels.map(function(label, i) {
        var width = (Number(safeValues[i]) / max) * 100;
        return '<div class="simple-chart-row" data-label="' + label + '" data-value="' + Number(safeValues[i]).toFixed(1) + '" role="button" tabindex="0">' +
          '<div class="small text-muted text-truncate" style="width:110px;">' + label + '</div>' +
          '<div class="simple-chart-bar-track">' +
          '<div class="simple-chart-bar-fill" style="width:' + width + '%; background:' + colors[i % colors.length] + '"></div>' +
          '</div>' +
          '<div class="small fw-semibold ms-2">' + Number(safeValues[i]).toFixed(1) + 'h</div>' +
          '</div>';
      }).join(''),
      '</div>'
    ].join('');
  }

  @foreach($cardConfigs as $cc)
  @php $cardJs = $cc['data']; $nJs = $cc['num']; @endphp
  @if($cardJs)
  (function() {
    var rawBt = @json($cardJs['chart_bd_types']);
    var elBt  = document.getElementById('chart-bd-type-{{ $nJs }}');
    if (elBt && Array.isArray(rawBt)) {
      var btLabels = rawBt.map(function(d){ return d.name; });
      var btValues = rawBt.map(function(d){ return Number(d.hrs || d.value || 0); });
      renderSimpleChart(elBt, 'donut', btLabels, btValues, chartColors, Number(@json($cardJs['total_jam_bd'])));
    }

    var rawCg = @json($cardJs['comp_group_chart']);
    var elCg  = document.getElementById('chart-comp-group-{{ $nJs }}');
    if (elCg && Array.isArray(rawCg)) {
      var labelsCg = rawCg.map(function(d){ return d.name; });
      var valuesCg = rawCg.map(function(d){ return Number(d.value || 0); });
      renderSimpleChart(elCg, 'bar', labelsCg, valuesCg, chartColors, 0);
    }

    var rawDt = @json($cardJs['downtime_code_chart']);
    var elDt  = document.getElementById('chart-downtime-code-{{ $nJs }}');
    if (elDt && Array.isArray(rawDt)) {
      var labelsDt = rawDt.map(function(d){ return d.name; });
      var seriesDt = rawDt.map(function(d){ return Number(d.value || 0); });
      renderSimpleChart(elDt, 'donut', labelsDt, seriesDt, chartColors, 0);
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
