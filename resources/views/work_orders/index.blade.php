@extends('layouts.tabler')
@section('title', 'Daftar Work Order - CMMS')
@section('content')
<div class="page-header d-print-none">
  <div class="row align-items-center">
    <div class="col"><h2 class="page-title">Daftar Work Order</h2></div>
    <div class="col-auto ms-auto d-print-none">
      <a href="#" class="btn btn-success me-2" data-bs-toggle="modal" data-bs-target="#modal-download-dmbd">
        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-file-spreadsheet" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><path d="M8 11h8v7h-8z" /><path d="M8 15h8" /><path d="M11 11v7" /></svg>
        Download DMBD
      </a>
      @can('create_work_orders')
      <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-tambah-wo">
        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="12" y1="5" x2="12" y2="19" /><line x1="5" y1="12" x2="19" y2="12" /></svg>
        Buat Work Order
      </a>
      @endcan
    </div>
  </div>
</div>

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

<!-- Filter -->
<style>
  /* ── Filter Card & Unified Inputs ── */
  .filter-card {
    border: 1px solid rgba(98, 105, 118, 0.16);
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
    border-radius: 8px;
    background: #ffffff;
  }
  .filter-label {
    font-size: 0.72rem;
    font-weight: 700;
    color: #64748b;
    margin-bottom: 5px;
    text-transform: uppercase;
    letter-spacing: 0.03em;
    display: flex;
    align-items: center;
    gap: 4px;
    height: 18px;
    white-space: nowrap;
  }
  .filter-control {
    height: 36px !important;
    min-height: 36px !important;
    max-height: 36px !important;
    font-size: 0.82rem !important;
    border-radius: 6px !important;
    border: 1px solid #cbd5e1 !important;
    background-color: #ffffff !important;
    color: #1e293b !important;
    padding: 0.35rem 0.65rem !important;
    box-sizing: border-box !important;
    transition: all 0.15s ease-in-out;
  }
  .filter-control:focus {
    border-color: #206bc4 !important;
    box-shadow: 0 0 0 3px rgba(32, 107, 196, 0.12) !important;
    outline: none !important;
  }
  
  /* Unified Virtual Select (.excel-filter) */
  .filter-card .vscomp-wrapper {
    width: 100% !important;
    font-family: inherit !important;
  }
  .filter-card .vscomp-ele {
    height: 36px !important;
    min-height: 36px !important;
    max-height: 36px !important;
    padding: 0 8px !important;
    border-radius: 6px !important;
    border: 1px solid #cbd5e1 !important;
    background-color: #ffffff !important;
    font-size: 0.82rem !important;
    display: flex !important;
    align-items: center !important;
    box-sizing: border-box !important;
    transition: all 0.15s ease-in-out !important;
  }
  .filter-card .vscomp-ele:hover {
    border-color: #94a3b8 !important;
  }
  .filter-card .vscomp-wrapper.focused .vscomp-ele {
    border-color: #206bc4 !important;
    box-shadow: 0 0 0 3px rgba(32, 107, 196, 0.12) !important;
  }
  .filter-card .vscomp-placeholder {
    font-size: 0.82rem !important;
    color: #94a3b8 !important;
  }
  .filter-card .vscomp-value {
    font-size: 0.82rem !important;
    color: #1e293b !important;
    font-weight: 500 !important;
  }
  .filter-card .vscomp-arrow {
    right: 8px !important;
  }
  .filter-card .vscomp-arrow::after {
    border-width: 1.5px 0 0 1.5px !important;
    width: 6px !important;
    height: 6px !important;
    border-color: #64748b !important;
  }
  
  /* Filter Action Buttons */
  .filter-btn {
    height: 36px !important;
    min-height: 36px !important;
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    font-size: 0.82rem !important;
    font-weight: 600 !important;
    border-radius: 6px !important;
    padding: 0 12px !important;
    box-sizing: border-box !important;
  }
  .filter-btn-icon {
    height: 36px !important;
    width: 36px !important;
    min-height: 36px !important;
    min-width: 36px !important;
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    border-radius: 6px !important;
    padding: 0 !important;
    box-sizing: border-box !important;
  }

  /* Dark mode support */
  [data-bs-theme="dark"] .filter-card {
    background: #182234;
    border-color: rgba(255, 255, 255, 0.08);
  }
  [data-bs-theme="dark"] .filter-label {
    color: #94a3b8;
  }
  [data-bs-theme="dark"] .filter-control {
    background-color: #1d273b !important;
    border-color: #2c3b52 !important;
    color: #e2e8f0 !important;
  }
  [data-bs-theme="dark"] .filter-card .vscomp-ele {
    background-color: #1d273b !important;
    border-color: #2c3b52 !important;
  }
  [data-bs-theme="dark"] .filter-card .vscomp-value {
    color: #e2e8f0 !important;
  }
  [data-bs-theme="dark"] .filter-card .vscomp-placeholder {
    color: #64748b !important;
  }
</style>

<div class="card filter-card mt-3">
  <div class="card-body p-3">
    <form method="GET" class="row g-2 align-items-start">
      <!-- Search -->
      <div class="col-12 col-sm-6 col-lg-2">
        <label class="filter-label">
          <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-search" width="14" height="14" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="10" cy="10" r="7"/><line x1="21" y1="21" x2="15" y2="15"/></svg>
          No WO / Unit
        </label>
        <input type="text" class="form-control filter-control" name="search" value="{{ request('search') }}" placeholder="Cari WO / Unit...">
      </div>

      <!-- Status -->
      <div class="col-12 col-sm-6 col-lg-2">
        <label class="filter-label">
          <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-adjustments-horizontal" width="14" height="14" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="14" cy="6" r="2"/><line x1="4" y1="6" x2="12" y2="6"/><line x1="16" y1="6" x2="20" y2="6"/><circle cx="8" cy="12" r="2"/><line x1="4" y1="12" x2="6" y2="12"/><line x1="10" y1="12" x2="20" y2="12"/><circle cx="17" cy="18" r="2"/><line x1="4" y1="18" x2="15" y2="18"/><line x1="19" y1="18" x2="20" y2="18"/></svg>
          Status WO
        </label>
        <select class="form-select excel-filter" name="status_wo[]" multiple data-placeholder="Semua Status">
          @foreach(['Open','Inprogress','Completed','Cancel','Backlog'] as $s)
            <option value="{{ $s }}" {{ in_array($s, (array)request('status_wo', [])) ? 'selected' : '' }}>{{ $s }}</option>
          @endforeach
        </select>
      </div>

      <!-- Tipe -->
      <div class="col-12 col-sm-6 col-lg-2">
        <label class="filter-label">
          <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-category" width="14" height="14" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 4h6v6h-6z"/><path d="M14 4h6v6h-6z"/><path d="M4 14h6v6h-6z"/><circle cx="17" cy="17" r="3"/></svg>
          Tipe WO
        </label>
        <select class="form-select excel-filter" name="tipe_wo[]" multiple data-placeholder="Semua Tipe">
          <option value="BD" {{ in_array('BD', (array)request('tipe_wo', [])) ? 'selected' : '' }}>BD (Breakdown)</option>
          <option value="Plan" {{ in_array('Plan', (array)request('tipe_wo', [])) ? 'selected' : '' }}>Plan</option>
        </select>
      </div>

      <!-- Site -->
      <div class="col-12 col-sm-6 col-lg-2">
        <label class="filter-label">
          <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-map-pin" width="14" height="14" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="11" r="3"/><path d="M17.657 16.657l-4.243 4.243a2 2 0 0 1 -2.827 0l-4.244 -4.243a8 8 0 1 1 11.314 0z"/></svg>
          Site Lokasi
        </label>
        <select class="form-select excel-filter" name="site_id[]" multiple data-placeholder="Semua Site">
          @foreach($sites as $site)
            <option value="{{ $site->id }}" {{ in_array($site->id, (array)request('site_id', [])) ? 'selected' : '' }}>{{ $site->name }}</option>
          @endforeach
        </select>
      </div>

      <!-- Dari Tanggal -->
      <div class="col-12 col-sm-6 col-lg-2">
        <label class="filter-label">
          <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-calendar" width="14" height="14" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><rect x="4" y="5" width="16" height="16" rx="2"/><line x1="16" y1="3" x2="16" y2="7"/><line x1="8" y1="3" x2="8" y2="7"/><line x1="4" y1="11" x2="20" y2="11"/></svg>
          Dari Tanggal
        </label>
        <input type="date" class="form-control filter-control" name="date_from" value="{{ request('date_from') }}">
      </div>

      <!-- Actions -->
      <div class="col-12 col-sm-6 col-lg-2">
        <label class="filter-label text-muted d-none d-lg-flex align-items-center">
          <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-bolt" width="14" height="14" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><polyline points="13 3 13 10 19 10 11 21 11 14 5 14 13 3"/></svg>
          Aksi
        </label>
        <div class="d-flex gap-1">
          <button type="submit" class="btn btn-primary filter-btn flex-grow-1 shadow-none">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-filter me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 4h16v2.172a2 2 0 0 1 -.586 1.414l-4.414 4.414v7l-6 2v-8.5l-4.48 -4.928a2 2 0 0 1 -.52 -1.344v-2.228z"/></svg>
            Filter
          </button>
          <a href="{{ route('work-orders.index') }}" class="btn btn-outline-secondary filter-btn-icon shadow-none" data-bs-toggle="tooltip" title="Reset Filter">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-rotate m-0" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M19.95 11a8 8 0 1 0 -.5 4m.5 5v-5h-5"/></svg>
          </a>
          <button type="submit" formaction="{{ route('work-orders.export') }}" class="btn btn-outline-success filter-btn-icon shadow-none" data-bs-toggle="tooltip" title="Export Excel">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-file-spreadsheet m-0" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4"/><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z"/><path d="M8 11h8v7h-8z"/><path d="M8 15h8"/><path d="M11 11v7"/></svg>
          </button>
        </div>
      </div>
    </form>
  </div>
</div>

<style>
  @media print {
    .badge {
      border: 0 !important;
    }
  }
</style>

<style>
.wo-group-header {
  background: linear-gradient(90deg, var(--group-color, #f0f0f0) 0%, rgba(255,255,255,0.05) 100%);
  cursor: pointer;
  user-select: none;
  transition: opacity 0.15s;
}
.wo-group-header:hover { opacity: 0.85; }
.wo-group-header td { padding: 0.45rem 0.75rem !important; border-bottom: 2px solid rgba(0,0,0,0.08); }
.wo-group-chevron { transition: transform 0.25s ease; display: inline-block; }
.wo-group-collapsed .wo-group-chevron { transform: rotate(-90deg); }
.wo-group-row { transition: opacity 0.2s; }
.status-completed-section td { background-color: rgba(42, 167, 110, 0.06); }
</style>

<div class="card mt-2">
  <div class="table-responsive">
    <table class="table card-table table-vcenter text-nowrap" id="wo-grouped-table">
      <thead>
        <tr>
          <th>No WO</th>
          <th>Status</th>
          <th>Model</th>
          <th>Unit</th>
          <th>Site</th>
          <th>Waktu BD</th>
          <th>Durasi (Hrs)</th>
        </tr>
      </thead>
      <tbody>
        @php
          $statusOrder = ['Open', 'Inprogress', 'Backlog', 'Cancel', 'Completed'];
          $grouped = $workOrders->getCollection()->groupBy('status_wo');
          // bg = background hex, fg = foreground/text hex
          $statusConfig = [
            'Open'       => ['bg' => '#206bc4', 'fg' => '#ffffff', 'icon' => '🔵', 'label' => 'Open'],
            'Inprogress' => ['bg' => '#f6a500', 'fg' => '#3d2a00', 'icon' => '🟡', 'label' => 'Inprogress'],
            'Backlog'    => ['bg' => '#7263d5', 'fg' => '#ffffff', 'icon' => '🟣', 'label' => 'Backlog'],
            'Cancel'     => ['bg' => '#d63939', 'fg' => '#ffffff', 'icon' => '🔴', 'label' => 'Cancel'],
            'Completed'  => ['bg' => '#2fb344', 'fg' => '#ffffff', 'icon' => '🟢', 'label' => 'Completed'],
          ];
          $hasAny = $workOrders->isNotEmpty();
        @endphp

        @if(!$hasAny)
          <tr><td colspan="7" class="text-center text-muted py-4">Belum ada data Work Order.</td></tr>
        @else
          @foreach($statusOrder as $statusKey)
            @if($grouped->has($statusKey))
              @php
                $items   = $grouped->get($statusKey);
                $cfg     = $statusConfig[$statusKey] ?? ['bg'=>'#666666','fg'=>'#ffffff','icon'=>'⚪','label'=>$statusKey];
                $groupId = 'wo-group-' . strtolower($statusKey);
              @endphp
              {{-- Group Header Row --}}
              <tr class="wo-group-header {{ $statusKey === 'Completed' ? 'wo-completed-header' : '' }}"
                  style="--group-color: {{ $cfg['bg'] }}1a;"
                  onclick="toggleWoGroup('{{ $groupId }}')"
                  id="{{ $groupId }}-header">
                <td colspan="7">
                  <div class="d-flex align-items-center gap-2">
                    <span class="wo-group-chevron text-muted">▼</span>
                    <span class="badge fs-6 fw-bold"
                          style="background-color:{{ $cfg['bg'] }}; color:{{ $cfg['fg'] }}; border:1px solid {{ $cfg['bg'] }};"
                    >{{ $cfg['icon'] }} {{ $cfg['label'] }}</span>
                    <span class="badge bg-secondary-lt text-secondary">{{ $items->count() }} WO</span>
                    @if($statusKey === 'Completed')
                      <span class="badge bg-green-lt text-green ms-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-circle-check me-1" width="14" height="14" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="9"/><path d="M9 12l2 2l4 -4"/></svg>
                        Selesai
                      </span>
                    @endif
                  </div>
                </td>
              </tr>
              {{-- Group Rows --}}
              @foreach($items as $wo)
              <tr class="wo-group-row hover-bg-light {{ $statusKey === 'Completed' ? 'status-completed-section' : '' }}" 
                  data-group="{{ $groupId }}"
                  style="cursor: pointer;"
                  onclick="window.location='{{ route('work-orders.show', $wo) }}'">
                <td class="fw-bold">{{ $wo->no_wo }}</td>
                <td>
                  <span class="badge fw-semibold"
                        style="background-color:{{ $cfg['bg'] }}; color:{{ $cfg['fg'] }};"
                  >{{ $cfg['label'] }}</span>
                </td>
                <td>{{ $wo->unit->model->name ?? '-' }}</td>
                <td>{{ $wo->unit->nomor_unit ?? '-' }}</td>
                <td>{{ $wo->site->name ?? ($wo->unit->siteRelation->name ?? ($wo->unit->site->name ?? '-')) }}</td>
                <td>{{ $wo->waktu_bd ? \Carbon\Carbon::parse($wo->waktu_bd)->format('d M Y H:i') : '-' }}</td>
                <td>{{ $wo->durasi_hrs ?? '-' }}</td>
              </tr>
              @endforeach
            @endif
          @endforeach
        @endif
      </tbody>
    </table>
  </div>
  @if($workOrders->hasPages())
  <div class="card-footer">{{ $workOrders->links('pagination::bootstrap-5') }}</div>
  @endif
</div>

<script>
function toggleWoGroup(groupId) {
  const header = document.getElementById(groupId + '-header');
  const rows = document.querySelectorAll('[data-group="' + groupId + '"]');
  const chevron = header ? header.querySelector('.wo-group-chevron') : null;

  const isCollapsed = header && header.classList.contains('wo-group-collapsed');

  rows.forEach(function(row) {
    row.style.display = isCollapsed ? '' : 'none';
  });

  if (header) {
    header.classList.toggle('wo-group-collapsed', !isCollapsed);
  }
}

// Auto-collapse "Completed" group on load
document.addEventListener('DOMContentLoaded', function () {
  toggleWoGroup('wo-group-completed');
});
</script>

<!-- Modal Download DMBD -->
<div class="modal modal-blur fade" id="modal-download-dmbd" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Download Laporan DMBD</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('work-orders.export-dmbd') }}" method="GET">
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Tipe WO</label>
            <select class="form-select" name="tipe_wo[]">
              <option value="">-- Semua Tipe --</option>
              <option value="BD">BD (Breakdown)</option>
              <option value="Plan">Plan</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Status WO</label>
            <select class="form-select excel-filter" name="status_wo[]" multiple data-placeholder="Pilih Status WO">
              @foreach(['Open','Inprogress','Completed','Cancel','Backlog'] as $s)
                <option value="{{ $s }}">{{ $s }}</option>
              @endforeach
            </select>
            <small class="text-muted">Bisa memilih lebih dari satu status.</small>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-link link-secondary me-auto" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-success">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-download" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2" /><path d="M7 11l5 5l5 -5" /><path d="M12 4l0 12" /></svg>
            Download
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
<div class="modal modal-blur fade" id="modal-tambah-wo" tabindex="-1" role="dialog" aria-hidden="true" data-bs-backdrop="static">
  <div class="modal-dialog modal-fullscreen" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Buat Work Order Baru</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body bg-muted-lt">
<form action="{{ route('work-orders.store') }}" method="post" id="wo-form">
@csrf

@if ($errors->any())
<div class="alert alert-danger mb-3">
  <strong>Gagal menyimpan Work Order!</strong> Terdapat kesalahan atau data yang kurang pada form:
  <ul class="mb-0 mt-1">
    @foreach ($errors->all() as $error)
      <li>{{ $error }}</li>
    @endforeach
  </ul>
</div>
@endif

<div class="row mt-3">
  <!-- Card 1: Identitas Work Order -->
  <div class="col-lg-3 mb-3">
    <div class="card h-100 shadow-sm border-0">
      <div class="card-status-top bg-primary"></div>
      <div class="card-header p-2 bg-transparent">
        <h3 class="card-title d-flex align-items-center fs-4 fw-bold text-primary">
          <span class="badge bg-primary text-white me-2">01</span> Identitas WO
        </h3>
      </div>
      <div class="card-body p-2">
        <div class="row g-2">
          <div class="col-6">
            <label class="form-label small fw-semibold text-muted mb-1">No WO</label>
            <input type="text" class="form-control form-control-sm bg-light fw-bold text-primary" value="{{ $no_wo }}" readonly>
          </div>
          <div class="col-6">
            <label class="form-label required small fw-semibold text-muted mb-1">Status WO</label>
            <select name="status_wo" class="form-select form-select-sm border-primary" required>
              @foreach(['Open','Inprogress','Completed','Cancel','Backlog'] as $s)
                <option value="{{ $s }}" {{ old('status_wo','Open') == $s ? 'selected' : '' }}>{{ $s }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-6">
            <label class="form-label required small fw-semibold text-muted mb-1">Tipe WO</label>
            <select name="tipe_wo" class="form-select form-select-sm" required>
              <option value="BD" {{ old('tipe_wo') == 'BD' ? 'selected' : '' }}>BD (Breakdown)</option>
              <option value="Plan" {{ old('tipe_wo') == 'Plan' ? 'selected' : '' }}>Plan</option>
            </select>
          </div>
          <div class="col-6">
            <label class="form-label required small fw-semibold text-muted mb-1">Downtime Code</label>
            <select name="downtime_code" class="form-select form-select-sm" required>
              <option value="Schedule" {{ old('downtime_code') == 'Schedule' ? 'selected' : '' }}>Schedule</option>
              <option value="Unschedule" {{ old('downtime_code','Unschedule') == 'Unschedule' ? 'selected' : '' }}>Unschedule</option>
              <option value="Accident" {{ old('downtime_code') == 'Accident' ? 'selected' : '' }}>Accident</option>
            </select>
          </div>
          <div class="col-12 mt-2">
            <label class="form-check form-switch m-0 pt-1">
              <input class="form-check-input" type="checkbox" name="opportunity" value="1" {{ old('opportunity') ? 'checked' : '' }}>
              <span class="form-check-label fw-bold text-dark small">Opportunity (Yes / No)</span>
            </label>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Card 2: Identitas Unit -->
  <div class="col-lg-3 mb-3">
    <div class="card h-100 shadow-sm border-0">
      <div class="card-status-top bg-info"></div>
      <div class="card-header p-2 bg-transparent">
        <h3 class="card-title d-flex align-items-center fs-4 fw-bold text-info">
          <span class="badge bg-info text-white me-2">02</span> Identitas Unit
        </h3>
      </div>
      <div class="card-body p-2">
        <div class="row g-2">
          <div class="col-md-4 mb-3">
            <label class="form-label required">Site Lokasi</label>
            <select id="site-select" class="form-select form-select-sm border-info" required>
              <option value="">-- Pilih Site --</option>
              @foreach($sites as $site)
                <option value="{{ $site->id }}" {{ old('site_id') == $site->id ? 'selected' : '' }}>{{ $site->name }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-6">
            <label class="form-label required small fw-semibold text-muted mb-1">Tipe Unit</label>
            <select id="type-select" class="form-select form-select-sm" required>
              <option value="">Pilih</option>
            </select>
          </div>
          <div class="col-6">
            <label class="form-label required small fw-semibold text-muted mb-1">Nomor Unit</label>
            <select id="unit-select" name="master_unit_id" class="form-select form-select-sm" required>
              <option value="">Pilih</option>
            </select>
          </div>
          <div class="col-6">
            <label class="form-label small fw-semibold text-muted mb-1">Model</label>
            <input type="text" id="model-display" class="form-control form-control-sm bg-light" readonly placeholder="-">
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Card 3: Waktu Breakdown -->
  <div class="col-lg-3 mb-3">
    <div class="card h-100 shadow-sm border-0">
      <div class="card-status-top bg-warning"></div>
      <div class="card-header p-2 bg-transparent">
        <h3 class="card-title d-flex align-items-center fs-4 fw-bold text-warning">
          <span class="badge bg-warning text-dark me-2">03</span> Waktu Breakdown
        </h3>
      </div>
      <div class="card-body p-2">
        <div class="row g-2">
          <div class="col-6">
            <label class="form-label small fw-semibold text-muted mb-1">Waktu BD</label>
            <input type="datetime-local" class="form-control form-control-sm" name="waktu_bd" id="waktu_bd" value="{{ old('waktu_bd') }}">
          </div>
          <div class="col-6">
            <label class="form-label small fw-semibold text-muted mb-1">Waktu RFU</label>
            <input type="datetime-local" class="form-control form-control-sm @error('waktu_rfu') is-invalid @enderror" name="waktu_rfu" id="waktu_rfu" value="{{ old('waktu_rfu') }}">
            @error('waktu_rfu')
              <div class="invalid-feedback small">{{ $message }}</div>
            @enderror
          </div>
          <div class="col-6">
            <label class="form-label small fw-semibold text-muted mb-1">Durasi (Hrs)</label>
            <input type="text" class="form-control form-control-sm bg-warning-lt fw-bold text-dark" id="durasi-display" readonly placeholder="(Auto)">
          </div>
          <div class="col-6">
            <label class="form-label small fw-semibold text-muted mb-1">Hours Meter</label>
            <input type="number" class="form-control form-control-sm" name="hours_meter" step="0.1" value="{{ old('hours_meter') }}" placeholder="0.0">
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Card 4: Klasifikasi -->
  <div class="col-lg-3 mb-3">
    <div class="card h-100 shadow-sm border-0">
      <div class="card-status-top bg-teal"></div>
      <div class="card-header p-2 bg-transparent">
        <h3 class="card-title d-flex align-items-center fs-4 fw-bold text-teal">
          <span class="badge bg-teal text-white me-2">04</span> Klasifikasi
        </h3>
      </div>
      <div class="card-body p-2">
        <div class="row g-2">
          @for($i = 1; $i <= 5; $i++)
          <div class="col-6">
            <label class="form-label small fw-semibold text-muted mb-1">Kategori {{ $i }}</label>
            <div class="input-group input-group-sm">
              <select name="wo_category_{{ $i }}_id" class="form-select" id="cat{{ $i }}-select">
                <option value="">Pilih</option>
                @foreach(${'categories'.$i} as $cat)
                  <option value="{{ $cat->id }}" {{ old('wo_category_'.$i.'_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
              </select>
              <button type="button" class="btn btn-outline-teal fw-bold px-2" onclick="inlineAddCat({{ $i }},'cat{{ $i }}-select')">+</button>
            </div>
          </div>
          @endfor
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Card 5: Tasks -->
<div class="card mb-3 shadow-sm border-0">
  <div class="card-status-top bg-indigo"></div>
  <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
    <h3 class="card-title text-indigo fw-bold m-0 d-flex align-items-center">
      <svg xmlns="http://www.w3.org/2000/svg" class="icon text-indigo me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 3v5m4 -2l-4 2l-4 -2" /><path d="M12 12v9" /><path d="M12 12l8 -4.5" /><path d="M12 12l-8 -4.5" /><path d="M12 16.5l8 -4.5" /><path d="M12 16.5l-8 -4.5" /></svg>
      Tasks & Problem List
    </h3>
    <button type="button" class="btn btn-indigo shadow-sm" onclick="addTask()">
      <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="12" y1="5" x2="12" y2="19" /><line x1="5" y1="12" x2="19" y2="12" /></svg>
      Tambah Task Baru
    </button>
  </div>
  <div class="card-body p-3" id="tasks-container">
    <!-- Tasks will be injected here by JS -->
  </div>
</div>

<div class="mb-5 text-end">
  <button type="submit" class="btn btn-success btn-lg shadow-sm px-5 fw-bold">
    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-check me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10" /></svg>
    Simpan Work Order
  </button>
</div>

</form>
      </div>
    </div>
  </div>
</div>

@endsection
@push('scripts')
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
<script>
// ========== Cascading Dropdowns ==========
const siteSelect = document.getElementById('site-select');
const typeSelect = document.getElementById('type-select');
const unitSelect = document.getElementById('unit-select');
const modelDisplay = document.getElementById('model-display');

siteSelect.addEventListener('change', function() {
    typeSelect.innerHTML = '<option value="">Memuat...</option>';
    unitSelect.innerHTML = '<option value="">-- Pilih Tipe Unit dahulu --</option>';
    modelDisplay.value = '';
    if (!this.value) return;
    fetch('/api/wo/unit-types?site_id=' + this.value)
        .then(r => r.json())
        .then(data => {
            typeSelect.innerHTML = '<option value="">-- Pilih Tipe Unit --</option>';
            data.forEach(t => {
                typeSelect.innerHTML += `<option value="${t.id}">${t.name}</option>`;
            });
        });
});

typeSelect.addEventListener('change', function() {
    unitSelect.innerHTML = '<option value="">Memuat...</option>';
    modelDisplay.value = '';
    if (!this.value) return;
    fetch('/api/wo/units?site_id=' + siteSelect.value + '&unit_type_id=' + this.value)
        .then(r => r.json())
        .then(data => {
            unitSelect.innerHTML = '<option value="">-- Pilih Nomor Unit --</option>';
            data.forEach(u => {
                unitSelect.innerHTML += `<option value="${u.id}">${u.nomor_unit}</option>`;
            });
        });
});

unitSelect.addEventListener('change', function() {
    modelDisplay.value = '';
    if (!this.value) return;
    fetch('/api/wo/unit-detail?unit_id=' + this.value)
        .then(r => r.json())
        .then(data => {
            modelDisplay.value = data.model_name || '-';
        });
});

// ========== Durasi Auto-Calc ==========
function calcDurasi() {
    const bd = document.getElementById('waktu_bd').value;
    const rfu = document.getElementById('waktu_rfu').value;
    if (!bd) { document.getElementById('durasi-display').value = ''; return; }
    const start = new Date(bd);
    const end = rfu ? new Date(rfu) : new Date();
    const hrs = ((end - start) / 3600000).toFixed(1);
    document.getElementById('durasi-display').value = hrs + ' jam';
}
document.getElementById('waktu_bd').addEventListener('change', calcDurasi);
document.getElementById('waktu_rfu').addEventListener('change', calcDurasi);
setInterval(function() {
    if (document.getElementById('waktu_bd').value && !document.getElementById('waktu_rfu').value) calcDurasi();
}, 60000);
calcDurasi();

// ========== Inline Add ==========
function inlineAdd(table, selectId) {
    let code = '';
    if (table === 'breakdown_types') {
        code = prompt('Masukkan kode (opsional):') || '';
    }
    const name = prompt('Masukkan nama baru:');
    if (!name) return;
    fetch('/api/wo/inline-add', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ table: table, name: name, code: code })
    }).then(r => r.json()).then(data => {
        const sel = document.getElementById(selectId);
        const text = data.code ? data.code + ' - ' + data.name : data.name;
        const opt = new Option(text, data.id, true, true);
        sel.appendChild(opt);
    }).catch(e => alert('Gagal menambahkan: ' + e));
}

function inlineAddCat(level, selectId) {
    const name = prompt('Masukkan nama Kategori ' + level + ' baru:');
    if (!name) return;
    fetch('/api/wo/inline-add', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ table: 'wo_categories', name: name, level: level })
    }).then(r => r.json()).then(data => {
        const sel = document.getElementById(selectId);
        const opt = new Option(data.name, data.id, true, true);
        sel.appendChild(opt);
    }).catch(e => alert('Gagal menambahkan: ' + e));
}

// Also for component_groups used inside tasks
function inlineAddForSelect(table, selectEl) {
    const name = prompt('Masukkan nama baru:');
    if (!name) return;
    fetch('/api/wo/inline-add', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ table: table, name: name })
    }).then(r => r.json()).then(data => {
        const opt = new Option(data.name, data.id, true, true);
        selectEl.appendChild(opt);
    }).catch(e => alert('Gagal menambahkan: ' + e));
}

function inlineAddPart(selectEl) {
    const pn = prompt('Masukkan Part Number baru:');
    if (!pn) return;
    const desc = prompt('Masukkan Part Description:');
    if (!desc) return;
    fetch('/api/wo/inline-add', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ table: 'parts', name: pn, part_number: pn, part_description: desc })
    }).then(r => r.json()).then(data => {
        const opt = new Option(data.part_number + ' - ' + data.part_description, data.id, true, true);
        selectEl.appendChild(opt);
    }).catch(e => alert('Gagal menambahkan: ' + e));
}

// ========== Dynamic Task/Subtask Builder ==========
let taskIndex = 0;

// Component groups options (for reuse in tasks)
const cgOptions = `<option value="">-- Pilih --</option>@foreach($componentGroups as $cg)<option value="{{ $cg->id }}">{{ $cg->name }}</option>@endforeach`;
const mechanicOptions = `@foreach($mechanics as $m)<option value="{{ $m->id }}">{{ $m->nama_lengkap }}</option>@endforeach`;
const partOptions = `<option value="">-- Pilih Part --</option>@foreach($parts as $p)<option value="{{ $p->id }}">{{ $p->part_number }} - {{ $p->part_description }}</option>@endforeach`;
const unitOptions = `<option value="">-- Pilih Unit --</option>@foreach($units as $u)<option value="{{ $u->id }}">{{ $u->nomor_unit }} - {{ $u->model->name ?? '' }}</option>@endforeach`;
const toolTxOptions = `<option value="">-- Pilih Transaksi --</option>@foreach($toolTransactions as $tx)<option value="{{ $tx->id }}">{{ $tx->tool->name ?? '' }} → {{ $tx->mechanic->nama_lengkap ?? '' }} ({{ $tx->tanggal_pinjam }})</option>@endforeach`;
const statusOptions = `<option value="Open">Open</option><option value="Inprogress">Inprogress</option><option value="Completed">Completed</option><option value="Cancel">Cancel</option><option value="Backlog">Backlog</option>`;

function addTask() {
    const ti = taskIndex++;
    const container = document.getElementById('tasks-container');
    const div = document.createElement('div');
    div.className = 'border-start border-4 border-indigo rounded-3 p-2 mb-2 bg-blue-lt shadow-sm position-relative';
    div.id = 'task-' + ti;
    div.innerHTML = `
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0 text-indigo fw-bold d-flex align-items-center">
                <span class="badge bg-indigo text-white me-2">Task #${ti+1}</span>
                Problem / Gejala Kerusakan
            </h4>
            <button type="button" class="btn btn-sm btn-outline-danger rounded-pill" onclick="document.getElementById('task-${ti}').remove()">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-trash me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="4" y1="7" x2="20" y2="7" /><line x1="10" y1="11" x2="10" y2="17" /><line x1="14" y1="11" x2="14" y2="17" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
                Hapus Task
            </button>
        </div>
        <div class="row g-3">
            <!-- KIRI: Informasi Task / Problem -->
            <div class="col-lg-5">
                <div class="h-100 p-2 rounded bg-white border">
                    <label class="form-label required small fw-semibold text-muted">Deskripsi Problem</label>
                    <textarea class="form-control form-control-sm bg-white" name="tasks[${ti}][problem]" rows="2" placeholder="Uraikan problem/gejala..." required></textarea>
                    <div class="row g-2 mt-1">
                        <div class="col-12">
                            <label class="form-label small fw-semibold text-muted">Component Group</label>
                            <div class="input-group input-group-sm">
                                <select name="tasks[${ti}][component_group_id]" class="form-select bg-white" id="task-cg-${ti}">${cgOptions}</select>
                                <button type="button" class="btn btn-outline-indigo" onclick="inlineAddForSelect('component_groups', document.getElementById('task-cg-${ti}'))">+</button>
                            </div>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-semibold text-muted">Date Problem</label>
                            <input type="datetime-local" class="form-control form-control-sm bg-white" name="tasks[${ti}][date_problem]">
                            <div class="invalid-feedback d-block mt-1" data-field="date_problem"></div>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-semibold text-muted">Status Task</label>
                            <select name="tasks[${ti}][status]" class="form-select form-select-sm bg-white">${statusOptions}</select>
                        </div>
                    </div>
                </div>
            </div>
            <!-- KANAN: Subtasks -->
            <div class="col-lg-7">
                <div class="h-100 p-2 rounded bg-white border border-success">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <strong class="text-indigo small text-uppercase">SubTasks (Tindakan / Action)</strong>
                        <button type="button" class="btn btn-sm btn-outline-success bg-white shadow-xs" onclick="addSubtask(${ti})">
                            + Tambah SubTask
                        </button>
                    </div>
                    <div id="subtasks-container-${ti}"></div>
                </div>
            </div>
        </div>
    `;
    container.appendChild(div);
    bindTaskDateProblemField(div);
}

function bindTaskDateProblemField(taskEl) {
    const taskDateInput = taskEl.querySelector('input[name$="[date_problem]"]');
    const taskFeedback = taskEl.querySelector('[data-field="date_problem"]');
    const showTaskError = () => {
        const taskDate = taskDateInput && taskDateInput.value ? new Date(taskDateInput.value) : null;
        const workOrderDateInput = document.getElementById('waktu_bd');
        const workOrderDate = workOrderDateInput && workOrderDateInput.value ? new Date(workOrderDateInput.value) : null;
        if (taskFeedback) {
            taskFeedback.textContent = '';
            if (workOrderDate && taskDate && taskDate < workOrderDate) {
                taskFeedback.textContent = 'Date Problem tidak boleh kurang dari Waktu BD.';
            }
        }
        taskEl.querySelectorAll('.subtask-item').forEach(function(subtaskEl) {
            const feedback = subtaskEl.querySelector('[data-field="date_action"]');
            if (feedback) {
                feedback.textContent = '';
                const actionDate = subtaskEl.querySelector('input[name$="[date_action]"]')?.value;
                const actionDateValue = actionDate ? new Date(actionDate) : null;
                if (taskDate && actionDateValue && actionDateValue < taskDate) {
                    feedback.textContent = 'Date Action tidak boleh kurang dari Date Problem.';
                }
            }
        });
    };

    [taskDateInput, document.getElementById('waktu_bd')].forEach(input => input && input.addEventListener('change', showTaskError));
    [taskDateInput, document.getElementById('waktu_bd')].forEach(input => input && input.addEventListener('input', showTaskError));
    showTaskError();
}

let subtaskCounters = {};
function bindSubtaskDateFields(subtaskEl) {
    const dateAction = subtaskEl.querySelector('input[name$="[date_action]"]');
    const dateFinish = subtaskEl.querySelector('input[name$="[date_finish]"]');
    const duration = subtaskEl.querySelector('input[name$="[duration_hours]"]');
    const syncDuration = () => {
        const start = dateAction && dateAction.value ? new Date(dateAction.value) : null;
        const end = dateFinish && dateFinish.value ? new Date(dateFinish.value) : null;
        let value = '';
        if (start) {
            const reference = end || new Date();
            value = (((reference - start) / 3600000)).toFixed(2);
        }
        if (duration) duration.value = value;
    };
    const showDateError = () => {
        const taskDateInput = subtaskEl.closest('[id^="task-"]')?.querySelector('input[name$="[date_problem]"]');
        const taskDate = taskDateInput && taskDateInput.value ? new Date(taskDateInput.value) : null;
        const actionDate = dateAction && dateAction.value ? new Date(dateAction.value) : null;
        const feedback = subtaskEl.querySelector('[data-field="date_action"]');
        if (feedback) {
            feedback.textContent = '';
            if (taskDate && actionDate && actionDate < taskDate) {
                feedback.textContent = 'Date Action tidak boleh kurang dari Date Problem.';
            }
        }
    };
    [dateAction, dateFinish].forEach(input => input && input.addEventListener('change', syncDuration));
    [dateAction, dateFinish].forEach(input => input && input.addEventListener('input', syncDuration));
    [dateAction].forEach(input => input && input.addEventListener('change', showDateError));
    [dateAction].forEach(input => input && input.addEventListener('input', showDateError));
    syncDuration();
    showDateError();
}

function addSubtask(taskIdx) {
    if (!subtaskCounters[taskIdx]) subtaskCounters[taskIdx] = 0;
    const si = subtaskCounters[taskIdx]++;
    const container = document.getElementById('subtasks-container-' + taskIdx);
    const div = document.createElement('div');
    div.className = 'border-start border-3 border-success rounded-3 p-2 mb-1 bg-white shadow-xs subtask-item';
    div.id = `subtask-${taskIdx}-${si}`;
    div.innerHTML = `
        <div class="d-flex justify-content-between align-items-center mb-2">
            <span class="badge bg-success-lt text-success fw-bold">SubTask #${si+1}</span>
            <button type="button" class="btn btn-sm btn-outline-danger border-0 p-1" onclick="document.getElementById('subtask-${taskIdx}-${si}').remove()">✕ Hapus SubTask</button>
        </div>
        <div class="row g-1 mb-1 align-items-end" style="display:flex; flex-wrap:wrap; gap:0.35rem;">
            <div style="flex:1 1 220px; min-width:220px;">
                <label class="form-label required small fw-semibold text-muted mb-1">Action / Tindakan</label>
                <textarea class="form-control form-control-sm py-1" name="tasks[${taskIdx}][subtasks][${si}][action]" rows="1" placeholder="Uraian perbaikan..." required></textarea>
            </div>
            <div style="flex:1 1 140px; min-width:140px;">
                <label class="form-label small fw-semibold text-muted mb-1">Date Action</label>
                <input type="datetime-local" class="form-control form-control-sm py-1" name="tasks[${taskIdx}][subtasks][${si}][date_action]">
                <div class="invalid-feedback d-block mt-1" data-field="date_action"></div>
            </div>
            <div style="flex:1 1 140px; min-width:140px;">
                <label class="form-label small fw-semibold text-muted mb-1">Date Finish</label>
                <input type="datetime-local" class="form-control form-control-sm py-1" name="tasks[${taskIdx}][subtasks][${si}][date_finish]">
            </div>
            <div style="flex:0 0 90px; min-width:90px;">
                <label class="form-label small fw-semibold text-muted mb-1">Durasi</label>
                <input type="number" step="0.01" class="form-control form-control-sm py-1" name="tasks[${taskIdx}][subtasks][${si}][duration_hours]" readonly>
            </div>
        </div>
        <div class="row g-1 mb-1 align-items-end" style="display:flex; flex-wrap:wrap; gap:0.35rem;">
            <div style="flex:1 1 220px; min-width:220px;">
                <label class="form-label small fw-semibold text-muted mb-1">Tipe Breakdown</label>
                <select name="tasks[${taskIdx}][subtasks][${si}][breakdown_type_id]" class="form-select form-select-sm py-1">
                    <option value="">-- Pilih --</option>
                    @foreach($breakdownTypes as $bt)
                        <option value="{{ $bt->id }}">{{ $bt->code ? $bt->code . ' - ' : '' }}{{ $bt->name }}</option>
                    @endforeach
                </select>
            </div>
            <div style="flex:1 1 220px; min-width:220px;">
                <label class="form-label small fw-semibold text-muted mb-1">Status</label>
                <select name="tasks[${taskIdx}][subtasks][${si}][status]" class="form-select form-select-sm py-1">${statusOptions}</select>
            </div>
        </div>
        <div class="row g-3 mt-1">
            <!-- Manpower -->
            <div class="col-12">
                <div class="p-2 rounded bg-light border">
                    <label class="form-label small fw-bold text-azure mb-1 d-flex justify-content-between">
                        <span>👷 Manpower (Mekanik)</span>
                        <button type="button" class="btn btn-xs btn-azure py-0 px-1" onclick="addManpower(${taskIdx},${si})">+</button>
                    </label>
                    <div id="manpower-${taskIdx}-${si}"></div>
                </div>
            </div>
            <!-- Parts -->
            <div class="col-12">
                <div class="p-2 rounded bg-light border">
                    <label class="form-label small fw-bold text-orange mb-1 d-flex justify-content-between">
                        <span>⚙️ Spareparts</span>
                        <button type="button" class="btn btn-xs btn-orange py-0 px-1" onclick="addPartRow(${taskIdx},${si})">+</button>
                    </label>
                    <div id="parts-${taskIdx}-${si}"></div>
                </div>
            </div>
            <!-- Tools -->
            <div class="col-12">
                <div class="p-2 rounded bg-light border">
                    <label class="form-label small fw-bold text-purple mb-1 d-flex justify-content-between">
                        <span>🔧 Tools (Peminjaman)</span>
                        <button type="button" class="btn btn-xs btn-purple py-0 px-1" onclick="addToolRow(${taskIdx},${si})">+</button>
                    </label>
                    <div id="tools-${taskIdx}-${si}"></div>
                </div>
            </div>
        </div>
    `;
    container.appendChild(div);
    bindSubtaskDateFields(div);
}

let mpCounters = {};
function addManpower(taskIdx, subtaskIdx) {
    const key = taskIdx + '-' + subtaskIdx;
    if (!mpCounters[key]) mpCounters[key] = 0;
    const mi = mpCounters[key]++;
    const selectId = `mp-sel-${key}-${mi}`;
    const container = document.getElementById('manpower-' + key);
    const row = document.createElement('div');
    row.className = 'd-flex gap-1 mb-1 align-items-center';
    row.innerHTML = `
        <select name="tasks[${taskIdx}][subtasks][${subtaskIdx}][manpower_ids][]" class="form-select form-select-sm" id="${selectId}">
            <option value="">-- Pilih Mekanik --</option>${mechanicOptions}
        </select>
        <button type="button" class="btn btn-sm btn-outline-danger p-1" onclick="this.parentElement.remove()">✕</button>
    `;
    container.appendChild(row);
    if (typeof TomSelect !== 'undefined') new TomSelect(`#${selectId}`, { create: false, placeholder: '-- Pilih Mekanik --' });
}

let partCounters = {};
function addPartRow(taskIdx, subtaskIdx, data) {
    const key = taskIdx+'-'+subtaskIdx;
    if (!partCounters[key]) partCounters[key] = 0;
    const pi = partCounters[key]++;
    const container = document.getElementById('parts-'+key);
    const row = document.createElement('div'); row.className = 'border rounded p-2 mb-2 bg-light';
    
    // Default values
    const status = data?.part_status || 'Replace';
    
    row.innerHTML = `
        <div class="d-flex gap-2 mb-2 align-items-end">
            <div class="input-group input-group-sm" style="flex:3">
                <select name="tasks[${taskIdx}][subtasks][${subtaskIdx}][parts][${pi}][part_id]" class="form-select form-select-sm" id="part-sel-${key}-${pi}" required>
                    ${partOptions}
                </select>
                <button type="button" class="btn btn-outline-primary btn-sm px-1" onclick="inlineAddPart(document.getElementById('part-sel-${key}-${pi}'))">+</button>
            </div>
            <div style="flex:1">
                <label class="form-label small text-muted mb-0">Status</label>
                <select name="tasks[${taskIdx}][subtasks][${subtaskIdx}][parts][${pi}][part_status]" class="form-select form-select-sm" id="part-status-${key}-${pi}" onchange="togglePartStatusFields('${key}-${pi}')">
                    <option value="Replace">Replace</option>
                    <option value="Repair">Repair</option>
                    <option value="Order Part">Order Part</option>
                    <option value="Swap / Canibal">Swap / Canibal</option>
                </select>
            </div>
            <div style="flex:1">
                <label class="form-label small text-muted mb-0">Qty</label>
                <input type="number" name="tasks[${taskIdx}][subtasks][${subtaskIdx}][parts][${pi}][qty]" class="form-control form-control-sm" min="1" value="${data?.qty||1}">
            </div>
            <div style="flex:1">
                <label class="form-label small text-muted mb-0">Satuan</label>
                <input type="text" name="tasks[${taskIdx}][subtasks][${subtaskIdx}][parts][${pi}][satuan]" class="form-control form-control-sm" value="${data?.satuan||''}">
            </div>
            <div>
                <button type="button" class="btn btn-sm btn-outline-danger p-1" onclick="this.parentElement.parentElement.parentElement.remove()">✕</button>
            </div>
        </div>
        
        <!-- Order Part Fields -->
        <div class="row g-2 mb-1 d-none" id="order-fields-${key}-${pi}">
            <div class="col-6">
                <input type="text" class="form-control form-control-sm" name="tasks[${taskIdx}][subtasks][${subtaskIdx}][parts][${pi}][mol_pr_order]" placeholder="MOL/PR" value="${status === 'Order Part' ? (data?.mol_pr||'') : ''}">
            </div>
            <div class="col-6">
                <select class="form-select form-select-sm" name="tasks[${taskIdx}][subtasks][${subtaskIdx}][parts][${pi}][order_status]">
                    <option value="">-- Status Orderan --</option>
                    <option value="Waiting Approve" ${data?.order_status === 'Waiting Approve' ? 'selected' : ''}>Waiting Approve</option>
                    <option value="Waiting PO" ${data?.order_status === 'Waiting PO' ? 'selected' : ''}>Waiting PO</option>
                    <option value="On The Way" ${data?.order_status === 'On The Way' ? 'selected' : ''}>On The Way</option>
                </select>
            </div>
        </div>

        <!-- Swap Component Fields -->
        <div class="row g-2 mb-1 d-none" id="swap-fields-${key}-${pi}">
            <div class="col-3">
                <select class="form-select form-select-sm" name="tasks[${taskIdx}][subtasks][${subtaskIdx}][parts][${pi}][swap_type]">
                    <option value="">-- Tipe Swap --</option>
                    <option value="Swap To" ${data?.swap_type === 'Swap To' ? 'selected' : ''}>Swap To</option>
                    <option value="Swap From" ${data?.swap_type === 'Swap From' ? 'selected' : ''}>Swap From</option>
                </select>
            </div>
            <div class="col-3">
                <select class="form-select form-select-sm" name="tasks[${taskIdx}][subtasks][${subtaskIdx}][parts][${pi}][swap_unit_id]">
                    ${unitOptions}
                </select>
            </div>
            <div class="col-2">
                <input type="text" class="form-control form-control-sm" name="tasks[${taskIdx}][subtasks][${subtaskIdx}][parts][${pi}][mol_pr_swap]" placeholder="MOL/PR" value="${status === 'Swap / Canibal' ? (data?.mol_pr||'') : ''}">
            </div>
            <div class="col-2">
                <select class="form-select form-select-sm" name="tasks[${taskIdx}][subtasks][${subtaskIdx}][parts][${pi}][swap_status]">
                    <option value="">-- Status --</option>
                    <option value="Waiting Part" ${data?.swap_status === 'Waiting Part' ? 'selected' : ''}>Waiting Part</option>
                    <option value="Completed" ${data?.swap_status === 'Completed' ? 'selected' : ''}>Completed</option>
                    <option value="Cancel" ${data?.swap_status === 'Cancel' ? 'selected' : ''}>Cancel</option>
                </select>
            </div>
            <div class="col-2">
                <input type="text" class="form-control form-control-sm" name="tasks[${taskIdx}][subtasks][${subtaskIdx}][parts][${pi}][swap_remarks]" placeholder="Remarks" value="${data?.swap_remarks||''}">
            </div>
        </div>
    `;
    container.appendChild(row);
    if (data?.part_id) row.querySelector(`#part-sel-${key}-${pi}`).value = data.part_id;
    if (data?.part_status) row.querySelector(`#part-status-${key}-${pi}`).value = data.part_status;
    if (data?.swap_unit_id) row.querySelector(`[name="tasks[${taskIdx}][subtasks][${subtaskIdx}][parts][${pi}][swap_unit_id]"]`).value = data.swap_unit_id;
    
    togglePartStatusFields(`${key}-${pi}`);
}

function togglePartStatusFields(id) {
    const status = document.getElementById(`part-status-${id}`).value;
    const orderFields = document.getElementById(`order-fields-${id}`);
    const swapFields = document.getElementById(`swap-fields-${id}`);
    
    orderFields.classList.add('d-none');
    swapFields.classList.add('d-none');
    
    if (status === 'Order Part') {
        orderFields.classList.remove('d-none');
    } else if (status === 'Swap / Canibal') {
        swapFields.classList.remove('d-none');
    }
}

let toolCounters = {};
function addToolRow(taskIdx, subtaskIdx) {
    const key = taskIdx + '-' + subtaskIdx;
    if (!toolCounters[key]) toolCounters[key] = 0;
    const ti = toolCounters[key]++;
    const selectId = `tool-sel-${key}-${ti}`;
    const container = document.getElementById('tools-' + key);
    const row = document.createElement('div');
    row.className = 'd-flex gap-1 mb-1 align-items-center';
    row.innerHTML = `
        <select name="tasks[${taskIdx}][subtasks][${subtaskIdx}][tool_transaction_ids][]" class="form-select form-select-sm" id="${selectId}">${toolTxOptions}</select>
        <button type="button" class="btn btn-sm btn-outline-danger p-1" onclick="this.parentElement.remove()">✕</button>
    `;
    container.appendChild(row);
    if (typeof TomSelect !== 'undefined') new TomSelect(`#${selectId}`, { create: false, placeholder: '-- Pilih Tools --' });
}
</script>

@if($errors->any() && !session('error_popup'))
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var createModal = new bootstrap.Modal(document.getElementById('modal-tambah-wo'));
        createModal.show();
    });
</script>
@endif

@endpush