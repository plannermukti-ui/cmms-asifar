@extends('layouts.tabler')

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
          <button type="button" class="btn btn-primary d-none d-sm-inline-block" data-bs-toggle="modal" data-bs-target="#modal-request">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
            Buat Request Baru
          </button>
        </div>
      </div>
    </div>
  </div>
</div>

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

    @if(session('error_popup'))
        <div class="modal modal-blur fade" id="modal-error-popup" tabindex="-1" role="dialog" aria-hidden="true">
          <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
            <div class="modal-content">
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              <div class="modal-status bg-danger"></div>
              <div class="modal-body text-center py-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon mb-2 text-danger icon-lg" width="48" height="48" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 9v4" /><path d="M12 17h.01" /><path d="M5 19h14a2 2 0 0 0 1.84 -2.75l-7.1 -12.25a2 2 0 0 0 -3.5 0l-7.1 12.25a2 2 0 0 0 1.75 2.75" /></svg>
                <h3 class="text-danger fw-bold mb-2">Penanganan Unit Aktif</h3>
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

    <div class="card">
      <form method="GET" action="{{ route('pra-work-orders.index') }}">
      <div class="table-responsive">
        <table class="table table-vcenter table-mobile-md card-table table-striped">
          <thead>
            <tr>
              <th>ID Request</th>
              <th>Nomor Unit</th>
              <th>Waktu BD</th>
              <th>HM / KM</th>
              <th>Lokasi Kerusakan</th>
              <th>Problem</th>
              <th>Status</th>
              <th class="w-1">Aksi</th>
            </tr>
            <tr>
              <th><input type="text" class="form-control form-control-sm" name="id_request" value="{{ request('id_request') }}" placeholder="Filter ID"></th>
              <th><input type="text" class="form-control form-control-sm" name="nomor_unit" value="{{ request('nomor_unit') }}" placeholder="Filter Unit"></th>
              <th><input type="date" class="form-control form-control-sm" name="waktu_bd" value="{{ request('waktu_bd') }}"></th>
              <th></th>
              <th><input type="text" class="form-control form-control-sm" name="lokasi_kerusakan" value="{{ request('lokasi_kerusakan') }}" placeholder="Filter Lokasi"></th>
              <th><input type="text" class="form-control form-control-sm" name="problem" value="{{ request('problem') }}" placeholder="Filter Problem"></th>
              <th>
                <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                  <option value="">Semua</option>
                  <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                  <option value="Generated" {{ request('status') == 'Generated' ? 'selected' : '' }}>Generated</option>
                  <option value="Cancelled" {{ request('status') == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
              </th>
              <th>
                <div class="d-flex gap-1">
                  <button type="submit" class="btn btn-sm btn-primary">Cari</button>
                  <a href="{{ route('pra-work-orders.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
                </div>
              </th>
            </tr>
          </thead>
          <tbody>
            @forelse($praWorkOrders as $pra)
            <tr>
              <td class="text-muted">
                REQ-{{ str_pad($pra->id, 4, '0', STR_PAD_LEFT) }}<br>
                <small>By: {{ $pra->creator->name ?? 'System' }}</small>
              </td>
              <td>
                <div class="fw-bold">{{ $pra->masterUnit->nomor_unit }}</div>
                <div class="text-muted small">{{ $pra->masterUnit->model->name ?? '' }}</div>
              </td>
              <td>{{ $pra->waktu_bd->format('d/m/Y H:i') }}</td>
              <td>{{ $pra->hours_meter ?? '-' }}</td>
              <td>{{ $pra->lokasi_kerusakan }}</td>
              <td class="text-wrap" style="max-width: 250px;">{{ $pra->problem }}</td>
              <td>
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
              <td>
                <div class="btn-list flex-nowrap">
                  <button type="button" class="btn btn-sm btn-success text-nowrap" data-bs-toggle="modal" data-bs-target="#modal-wa-{{ $pra->id }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 21l1.65 -3.8a9 9 0 1 1 3.4 2.9l-5.05 .9" /><path d="M9 10a.5 .5 0 0 0 1 0v-1a.5 .5 0 0 0 -1 0v1a5 5 0 0 0 5 5h1a.5 .5 0 0 0 0 -1h-1a.5 .5 0 0 0 0 1" /></svg>
                    Share WA
                  </button>
                @if($pra->status == 'Pending')
                    <form action="{{ route('pra-work-orders.generate', $pra) }}" method="POST">
                      @csrf
                      <button type="submit" class="btn btn-sm btn-primary text-nowrap">
                        Generate WO
                      </button>
                    </form>
                    <form action="{{ route('pra-work-orders.cancel', $pra) }}" method="POST" onsubmit="return confirm('Batalkan request ini?');">
                      @csrf
                      <button type="submit" class="btn btn-sm btn-outline-danger">
                        Cancel
                      </button>
                    </form>
                @endif
                </div>
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
            <div class="modal modal-blur fade" id="modal-wa-{{ $pra->id }}" tabindex="-1" role="dialog" aria-hidden="true">
              <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title text-success">
                      <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2 text-success" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 21l1.65 -3.8a9 9 0 1 1 3.4 2.9l-5.05 .9" /><path d="M9 10a.5 .5 0 0 0 1 0v-1a.5 .5 0 0 0 -1 0v1a5 5 0 0 0 5 5h1a.5 .5 0 0 0 0 -1h-1a.5 .5 0 0 0 0 1" /></svg>
                      Kirim Laporan WA
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                    <p class="small text-muted mb-2">Salin teks di bawah ini dan kirimkan ke grup WA tim mekanik:</p>
                    <textarea class="form-control bg-light text-dark font-monospace small mb-3" rows="8" readonly id="wa-text-{{ $pra->id }}">{!! $waText !!}</textarea>
                    <button type="button" class="btn btn-success w-100 fw-bold shadow-sm" onclick="copyWaText({{ $pra->id }}, this)">
                      Salin Teks WA
                    </button>
                  </div>
                </div>
              </div>
            </div>
            @empty
            <tr>
              <td colspan="8" class="text-center text-muted py-4">Belum ada data request perbaikan.</td>
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
      </form>
    </div>
  </div>
</div>

<!-- Modal Create Request -->
<div class="modal modal-blur fade" id="modal-request" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
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
