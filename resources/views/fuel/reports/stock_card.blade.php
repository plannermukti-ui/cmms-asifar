@extends('layouts.tabler')

@section('title', 'Buku Kas / Kartu Stok BBM - FMS')

@section('content')
<div class="page-header d-print-none">
  <div class="container-xl">
    <div class="row g-2 align-items-center">
      <div class="col">
        <div class="page-pretitle text-uppercase font-monospace text-primary">Audit Trail & Mutasi Stok</div>
        <h2 class="page-title d-flex align-items-center gap-2">
          <svg xmlns="http://www.w3.org/2000/svg" class="icon text-primary" width="28" height="28" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><path d="M9 17h6" /><path d="M9 13h6" /></svg>
          Buku Kas / Kartu Stok BBM (Audit Trail)
        </h2>
      </div>
    </div>
  </div>
</div>

<div class="page-body">
  <div class="container-xl">
    
    <!-- Filter Card -->
    <div class="card mb-3 shadow-sm border">
      <div class="card-body p-3">
        <form method="GET" action="{{ route('fuel.reports.stock-card') }}" class="row g-2 align-items-end">
          <div class="col-6 col-md-2">
            <label class="form-label small text-muted">Site</label>
            <select name="site_id" class="form-select form-select-sm">
              <option value="">-- Semua Site --</option>
              @foreach($sites as $s)
                <option value="{{ $s->id }}" {{ $siteId == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-6 col-md-2">
            <label class="form-label small text-muted">Tipe Perangkat</label>
            <select name="reference_type" id="ref_type_select" class="form-select form-select-sm">
              <option value="fuel_storage" {{ $refType == 'fuel_storage' ? 'selected' : '' }}>Tangki Timbun</option>
              <option value="fuel_truck" {{ $refType == 'fuel_truck' ? 'selected' : '' }}>Fuel Truck</option>
            </select>
          </div>
          <div class="col-6 col-md-3">
            <label class="form-label small text-muted">Pilih Tangki / Fuel Truck</label>
            <select name="reference_id" id="ref_id_select" class="form-select form-select-sm">
              <option value="">-- Semua --</option>
              @if($refType == 'fuel_storage')
                @foreach($storages as $st)
                  <option value="{{ $st->id }}" {{ $refId == $st->id ? 'selected' : '' }}>{{ $st->code }} - {{ $st->name }}</option>
                @endforeach
              @else
                @foreach($fuelTrucks as $ft)
                  <option value="{{ $ft->id }}" {{ $refId == $ft->id ? 'selected' : '' }}>{{ $ft->masterUnit->nomor_unit ?? '-' }}</option>
                @endforeach
              @endif
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
          <div class="col-12 col-md-1 d-flex gap-1">
            <button type="submit" class="btn btn-primary btn-sm w-100">Filter</button>
          </div>
        </form>
      </div>
    </div>

    <!-- Table Kartu Stok -->
    <div class="card shadow-sm border">
      <div class="table-responsive">
        <table class="table table-vcenter table-hover card-table">
          <thead>
            <tr>
              <th>Waktu Transaksi</th>
              <th>Unit / Tangki</th>
              <th>Jenis Transaksi</th>
              <th>No. Referensi</th>
              <th class="text-end text-success">Masuk (L)</th>
              <th class="text-end text-danger">Keluar (L)</th>
              <th class="text-end fw-bold">Saldo Akhir (L)</th>
              <th class="text-end font-monospace">Totalizer Terkini</th>
              <th>Keterangan / Catatan</th>
              <th>Petugas</th>
            </tr>
          </thead>
          <tbody>
            @forelse($logs as $log)
            <tr>
              <td>
                <div class="fw-semibold">{{ $log->date_time ? $log->date_time->format('d/m/Y') : '-' }}</div>
                <div class="text-muted small">{{ $log->date_time ? $log->date_time->format('H:i') : '' }} WITA</div>
              </td>
              <td>
                <span class="small fw-bold text-body">{{ $log->device_name }}</span>
              </td>
              <td>
                <span class="badge {{ $log->qty_in > 0 ? 'bg-success-lt' : 'bg-danger-lt' }} fw-bold">
                  {{ $log->transaction_type }}
                </span>
              </td>
              <td class="font-monospace small">{{ $log->transaction_number ?? '-' }}</td>
              <td class="text-end font-monospace text-success fw-bold">
                {{ $log->qty_in > 0 ? '+' . number_format($log->qty_in, 0, ',', '.') : '-' }}
              </td>
              <td class="text-end font-monospace text-danger fw-bold">
                {{ $log->qty_out > 0 ? '-' . number_format($log->qty_out, 0, ',', '.') : '-' }}
              </td>
              <td class="text-end font-monospace fw-bold fs-4 text-body">
                {{ number_format($log->balance_after, 0, ',', '.') }}
              </td>
              <td class="text-end font-monospace small">
                {{ $log->totalizer_record ? number_format($log->totalizer_record, 2) : '-' }}
              </td>
              <td class="small text-secondary">{{ $log->notes ?? '-' }}</td>
              <td class="small text-muted">{{ $log->creator->nama_lengkap ?? $log->creator->name ?? '-' }}</td>
            </tr>
            @empty
            <tr>
              <td colspan="10" class="text-center text-muted py-4">Belum ada transaksi kartu stok tercatat pada rentang ini.</td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
      @if($logs->hasPages())
      <div class="card-footer d-flex align-items-center justify-content-end p-2 border-0">
        {{ $logs->links() }}
      </div>
      @endif
    </div>

  </div>
</div>
@endsection
