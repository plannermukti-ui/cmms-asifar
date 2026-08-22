@extends('layouts.tabler')

@section('title', 'Detail Fuel Truck: ' . ($truck->masterUnit->nomor_unit ?? ''))

@section('content')
<div class="page-header d-print-none">
  <div class="container-xl">
    <div class="row g-2 align-items-center">
      <div class="col">
        <div class="page-pretitle text-uppercase font-monospace text-primary">Detail Mobile Fuel Dispenser</div>
        <h2 class="page-title">{{ $truck->masterUnit->nomor_unit ?? '-' }}</h2>
      </div>
      <div class="col-auto ms-auto">
        <a href="{{ route('fuel.trucks.index') }}" class="btn btn-outline-secondary btn-sm">
          &laquo; Kembali ke Daftar
        </a>
      </div>
    </div>
  </div>
</div>

<div class="page-body">
  <div class="container-xl">
    
    <div class="row g-3 mb-4">
      <div class="col-md-4">
        <div class="card shadow-sm border h-100">
          <div class="card-body">
            <h3 class="card-title fw-bold text-warning mb-3">Spesifikasi Fuel Truck</h3>
            <div class="list-group list-group-flush">
              <div class="list-group-item d-flex justify-content-between px-0 py-1.5">
                <span class="text-muted">Nomor Unit</span>
                <span class="fw-bold fs-4 text-body">{{ $truck->masterUnit->nomor_unit ?? '-' }}</span>
              </div>
              <div class="list-group-item d-flex justify-content-between px-0 py-1.5">
                <span class="text-muted">Tipe & Model</span>
                <span>{{ $truck->masterUnit->type->name ?? '-' }} • {{ $truck->masterUnit->model->name ?? '-' }}</span>
              </div>
              <div class="list-group-item d-flex justify-content-between px-0 py-1.5">
                <span class="text-muted">Site</span>
                <span>{{ $truck->site->name ?? $truck->masterUnit->site->name ?? 'Semua Site' }}</span>
              </div>
              <div class="list-group-item d-flex justify-content-between px-0 py-1.5">
                <span class="text-muted">Kapasitas Tangki</span>
                <span class="fw-bold">{{ number_format($truck->capacity, 0, ',', '.') }} Liter</span>
              </div>
              <div class="list-group-item d-flex justify-content-between px-0 py-1.5">
                <span class="text-muted">No. Seri Flowmeter</span>
                <span class="font-monospace">{{ $truck->flowmeter_serial_number ?? '-' }}</span>
              </div>
              <div class="list-group-item d-flex justify-content-between px-0 py-1.5">
                <span class="text-muted">Merk Dispenser</span>
                <span>{{ $truck->dispenser_brand ?? '-' }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-8">
        <div class="card shadow-sm border h-100 bg-body-tertiary">
          <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center mb-2">
              <span class="text-secondary font-monospace text-uppercase fw-bold">Stok BBM di Tangki Fuel Truck</span>
              <span class="badge {{ $truck->status_badge_class }} fs-4 px-3 py-1">{{ $truck->fill_percentage }}% TERISI</span>
            </div>

            <div class="progress mb-3" style="height: 20px;">
              <div class="progress-bar {{ $truck->fill_percentage <= 20 ? 'bg-danger' : ($truck->fill_percentage <= 40 ? 'bg-warning' : 'bg-warning') }}" 
                   style="width: {{ $truck->fill_percentage }}%"></div>
            </div>

            <div class="row g-3 text-center">
              <div class="col-6">
                <div class="bg-white dark:bg-dark p-3 rounded border">
                  <div class="text-muted small">Stok BBM Tersedia</div>
                  <div class="fs-1 fw-bold text-warning">{{ number_format($truck->current_stock, 0, ',', '.') }} <span class="fs-4 text-muted">L</span></div>
                </div>
              </div>
              <div class="col-6">
                <div class="bg-white dark:bg-dark p-3 rounded border">
                  <div class="text-muted small">Totalizer Flowmeter Terkini</div>
                  <div class="fs-1 fw-bold text-primary font-monospace">{{ number_format($truck->current_totalizer, 2, ',', '.') }}</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Riwayat Transaksi Kartu Stok Fuel Truck -->
    <div class="card shadow-sm border">
      <div class="card-header border-0 pb-1">
        <h3 class="card-title fw-bold text-warning">Riwayat Mutasi & Distribusi Fuel Truck (50 Transaksi Terakhir)</h3>
      </div>
      <div class="table-responsive">
        <table class="table table-vcenter table-hover card-table">
          <thead>
            <tr>
              <th>Waktu</th>
              <th>Jenis Transaksi</th>
              <th>No. Dokumen</th>
              <th class="text-end text-success">Isi Ulang (+)</th>
              <th class="text-end text-danger">Distribusi Unit (-)</th>
              <th class="text-end fw-bold">Sisa Stok (L)</th>
              <th class="text-end font-monospace">Totalizer Flowmeter</th>
              <th>Keterangan</th>
            </tr>
          </thead>
          <tbody>
            @forelse($truck->stockLogs as $log)
            <tr>
              <td>{{ $log->date_time ? $log->date_time->format('d/m/Y H:i') : '-' }}</td>
              <td><span class="badge bg-yellow-lt">{{ $log->transaction_type }}</span></td>
              <td class="font-monospace small">{{ $log->transaction_number ?? '-' }}</td>
              <td class="text-end font-monospace text-success fw-bold">
                {{ $log->qty_in > 0 ? '+' . number_format($log->qty_in, 0, ',', '.') : '-' }}
              </td>
              <td class="text-end font-monospace text-danger fw-bold">
                {{ $log->qty_out > 0 ? '-' . number_format($log->qty_out, 0, ',', '.') : '-' }}
              </td>
              <td class="text-end font-monospace fw-bold text-body">
                {{ number_format($log->balance_after, 0, ',', '.') }}
              </td>
              <td class="text-end font-monospace small">
                {{ $log->totalizer_record ? number_format($log->totalizer_record, 2, ',', '.') : '-' }}
              </td>
              <td class="small text-secondary">{{ $log->notes }}</td>
            </tr>
            @empty
            <tr>
              <td colspan="8" class="text-center text-muted py-4">Belum ada catatan mutasi pada Fuel Truck ini.</td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

  </div>
</div>
@endsection
