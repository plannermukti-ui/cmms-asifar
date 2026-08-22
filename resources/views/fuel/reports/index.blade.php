@extends('layouts.tabler')

@section('title', 'Rekapitulasi Konsumsi BBM & Burn Rate Unit - FMS')

@section('content')
<div class="page-header d-print-none">
  <div class="container-xl">
    <div class="row g-2 align-items-center">
      <div class="col">
        <div class="page-pretitle text-uppercase font-monospace text-primary">Consumption & Burn Rate Analytics</div>
        <h2 class="page-title d-flex align-items-center gap-2">
          <svg xmlns="http://www.w3.org/2000/svg" class="icon text-primary" width="28" height="28" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0" /><path d="M12 7v5l3 3" /></svg>
          Rekapitulasi Pemakaian BBM & Konsumsi Unit
        </h2>
      </div>
      <div class="col-auto ms-auto d-flex gap-2">
        <a href="{{ route('fuel.reports.index', array_merge(request()->all(), ['export' => 'pdf'])) }}" target="_blank" class="btn btn-outline-primary btn-sm d-flex align-items-center gap-1">
          <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><path d="M9 17h6" /><path d="M9 13h6" /></svg>
          Export Rekap (PDF)
        </a>
      </div>
    </div>
  </div>
</div>

<div class="page-body">
  <div class="container-xl">
    
    <!-- Filter Card -->
    <div class="card mb-3 shadow-sm border">
      <div class="card-body p-3">
        <form method="GET" action="{{ route('fuel.reports.index') }}" class="row g-2 align-items-end">
          <div class="col-6 col-md-3">
            <label class="form-label small text-muted">Site</label>
            <select name="site_id" class="form-select form-select-sm">
              <option value="">-- Semua Site --</option>
              @foreach($sites as $s)
                <option value="{{ $s->id }}" {{ $siteId == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-6 col-md-3">
            <label class="form-label small text-muted">Unit Tertentu</label>
            <select name="master_unit_id" class="form-select form-select-sm">
              <option value="">-- Semua Unit --</option>
              @foreach($units as $u)
                <option value="{{ $u->id }}" {{ $unitId == $u->id ? 'selected' : '' }}>{{ $u->nomor_unit }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-6 col-md-2">
            <label class="form-label small text-muted">Dari Tanggal</label>
            <input type="date" name="date_from" value="{{ $dateFrom }}" class="form-control form-control-sm">
          </div>
          <div class="col-6 col-md-2">
            <label class="form-label small text-muted">Sampai Tanggal</label>
            <input type="date" name="date_to" value="{{ $dateTo }}" class="form-control form-control-sm">
          </div>
          <div class="col-12 col-md-2 d-flex gap-1">
            <button type="submit" class="btn btn-primary btn-sm w-50">Filter</button>
            <a href="{{ route('fuel.reports.index') }}" class="btn btn-outline-secondary btn-sm w-50">Reset</a>
          </div>
        </form>
      </div>
    </div>

    <!-- Summary Metrics -->
    <div class="row row-cards mb-4">
      <div class="col-sm-6 col-lg-6">
        <div class="card card-sm shadow-sm border-0 border-start border-primary border-3">
          <div class="card-body">
            <div class="text-secondary small font-monospace text-uppercase">Total Penerimaan BBM (Inbound Periode Ini)</div>
            <div class="fw-bold fs-1 text-primary">{{ number_format($totalInbound, 0, ',', '.') }} <span class="fs-3 text-muted">Liter</span></div>
            <div class="text-muted small">Periode: {{ date('d/m/Y', strtotime($dateFrom)) }} s/d {{ date('d/m/Y', strtotime($dateTo)) }}</div>
          </div>
        </div>
      </div>

      <div class="col-sm-6 col-lg-6">
        <div class="card card-sm shadow-sm border-0 border-start border-success border-3">
          <div class="card-body">
            <div class="text-secondary small font-monospace text-uppercase">Total Pemakaian Unit (Outbound Periode Ini)</div>
            <div class="fw-bold fs-1 text-success">{{ number_format($totalOutbound, 0, ',', '.') }} <span class="fs-3 text-muted">Liter</span></div>
            <div class="text-muted small">{{ $unitDistributions->count() }} Unit Operasional Terisi</div>
          </div>
        </div>
      </div>
    </div>

    <!-- Table Breakdown per Unit -->
    <div class="card shadow-sm border">
      <div class="card-header border-0 pb-1">
        <h3 class="card-title fw-bold text-primary">Rincian Konsumsi Bahan Bakar per Unit</h3>
      </div>
      <div class="table-responsive">
        <table class="table table-vcenter table-hover card-table">
          <thead>
            <tr>
              <th>Nomor Unit</th>
              <th>Tipe & Model Unit</th>
              <th class="text-center">Frekuensi Isi</th>
              <th class="text-end">Reading Meter Awal</th>
              <th class="text-end">Reading Meter Akhir</th>
              <th class="text-end">Delta Jam/KM Operasi</th>
              <th class="text-end">Total Liter Diisi</th>
              <th class="text-end">Burn Rate Rata-rata (L/Hour)</th>
            </tr>
          </thead>
          <tbody>
            @forelse($unitDistributions as $ud)
            @php
                $deltaMeter = ($ud->max_meter && $ud->min_meter) ? max(0, $ud->max_meter - $ud->min_meter) : 0;
                $burnRate = ($deltaMeter > 0) ? round($ud->total_liters / $deltaMeter, 2) : 0;
            @endphp
            <tr>
              <td>
                <span class="badge bg-blue-lt font-monospace px-2 py-0.5 me-1">UNIT</span>
                <span class="fw-bold fs-4 text-body">{{ $ud->masterUnit->nomor_unit ?? '-' }}</span>
              </td>
              <td>
                <div>{{ $ud->masterUnit->type->name ?? '-' }}</div>
                <div class="text-muted small">{{ $ud->masterUnit->model->name ?? '-' }}</div>
              </td>
              <td class="text-center font-monospace">{{ $ud->fill_count }}x Pengisian</td>
              <td class="text-end font-monospace">{{ $ud->min_meter ? number_format($ud->min_meter, 1) : '-' }}</td>
              <td class="text-end font-monospace">{{ $ud->max_meter ? number_format($ud->max_meter, 1) : '-' }}</td>
              <td class="text-end font-monospace">{{ $deltaMeter > 0 ? number_format($deltaMeter, 1) : '-' }}</td>
              <td class="text-end font-monospace fw-bold fs-4 text-success">{{ number_format($ud->total_liters, 1, ',', '.') }} L</td>
              <td class="text-end font-monospace">
                @if($burnRate > 0)
                  <span class="badge bg-purple-lt fs-5 fw-bold">{{ number_format($burnRate, 2) }} L/HM</span>
                @else
                  <span class="text-muted">-</span>
                @endif
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="8" class="text-center text-muted py-4">Tidak ada data konsumsi unit pada rentang filter ini.</td>
            </tr>
            @endforelse
          </tbody>
          @if($unitDistributions->count() > 0)
          <tfoot>
            <tr class="bg-body-tertiary fw-bold">
              <td colspan="6" class="text-end text-uppercase">TOTAL KONSUMSI UNIT:</td>
              <td class="text-end font-monospace text-success fs-3">{{ number_format($unitDistributions->sum('total_liters'), 1, ',', '.') }} L</td>
              <td></td>
            </tr>
          </tfoot>
          @endif
        </table>
      </div>
    </div>

  </div>
</div>
@endsection
