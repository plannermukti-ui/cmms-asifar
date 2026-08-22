@extends('layouts.tabler')

@push('styles')
<style>
  /* =========================================================
     RESPONSIVE MOBILE — Pra Work Order (desktop tetap normal)
     ========================================================= */

  /* Tombol floating "Buat Request Baru" — hanya tampil di layar kecil (< md) */
  .btn-fab-request {
    position: fixed;
    right: 1.25rem;
    bottom: 1.5rem;
    z-index: 1035;
    width: 3.5rem;
    height: 3.5rem;
    padding: 0;
    border-radius: 50rem !important;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 10px 25px rgba(15, 23, 42, 0.35);
  }
  .btn-fab-request .icon {
    width: 1.5rem;
    height: 1.5rem;
    margin: 0;
  }

  @media (max-width: 767.98px) {
    /* Tombol aksi di kartu mobile: susun penuh biar gampang disentuh */
    .table-mobile-md .btn-list {
      flex-wrap: wrap;
    }
    .table-mobile-md .btn-list .btn {
      flex: 1 1 100%;
      margin: 0.25rem 0 !important;
    }
  }

  /* Modal fullscreen di HP: header & footer tetap di tempat, isian bisa discroll */
  @media (max-width: 575.98px) {
    .modal-fullscreen-sm-down {
      display: flex;
      flex-direction: column;
      /* override align-items:center dari modal-dialog-centered supaya konten penuh selebar layar */
      align-items: stretch;
    }
    .modal-fullscreen-sm-down .modal-content {
      flex: 1 1 auto;
      display: flex;
      flex-direction: column;
    }
    .modal-fullscreen-sm-down .modal-header,
    .modal-fullscreen-sm-down .modal-footer {
      flex-shrink: 0;
    }
    .modal-fullscreen-sm-down .modal-body {
      flex: 1 1 auto;
      overflow-y: auto;
    }
    .modal-fullscreen-sm-down .form-control,
    .modal-fullscreen-sm-down .form-select {
      min-height: 44px;
    }
  }
</style>
@endpush

@section('content')
<div class="page-header d-print-none">
  <div class="container-xl">
    <div class="row g-2 align-items-center">
      <div class="col">
        <h2 class="page-title">
          Request Perbaikan (Pra-Work Order)
        </h2>
        <div class="text-muted mt-1">Daftar laporan kerusakan dari pengguna unit</div>
      </div>
      <div class="col-auto ms-auto d-print-none">
        <div class="btn-list">
          <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-request">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
            Buat Request Baru
          </button>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Tombol floating khusus mobile agar request baru mudah dibuat di HP -->
<button type="button" class="btn btn-primary btn-fab-request d-md-none" data-bs-toggle="modal" data-bs-target="#modal-request" aria-label="Buat Request Baru">
  <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
</button>

<div class="page-body">
  <div class="container-xl">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible" role="alert">
            <div class="d-flex">
                <div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10" /></svg>
                </div>
                <div>{{ session('success') }}</div>
            </div>
            <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible" role="alert">
            <div>{{ session('error') }}</div>
            <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
        </div>
    @endif

    @if(session('error_popup'))
        <div class="modal modal-blur fade" id="modal-error-popup" tabindex="-1" role="dialog" aria-hidden="true">
          <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
            <div class="modal-content">
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              <div class="modal-status bg-danger"></div>
              <div class="modal-body text-center py-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon mb-2 text-danger icon-lg" width="48" height="48" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 9v4" /><path d="M12 17h.01" /><path d="M5 19h14a2 2 0 0 0 1.84 -2.75l-7.1 -12.25a2 2 0 0 0 -3.5 0l-7.1 12.25a2 2 0 0 0 1.75 2.75" /></svg>
                <h3 class="text-danger fw-bold mb-2">Generate Work Order Gagal</h3>
                <div class="text-muted">{{ session('error_popup') }}</div>
              </div>
              <div class="modal-footer">
                <div class="w-100">
                  <button type="button" class="btn btn-danger w-100 fw-bold" data-bs-dismiss="modal">
                    Saya Mengerti
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var errorModal = new bootstrap.Modal(document.getElementById('modal-error-popup'));
                errorModal.show();
            });
        </script>
    @endif

    <form id="pwo-filter" method="GET" action="{{ route('pra-work-orders.index') }}"></form>



    <div class="card">
      <div class="table-responsive">
        <table class="table table-vcenter card-table table-striped">
          <thead>
            <tr>
              <th>ID Request</th>
              <th>Nomor Unit</th>
              <th>Waktu BD</th>
              <th>HM / KM</th>
              <th>Lokasi Kerusakan</th>
              <th>Problem</th>
              <th>Status</th>
            </tr>
            <tr>
              <th><input type="text" class="form-control form-control-sm" form="pwo-filter" name="id_request" value="{{ request('id_request') }}" placeholder="Filter ID"></th>
              <th><input type="text" class="form-control form-control-sm" form="pwo-filter" name="nomor_unit" value="{{ request('nomor_unit') }}" placeholder="Filter Unit"></th>
              <th><input type="date" class="form-control form-control-sm" form="pwo-filter" name="waktu_bd" value="{{ request('waktu_bd') }}"></th>
              <th></th>
              <th><input type="text" class="form-control form-control-sm" form="pwo-filter" name="lokasi_kerusakan" value="{{ request('lokasi_kerusakan') }}" placeholder="Filter Lokasi"></th>
              <th><input type="text" class="form-control form-control-sm" form="pwo-filter" name="problem" value="{{ request('problem') }}" placeholder="Filter Problem"></th>
              <th>
                <select name="status" form="pwo-filter" class="form-select form-select-sm mb-1" onchange="document.getElementById('pwo-filter').submit()">
                  <option value="">Semua</option>
                  <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                  <option value="Generated" {{ request('status') == 'Generated' ? 'selected' : '' }}>Generated</option>
                  <option value="Cancelled" {{ request('status') == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
                <div class="d-flex gap-1 mt-1">
                  <button type="submit" form="pwo-filter" class="btn btn-sm btn-primary w-100 p-1">Cari</button>
                  <a href="{{ route('pra-work-orders.index') }}" class="btn btn-sm btn-outline-secondary w-100 p-1">Reset</a>
                </div>
              </th>
            </tr>
          </thead>
          <tbody>
            @forelse($praWorkOrders as $pra)
            <tr style="cursor: pointer;" class="hover-bg-light" data-bs-toggle="modal" data-bs-target="#modal-action-{{ $pra->id }}">
              <td class="text-muted" data-label="ID Request">
                REQ-{{ str_pad($pra->id, 4, '0', STR_PAD_LEFT) }}<br>
                <small>By: {{ $pra->creator->name ?? 'System' }}</small>
              </td>
              <td data-label="Nomor Unit">
                <div class="fw-bold">{{ $pra->masterUnit->nomor_unit }}</div>
                <div class="text-muted small">{{ $pra->masterUnit->model->name ?? '' }}</div>
              </td>
              <td data-label="Waktu BD">{{ $pra->waktu_bd->format('d/m/Y H:i') }}</td>
              <td data-label="HM / KM">{{ $pra->hours_meter ?? '-' }}</td>
              <td data-label="Lokasi Kerusakan">{{ $pra->lokasi_kerusakan }}</td>
              <td class="text-wrap" style="max-width: 250px;" data-label="Problem">{{ $pra->problem }}</td>
              <td data-label="Status">
                @if($pra->status == 'Pending')
                  <span class="badge bg-warning text-white">Pending</span>
                @elseif($pra->status == 'Generated')
                  <span class="badge bg-success text-white">Generated</span>
                  @if($pra->workOrder)
                  <a href="{{ route('work-orders.show', $pra->workOrder->id) }}" class="d-block mt-1 small text-nowrap">
                    {{ $pra->workOrder->no_wo }}
                  </a>
                  @endif
                @else
                  <span class="badge bg-danger text-white">Cancelled</span>
                @endif
              </td>
            </tr>
            @php
                $waText = "🚨 *Laporan Kerusakan Unit (Pra-WO)* 🚨\n"
                        . "Unit: *" . $pra->masterUnit->nomor_unit . " (" . ($pra->masterUnit->model->name ?? '-') . ")*\n"
                        . "Lokasi: " . $pra->lokasi_kerusakan . "\n"
                        . "Waktu BD: " . $pra->waktu_bd->format('d/m/Y H:i') . "\n"
                        . "HM: " . ($pra->hours_meter ?? '-') . "\n"
                        . "Problem: \n" . $pra->problem . "\n\n"
                        . "Mohon segera diverifikasi. Terima kasih.";
            @endphp
            <div class="modal modal-blur fade" id="modal-action-{{ $pra->id }}" tabindex="-1" role="dialog" aria-hidden="true">
              <div class="modal-dialog modal-md modal-dialog-centered" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title">
                      Detail Request: REQ-{{ str_pad($pra->id, 4, '0', STR_PAD_LEFT) }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                    <div class="datagrid mb-3">
                      <div class="datagrid-item">
                        <div class="datagrid-title">Nomor Unit</div>
                        <div class="datagrid-content fw-bold">{{ $pra->masterUnit->nomor_unit }}</div>
                      </div>
                      <div class="datagrid-item">
                        <div class="datagrid-title">Lokasi Kerusakan</div>
                        <div class="datagrid-content">{{ $pra->lokasi_kerusakan }}</div>
                      </div>
                      <div class="datagrid-item">
                        <div class="datagrid-title">Waktu BD</div>
                        <div class="datagrid-content">{{ $pra->waktu_bd->format('d/m/Y H:i') }}</div>
                      </div>
                      <div class="datagrid-item">
                        <div class="datagrid-title">Status</div>
                        <div class="datagrid-content">
                          @if($pra->status == 'Pending')
                            <span class="badge bg-warning text-white">Pending</span>
                          @elseif($pra->status == 'Generated')
                            <span class="badge bg-success text-white">Generated</span>
                          @else
                            <span class="badge bg-danger text-white">Cancelled</span>
                          @endif
                        </div>
                      </div>
                      <div class="datagrid-item" style="grid-column: 1 / -1;">
                        <div class="datagrid-title">Problem</div>
                        <div class="datagrid-content text-wrap">{{ $pra->problem }}</div>
                      </div>
                    </div>
                    
                    <p class="small text-muted mb-2 fw-bold">Teks Salinan Laporan (Opsional):</p>
                    <textarea class="form-control bg-light text-dark font-monospace small mb-3" rows="4" readonly id="wa-text-{{ $pra->id }}">{!! $waText !!}</textarea>
                  </div>
                  <div class="modal-footer bg-light">
                    <div class="w-100 d-flex gap-2 flex-wrap justify-content-end">
                      <button type="button" class="btn btn-outline-success" onclick="copyWaText({{ $pra->id }}, this)">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 21l1.65 -3.8a9 9 0 1 1 3.4 2.9l-5.05 .9" /><path d="M9 10a.5 .5 0 0 0 1 0v-1a.5 .5 0 0 0 -1 0v1a5 5 0 0 0 5 5h1a.5 .5 0 0 0 0 -1h-1a.5 .5 0 0 0 0 1" /></svg>
                        Salin WA
                      </button>
                    @if($pra->status == 'Pending')
                        <form action="{{ route('pra-work-orders.cancel', $pra) }}" method="POST" onsubmit="return confirm('Batalkan request ini?');">
                          @csrf
                          <button type="submit" class="btn btn-outline-danger">Cancel</button>
                        </form>
                        <form action="{{ route('pra-work-orders.generate', $pra) }}" method="POST" onsubmit="return confirm('Generate request ini menjadi Work Order?');">
                          @csrf
                          <button type="submit" class="btn btn-primary">Generate WO</button>
                        </form>
                    @endif
                    </div>
                  </div>
                </div>
              </div>
            </div>
            @empty
            <tr>
              <td colspan="7" class="text-center text-muted py-4">Belum ada data request perbaikan.</td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
      @if($praWorkOrders->hasPages())
      <div class="card-footer d-flex align-items-center">
        {!! $praWorkOrders->links('pagination::bootstrap-5') !!}
      </div>
      @endif
    </div>
  </div>
</div>

<!-- Modal Create Request -->
<div class="modal modal-blur fade" id="modal-request" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-fullscreen-sm-down" role="document">
    <div class="modal-content">
      <form action="{{ route('pra-work-orders.store') }}" method="POST">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title">Buat Laporan Kerusakan Unit</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label required">Nomor Unit</label>
              <select class="form-select" name="master_unit_id" required>
                <option value="">-- Pilih Unit --</option>
                @foreach($units as $unit)
                  <option value="{{ $unit->id }}">{{ $unit->nomor_unit }} ({{ $unit->model->name ?? '' }})</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label required">Waktu BD (Breakdown)</label>
              <input type="datetime-local" class="form-control" name="waktu_bd" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Hour Meter / KM</label>
              <input type="number" step="0.1" class="form-control" name="hours_meter" placeholder="HM saat ini">
            </div>
            <div class="col-md-6">
              <label class="form-label required">Lokasi Kerusakan</label>
              <input type="text" class="form-control" name="lokasi_kerusakan" placeholder="Misal: Pit 2, KM 5, Jetty" required>
            </div>
            <div class="col-md-12">
              <label class="form-label required">Problem / Keluhan</label>
              <textarea class="form-control" name="problem" rows="3" placeholder="Jelaskan kendala atau kerusakan yang terjadi pada unit..." required></textarea>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn me-auto" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Kirim Laporan</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
function copyWaText(id, btn) {
    var textarea = document.getElementById('wa-text-' + id);
    textarea.select();
    textarea.setSelectionRange(0, 99999);
    document.execCommand("copy");
    var originalText = btn.innerHTML;
    btn.innerHTML = "Berhasil Disalin!";
    setTimeout(function() { btn.innerHTML = originalText; }, 2000);
}
</script>
@endpush
