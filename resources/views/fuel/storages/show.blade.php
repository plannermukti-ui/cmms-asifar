@extends('layouts.tabler')

@section('title', 'Detail Fuel Storage: ' . $storage->name)

@section('content')
<div class="page-header d-print-none">
  <div class="container-xl">
    <div class="row g-2 align-items-center">
      <div class="col">
        <div class="page-pretitle text-uppercase font-monospace text-primary">Detail Tangki / Station</div>
        <h2 class="page-title">{{ $storage->code }} - {{ $storage->name }}</h2>
      </div>
      <div class="col-auto ms-auto">
        <a href="{{ route('fuel.storages.index') }}" class="btn btn-outline-secondary btn-sm">
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
            <h3 class="card-title fw-bold text-primary mb-3">Informasi Teknis Tangki</h3>
            <div class="list-group list-group-flush">
              <div class="list-group-item d-flex justify-content-between px-0 py-1.5">
                <span class="text-muted">Kode Tangki</span>
                <span class="fw-bold font-monospace">{{ $storage->code }}</span>
              </div>
              <div class="list-group-item d-flex justify-content-between px-0 py-1.5">
                <span class="text-muted">Tipe</span>
                <span class="badge bg-blue-lt">{{ $storage->type }}</span>
              </div>
              <div class="list-group-item d-flex justify-content-between px-0 py-1.5">
                <span class="text-muted">Site</span>
                <span class="fw-semibold">{{ $storage->site->name ?? 'Semua Site' }}</span>
              </div>
              <div class="list-group-item d-flex justify-content-between px-0 py-1.5">
                <span class="text-muted">Lokasi / Area</span>
                <span>{{ $storage->location ?? '-' }}</span>
              </div>
              <div class="list-group-item d-flex justify-content-between px-0 py-1.5">
                <span class="text-muted">Kapasitas Maksimal</span>
                <span class="fw-bold">{{ number_format($storage->capacity, 0, ',', '.') }} Liter</span>
              </div>
              <div class="list-group-item d-flex justify-content-between px-0 py-1.5">
                <span class="text-muted">Batas Alert Min. Stok</span>
                <span class="text-danger fw-bold">{{ number_format($storage->min_stock_alert, 0, ',', '.') }} Liter</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-8">
        <div class="card shadow-sm border h-100 bg-body-tertiary">
          <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center mb-2">
              <span class="text-secondary font-monospace text-uppercase fw-bold">Kondisi Stok Aktual</span>
              <span class="badge {{ $storage->status_badge_class }} fs-4 px-3 py-1">{{ $storage->fill_percentage }}% TERISI</span>
            </div>

            <div class="progress mb-3" style="height: 20px;">
              <div class="progress-bar {{ $storage->fill_percentage <= 20 ? 'bg-danger' : ($storage->fill_percentage <= 40 ? 'bg-warning' : 'bg-primary') }}" 
                   style="width: {{ $storage->fill_percentage }}%"></div>
            </div>

            <div class="row g-3 text-center">
              <div class="col-6 col-md-6">
                <div class="bg-white dark:bg-dark p-3 rounded border">
                  <div class="text-muted small">Stok BBM Saat Ini</div>
                  <div class="fs-1 fw-bold text-azure">{{ number_format($storage->current_stock, 0, ',', '.') }} <span class="fs-4 text-muted">L</span></div>
                </div>
              </div>
              <div class="col-6 col-md-6">
                <div class="bg-white dark:bg-dark p-3 rounded border">
                  <div class="text-muted small">Totalizer Flowmeter Pompa</div>
                  <div class="fs-1 fw-bold text-primary font-monospace">{{ number_format($storage->current_totalizer, 2, ',', '.') }}</div>
                </div>
              </div>
            </div>

            @if($storage->remarks)
            <div class="mt-3 text-secondary small">
              <strong>Catatan:</strong> {{ $storage->remarks }}
            </div>
            @endif
          </div>
        </div>
      </div>
    </div>

    <!-- Riwayat Transaksi Kartu Stok Tangki -->
    <div class="card shadow-sm border">
      <div class="card-header border-0 pb-1">
        <h3 class="card-title fw-bold text-primary">Riwayat Transaksi & Kartu Stok (50 Transaksi Terakhir)</h3>
      </div>
      <div class="table-responsive">
        <table class="table table-vcenter table-hover card-table">
          <thead>
            <tr>
              <th>Waktu</th>
              <th>Jenis Transaksi</th>
              <th>No. Referensi</th>
              <th class="text-end text-success">Masuk (L)</th>
              <th class="text-end text-danger">Keluar (L)</th>
              <th class="text-end fw-bold">Saldo Akhir (L)</th>
              <th class="text-end font-monospace">Totalizer Pompa</th>
              <th>Keterangan</th>
            </tr>
          </thead>
          <tbody>
            @forelse($storage->stockLogs as $log)
            <tr>
              <td>{{ $log->date_time ? $log->date_time->format('d/m/Y H:i') : '-' }}</td>
              <td><span class="badge bg-blue-lt">{{ $log->transaction_type }}</span></td>
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
              <td colspan="8" class="text-center text-muted py-4">Belum ada catatan transaksi pada tangki ini.</td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

  </div>
</div>
@endsection
