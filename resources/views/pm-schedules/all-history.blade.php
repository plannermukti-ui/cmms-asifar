@extends('layouts.tabler')
@section('title', 'Global History Service PM - CMMS')

@section('content')
<div class="page-header d-print-none">
  <div class="row align-items-center">
    <div class="col">
      <div class="page-pretitle">Preventive Maintenance</div>
      <h2 class="page-title text-primary fw-bold">Riwayat History Service (Global)</h2>
      <div class="text-muted mt-1">Daftar log riwayat servis berkala unit & pembaruan massal Excel</div>
    </div>
    <div class="col-auto ms-auto d-print-none">
      <div class="btn-list">
        @can('create_pm_schedules')
        <a href="#" class="btn btn-success d-none d-sm-inline-block" data-bs-toggle="modal" data-bs-target="#modal-import-history">
          <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><path d="M12 11v6" /><path d="M9.5 13.5l2.5 -2.5l2.5 2.5" /></svg>
          Import Excel
        </a>
        @endcan
        <a href="{{ route('pm-schedules.index') }}" class="btn btn-outline-primary d-none d-sm-inline-block">
          <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 11l-4 4l4 4m-4 -4h11a4 4 0 0 0 0 -8h-1" /></svg>
          Kembali ke Jadwal PM
        </a>
      </div>
    </div>
  </div>
</div>

@if(session('import_errors'))
<div class="alert alert-warning alert-dismissible mt-3" role="alert">
  <div class="d-flex">
    <div>
      <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 9v2m0 4v.01" /><path d="M5 19h14a2 2 0 0 0 1.84 -2.75l-7.1 -12.25a2 2 0 0 0 -3.5 0l-7.1 12.25a2 2 0 0 0 1.75 2.75" /></svg>
    </div>
    <div>
      <h4 class="alert-title">Catatan Saat Import</h4>
      <div class="text-secondary">
        <ul class="mb-0">
          @foreach(session('import_errors') as $err)
            <li>{{ $err }}</li>
          @endforeach
        </ul>
      </div>
    </div>
  </div>
  <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
</div>
@endif

<div class="card mt-3">
  <div class="card-body">
    <form method="GET" action="{{ route('pm-schedules.all-history') }}" class="row g-2 align-items-end">
      <div class="col-md-4">
        <label class="form-label small fw-bold">Cari Data Riwayat</label>
        <input type="text" class="form-control form-control-sm" name="search" value="{{ request('search') }}" placeholder="Cari Nomor Unit / Template / No WO...">
      </div>
      <div class="col-md-3">
        <div class="d-flex gap-2">
          <button type="submit" class="btn btn-primary btn-sm flex-fill">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" /><path d="M21 21l-6 -6" /></svg>
            Filter
          </button>
          @if(request('search'))
            <a href="{{ route('pm-schedules.all-history') }}" class="btn btn-outline-secondary btn-sm">
              Reset
            </a>
          @endif
        </div>
      </div>
    </form>
  </div>
</div>

<div class="card mt-2">
  <div class="table-responsive">
    <table class="table card-table table-vcenter table-hover text-nowrap">
      <thead class="table-light">
        <tr>
          <th width="50">No</th>
          <th>No Unit</th>
          <th>Template Service</th>
          <th>HM Service</th>
          <th>Date Service</th>
          <th>Nomor Work Order</th>
          <th>Catatan</th>
          <th>Dibuat Oleh</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($histories as $idx => $history)
        <tr>
          <td>{{ $histories->firstItem() + $idx }}</td>
          <td class="fw-bold text-primary">{{ $history->pmSchedule->masterUnit->nomor_unit ?? '-' }}</td>
          <td>
            <span class="badge bg-purple-lt fw-semibold">
              {{ $history->pmSchedule->pmTemplate->name ?? '-' }}
            </span>
          </td>
          <td>
            <span class="badge bg-secondary-lt fw-bold">{{ number_format($history->hm_service, 1) }}</span>
          </td>
          <td>{{ $history->executed_at ? $history->executed_at->format('d M Y') : '-' }}</td>
          <td>
            @if($history->work_order_no)
              @if($history->workOrder)
                <a href="{{ route('work-orders.show', $history->workOrder->id) }}" class="fw-bold text-azure text-decoration-none">
                  {{ $history->work_order_no }}
                </a>
              @else
                <span class="badge bg-azure-lt">{{ $history->work_order_no }}</span>
              @endif
            @else
              <span class="text-muted">-</span>
            @endif
          </td>
          <td class="text-truncate" style="max-width: 250px;" title="{{ $history->notes }}">
            {{ $history->notes ?? '-' }}
          </td>
          <td class="text-muted small">
            {{ $history->creator->name ?? 'System' }}
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="8" class="text-center text-muted py-4">Belum ada data riwayat servis yang ditemukan.</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($histories->hasPages())
  <div class="card-footer d-flex align-items-center justify-content-between">
    <div class="text-muted small">
      Menampilkan {{ $histories->firstItem() ?? 0 }} - {{ $histories->lastItem() ?? 0 }} dari {{ $histories->total() }} riwayat
    </div>
    {{ $histories->links('pagination::bootstrap-5') }}
  </div>
  @endif
</div>

{{-- Modal Import Massal Excel --}}
@can('create_pm_schedules')
<div class="modal modal-blur fade" id="modal-import-history" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
      <form action="{{ route('pm-schedules.import-history') }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><path d="M12 11v6" /><path d="M9.5 13.5l2.5 -2.5l2.5 2.5" /></svg>
            Update Massal & Import Riwayat Servis PM
          </h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          {{-- Keterangan & Panduan Cara Import --}}
          <div class="alert alert-info shadow-none" role="alert">
            <h4 class="alert-title fw-bold">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-inline me-1" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0" /><path d="M12 9h.01" /><path d="M11 12h1v4h1" /></svg>
              Keterangan & Petunjuk Pengisian Format Excel
            </h4>
            <div class="text-secondary small mt-2">
              <p class="mb-2">
                Fitur ini digunakan untuk <strong>mencatat riwayat servis unit secara massal</strong> sekaligus <strong>memperbarui status jadwal PM secara otomatis</strong>. Saat data diimport:
              </p>
              <ul class="mb-2">
                <li>Kolom <strong>HM Service Terakhir</strong> pada jadwal unit akan otomatis terupdate.</li>
                <li>Sistem secara otomatis menghitung ulang <strong>Next Due HM</strong> dan <strong>Next Due Date</strong> unit tersebut.</li>
              </ul>

              <div class="fw-bold mb-1">Struktur Kolom Template:</div>
              <ol class="mb-0">
                <li><strong>Date</strong>: Tanggal servis dilaksanakan (Format: <code>YYYY-MM-DD</code>, contoh: <code>2026-08-15</code>).</li>
                <li><strong>Unit</strong>: Nomor unit armada (contoh: <code>EX-01</code>, <code>DT-05</code>).</li>
                <li><strong>Template</strong>: Nama template PM servis (contoh: <code>PM 250</code>, <code>PM 500</code>). <em>Opsional: jika dikosongkan, sistem akan otomatis mencocokkan jadwal PM unit yang ada.</em></li>
                <li><strong>HM</strong>: Nilai Hour Meter unit saat servis dilakukan (contoh: <code>12500.5</code>).</li>
                <li><strong>WO_No</strong>: Nomor Work Order terkait (opsional, contoh: <code>WO-2026-08-001</code>).</li>
                <li><strong>Notes</strong>: Catatan atau keterangan servis (opsional, contoh: <code>Service Berkala 250 Jam</code>).</li>
              </ol>
            </div>
          </div>

          <div class="mb-3 d-flex align-items-center justify-content-between p-3 border rounded bg-light">
            <div>
              <div class="fw-bold">Unduh File Contoh / Template</div>
              <div class="text-muted small">Gunakan format ini untuk memastikan data terbaca dengan sempurna.</div>
            </div>
            <a href="{{ route('pm-schedules.download-history-template') }}" class="btn btn-outline-primary btn-sm">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2" /><path d="M7 11l5 5l5 -5" /><path d="M12 4l0 12" /></svg>
              Download Template Excel
            </a>
          </div>

          <div class="mb-3">
            <label class="form-label required fw-bold">Pilih File Excel / CSV</label>
            <input type="file" class="form-control" name="file" accept=".xlsx,.xls,.csv" required>
            <div class="form-hint">Mendukung format <code>.xlsx</code>, <code>.xls</code>, atau <code>.csv</code> (Maksimal 10 MB).</div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-link link-secondary me-auto" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2" /><path d="M7 9l5 -5l5 5" /><path d="M12 4l0 12" /></svg>
            Proses Import
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endcan
@endsection
