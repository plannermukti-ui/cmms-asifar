@extends('layouts.tabler')
@section('title', 'Kanban Board Work Order - CMMS')

@section('content')
<style>
  .kanban-board {
    display: flex;
    gap: 12px;
    overflow-x: auto;
    padding-bottom: 12px;
    align-items: flex-start;
    height: calc(100vh - 220px);
  }

  .kanban-col-wrapper {
    flex: 1;
    min-width: 280px;
    max-width: 320px;
    display: flex;
    flex-direction: column;
    height: 100%;
  }

  .kanban-column {
    flex: 1;
    min-height: 200px;
    overflow-y: auto;
    padding: 8px;
    background-color: #f8fafc;
    border-radius: 8px;
    border: 1px dashed #cbd5e1;
    transition: background-color 0.2s ease;
  }

  .kanban-column.sortable-ghost-target {
    background-color: #e2e8f0;
  }

  .kanban-card {
    cursor: grab;
    transition: transform 0.15s ease, box-shadow 0.15s ease;
    user-select: none;
  }

  .kanban-card:active {
    cursor: grabbing;
  }

  .kanban-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.08) !important;
  }

  .sortable-ghost {
    opacity: 0.4;
    background-color: #cbd5e1 !important;
  }
</style>

<div class="page-header d-print-none">
  <div class="row align-items-center">
    <div class="col">
      <h2 class="page-title text-primary fw-bold">
        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-layout-kanban me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 4m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z" /><path d="M10 4m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z" /><path d="M16 4m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v9a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z" /></svg>
        Kanban Board Work Order
      </h2>
    </div>
    <div class="col-auto ms-auto d-print-none d-flex gap-2">
      <a href="{{ route('work-orders.index') }}" class="btn btn-outline-secondary btn-sm">
        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 6l6 6l-6 6" /></svg>
        Tampilan Table
      </a>
      @can('create_work_orders')
      <button type="button" class="btn btn-primary btn-sm shadow-sm" data-bs-toggle="modal" data-bs-target="#modal-tambah-wo">
        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="12" y1="5" x2="12" y2="19" /><line x1="5" y1="12" x2="19" y2="12" /></svg>
        Buat Work Order
      </button>
      @endcan
    </div>
  </div>
</div>

<!-- Filter Bar -->
<div class="card mt-3 mb-3 border-0 shadow-xs">
  <div class="card-body p-2">
    <form method="GET" class="row g-2 align-items-center">
      <div class="col-md-3">
        <input type="text" class="form-control form-control-sm" name="search" value="{{ request('search') }}" placeholder="Cari No WO / Nomor Unit...">
      </div>
      <div class="col-md-2">
        <select class="form-select form-select-sm" name="tipe_wo">
          <option value="">Semua Tipe WO</option>
          <option value="BD" {{ request('tipe_wo') == 'BD' ? 'selected' : '' }}>BD (Breakdown)</option>
          <option value="Plan" {{ request('tipe_wo') == 'Plan' ? 'selected' : '' }}>Plan</option>
        </select>
      </div>
      <div class="col-md-2">
        <select class="form-select form-select-sm" name="site_id">
          <option value="">Semua Site</option>
          @foreach($sites as $s)
            <option value="{{ $s->id }}" {{ request('site_id') == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-3">
        <select class="form-select form-select-sm" name="history_range">
          <option value="7" {{ request('history_range', '14') == '7' ? 'selected' : '' }}>History: 7 Hari Terakhir</option>
          <option value="14" {{ request('history_range', '14') == '14' ? 'selected' : '' }}>History: 14 Hari Terakhir (Default)</option>
          <option value="30" {{ request('history_range', '14') == '30' ? 'selected' : '' }}>History: 30 Hari Terakhir</option>
          <option value="all" {{ request('history_range') == 'all' ? 'selected' : '' }}>History: Tampilkan Semua</option>
        </select>
      </div>
      <div class="col-md-2">
        <button type="submit" class="btn btn-secondary btn-sm w-100">Filter Board</button>
      </div>
    </form>
  </div>
</div>

<!-- KANBAN BOARD CONTAINER -->
<div class="kanban-board">
  @php
    $statusConfigs = [
      'Open' => ['title' => 'Open', 'color' => 'primary', 'border' => 'border-primary', 'bg' => 'bg-primary-lt'],
      'Inprogress' => ['title' => 'In Progress', 'color' => 'warning', 'border' => 'border-warning', 'bg' => 'bg-warning-lt'],
      'Completed' => ['title' => 'Completed', 'color' => 'success', 'border' => 'border-success', 'bg' => 'bg-success-lt'],
      'Cancel' => ['title' => 'Cancel', 'color' => 'danger', 'border' => 'border-danger', 'bg' => 'bg-danger-lt'],
      'Backlog' => ['title' => 'Backlog', 'color' => 'purple', 'border' => 'border-purple', 'bg' => 'bg-purple-lt'],
    ];
  @endphp

  @foreach($statuses as $st)
  @php
    $cfg = $statusConfigs[$st];
    $items = $groupedWorkOrders[$st] ?? collect();
  @endphp
  <div class="kanban-col-wrapper">
    <!-- Header Column -->
    <div class="card mb-2 border-0 shadow-xs border-top border-4 {{ $cfg['border'] }}">
      <div class="card-header p-2 bg-transparent d-flex justify-content-between align-items-center">
        <h4 class="card-title fs-4 fw-bold m-0 text-capitalize d-flex align-items-center">
          <span class="status-dot bg-{{ $cfg['color'] }} me-2"></span>
          {{ $cfg['title'] }}
        </h4>
        <span class="badge bg-{{ $cfg['color'] }} text-white rounded-pill px-2 py-1 count-badge-{{ $st }}">{{ $items->count() }}</span>
      </div>
    </div>

    <!-- Drop Zone Column -->
    <div class="kanban-column" data-status="{{ $st }}" id="col-{{ $st }}">
      @foreach($items as $wo)
      <div class="card mb-2 shadow-xs border-0 kanban-card" data-wo-id="{{ $wo->id }}" data-no-wo="{{ $wo->no_wo }}" data-waktu-rfu="{{ $wo->waktu_rfu ? $wo->waktu_rfu->format('Y-m-d\TH:i') : '' }}" data-edit-url="{{ route('work-orders.edit', $wo) }}">
        <div class="card-status-top bg-{{ $cfg['color'] }}"></div>
        <div class="card-body p-2.5">
          
          <!-- Header: No Unit (ME071) & Badges -->
          <div class="d-flex justify-content-between align-items-center mb-1">
            <strong class="fs-2 fw-bold text-primary mb-0" style="line-height: 1;">{{ $wo->unit->nomor_unit ?? '-' }}</strong>
            <div class="d-flex gap-1 align-items-center">
              @if($wo->opportunity)
                <span class="badge bg-success-lt text-success px-1.5 py-0.5 small" title="Opportunity WO">OPP</span>
              @endif
              <span class="badge {{ $wo->tipe_wo == 'BD' ? 'bg-danger-lt text-danger' : 'bg-info-lt text-info' }} px-1.5 py-0.5 small">
                {{ $wo->tipe_wo }}
              </span>
              <span class="badge bg-light text-dark border">{{ $wo->unit->site->code ?? '-' }}</span>
            </div>
          </div>

          <!-- Subheader: No WO & Unit Type & HM -->
          <div class="d-flex justify-content-between align-items-center mb-2 small">
            <div>
              <a href="{{ route('work-orders.show', $wo) }}" class="fw-semibold text-secondary text-decoration-none">
                {{ $wo->no_wo }}
              </a>
              <span class="text-muted ms-1" style="font-size: 0.75rem;">({{ $wo->unit->type->name ?? '-' }})</span>
            </div>
            <div class="text-muted" style="font-size: 0.7rem;">HM: {{ $wo->hours_meter ?? '-' }}</div>
          </div>

          <!-- Problem & BD Info Box -->
          <div class="p-1.5 rounded bg-light border mb-2 small" style="font-size: 0.75rem;">
            <div class="d-flex justify-content-between">
              <span>BD: {{ $wo->waktu_bd ? $wo->waktu_bd->format('d/m H:i') : '-' }}</span>
              <strong class="text-danger">{{ $wo->durasi_hrs ? $wo->durasi_hrs . ' Hrs' : '-' }}</strong>
            </div>
            @php
              $firstTaskProblem = $wo->tasks->first()->problem ?? '-';
            @endphp
            <div class="text-dark fw-medium text-truncate mt-1" title="{{ $firstTaskProblem }}">
              {{ $firstTaskProblem }}
            </div>
          </div>

          <div class="d-flex justify-content-between align-items-center pt-1 border-top" style="font-size: 0.7rem;">
            <span class="text-muted">By: {{ $wo->creator->name ?? 'System' }}</span>
            <div class="btn-group">
              <a href="{{ route('work-orders.show', $wo) }}" class="btn btn-xs btn-outline-info py-0 px-1" title="Detail">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-eye m-0" width="14" height="14" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" /></svg>
              </a>
              @can('edit_work_orders')
              <a href="{{ route('work-orders.edit', $wo) }}" class="btn btn-xs btn-outline-primary py-0 px-1" title="Edit">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-pencil m-0" width="14" height="14" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 20h4l10.5 -10.5a1.5 1.5 0 0 0 -4 -4l-10.5 10.5v4" /><path d="M13.5 6.5l4 4" /></svg>
              </a>
              @endcan
            </div>
          </div>

        </div>
      </div>
      @endforeach
    </div>

  </div>
  @endforeach
</div>

@push('scripts')
<!-- SortableJS CDN -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const columns = document.querySelectorAll('.kanban-column');
    
    function sendStatusUpdate(woId, noWo, newStatus, oldStatus, itemEl, waktuRfuValue = null) {
        fetch('/api/wo/update-status', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                id: woId,
                status_wo: newStatus,
                waktu_rfu: waktuRfuValue
            })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                if (waktuRfuValue) {
                    itemEl.dataset.waktuRfu = waktuRfuValue;
                }

                // Update badge counts
                const fromBadge = document.querySelector('.count-badge-' + oldStatus);
                const toBadge = document.querySelector('.count-badge-' + newStatus);
                if (fromBadge) fromBadge.textContent = Math.max(0, parseInt(fromBadge.textContent) - 1);
                if (toBadge) toBadge.textContent = parseInt(toBadge.textContent) + 1;

                if (typeof Swal !== 'undefined') {
                    if (data.is_completed) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Work Order Completed!',
                            html: `WO <b>${noWo}</b> beserta <b>seluruh Task & SubTask</b> telah diset menjadi <b>Completed</b>.<br><br>Apakah Anda ingin memeriksa atau mengedit rincian akhir Work Order ini?`,
                            showCancelButton: true,
                            confirmButtonText: '⚡ Buka Form Edit WO',
                            cancelButtonText: 'Tutup',
                            confirmButtonColor: '#206bc4'
                        }).then((res) => {
                            if (res.isConfirmed) {
                                window.location.href = data.edit_url;
                            }
                        });
                    } else {
                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 2500,
                            timerProgressBar: true
                        });
                        Toast.fire({
                            icon: 'success',
                            title: `WO ${noWo} dipindah ke ${newStatus}`
                        });
                    }
                }
            } else {
                if (data.requires_rfu) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Waktu RFU Wajib Diisi!',
                        text: data.message,
                        showCancelButton: true,
                        confirmButtonText: 'Edit WO untuk Isi Waktu RFU',
                        cancelButtonText: 'Batal'
                    }).then((res) => {
                        evtRevert(evt);
                        if (res.isConfirmed) {
                            window.location.href = data.edit_url;
                        }
                    });
                } else {
                    alert('Gagal: ' + data.message);
                    evtRevert(evt);
                }
            }
        })
        .catch(e => {
            alert('Terjadi kesalahan: ' + e);
            evtRevert(evt);
        });
    }

    function evtRevert(evt) {
        if (evt && evt.from && evt.item) {
            evt.from.appendChild(evt.item);
        }
    }

    columns.forEach(col => {
        new Sortable(col, {
            group: 'kanban-wo',
            animation: 200,
            ghostClass: 'sortable-ghost',
            dragClass: 'shadow-lg',
            onEnd: function (evt) {
                const itemEl = evt.item;
                const woId = itemEl.dataset.woId;
                const noWo = itemEl.dataset.noWo;
                const currentRfu = itemEl.dataset.waktuRfu;
                const editUrl = itemEl.dataset.editUrl;
                const newStatus = evt.to.dataset.status;
                const oldStatus = evt.from.dataset.status;

                if (newStatus === oldStatus) return;

                // Mandatory Waktu RFU check when moving to Completed
                if (newStatus === 'Completed' && !currentRfu) {
                    const nowDefault = new Date().toISOString().slice(0, 16);
                    Swal.fire({
                        title: `Input Waktu RFU - ${noWo}`,
                        html: `
                            <div class="text-start mb-2 small text-muted">
                                Status <b>Completed</b> mengharuskan <b>Waktu RFU</b> diisi secara manual. Silakan masukkan Waktu RFU di bawah ini:
                            </div>
                            <input type="datetime-local" id="swal-input-rfu" class="form-control" value="${nowDefault}">
                        `,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Simpan & Set Completed',
                        cancelButtonText: 'Batal',
                        confirmButtonColor: '#206bc4',
                        preConfirm: () => {
                            const val = document.getElementById('swal-input-rfu').value;
                            if (!val) {
                                Swal.showValidationMessage('Waktu RFU tidak boleh kosong!');
                            }
                            return val;
                        }
                    }).then((result) => {
                        if (result.isConfirmed && result.value) {
                            sendStatusUpdate(woId, noWo, newStatus, oldStatus, itemEl, result.value);
                        } else {
                            evtRevert(evt);
                        }
                    });
                } else {
                    sendStatusUpdate(woId, noWo, newStatus, oldStatus, itemEl, currentRfu);
                }
            }
        });
    });
});
</script>
@endpush


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
            <input type="datetime-local" class="form-control form-control-sm" name="waktu_rfu" id="waktu_rfu" value="{{ old('waktu_rfu') }}">
          </div>
          <div class="col-6">
            <label class="form-label small fw-semibold text-muted mb-1">Durasi (Hrs)</label>
            <input type="text" class="form-control form-control-sm bg-warning-lt fw-bold text-dark" id="durasi-display" readonly placeholder="(Auto)">
          </div>
          <div class="col-6">
            <label class="form-label small fw-semibold text-muted mb-1">Hours Meter</label>
            <input type="number" class="form-control form-control-sm" name="hours_meter" step="0.1" value="{{ old('hours_meter') }}" placeholder="0.0">
          </div>
          <div class="col-12">
            <label class="form-label small fw-semibold text-muted mb-1">Tipe Breakdown</label>
            <div class="input-group input-group-sm">
              <select name="breakdown_type_id" class="form-select" id="breakdown-type-select">
                <option value="">Pilih</option>
                @foreach($breakdownTypes as $bt)
                  <option value="{{ $bt->id }}" {{ old('breakdown_type_id') == $bt->id ? 'selected' : '' }}>{{ $bt->code ? $bt->code . ' - ' : '' }}{{ $bt->name }}</option>
                @endforeach
              </select>
              <button type="button" class="btn btn-outline-warning text-dark fw-bold" onclick="inlineAdd('breakdown_types','breakdown-type-select')">+</button>
            </div>
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
          <div class="col-6">
            <label class="form-label small fw-semibold text-muted mb-1">Comp. Group</label>
            <div class="input-group input-group-sm">
              <select name="component_group_id" class="form-select" id="cg-select">
                <option value="">Pilih</option>
                @foreach($componentGroups as $cg)
                  <option value="{{ $cg->id }}" {{ old('component_group_id') == $cg->id ? 'selected' : '' }}>{{ $cg->name }}</option>
                @endforeach
              </select>
              <button type="button" class="btn btn-outline-teal fw-bold px-2" onclick="inlineAdd('component_groups','cg-select')">+</button>
            </div>
          </div>
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
