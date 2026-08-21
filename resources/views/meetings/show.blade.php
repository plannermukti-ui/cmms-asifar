@extends('layouts.tabler')

@section('title', 'Notulen Rapat: ' . $meeting->meeting_number . ' - CMMS')

@section('content')
<style>
  [data-bs-theme="dark"] .bg-light {
    background-color: #131c2c !important;
    color: #f1f5f9 !important;
  }
  [data-bs-theme="dark"] .table-light th {
    background-color: #131c2c !important;
    color: #cbd5e1 !important;
    border-color: rgba(255, 255, 255, 0.08) !important;
  }
  [data-bs-theme="dark"] .text-dark {
    color: #f8fafc !important;
  }
  [data-bs-theme="dark"] .card-header.bg-light {
    background-color: #131c2c !important;
    border-color: rgba(255, 255, 255, 0.08) !important;
  }
</style>

<div class="page-header d-print-none">
  <div class="container-xl">
    <div class="row g-2 align-items-center">
      <div class="col">
        <div class="page-pretitle">Notulen Rapat</div>
        <h2 class="page-title">
          {{ $meeting->title }}
        </h2>
      </div>
      <div class="col-auto ms-auto d-print-none">
        <div class="btn-list">
          <a href="{{ route('meetings.index') }}" class="btn btn-secondary">
            Kembali
          </a>
          <a href="{{ route('meetings.export-pdf', $meeting) }}" target="_blank" class="btn btn-danger">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><line x1="12" y1="11" x2="12" y2="17" /><polyline points="9 14 12 17 15 14" /></svg>
            Export PDF (A4)
          </a>
          @can('edit_meetings')
          <a href="{{ route('meetings.edit', $meeting) }}" class="btn btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 20h4l10.5 -10.5a2.828 2.828 0 1 0 -4 -4l-10.5 10.5v4" /><path d="M13.5 6.5l4 4" /></svg>
            Edit Notulen
          </a>
          @endcan
        </div>
      </div>
    </div>
  </div>
</div>

<div class="page-body">
  <div class="container-xl">

    @if(session('success'))
      <div class="alert alert-success alert-dismissible mb-3" role="alert">
        <div class="d-flex">
          <div>
            <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10" /></svg>
          </div>
          <div>{{ session('success') }}</div>
        </div>
        <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
      </div>
    @endif

    {{-- Card Header Dokumen --}}
    <div class="card mb-3 border-0 shadow-sm">
      <div class="card-header bg-light d-flex justify-content-between align-items-center">
        <div>
          <span class="badge bg-primary text-white font-monospace fs-4 px-3 py-1">{{ $meeting->meeting_number }}</span>
          <span class="badge bg-blue-lt ms-2">{{ $meeting->meeting_type }}</span>
        </div>
        <div>
          <span class="text-muted small">Dibuat oleh: <b>{{ $meeting->creator->nama_lengkap ?? '-' }}</b> pada {{ $meeting->created_at->format('d/m/Y H:i') }}</span>
        </div>
      </div>
      <div class="card-body">
        <div class="row g-3">
          <div class="col-md-3">
            <div class="text-muted small text-uppercase fw-semibold">Tanggal Pelaksanaan</div>
            <div class="fw-bold fs-3 text-dark">{{ $meeting->meeting_date->format('d F Y') }}</div>
          </div>
          <div class="col-md-3">
            <div class="text-muted small text-uppercase fw-semibold">Waktu / Durasi</div>
            <div class="fw-bold fs-3 text-dark">
              {{ $meeting->start_time ? substr($meeting->start_time, 0, 5) : '--:--' }} 
              {{ $meeting->end_time ? 's/d ' . substr($meeting->end_time, 0, 5) : '' }}
            </div>
          </div>
          <div class="col-md-3">
            <div class="text-muted small text-uppercase fw-semibold">Lokasi / Ruang Rapat</div>
            <div class="fw-bold fs-3 text-dark">{{ $meeting->location ?: 'Ruang Rapat Utama' }}</div>
          </div>
          <div class="col-md-3">
            <div class="text-muted small text-uppercase fw-semibold">Lokasi Site</div>
            <div class="fw-bold fs-3 text-dark">{{ $meeting->site->name ?? 'Semua Site' }}</div>
          </div>

          <div class="col-md-6 border-top pt-3">
            <div class="text-muted small text-uppercase fw-semibold">Pimpinan Rapat / Moderator</div>
            <div class="fw-bold text-dark">{{ $meeting->leader_name ?: '-' }}</div>
          </div>
          <div class="col-md-6 border-top pt-3">
            <div class="text-muted small text-uppercase fw-semibold">Notulis Rapat</div>
            <div class="fw-bold text-dark">{{ $meeting->notetaker_name ?: '-' }}</div>
          </div>

          @if($meeting->attendees)
          <div class="col-12 border-top pt-3">
            <div class="text-muted small text-uppercase fw-semibold mb-1">Daftar Hadir / Peserta Rapat</div>
            <div class="p-3 bg-light rounded-2 text-dark" style="white-space: pre-line;">{{ $meeting->attendees }}</div>
          </div>
          @endif

          @if($meeting->agenda)
          <div class="col-12 border-top pt-3">
            <div class="text-muted small text-uppercase fw-semibold mb-1">Agenda & Pembahasan Umum</div>
            <div class="p-3 bg-light rounded-2 text-dark" style="white-space: pre-line;">{{ $meeting->agenda }}</div>
          </div>
          @endif
        </div>
      </div>
    </div>

    {{-- Card Action Items Matrix --}}
    <div class="card border-0 shadow-sm">
      <div class="card-header bg-light d-flex justify-content-between align-items-center">
        <h3 class="card-title fw-bold m-0">
          <svg xmlns="http://www.w3.org/2000/svg" class="icon text-teal me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2" /><path d="M9 3m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z" /><path d="M9 14l2 2l4 -4" /></svg>
          Matriks Butir Tindak Lanjut (Action Items)
        </h3>
        <span class="badge bg-secondary-lt">{{ count($meeting->actionItems) }} Butir Tindak Lanjut</span>
      </div>

      <div class="table-responsive">
        <table class="table card-table table-vcenter table-hover table-bordered">
          <thead class="table-light">
            <tr>
              <th style="width: 40px;" class="text-center">No</th>
              <th style="min-width: 240px;">Isu / Permasalahan</th>
              <th style="min-width: 250px;">Pembahasan & Tindakan yang Disepakati</th>
              <th style="width: 130px;">Kategori</th>
              <th style="width: 150px;">PIC</th>
              <th style="width: 100px;">Prioritas</th>
              <th style="width: 120px;">Target Selesai</th>
              <th style="min-width: 130px;">Progres (%)</th>
              <th style="width: 110px;">Status</th>
              <th class="text-center" style="width: 80px;">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($meeting->actionItems as $item)
            @php $isOverdue = $item->isOverdue(); @endphp
            <tr class="{{ $isOverdue ? 'table-danger' : '' }}">
              <td class="text-center fw-bold">{{ $item->item_number }}</td>
              <td>
                <div class="fw-bold text-dark">{{ $item->issue }}</div>
                @if($item->parentActionItem)
                  <div class="mt-1">
                    <span class="badge bg-warning-lt text-warning font-monospace" style="font-size: 0.65rem;">
                      Lanjutan dari {{ $item->parentActionItem->meeting->meeting_number ?? 'Sesi Sebelumnya' }}
                    </span>
                  </div>
                @endif
              </td>
              <td>
                <div class="text-muted" style="white-space: pre-line;">{{ $item->discussion ?: '-' }}</div>
                @if($item->latest_update)
                  <div class="mt-2 p-2 bg-light rounded text-info small border">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-inline me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 21v-13a3 3 0 0 1 3 -3h10a3 3 0 0 1 3 3v6a3 3 0 0 1 -3 3h-9l-4 4" /><line x1="8" y1="9" x2="16" y2="9" /><line x1="8" y1="13" x2="14" y2="13" /></svg>
                    <b>Catatan Terkini:</b> {{ $item->latest_update }}
                  </div>
                @endif
              </td>
              <td><span class="badge bg-secondary-lt">{{ $item->category }}</span></td>
              <td><div class="fw-semibold small">{{ $item->effective_pic_name }}</div></td>
              <td><span class="badge {{ $item->priority_badge }}">{{ $item->priority }}</span></td>
              <td>
                @if($item->due_date)
                  <div class="fw-bold {{ $isOverdue ? 'text-danger' : 'text-dark' }}">{{ $item->due_date->format('d M Y') }}</div>
                  @if($isOverdue)
                    <span class="badge bg-danger text-white pill font-monospace" style="font-size: 0.65rem;">OVERDUE</span>
                  @endif
                @else
                  <span class="text-muted">-</span>
                @endif
              </td>
              <td>
                <div class="d-flex align-items-center gap-2">
                  <div class="progress flex-fill" style="height: 6px;">
                    <div class="progress-bar {{ $item->status === 'Completed' ? 'bg-success' : ($item->progress_percent >= 50 ? 'bg-primary' : 'bg-warning') }}" style="width: {{ $item->progress_percent }}%"></div>
                  </div>
                  <span class="small fw-bold">{{ $item->progress_percent }}%</span>
                </div>
              </td>
              <td><span class="badge {{ $item->status_badge }}">{{ $item->status }}</span></td>
              <td class="text-center">
                @can('edit_meetings')
                <button type="button" class="btn btn-sm btn-outline-primary btn-update-action-item" 
                        data-id="{{ $item->id }}"
                        data-issue="{{ $item->issue }}"
                        data-status="{{ $item->status }}"
                        data-progress="{{ $item->progress_percent }}"
                        data-note="{{ $item->latest_update }}"
                        title="Update Progress">
                  Update
                </button>
                @endcan
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="10" class="text-center text-muted py-4">Belum ada butir tindak lanjut dalam notulen ini.</td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

  </div>
</div>

{{-- Modal Quick Update Action Item --}}
<div class="modal modal-blur fade" id="modal-update-action-item" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title fw-bold">Update Progres Tindak Lanjut</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="form-update-action-item" method="POST">
        @csrf
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label text-muted">Isu / Topik</label>
            <div class="form-control-plaintext fw-bold" id="modal-action-item-issue">-</div>
          </div>
          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label class="form-label required">Status</label>
              <select name="status" id="modal-action-item-status" class="form-select" required>
                <option value="Open">Open</option>
                <option value="In Progress">In Progress</option>
                <option value="Waiting Part">Waiting Part / Vendor</option>
                <option value="Completed">Completed / Selesai</option>
                <option value="Cancelled">Cancelled</option>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label required">Progres (%)</label>
              <div class="input-group">
                <input type="number" name="progress_percent" id="modal-action-item-progress" class="form-control" min="0" max="100" required>
                <span class="input-group-text">%</span>
              </div>
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label required">Catatan Perkembangan Terkini</label>
            <textarea name="note" id="modal-action-item-note" class="form-control" rows="3" placeholder="Tuliskan perkembangan terbaru..." required></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn me-auto" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan Perkembangan</button>
        </div>
      </form>
    </div>
  </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const updateButtons = document.querySelectorAll('.btn-update-action-item');
    const modalEl = document.getElementById('modal-update-action-item');
    const modal = new bootstrap.Modal(modalEl);
    const form = document.getElementById('form-update-action-item');

    updateButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            const issue = this.dataset.issue;
            const status = this.dataset.status;
            const progress = this.dataset.progress;
            const note = this.dataset.note;

            document.getElementById('modal-action-item-issue').textContent = issue;
            document.getElementById('modal-action-item-status').value = status;
            document.getElementById('modal-action-item-progress').value = progress;
            document.getElementById('modal-action-item-note').value = note || '';

            form.action = `/meetings/action-items/${id}/update`;
            modal.show();
        });
    });

    const statusSelect = document.getElementById('modal-action-item-status');
    const progressInput = document.getElementById('modal-action-item-progress');
    statusSelect.addEventListener('change', function() {
        if (this.value === 'Completed') {
            progressInput.value = 100;
        } else if (this.value === 'Open' && progressInput.value == 100) {
            progressInput.value = 0;
        }
    });
});
</script>
@endpush

@endsection
