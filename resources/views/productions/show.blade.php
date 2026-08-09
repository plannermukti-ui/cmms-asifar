@extends('layouts.tabler')
@section('title', 'Laporan Produksi Harian - Shift ' . $production->shift . ' (' . \Carbon\Carbon::parse($production->date)->format('d M Y') . ')')

@section('content')
<style>
  /* Base Styling */
  .prod-report-card {
    background: #ffffff;
    border: 1px solid #cbd5e1;
    border-radius: 8px;
    font-size: 0.85rem;
    color: #1e293b;
  }

  .report-section-title {
    font-size: 0.8rem;
    font-weight: 700;
    text-transform: uppercase;
    color: #475569;
    letter-spacing: 0.05em;
    margin-bottom: 8px;
  }

  .table-report {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.8rem;
  }

  .table-report th {
    background-color: #f8fafc !important;
    color: #334155 !important;
    font-weight: 700;
    text-transform: uppercase;
    font-size: 0.72rem;
    border: 1px solid #e2e8f0 !important;
    padding: 6px 8px;
    vertical-align: middle;
  }

  .table-report td {
    border: 1px solid #e2e8f0 !important;
    padding: 6px 8px;
    vertical-align: middle;
  }

  .summary-box {
    background-color: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    padding: 10px;
    text-align: center;
  }

  .signature-box {
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    padding: 10px;
    text-align: center;
    background-color: #ffffff;
  }

  .signature-space {
    height: 50px;
  }

  /* Print Specific Optimization */
  @media print {
    body {
      background: #ffffff !important;
      font-size: 10px !important;
      color: #0f172a !important;
      -webkit-print-color-adjust: exact !important;
      print-color-adjust: exact !important;
    }
    
    .page-header, .navbar, .footer, .d-print-none, .sidebar, #chatWidgetContainer {
      display: none !important;
    }
    
    .page-wrapper, .page-body, .container-xl {
      padding: 0 !important;
      margin: 0 !important;
      max-width: 100% !important;
      width: 100% !important;
    }
    
    .prod-report-card {
      border: none !important;
      box-shadow: none !important;
      padding: 0 !important;
      border-radius: 0 !important;
    }
    
    .summary-box, .signature-box, .fleet-container {
      border: 1px solid #cbd5e1 !important;
      page-break-inside: avoid;
    }
    
    .table-report th {
      background-color: #f1f5f9 !important;
      color: #0f172a !important;
      border: 1px solid #cbd5e1 !important;
    }

    .table-report td {
      border: 1px solid #cbd5e1 !important;
    }

    .badge {
      border: 1px solid #64748b !important;
      color: #0f172a !important;
      background: transparent !important;
    }

    .row {
      margin-left: 0 !important;
      margin-right: 0 !important;
    }
  }
</style>

<div class="page-header d-print-none mb-3">
  <div class="container-xl">
    <div class="row g-2 align-items-center">
      <div class="col">
        <h2 class="page-title text-uppercase font-weight-bold">
          Detail Laporan Produksi Shift
        </h2>
        <div class="text-muted small mt-1">Laporan Operasional Loading & Hauling Tambang</div>
      </div>
      <div class="col-auto ms-auto d-print-none">
        <div class="btn-list">
          <button type="button" class="btn btn-primary fw-bold shadow-sm" onclick="window.print();">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-printer me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2" /><path d="M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4" /><path d="M7 13m0 2a2 2 0 0 1 2 -2h6a2 2 0 0 1 2 2v4a2 2 0 0 1 -2 2h-6a2 2 0 0 1 -2 -2z" /></svg>
            Print Laporan
          </button>
          <a href="{{ route('productions.index') }}" class="btn btn-outline-secondary">Kembali</a>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="page-body">
  <div class="container-xl">

    <!-- PRINTABLE PRODUCTION REPORT DOCUMENT CARD -->
    <div class="card prod-report-card p-4">
      
      <!-- Standard Company Header -->
      <div class="row align-items-center pb-3 mb-3 border-bottom">
        <div class="col-8">
          <div class="d-flex align-items-center">
            @php
                $appLogo = \App\Models\AppSetting::where('key', 'app_logo')->first()?->value;
                $appName = \App\Models\AppSetting::where('key', 'app_name')->first()?->value ?? 'CMMS AISFAR';
                $appAddress = \App\Models\AppSetting::where('key', 'app_address')->first()?->value ?? '';
            @endphp
            @if($appLogo)
                <img src="{{ asset('storage/logos/' . $appLogo) }}" alt="Logo" style="max-height: 50px;" class="me-3">
            @endif
            <div>
              <h2 class="m-0 fw-extrabold text-uppercase text-body tracking-wide" style="font-size: 1.35rem;">{{ $appName }}</h2>
              @if($appAddress)
                <div style="font-size: 0.78rem; color: #64748b; margin-bottom: 2px;">{{ $appAddress }}</div>
              @endif
              <div class="text-muted small fw-semibold">DAILY PRODUCTION & FLEET MANAGEMENT REPORT</div>
            </div>
          </div>
        </div>
        <div class="col-4 text-end">
          <div class="badge bg-dark text-white px-3 py-1.5 fs-4 mb-1">SHIFT: {{ $production->shift }}</div>
          <div class="small fw-bold text-dark">Tanggal: {{ \Carbon\Carbon::parse($production->date)->format('d F Y') }}</div>
          <div class="small text-muted" style="font-size: 0.75rem;">Tanggal Cetak: {{ date('d M Y H:i') }}</div>
        </div>
      </div>

      @php
          $totalRit = 0;
          $totalTon = 0;
          $totalHauler = 0;
          foreach($production->fleets as $fleet) {
              $totalHauler += $fleet->haulers->count();
              foreach($fleet->haulers as $h) {
                  $totalRit += $h->total_ritasi;
                  $totalTon += ($h->payload * $h->total_ritasi);
              }
          }
      @endphp

      <!-- Rangkuman Ringkas (4 Metric Summary Boxes) -->
      <div class="row g-2 mb-4">
        <div class="col-3">
          <div class="summary-box">
            <div class="text-muted small text-uppercase fw-semibold" style="font-size:0.7rem;">Total Fleets</div>
            <div class="fs-2 fw-bold text-primary">{{ $production->fleets->count() }} <span class="fs-6 text-muted font-normal">({{ $totalHauler }} DT)</span></div>
          </div>
        </div>
        <div class="col-3">
          <div class="summary-box">
            <div class="text-muted small text-uppercase fw-semibold" style="font-size:0.7rem;">Total Ritasi</div>
            <div class="fs-2 fw-bold text-success">{{ number_format($totalRit) }} <span class="fs-6 text-muted font-normal">Rit</span></div>
          </div>
        </div>
        <div class="col-3">
          <div class="summary-box">
            <div class="text-muted small text-uppercase fw-semibold" style="font-size:0.7rem;">Total Produksi</div>
            <div class="fs-2 fw-bold text-warning">{{ number_format($totalTon, 2) }} <span class="fs-6 text-muted font-normal">BCM</span></div>
          </div>
        </div>
        <div class="col-3">
          <div class="summary-box">
            <div class="text-muted small text-uppercase fw-semibold" style="font-size:0.7rem;">Total Delay</div>
            <div class="fs-2 fw-bold text-danger">{{ $production->delays->count() }} <span class="fs-6 text-muted font-normal">Event</span></div>
          </div>
        </div>
      </div>

      @if($production->notes)
        <div class="alert alert-info py-2 px-3 mb-4 small border">
          <strong>Catatan Shift:</strong> {{ $production->notes }}
        </div>
      @endif

      <!-- Section 1: Fleets -->
      <div class="mb-4">
        <div class="report-section-title">1. Rincian Fleet & Produksi Per Jam</div>
        
        @foreach($production->fleets as $index => $fleet)
        <div class="fleet-container rounded border mb-3 overflow-hidden">
            <div class="bg-light py-2 px-3 border-bottom d-flex justify-content-between align-items-center">
                <div>
                  <span class="badge bg-secondary me-2">FLEET #{{ $index + 1 }}</span>
                  <strong class="text-dark">DIGGER:</strong> <span class="text-uppercase fw-bold text-primary">{{ $fleet->digger->nomor_unit ?? '-' }}</span> 
                  <span class="text-muted">({{ $fleet->digger->type->name ?? '-' }})</span>
                </div>
                <div class="small text-secondary">
                  Target: <strong class="text-primary">{{ $fleet->target_bcm_per_hour ? number_format($fleet->target_bcm_per_hour, 2) . ' BCM/Jam' : '-' }}</strong> | Material: <strong class="text-dark">{{ $fleet->material_type }}</strong> | Jarak: <strong class="text-dark">{{ $fleet->distance ? $fleet->distance . ' KM' : '-' }}</strong>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table-report m-0">
                    <thead>
                        <tr>
                            <th rowspan="2" class="text-start ps-2" style="width: 150px;">Unit Hauler</th>
                            <th rowspan="2" class="text-center" style="width: 110px;">Payload (BCM)</th>
                            <th colspan="12" class="text-center">Ritasi Per Jam (Jam 1 - 12)</th>
                            <th rowspan="2" class="text-center" style="width: 90px;">Total Rit</th>
                            <th rowspan="2" class="text-end pe-2" style="width: 120px;">Total Produksi</th>
                        </tr>
                        <tr>
                            @for($i=1; $i<=12; $i++)
                                <th class="text-center p-1" style="width: 32px; font-size:10px;">{{$i}}</th>
                            @endfor
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($fleet->haulers as $h)
                            <tr>
                                <td class="fw-bold ps-2 text-dark">{{ $h->hauler->nomor_unit ?? '-' }}</td>
                                <td class="text-center">{{ number_format($h->payload, 2) }}</td>
                                @for($i=1; $i<=12; $i++)
                                    <td class="text-center p-1 small">{{ isset($h->hourly_ritasi[$i]) ? $h->hourly_ritasi[$i] : '' }}</td>
                                @endfor
                                <td class="fw-bold text-center bg-light">{{ $h->total_ritasi }}</td>
                                <td class="fw-bold text-end pe-2 text-success">{{ number_format($h->payload * $h->total_ritasi, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endforeach
      </div>

      <!-- Section 2 & 3: Support Equipment & Delays -->
      <div class="row g-3 mb-4">
        <div class="col-6">
          <div class="report-section-title">2. Unit Support Equipment</div>
          <div class="border rounded overflow-hidden">
            <table class="table-report m-0">
              <thead>
                <tr>
                  <th class="text-start ps-2">Unit Support</th>
                  <th class="text-center">HM Awal</th>
                  <th class="text-center">HM Akhir</th>
                  <th class="text-center">Total Jam</th>
                </tr>
              </thead>
              <tbody>
                @forelse($production->supports as $s)
                  <tr>
                    <td class="fw-bold ps-2 text-dark">{{ $s->support->nomor_unit ?? '-' }}</td>
                    <td class="text-center">{{ number_format($s->hm_awal, 2) }}</td>
                    <td class="text-center">{{ number_format($s->hm_akhir, 2) }}</td>
                    <td class="text-center fw-bold">{{ ($s->hm_akhir && $s->hm_awal) ? number_format($s->hm_akhir - $s->hm_awal, 2) : '-' }}</td>
                  </tr>
                @empty
                  <tr><td colspan="4" class="text-muted small text-center py-3">Tidak ada unit support.</td></tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>

        <div class="col-6">
          <div class="report-section-title">3. Delay / Standby Time</div>
          <div class="border rounded overflow-hidden">
            <table class="table-report m-0">
              <thead>
                <tr>
                  <th class="text-start ps-2">Waktu</th>
                  <th class="text-center">Kode</th>
                  <th class="text-start">Terdampak</th>
                  <th class="text-start">Keterangan</th>
                </tr>
              </thead>
              <tbody>
                @forelse($production->delays as $d)
                  <tr>
                    <td class="fw-bold ps-2 text-dark" style="white-space: nowrap;">{{ \Carbon\Carbon::parse($d->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($d->end_time)->format('H:i') }}</td>
                    <td class="text-center"><span class="badge bg-danger text-white px-1.5 py-0.5" style="font-size:0.7rem;">{{ $d->delay_code }}</span></td>
                    <td class="small text-secondary">
                        @if($d->fleet)
                            Digger: {{ $d->fleet->digger->nomor_unit ?? '' }}
                        @else
                            Global
                        @endif
                    </td>
                    <td class="text-muted small">{{ $d->remarks ?: '-' }}</td>
                  </tr>
                @empty
                  <tr><td colspan="4" class="text-muted small text-center py-3">Tidak ada delay.</td></tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- Section 4: Signatures -->
      <div>
        <div class="report-section-title">4. Lembar Pengesahan Laporan Produksi</div>
        <div class="row g-3">
          <div class="col-4">
            <div class="signature-box">
              <div class="fw-bold small text-secondary">Dibuat Oleh (Dispatcher)</div>
              <div class="signature-space"></div>
              <div class="border-top pt-1 small fw-bold text-dark">( .................................... )</div>
              <div class="text-muted" style="font-size: 0.7rem;">Production Dispatcher</div>
            </div>
          </div>
          <div class="col-4">
            <div class="signature-box">
              <div class="fw-bold small text-secondary">Diperiksa Oleh (Supervisor)</div>
              <div class="signature-space"></div>
              <div class="border-top pt-1 small fw-bold text-dark">( .................................... )</div>
              <div class="text-muted" style="font-size: 0.7rem;">Production Supervisor</div>
            </div>
          </div>
          <div class="col-4">
            <div class="signature-box">
              <div class="fw-bold small text-secondary">Disetujui Oleh (Superintendent)</div>
              <div class="signature-space"></div>
              <div class="border-top pt-1 small fw-bold text-dark">( .................................... )</div>
              <div class="text-muted" style="font-size: 0.7rem;">Production Superintendent</div>
            </div>
          </div>
        </div>
      </div>

    </div>

  </div>
</div>
@endsection
