@extends('layouts.tabler')

@section('title', 'Laporan Stok Terkini BBM - FMS')

@section('content')
<div class="page-header d-print-none">
  <div class="container-xl">
    <div class="row g-2 align-items-center">
      <div class="col">
        <div class="page-pretitle text-uppercase font-monospace text-primary">Real-time Stock Inventory</div>
        <h2 class="page-title d-flex align-items-center gap-2">
          <svg xmlns="http://www.w3.org/2000/svg" class="icon text-azure" width="28" height="28" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 21v-14a3 3 0 0 1 3 -3h6a3 3 0 0 1 3 3v14" /><path d="M9 11l6 0" /><path d="M6 21l12 0" /></svg>
          Laporan Stok BBM Terkini (Tangki Timbun & Fuel Truck)
        </h2>
      </div>
      <div class="col-auto ms-auto d-flex gap-2">
        <a href="{{ route('fuel.reports.current-stock', array_merge(request()->all(), ['export' => 'pdf'])) }}" target="_blank" class="btn btn-outline-primary btn-sm d-flex align-items-center gap-1">
          <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><path d="M9 17h6" /><path d="M9 13h6" /></svg>
          Export PDF
        </a>
      </div>
    </div>
  </div>
</div>

<div class="page-body">
  <div class="container-xl">
    
    <!-- Filter Site -->
    <div class="card mb-3 shadow-sm border">
      <div class="card-body p-3">
        <form method="GET" action="{{ route('fuel.reports.current-stock') }}" class="row g-2 align-items-center">
          <div class="col-md-4">
            <select name="site_id" class="form-select form-select-sm" onchange="this.form.submit()">
              <option value="">-- Semua Site --</option>
              @foreach($sites as $s)
                <option value="{{ $s->id }}" {{ $siteId == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-8 text-end text-muted small">
            Posisi Data: <strong class="text-body">{{ now()->format('d F Y, H:i') }} WITA</strong>
          </div>
        </form>
      </div>
    </div>

    <!-- TABEL 1: TANGKI TIMBUN & FUEL STATION -->
    <div class="card shadow-sm border mb-4">
      <div class="card-header border-0 pb-1">
        <h3 class="card-title fw-bold text-azure">1. Stok Tangki Timbun (Fuel Storage) & Fuel Station</h3>
      </div>
      <div class="table-responsive">
        <table class="table table-vcenter table-hover card-table">
          <thead>
            <tr>
              <th>Kode</th>
              <th>Nama Tangki / Station</th>
              <th>Tipe</th>
              <th>Site & Lokasi</th>
              <th class="text-end">Kapasitas (L)</th>
              <th class="text-end">Stok Aktual (L)</th>
              <th class="text-center">Persentase (%)</th>
              <th class="text-end">Totalizer Pompa</th>
            </tr>
          </thead>
          <tbody>
            @php $totStCap = 0; $totStStock = 0; @endphp
            @forelse($storages as $st)
            @php $totStCap += $st->capacity; $totStStock += $st->current_stock; @endphp
            <tr>
              <td class="fw-bold font-monospace text-primary">{{ $st->code }}</td>
              <td class="fw-semibold">{{ $st->name }}</td>
              <td><span class="badge bg-blue-lt">{{ $st->type }}</span></td>
              <td>{{ $st->site->name ?? '-' }} ({{ $st->location ?? '-' }})</td>
              <td class="text-end font-monospace">{{ number_format($st->capacity, 0, ',', '.') }}</td>
              <td class="text-end font-monospace fw-bold text-azure fs-4">{{ number_format($st->current_stock, 0, ',', '.') }}</td>
              <td class="text-center" style="width: 140px;">
                <div class="d-flex align-items-center gap-2">
                  <div class="progress flex-grow-1" style="height: 8px;">
                    <div class="progress-bar {{ $st->fill_percentage <= 20 ? 'bg-danger' : ($st->fill_percentage <= 40 ? 'bg-warning' : 'bg-success') }}" 
                         style="width: {{ $st->fill_percentage }}%"></div>
                  </div>
                  <span class="small font-monospace">{{ $st->fill_percentage }}%</span>
                </div>
              </td>
              <td class="text-end font-monospace">{{ number_format($st->current_totalizer, 2) }}</td>
            </tr>
            @empty
            <tr>
              <td colspan="8" class="text-center text-muted py-3">Tidak ada data tangki timbun.</td>
            </tr>
            @endforelse
          </tbody>
          <tfoot>
            <tr class="bg-body-tertiary fw-bold">
              <td colspan="4" class="text-end text-uppercase">TOTAL STOK TANGKI TIMBUN:</td>
              <td class="text-end font-monospace">{{ number_format($totStCap, 0, ',', '.') }} L</td>
              <td class="text-end font-monospace text-azure fs-3">{{ number_format($totStStock, 0, ',', '.') }} L</td>
              <td colspan="2"></td>
            </tr>
          </tfoot>
        </table>
      </div>
    </div>

    <!-- TABEL 2: MOBILE FUEL TRUCK -->
    <div class="card shadow-sm border mb-4">
      <div class="card-header border-0 pb-1">
        <h3 class="card-title fw-bold text-warning">2. Stok di Tangki Mobile Fuel Truck</h3>
      </div>
      <div class="table-responsive">
        <table class="table table-vcenter table-hover card-table">
          <thead>
            <tr>
              <th>Nomor Unit FT</th>
              <th>Tipe / Model</th>
              <th>Site</th>
              <th class="text-end">Kapasitas Tangki (L)</th>
              <th class="text-end">Stok BBM Saat Ini (L)</th>
              <th class="text-center">Level (%)</th>
              <th class="text-end">Totalizer Terkini</th>
              <th>No Seri Flowmeter</th>
            </tr>
          </thead>
          <tbody>
            @php $totFtCap = 0; $totFtStock = 0; @endphp
            @forelse($fuelTrucks as $ft)
            @php $totFtCap += $ft->capacity; $totFtStock += $ft->current_stock; @endphp
            <tr>
              <td>
                <span class="badge bg-yellow-lt font-monospace me-1">FT</span>
                <span class="fw-bold fs-4 text-body">{{ $ft->masterUnit->nomor_unit ?? '-' }}</span>
              </td>
              <td>{{ $ft->masterUnit->type->name ?? '-' }} ({{ $ft->masterUnit->model->name ?? '-' }})</td>
              <td>{{ $ft->site->name ?? $ft->masterUnit->site->name ?? '-' }}</td>
              <td class="text-end font-monospace">{{ number_format($ft->capacity, 0, ',', '.') }}</td>
              <td class="text-end font-monospace fw-bold text-warning fs-4">{{ number_format($ft->current_stock, 0, ',', '.') }}</td>
              <td class="text-center" style="width: 140px;">
                <div class="d-flex align-items-center gap-2">
                  <div class="progress flex-grow-1" style="height: 8px;">
                    <div class="progress-bar {{ $ft->fill_percentage <= 20 ? 'bg-danger' : ($ft->fill_percentage <= 40 ? 'bg-warning' : 'bg-warning') }}" 
                         style="width: {{ $ft->fill_percentage }}%"></div>
                  </div>
                  <span class="small font-monospace">{{ $ft->fill_percentage }}%</span>
                </div>
              </td>
              <td class="text-end font-monospace fw-bold text-primary">{{ number_format($ft->current_totalizer, 2) }}</td>
              <td class="font-monospace small">{{ $ft->flowmeter_serial_number ?? '-' }}</td>
            </tr>
            @empty
            <tr>
              <td colspan="8" class="text-center text-muted py-3">Tidak ada data unit Fuel Truck.</td>
            </tr>
            @endforelse
          </tbody>
          <tfoot>
            <tr class="bg-body-tertiary fw-bold">
              <td colspan="3" class="text-end text-uppercase">TOTAL STOK FUEL TRUCK:</td>
              <td class="text-end font-monospace">{{ number_format($totFtCap, 0, ',', '.') }} L</td>
              <td class="text-end font-monospace text-warning fs-3">{{ number_format($totFtStock, 0, ',', '.') }} L</td>
              <td colspan="3"></td>
            </tr>
          </tfoot>
        </table>
      </div>
    </div>

    <!-- GRAND TOTAL CARD -->
    <div class="card shadow-sm border bg-primary-lt">
      <div class="card-body p-3 d-flex justify-content-between align-items-center">
        <div>
          <span class="text-uppercase font-monospace fw-bold text-primary">GRAND TOTAL INVENTORY BBM SITE</span>
          <div class="text-muted small">Akumulasi Seluruh Tangki Timbun + Armada Fuel Truck</div>
        </div>
        <div class="text-end font-monospace">
          <div class="fs-1 fw-bold text-primary">{{ number_format($totStStock + $totFtStock, 0, ',', '.') }} <span class="fs-3">LITER</span></div>
          <div class="text-muted small">Total Kapasitas: {{ number_format($totStCap + $totFtCap, 0, ',', '.') }} Liter</div>
        </div>
      </div>
    </div>

  </div>
</div>
@endsection
