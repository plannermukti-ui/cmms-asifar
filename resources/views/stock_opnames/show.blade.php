@extends('layouts.tabler')

@section('title', 'Detail Stock Opname - CMMS')

@section('content')
<style>
  /* Screen & Print Styling */
  .wo-report-card {
    background: var(--tblr-card-bg, #fff);
    border: 1px solid var(--tblr-border-color, #cbd5e1);
    box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
    font-size: 0.85rem;
    color: var(--tblr-body-color, #1e293b);
  }

  .table-report-header th {
    background-color: var(--tblr-bg-surface-tertiary, #f1f5f9) !important;
    color: var(--tblr-body-color, #334155) !important;
    font-weight: 700;
    text-transform: uppercase;
    font-size: 0.75rem;
    border-bottom: 2px solid var(--tblr-border-color, #cbd5e1) !important;
  }

  .table-condensed th, .table-condensed td {
    padding: 0.35rem 0.5rem !important;
    vertical-align: middle;
    font-size: 0.8rem;
    color: var(--tblr-body-color);
  }

  .signature-box {
    border: 1px solid var(--tblr-border-color, #cbd5e1);
    border-radius: 4px;
    padding: 8px;
    text-align: center;
    background-color: var(--tblr-bg-surface-tertiary, #f8fafc);
    color: var(--tblr-body-color);
  }

  .signature-space {
    height: 70px;
  }

  /* ── Dark Mode Harmonization ── */
  [data-bs-theme="dark"] .wo-report-card {
    background: #182234 !important;
    border-color: rgba(255, 255, 255, 0.08) !important;
    color: #f1f5f9 !important;
  }
  [data-bs-theme="dark"] .table-report-header th {
    background-color: #131c2c !important;
    color: #cbd5e1 !important;
    border-bottom-color: rgba(255, 255, 255, 0.08) !important;
  }
  [data-bs-theme="dark"] .signature-box {
    background-color: #131c2c !important;
    border-color: rgba(255, 255, 255, 0.08) !important;
    color: #f1f5f9 !important;
  }

  @media print {
    body {
      background: #fff !important;
      font-size: 11px !important;
      color: #000 !important;
    }
    .page-header, .navbar, .footer, .d-print-none {
      display: none !important;
    }
    .page-wrapper, .page-body, .container-xl {
      padding: 0 !important;
      margin: 0 !important;
      max-width: 100% !important;
      width: 100% !important;
    }
    .card.wo-report-card {
      --tblr-body-color: #000 !important;
      --tblr-muted-color: #4a5568 !important;
      background-color: #fff !important;
      color: #000 !important;
      border: 1px solid #000 !important;
      box-shadow: none !important;
      padding: 12px !important;
    }
    .card.wo-report-card .app-address {
      color: #333 !important;
    }
    .card.wo-report-card .table {
      color: #000 !important;
    }
    .wo-report-card .table-report-header th {
      background-color: #f1f5f9 !important;
      color: #000 !important;
    }
    .card.wo-report-card .signature-box {
      background-color: #f8fafc !important;
      color: #000 !important;
    }
    .table-condensed th, .table-condensed td {
      border-color: #cbd5e1 !important;
    }
  }
</style>

<div class="page-header d-print-none">
  <div class="row align-items-center">
    <div class="col">
      <h2 class="page-title">
        Detail Stock Opname #AUD-{{ str_pad($stockOpname->id, 4, '0', STR_PAD_LEFT) }}
      </h2>
    </div>
    <div class="col-auto ms-auto d-print-none">
      <button type="button" class="btn btn-success fw-bold shadow-sm me-2" onclick="window.print();">
        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2" /><path d="M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4" /><path d="M7 13m0 2a2 2 0 0 1 2 -2h6a2 2 0 0 1 2 2v4a2 2 0 0 1 -2 2h-6a2 2 0 0 1 -2 -2z" /></svg>
        Cetak Laporan / Berita Acara
      </button>
      <a href="{{ route('stock-opnames.index') }}" class="btn btn-secondary">
        Kembali
      </a>
    </div>
  </div>
</div>

<div class="card mt-3 wo-report-card p-4 rounded-3">
    <!-- Document Header -->
    <div class="row align-items-center border-bottom pb-3 mb-3">
        <div class="col-8">
            <div class="d-flex align-items-center">
                @php
                    $appLogo = \App\Models\AppSetting::where('key', 'app_logo')->first()?->value;
                    $appName = \App\Models\AppSetting::where('key', 'app_name')->first()?->value ?? 'CMMS AISFAR';
                    $appAddress = \App\Models\AppSetting::where('key', 'app_address')->first()?->value ?? '';
                    $siteCode = auth()->user()->site?->code ?? '';
                    if ($siteCode) {
                        $appName .= ' - ' . $siteCode;
                    }
                @endphp
                @if($appLogo)
                    <img src="{{ asset('storage/logos/' . $appLogo) }}" alt="Logo" style="max-height: 45px;" class="me-3">
                @endif
                <div>
                    <h2 class="m-0 fw-extrabold text-uppercase text-body tracking-wide" style="font-size: 1.3rem;">{{ $appName }}</h2>
                    @if($appAddress)
                        <div class="app-address" style="font-size: 0.75rem; color: #555; margin-bottom: 2px;">{{ $appAddress }}</div>
                    @endif
                    <div class="text-muted small fw-semibold">BERITA ACARA STOCK OPNAME TOOL</div>
                </div>
            </div>
        </div>
        <div class="col-4 text-end">
            <div class="badge bg-dark text-white px-3 py-2 fs-4 mb-1">#AUD-{{ str_pad($stockOpname->id, 4, '0', STR_PAD_LEFT) }}</div>
            <div class="small text-muted">Tanggal Cetak: {{ date('d M Y H:i') }}</div>
        </div>
    </div>

    <!-- Info Box -->
    <div class="row g-2 mb-3">
        <div class="col-6">
            <div class="border rounded p-2 bg-body-tertiary h-100">
                <table class="w-100 small table-condensed">
                    <tr><td class="text-muted" width="120">Tanggal Audit:</td><td class="fw-bold">{{ \Carbon\Carbon::parse($stockOpname->tanggal_audit)->format('d M Y') }}</td></tr>
                    <tr><td class="text-muted">Auditor:</td><td class="fw-bold">{{ $stockOpname->auditor->nama_lengkap ?? $stockOpname->auditor->name ?? '-' }}</td></tr>
                    <tr><td class="text-muted">Status:</td><td class="fw-bold">
                        @if($stockOpname->status == 'Approved')
                            <span class="badge bg-success text-white">Approved</span>
                        @elseif($stockOpname->status == 'Pending Approval')
                            <span class="badge bg-warning text-white">Pending Approval</span>
                        @else
                            <span class="badge bg-danger text-white">Rejected</span>
                        @endif
                    </td></tr>
                </table>
            </div>
        </div>
        <div class="col-6">
            <div class="border rounded p-2 bg-body-tertiary h-100">
                <table class="w-100 small table-condensed">
                    <tr><td class="text-muted" width="120">Lokasi Audit:</td><td class="fw-bold">{{ $stockOpname->tipe_audit }}</td></tr>
                    @if($stockOpname->tipe_audit === 'Mechanic')
                        <tr><td class="text-muted">Nama Mekanik:</td><td class="fw-bold">{{ $stockOpname->mechanic->nama_lengkap ?? '-' }}</td></tr>
                    @endif
                    <tr><td class="text-muted">Menunggu Approval:</td><td class="fw-bold">{{ $stockOpname->approver->nama_lengkap ?? $stockOpname->approver->name ?? '-' }}</td></tr>
                </table>
            </div>
        </div>
    </div>
        
    <!-- Table Content -->
    <table class="table table-bordered table-condensed mb-4">
        <thead class="table-report-header">
            <tr>
                <th>No</th>
                <th>Deskripsi Tool</th>
                <th class="text-center">Stok Sistem (Sblm)</th>
                <th class="text-center">Stok Fisik Aktual</th>
                <th class="text-center">Selisih</th>
            </tr>
        </thead>
        <tbody>
            @php 
                $totalSelisih = 0; 
                $hasDifference = false;
            @endphp
            @foreach($stockOpname->details as $index => $detail)
            @php 
                if($detail->selisih != 0) $hasDifference = true;
                $totalSelisih += abs($detail->selisih);
            @endphp
            <tr>
                <td width="5%">{{ $index + 1 }}</td>
                <td>{{ $detail->tool->name ?? '-' }}</td>
                <td class="text-center" width="15%">{{ $detail->stok_sistem }}</td>
                <td class="text-center fw-bold" width="15%">{{ $detail->stok_fisik }}</td>
                <td class="text-center fw-bold" width="15%">
                    @if($detail->selisih > 0)
                        +{{ $detail->selisih }}
                    @elseif($detail->selisih < 0)
                        {{ $detail->selisih }}
                    @else
                        0
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Signature Area -->
    <div class="mt-4 pt-3">
        <p class="small text-muted mb-3">Dengan ditandatanganinya Berita Acara ini, para pihak menyatakan bahwa hasil pemeriksaan stok fisik (Stock Opname) adalah sah dan benar. Sistem akan disesuaikan mengacu pada hasil fisik ini.</p>
        <div class="row">
            <div class="col-4">
                <div class="signature-box">
                    <div class="fw-bold small border-bottom pb-1 mb-2">Dibuat Oleh (Auditor)</div>
                    <div class="signature-space"></div>
                    <div class="fw-bold text-decoration-underline">{{ $stockOpname->auditor->nama_lengkap ?? $stockOpname->auditor->name ?? '............................' }}</div>
                    <div class="text-muted small">Admin / Kasir</div>
                </div>
            </div>
            @if($stockOpname->tipe_audit === 'Mechanic')
            <div class="col-4">
                <div class="signature-box">
                    <div class="fw-bold small border-bottom pb-1 mb-2">Diketahui Oleh</div>
                    <div class="signature-space"></div>
                    <div class="fw-bold text-decoration-underline">{{ $stockOpname->mechanic->nama_lengkap ?? '............................' }}</div>
                    <div class="text-muted small">Mekanik</div>
                </div>
            </div>
            @endif
            <div class="{{ $stockOpname->tipe_audit === 'Mechanic' ? 'col-4' : 'col-8' }}">
                <div class="signature-box">
                    <div class="fw-bold small border-bottom pb-1 mb-2">Disahkan Oleh</div>
                    <div class="signature-space"></div>
                    <div class="fw-bold text-decoration-underline">{{ $stockOpname->approver->nama_lengkap ?? $stockOpname->approver->name ?? '............................' }}</div>
                    <div class="text-muted small">Supervisor / Superintendent</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection