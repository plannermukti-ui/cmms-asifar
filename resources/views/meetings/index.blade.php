@extends('layouts.tabler')

@section('title', 'Notulen Rapat & Action Items Tracker - CMMS')

@section('content')
<style>
  [data-bs-theme="dark"] .bg-light {
    background-color: #131c2c !important;
    color: #f1f5f9 !important;
  }
  [data-bs-theme="dark"] .table-light {
    background-color: #131c2c !important;
    color: #cbd5e1 !important;
  }
  [data-bs-theme="dark"] .table-light th {
    background-color: #131c2c !important;
    color: #cbd5e1 !important;
    border-color: rgba(255, 255, 255, 0.08) !important;
  }
  [data-bs-theme="dark"] .text-dark {
    color: #f8fafc !important;
  }
  [data-bs-theme="dark"] .card.bg-light {
    background-color: #131c2c !important;
    border-color: rgba(255, 255, 255, 0.08) !important;
  }
  [data-bs-theme="dark"] .modal-header.bg-light,
  [data-bs-theme="dark"] .modal-footer.bg-light {
    background-color: #131c2c !important;
    border-color: rgba(255, 255, 255, 0.08) !important;
  }
</style>

<div class="page-header d-print-none">
  <div class="container-xl">
    <div class="row g-2 align-items-center">
      <div class="col">
        <div class="page-pretitle">Kolaborasi & Tindak Lanjut Operasional</div>
        <h2 class="page-title">
          <svg xmlns="http://www.w3.org/2000/svg" class="icon text-teal me-2" width="28" height="28" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2" /><path d="M9 3m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z" /><path d="M9 12h6" /><path d="M9 16h6" /></svg>
          Notulen Rapat & Action Items
        </h2>
      </div>
      <div class="col-auto ms-auto d-print-none">
        <div class="btn-list">
          @can('create_meetings')
          <button type="button" class="btn btn-primary d-none d-sm-inline-block" data-bs-toggle="modal" data-bs-target="#modal-create-meeting">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="12" y1="5" x2="12" y2="19" /><line x1="5" y1="12" x2="19" y2="12" /></svg>
            Buat Notulen Baru
          </button>
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

    @if(session('error'))
      <div class="alert alert-danger alert-dismissible mb-3" role="alert">
        <div class="d-flex">
          <div>
            <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="9" /><line x1="12" y1="8" x2="12" y2="12" /><line x1="12" y1="16" x2="12.01" y2="16" /></svg>
          </div>
          <div>{{ session('error') }}</div>
        </div>
        <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
      </div>
    @endif

    {{-- Quick Stat Cards --}}
    <div class="row row-deck row-cards mb-3">
      <div class="col-sm-6 col-lg-3">
        <div class="card card-sm border-0 shadow-sm bg-primary-lt">
          <div class="card-body">
            <div class="row align-items-center">
              <div class="col-auto">
                <span class="bg-primary text-white avatar">
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 9h8" /><path d="M8 13h6" /><path d="M9 18h-3a3 3 0 0 1 -3 -3v-8a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v8a3 3 0 0 1 -3 3h-3l-3 3l-3 -3z" /></svg>
                </span>
              </div>
              <div class="col">
                <div class="text-muted small">Total Sesi Rapat</div>
                <div class="fw-bold fs-2 text-primary">{{ $stats['total_meetings'] }} <span class="fs-4 text-muted fw-normal">Sesi</span></div>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <div class="col-sm-6 col-lg-3">
        <div class="card card-sm border-0 shadow-sm bg-warning-lt">
          <div class="card-body">
            <div class="row align-items-center">
              <div class="col-auto">
                <span class="bg-warning text-white avatar">
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="9" /><polyline points="12 7 12 12 15 15" /></svg>
                </span>
              </div>
              <div class="col">
                <div class="text-muted small">Isu Aktif (Outstanding)</div>
                <div class="fw-bold fs-2 text-warning">{{ $stats['active_issues'] }} <span class="fs-4 text-muted fw-normal">Isu</span></div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-sm-6 col-lg-3">
        <div class="card card-sm border-0 shadow-sm bg-danger-lt">
          <div class="card-body">
            <div class="row align-items-center">
              <div class="col-auto">
                <span class="bg-danger text-white avatar">
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 9v4" /><path d="M12 16v.01" /><path d="M5 19h14a2 2 0 0 0 1.84 -2.75l-7.1 -12.25a2 2 0 0 0 -3.5 0l-7.1 12.25a2 2 0 0 0 1.75 2.75" /></svg>
                </span>
              </div>
              <div class="col">
                <div class="text-muted small">Isu Melewati Batas (Overdue)</div>
                <div class="fw-bold fs-2 text-danger">{{ $stats['overdue_issues'] }} <span class="fs-4 text-muted fw-normal">Isu</span></div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-sm-6 col-lg-3">
        <div class="card card-sm border-0 shadow-sm bg-green-lt">
          <div class="card-body">
            <div class="row align-items-center">
              <div class="col-auto">
                <span class="bg-green text-white avatar">
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10" /></svg>
                </span>
              </div>
              <div class="col">
                <div class="text-muted small">Tuntas Bulan Ini</div>
                <div class="fw-bold fs-2 text-green">{{ $stats['completed_this_month'] }} <span class="fs-4 text-muted fw-normal">Isu</span></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- Main Nav Tabs --}}
    <div class="card border-0 shadow-sm">
      <div class="card-header">
        <ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs" role="tablist">
          <li class="nav-item" role="presentation">
            <a href="#tab-meetings" class="nav-link {{ $activeTab === 'meetings' ? 'active' : '' }} fw-bold" data-bs-toggle="tab" aria-selected="{{ $activeTab === 'meetings' ? 'true' : 'false' }}" role="tab">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1 text-primary" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 19a9 9 0 0 1 9 0a9 9 0 0 1 9 0" /><path d="M3 6a9 9 0 0 1 9 0a9 9 0 0 1 9 0" /><line x1="3" y1="6" x2="3" y2="19" /><line x1="12" y1="6" x2="12" y2="19" /><line x1="21" y1="6" x2="21" y2="19" /></svg>
              Daftar Notulen Rapat (Sessions)
              <span class="badge bg-primary-lt ms-2">{{ $meetings->total() }}</span>
            </a>
          </li>
          <li class="nav-item" role="presentation">
            <a href="#tab-tracker" class="nav-link {{ $activeTab === 'tracker' ? 'active' : '' }} fw-bold text-danger-lt" data-bs-toggle="tab" aria-selected="{{ $activeTab === 'tracker' ? 'true' : 'false' }}" role="tab">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1 text-warning" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2" /><path d="M9 3m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z" /><path d="M9 14l2 2l4 -4" /></svg>
              Master Action Items Tracker (Tindak Lanjut)
              @if($stats['active_issues'] > 0)
                <span class="badge bg-warning text-dark ms-2">{{ $stats['active_issues'] }} Open</span>
              @endif
            </a>
          </li>
        </ul>
      </div>

      <div class="card-body p-0">
        <div class="tab-content">
          
          {{-- ======================================================== --}}
          {{-- TAB 1: DAFTAR NOTULEN RAPAT                              --}}
          {{-- ======================================================== --}}
          <div class="tab-pane {{ $activeTab === 'meetings' ? 'active show' : '' }}" id="tab-meetings" role="tabpanel">
            
            {{-- Filter Bar Notulen --}}
            <div class="p-3 bg-light border-bottom">
              <form action="{{ route('meetings.index') }}" method="GET" class="row g-2 align-items-center">
                <input type="hidden" name="tab" value="meetings">
                <div class="col-md-3">
                  <input type="text" class="form-control form-control-sm" name="meeting_search" value="{{ request('meeting_search') }}" placeholder="Cari nomor, judul, pimpinan...">
                </div>
                <div class="col-md-2">
                  <select name="meeting_type" class="form-select form-select-sm">
                    <option value="">-- Semua Jenis --</option>
                    @foreach(['Daily Standup', 'Weekly Coordination', 'Monthly Review', 'Safety Talk', 'Ad-hoc'] as $type)
                      <option value="{{ $type }}" {{ request('meeting_type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                    @endforeach
                  </select>
                </div>
                @if(is_null(auth()->user()->site_id))
                <div class="col-md-2">
                  <select name="meeting_site_id" class="form-select form-select-sm">
                    <option value="">-- Semua Site --</option>
                    @foreach($sites as $s)
                      <option value="{{ $s->id }}" {{ request('meeting_site_id') == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                    @endforeach
                  </select>
                </div>
                @endif
                <div class="col-md-2">
                  <input type="date" class="form-control form-control-sm" name="meeting_date_start" value="{{ request('meeting_date_start') }}" title="Dari Tanggal">
                </div>
                <div class="col-md-2">
                  <input type="date" class="form-control form-control-sm" name="meeting_date_end" value="{{ request('meeting_date_end') }}" title="Sampai Tanggal">
                </div>
                <div class="col-md-1 d-flex gap-1">
                  <button type="submit" class="btn btn-sm btn-primary w-100">Filter</button>
                  <a href="{{ route('meetings.index', ['tab' => 'meetings']) }}" class="btn btn-sm btn-secondary" title="Reset">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon m-0" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -4v4h4" /><path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4" /></svg>
                  </a>
                </div>
              </form>
            </div>

            <div class="table-responsive">
              <table class="table card-table table-vcenter table-hover table-striped">
                <thead class="table-light">
                  <tr>
                    <th style="width: 50px;">No</th>
                    <th>Nomor Dokumen</th>
                    <th>Tanggal & Waktu</th>
                    <th>Judul / Topik Rapat</th>
                    <th>Jenis</th>
                    <th>Pimpinan & Notulis</th>
                    <th>Site</th>
                    <th class="text-center">Action Items</th>
                    <th class="text-center" style="width: 140px;">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($meetings as $meeting)
                  <tr>
                    <td>{{ $loop->iteration + ($meetings->currentPage() - 1) * $meetings->perPage() }}</td>
                    <td>
                      <a href="{{ route('meetings.show', $meeting) }}" class="fw-bold font-monospace text-primary text-decoration-none">
                        {{ $meeting->meeting_number }}
                      </a>
                    </td>
                    <td>
                      <div class="fw-bold">{{ $meeting->meeting_date->format('d M Y') }}</div>
                      <div class="text-muted small">
                        {{ $meeting->start_time ? substr($meeting->start_time, 0, 5) : '--:--' }} 
                        {{ $meeting->end_time ? '- ' . substr($meeting->end_time, 0, 5) : '' }}
                      </div>
                    </td>
                    <td>
                      <div class="fw-bold text-dark">{{ $meeting->title }}</div>
                      @if($meeting->location)
                        <div class="text-muted small">
                          <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-inline me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 18.5l-3 -1.5l-6 3v-13l6 -3l6 3l6 -3v7.5" /><path d="M9 4v13" /><path d="M15 7v5.5" /></svg>
                          {{ $meeting->location }}
                        </div>
                      @endif
                    </td>
                    <td>
                      <span class="badge bg-blue-lt">{{ $meeting->meeting_type }}</span>
                    </td>
                    <td>
                      <div class="small fw-semibold">{{ $meeting->leader_name ?: '-' }}</div>
                      <div class="text-muted small">Notulis: {{ $meeting->notetaker_name ?: '-' }}</div>
                    </td>
                    <td>
                      <span class="badge bg-secondary-lt">{{ $meeting->site->name ?? 'Semua Site' }}</span>
                    </td>
                    <td class="text-center">
                      <div class="d-flex justify-content-center gap-1">
                        @if($meeting->open_action_items_count > 0)
                          <span class="badge bg-warning-lt text-warning" title="Isu Belum Selesai">{{ $meeting->open_action_items_count }} Open</span>
                        @endif
                        @if($meeting->completed_action_items_count > 0)
                          <span class="badge bg-success-lt text-success" title="Isu Selesai">{{ $meeting->completed_action_items_count }} Done</span>
                        @endif
                        @if($meeting->action_items_count == 0)
                          <span class="badge bg-secondary-lt text-muted">-</span>
                        @endif
                      </div>
                    </td>
                    <td class="text-center">
                      <div class="btn-group btn-group-sm">
                        <a href="{{ route('meetings.show', $meeting) }}" class="btn btn-outline-info" title="Lihat Detail">
                          <svg xmlns="http://www.w3.org/2000/svg" class="icon m-0" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="2" /><path d="M22 12c-2.667 4.667 -6 7 -10 7s-7.333 -2.333 -10 -7c2.667 -4.667 6 -7 10 -7s7.333 2.333 10 7" /></svg>
                        </a>
                        <a href="{{ route('meetings.export-pdf', $meeting) }}" target="_blank" class="btn btn-outline-danger" title="Download PDF">
                          <svg xmlns="http://www.w3.org/2000/svg" class="icon m-0" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><line x1="12" y1="11" x2="12" y2="17" /><polyline points="9 14 12 17 15 14" /></svg>
                        </a>
                        @can('edit_meetings')
                        <a href="{{ route('meetings.edit', $meeting) }}" class="btn btn-outline-primary" title="Edit Notulen">
                          <svg xmlns="http://www.w3.org/2000/svg" class="icon m-0" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 20h4l10.5 -10.5a2.828 2.828 0 1 0 -4 -4l-10.5 10.5v4" /><path d="M13.5 6.5l4 4" /></svg>
                        </a>
                        @endcan
                        @can('delete_meetings')
                        <form action="{{ route('meetings.destroy', $meeting) }}" method="POST" class="d-inline form-delete-meeting">
                          @csrf
                          @method('DELETE')
                          <button type="button" class="btn btn-outline-danger btn-trigger-delete" title="Hapus Notulen" data-number="{{ $meeting->meeting_number }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon m-0" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="4" y1="7" x2="20" y2="7" /><line x1="10" y1="11" x2="10" y2="17" /><line x1="14" y1="11" x2="14" y2="17" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
                          </button>
                        </form>
                        @endcan
                      </div>
                    </td>
                  </tr>
                  @empty
                  <tr>
                    <td colspan="9" class="text-center text-muted py-4">Belum ada sesi notulen rapat yang dicatat.</td>
                  </tr>
                  @endforelse
                </tbody>
              </table>
            </div>

            @if($meetings->hasPages())
            <div class="card-footer d-flex align-items-center">
              {{ $meetings->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>
            @endif
          </div>

          {{-- ======================================================== --}}
          {{-- TAB 2: MASTER ACTION ITEMS TRACKER (OUTSTANDING ISSUES)  --}}
          {{-- ======================================================== --}}
          <div class="tab-pane {{ $activeTab === 'tracker' ? 'active show' : '' }}" id="tab-tracker" role="tabpanel">
            
            {{-- Filter Bar Tracker --}}
            <div class="p-3 bg-light border-bottom">
              <form action="{{ route('meetings.index') }}" method="GET" class="row g-2 align-items-center">
                <input type="hidden" name="tab" value="tracker">
                <div class="col-md-3">
                  <input type="text" class="form-control form-control-sm" name="issue_search" value="{{ request('issue_search') }}" placeholder="Cari judul isu, rencana tindakan, PIC...">
                </div>
                <div class="col-md-2">
                  <select name="status" class="form-select form-select-sm">
                    <option value="">-- Semua Status --</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>📌 Semua Isu Aktif (Open/Progress)</option>
                    <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>⚠️ Overdue (Melewati Batas)</option>
                    <option value="Open" {{ request('status') == 'Open' ? 'selected' : '' }}>Open</option>
                    <option value="In Progress" {{ request('status') == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="Waiting Part" {{ request('status') == 'Waiting Part' ? 'selected' : '' }}>Waiting Part / Vendor</option>
                    <option value="Completed" {{ request('status') == 'Completed' ? 'selected' : '' }}>Completed / Selesai</option>
                    <option value="Cancelled" {{ request('status') == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                  </select>
                </div>
                <div class="col-md-2">
                  <select name="priority" class="form-select form-select-sm">
                    <option value="">-- Prioritas --</option>
                    <option value="Critical" {{ request('priority') == 'Critical' ? 'selected' : '' }}>🔴 Critical / Urgent</option>
                    <option value="High" {{ request('priority') == 'High' ? 'selected' : '' }}>🟠 High</option>
                    <option value="Medium" {{ request('priority') == 'Medium' ? 'selected' : '' }}>🔵 Medium</option>
                    <option value="Low" {{ request('priority') == 'Low' ? 'selected' : '' }}>⚪ Low</option>
                  </select>
                </div>
                <div class="col-md-2">
                  <select name="category" class="form-select form-select-sm">
                    <option value="">-- Kategori --</option>
                    @foreach(['Breakdown & WO', 'Sparepart & Logistic', 'Manpower', 'HSE & Safety', 'Operations & Plant', 'Budget & Admin', 'General'] as $cat)
                      <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="col-md-2">
                  <select name="pic_id" class="form-select form-select-sm">
                    <option value="">-- PIC User --</option>
                    @foreach($users as $u)
                      <option value="{{ $u->id }}" {{ request('pic_id') == $u->id ? 'selected' : '' }}>{{ $u->nama_lengkap }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="col-md-1 d-flex gap-1">
                  <button type="submit" class="btn btn-sm btn-primary w-100">Filter</button>
                  <a href="{{ route('meetings.index', ['tab' => 'tracker']) }}" class="btn btn-sm btn-secondary" title="Reset">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon m-0" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -4v4h4" /><path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4" /></svg>
                  </a>
                </div>
              </form>
            </div>

            <div class="table-responsive">
              <table class="table card-table table-vcenter table-hover table-striped">
                <thead class="table-light">
                  <tr>
                    <th style="width: 50px;">No</th>
                    <th>Isu / Permasalahan</th>
                    <th>Asal Rapat</th>
                    <th>Kategori</th>
                    <th>PIC</th>
                    <th>Prioritas</th>
                    <th>Target Selesai</th>
                    <th style="min-width: 140px;">Progres (%)</th>
                    <th>Status</th>
                    <th class="text-center" style="width: 100px;">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($actionItems as $item)
                  @php
                    $isOverdue = $item->isOverdue();
                  @endphp
                  <tr class="{{ $isOverdue ? 'table-danger' : '' }}">
                    <td>{{ $loop->iteration + ($actionItems->currentPage() - 1) * $actionItems->perPage() }}</td>
                    <td>
                      <div class="fw-bold text-dark">{{ $item->issue }}</div>
                      @if($item->discussion)
                        <div class="text-muted small text-truncate" style="max-width: 320px;" title="{{ $item->discussion }}">
                          {{ $item->discussion }}
                        </div>
                      @endif
                      @if($item->latest_update)
                        <div class="text-info small mt-1">
                          <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-inline me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 21v-13a3 3 0 0 1 3 -3h10a3 3 0 0 1 3 3v6a3 3 0 0 1 -3 3h-9l-4 4" /><line x1="8" y1="9" x2="16" y2="9" /><line x1="8" y1="13" x2="14" y2="13" /></svg>
                          <span class="fw-semibold">Update:</span> {{ $item->latest_update }}
                        </div>
                      @endif
                    </td>
                    <td>
                      <a href="{{ route('meetings.show', $item->meeting) }}" class="fw-semibold font-monospace small text-primary text-decoration-none">
                        {{ $item->meeting->meeting_number }}
                      </a>
                      <div class="text-muted small">{{ $item->meeting->meeting_date->format('d/m/Y') }}</div>
                    </td>
                    <td>
                      <span class="badge bg-secondary-lt">{{ $item->category }}</span>
                    </td>
                    <td>
                      <div class="fw-semibold small">{{ $item->effective_pic_name }}</div>
                    </td>
                    <td>
                      <span class="badge {{ $item->priority_badge }}">{{ $item->priority }}</span>
                    </td>
                    <td>
                      @if($item->due_date)
                        <div class="fw-bold {{ $isOverdue ? 'text-danger' : 'text-dark' }}">
                          {{ $item->due_date->format('d M Y') }}
                        </div>
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
                    <td>
                      <span class="badge {{ $item->status_badge }}">{{ $item->status }}</span>
                    </td>
                    <td class="text-center">
                      @can('edit_meetings')
                      <button type="button" class="btn btn-sm btn-outline-primary btn-update-action-item" 
                              data-id="{{ $item->id }}"
                              data-issue="{{ $item->issue }}"
                              data-status="{{ $item->status }}"
                              data-progress="{{ $item->progress_percent }}"
                              data-note="{{ $item->latest_update }}"
                              title="Update Progress & Status">
                        Update
                      </button>
                      @endcan
                    </td>
                  </tr>
                  @empty
                  <tr>
                    <td colspan="10" class="text-center text-muted py-4">Tidak ada action item yang sesuai filter.</td>
                  </tr>
                  @endforelse
                </tbody>
              </table>
            </div>

            @if($actionItems->hasPages())
            <div class="card-footer d-flex align-items-center">
              {{ $actionItems->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>
            @endif
          </div>

        </div>
      </div>
    </div>

  </div>
</div>

{{-- Modal Quick Update Action Item --}}
<div class="modal modal-blur fade" id="modal-update-action-item" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title fw-bold">Update Progres & Status Tindak Lanjut</h5>
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
            <textarea name="note" id="modal-action-item-note" class="form-control" rows="3" placeholder="Tuliskan perkembangan terbaru atau kendala yang dihadapi..." required></textarea>
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

{{-- Modal Create Meeting --}}
<div class="modal modal-blur fade" id="modal-create-meeting" tabindex="-1" role="dialog" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable" role="document" style="max-width: 95vw; width: 95vw;">
    <form action="{{ route('meetings.store') }}" method="POST" id="meeting-create-form" class="modal-content shadow-lg border-0" novalidate>
      <div class="modal-header bg-light py-3 border-bottom">
        <div>
          <h4 class="modal-title fw-bold text-dark m-0">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon text-primary me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2" /><path d="M9 3m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z" /><path d="M9 12h6" /><path d="M9 16h6" /></svg>
            Buat Notulen Rapat Baru
          </h4>
          <div class="text-muted small">Isi agenda rapat, pimpinan, peserta, dan butir tindak lanjut (Action Items).</div>
        </div>
        <button type="button" class="btn-close btn-close-create-modal" aria-label="Close"></button>
      </div>
      
      @csrf
      <div class="modal-body p-4">
          
          {{-- Dynamic Error Alert Banner --}}
          <div id="meeting-create-error-alert" class="alert alert-danger alert-dismissible d-none mb-3" role="alert">
            <div class="d-flex">
              <div>
                <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="9" /><line x1="12" y1="8" x2="12" y2="12" /><line x1="12" y1="16" x2="12.01" y2="16" /></svg>
              </div>
              <div>
                <h4 class="alert-title fw-bold">Terdapat data yang belum lengkap atau salah!</h4>
                <div class="text-muted" id="meeting-create-error-message">Mohon lengkapi semua kolom yang wajib diisi.</div>
              </div>
            </div>
          </div>

          {{-- Section 1: Informasi Header --}}
          <div class="card mb-3 border bg-light shadow-none">
            <div class="card-status-top bg-primary"></div>
            <div class="card-header py-2 bg-transparent">
              <h4 class="card-title fw-bold text-primary m-0">
                <span class="badge bg-primary text-white me-2">01</span> Informasi Sesi Rapat
              </h4>
            </div>
            <div class="card-body p-3">
              <div class="row g-3">
                <div class="col-md-4">
                  <label class="form-label required">Nomor Dokumen Notulen</label>
                  <input type="text" class="form-control font-monospace fw-bold" name="meeting_number" id="input-meeting-number" value="{{ $meetingNumber }}" required>
                  <div class="invalid-feedback">Nomor dokumen wajib diisi.</div>
                </div>
                <div class="col-md-4">
                  <label class="form-label required">Jenis Rapat</label>
                  <select name="meeting_type" class="form-select" id="input-meeting-type" required>
                    @foreach(['Daily Standup', 'Weekly Coordination', 'Monthly Review', 'Safety Talk', 'Ad-hoc'] as $type)
                      <option value="{{ $type }}">{{ $type }}</option>
                    @endforeach
                  </select>
                </div>
                @if(is_null(auth()->user()->site_id))
                <div class="col-md-4">
                  <label class="form-label">Lokasi Site</label>
                  <select name="site_id" class="form-select">
                    <option value="">-- Pilih Site --</option>
                    @foreach($sites as $site)
                      <option value="{{ $site->id }}">{{ $site->name }}</option>
                    @endforeach
                  </select>
                </div>
                @endif
                <div class="col-md-8">
                  <label class="form-label required">Topik / Judul Rapat</label>
                  <input type="text" class="form-control" name="title" id="input-meeting-title" placeholder="Contoh: Daily Coordination Meeting - Plant & Mine Ops" required>
                  <div class="invalid-feedback">Topik / Judul Rapat wajib diisi.</div>
                </div>
                <div class="col-md-4">
                  <label class="form-label required">Tanggal Rapat</label>
                  <input type="date" class="form-control" name="meeting_date" id="input-meeting-date" value="{{ date('Y-m-d') }}" required>
                  <div class="invalid-feedback">Tanggal rapat wajib diisi.</div>
                </div>
                <div class="col-md-3">
                  <label class="form-label">Jam Mulai</label>
                  <input type="time" class="form-control" name="start_time" value="08:00">
                </div>
                <div class="col-md-3">
                  <label class="form-label">Jam Selesai</label>
                  <input type="time" class="form-control" name="end_time" value="09:00">
                </div>
                <div class="col-md-6">
                  <label class="form-label">Lokasi / Ruang Rapat</label>
                  <input type="text" class="form-control" name="location" placeholder="Contoh: Ruang Rapat Workshop / Online Teams">
                </div>
                <div class="col-md-6">
                  <label class="form-label">Pimpinan Rapat / Moderator</label>
                  <input type="text" class="form-control" name="leader_name" value="{{ auth()->user()->nama_lengkap }}" placeholder="Nama pimpinan rapat">
                </div>
                <div class="col-md-6">
                  <label class="form-label">Notulis Rapat</label>
                  <input type="text" class="form-control" name="notetaker_name" value="{{ auth()->user()->nama_lengkap }}" placeholder="Nama pencatat notulen">
                </div>
                <div class="col-md-12">
                  <label class="form-label">Daftar Hadir / Peserta Rapat</label>
                  <textarea class="form-control" name="attendees" rows="2" placeholder="Tuliskan nama peserta atau departemen yang hadir..."></textarea>
                </div>
                <div class="col-md-12">
                  <label class="form-label">Agenda & Catatan Pembahasan Umum</label>
                  <textarea class="form-control" name="agenda" rows="2" placeholder="Ringkasan poin-poin agenda yang dibahas..."></textarea>
                </div>
              </div>
            </div>
          </div>

          {{-- Section 2: Action Items Repeater --}}
          <div class="card border shadow-none">
            <div class="card-status-top bg-teal"></div>
            <div class="card-header py-2 bg-light d-flex justify-content-between align-items-center flex-wrap gap-2">
              <h4 class="card-title fw-bold text-dark m-0">
                <span class="badge bg-teal text-white me-2">02</span> Butir Pembahasan & Tindak Lanjut (Action Items)
              </h4>
              <div class="btn-list">
                <button type="button" class="btn btn-sm btn-outline-warning" id="btn-modal-import-open-items">
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M13 3l0 7l6 0l-8 11l0 -7l-6 0z" /></svg>
                  ⚡ Tarik Isu Belum Selesai (Import)
                </button>
                <button type="button" class="btn btn-sm btn-outline-primary" id="btn-modal-add-item-row">
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="12" y1="5" x2="12" y2="19" /><line x1="5" y1="12" x2="19" y2="12" /></svg>
                  Tambah Baris Isu
                </button>
              </div>
            </div>
            <div class="table-responsive">
              <table class="table card-table table-vcenter table-bordered" id="modal-action-items-table">
                <thead class="table-light text-center">
                  <tr>
                    <th style="width: 40px;">No</th>
                    <th style="min-width: 220px;">Isu / Permasalahan <span class="text-danger">*</span></th>
                    <th style="min-width: 240px;">Rencana Tindakan</th>
                    <th style="width: 140px;">Kategori</th>
                    <th style="width: 160px;">PIC</th>
                    <th style="width: 110px;">Prioritas</th>
                    <th style="width: 130px;">Due Date</th>
                    <th style="width: 130px;">Status</th>
                    <th style="width: 95px;">Progres</th>
                    <th style="width: 50px;">Aksi</th>
                  </tr>
                </thead>
                <tbody id="modal-action-items-tbody">
                  <tr class="modal-action-item-row" data-row="0">
                    <td class="text-center row-number fw-bold">1</td>
                    <td>
                      <input type="hidden" name="items[0][parent_action_item_id]" class="input-parent-id" value="">
                      <textarea name="items[0][issue]" class="form-control form-control-sm input-issue" rows="2" placeholder="Tuliskan isu/masalah..." required></textarea>
                      <div class="invalid-feedback">Isu wajib diisi.</div>
                    </td>
                    <td>
                      <textarea name="items[0][discussion]" class="form-control form-control-sm" rows="2" placeholder="Rencana tindakan..."></textarea>
                    </td>
                    <td>
                      <select name="items[0][category]" class="form-select form-select-sm">
                        @foreach(['Breakdown & WO', 'Sparepart & Logistic', 'Manpower', 'HSE & Safety', 'Operations & Plant', 'Budget & Admin', 'General'] as $cat)
                          <option value="{{ $cat }}" {{ $cat == 'Breakdown & WO' ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                      </select>
                    </td>
                    <td>
                      <input type="text" name="items[0][pic_name]" class="form-control form-control-sm" placeholder="Nama PIC...">
                    </td>
                    <td>
                      <select name="items[0][priority]" class="form-select form-select-sm">
                        <option value="Critical">🔴 Critical</option>
                        <option value="High">🟠 High</option>
                        <option value="Medium" selected>🔵 Medium</option>
                        <option value="Low">⚪ Low</option>
                      </select>
                    </td>
                    <td>
                      <input type="date" name="items[0][due_date]" class="form-control form-control-sm">
                    </td>
                    <td>
                      <select name="items[0][status]" class="form-select form-select-sm select-status">
                        <option value="Open" selected>Open</option>
                        <option value="In Progress">In Progress</option>
                        <option value="Waiting Part">Waiting Part</option>
                        <option value="Completed">Completed</option>
                        <option value="Cancelled">Cancelled</option>
                      </select>
                    </td>
                    <td>
                      <div class="input-group input-group-sm">
                        <input type="number" name="items[0][progress_percent]" class="form-control input-progress" value="0" min="0" max="100">
                        <span class="input-group-text p-1">%</span>
                      </div>
                    </td>
                    <td class="text-center">
                      <button type="button" class="btn btn-sm btn-outline-danger btn-remove-row p-1" title="Hapus Baris">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon m-0" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="4" y1="7" x2="20" y2="7" /><line x1="10" y1="11" x2="10" y2="17" /><line x1="14" y1="11" x2="14" y2="17" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
                      </button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

        </div>
        <div class="modal-footer bg-light py-3 border-top d-flex justify-content-between">
          <button type="button" class="btn btn-secondary btn-close-create-modal">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="18" y1="6" x2="6" y2="18" /><line x1="6" y1="6" x2="18" y2="18" /></svg>
            Batal
          </button>
          <button type="submit" class="btn btn-primary px-4 shadow-sm" id="btn-submit-meeting">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10" /></svg>
            Simpan Notulen Rapat
          </button>
        </div>
      </form>
  </div>
</div>

{{-- Modal Import Open Action Items --}}
<div class="modal modal-blur fade" id="modal-import-open-items" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header bg-light">
        <div>
          <h5 class="modal-title fw-bold">Tarik Isu Belum Selesai (Outstanding Action Items)</h5>
          <div class="text-muted small">Pilih isu dari meeting sebelumnya yang masih berjalan untuk dilanjutkan pada meeting ini.</div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-0">
        <div id="import-loading" class="text-center py-5">
          <div class="spinner-border text-primary" role="status"></div>
          <div class="text-muted mt-2">Memuat daftar isu yang masih berjalan...</div>
        </div>
        <div id="import-empty" class="text-center py-5 d-none">
          <div class="text-muted">Tidak ada isu yang berstatus Open / In Progress dari meeting sebelumnya.</div>
        </div>
        <div class="table-responsive" id="import-table-container">
          <table class="table card-table table-vcenter table-hover table-striped mb-0">
            <thead class="table-light">
              <tr>
                <th style="width: 40px;" class="text-center">
                  <input type="checkbox" class="form-check-input" id="check-all-import">
                </th>
                <th>Isu / Permasalahan</th>
                <th>Asal Meeting</th>
                <th>Kategori</th>
                <th>PIC</th>
                <th>Target Selesai</th>
                <th>Status Terakhir</th>
              </tr>
            </thead>
            <tbody id="import-tbody">
              {{-- Populated via AJAX --}}
            </tbody>
          </table>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn me-auto" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-warning" id="btn-confirm-import">
          <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M13 3l0 7l6 0l-8 11l0 -7l-6 0z" /></svg>
          Tambahkan Isu Terpilih ke Notulen Ini
        </button>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // -------------------------------------------------------------
    // Delete Meeting Confirmation (Tabler UI Modal)
    // -------------------------------------------------------------
    document.querySelectorAll('.btn-trigger-delete').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const form = this.closest('form');
            const number = this.dataset.number || 'ini';

            window.showTablerConfirm({
                title: 'Hapus Notulen Rapat',
                message: `Apakah Anda yakin ingin menghapus notulen <b>${number}</b>?<br><span class="text-danger small">Seluruh butir tindak lanjut (Action Items) terkait juga akan ikut terhapus.</span>`,
                confirmText: 'Ya, Hapus Notulen',
                cancelText: 'Batal',
                type: 'danger',
                onConfirm: function() {
                    form.submit();
                }
            });
        });
    });

    // -------------------------------------------------------------
    // Quick Update Action Item Modal Logic
    // -------------------------------------------------------------
    const updateButtons = document.querySelectorAll('.btn-update-action-item');
    const updateModalEl = document.getElementById('modal-update-action-item');
    const updateModal = new bootstrap.Modal(updateModalEl);
    const updateForm = document.getElementById('form-update-action-item');

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

            updateForm.action = `/meetings/action-items/${id}/update`;
            updateModal.show();
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

    // -------------------------------------------------------------
    // Create Meeting Modal & Form Validation Logic
    // -------------------------------------------------------------
    const createModalEl = document.getElementById('modal-create-meeting');
    const createModal = new bootstrap.Modal(createModalEl);
    const createForm = document.getElementById('meeting-create-form');
    const errorAlert = document.getElementById('meeting-create-error-alert');
    const errorMessage = document.getElementById('meeting-create-error-message');
    const modalTbody = document.getElementById('modal-action-items-tbody');
    let modalRowIndex = 1;
    let isFormDirty = false;

    // Track when user modifies any input
    createForm.addEventListener('input', function() {
        isFormDirty = true;
    });

    // Close Confirmation Handler using Tabler UI Modal
    document.querySelectorAll('.btn-close-create-modal').forEach(btn => {
        btn.addEventListener('click', function() {
            if (isFormDirty) {
                window.showTablerConfirm({
                    title: 'Perubahan Belum Disimpan!',
                    message: 'Ada isian data notulen yang belum disimpan. Apakah Anda yakin ingin menutup formulir tanpa menyimpan?',
                    confirmText: 'Ya, Tutup Tanpa Menyimpan',
                    cancelText: 'Kembali Mengedit',
                    type: 'warning',
                    onConfirm: function() {
                        isFormDirty = false;
                        createModal.hide();
                    }
                });
            } else {
                createModal.hide();
            }
        });
    });

    // Form Validation before submission
    createForm.addEventListener('submit', function(e) {
        let errors = [];

        // Clear previous error state
        errorAlert.classList.add('d-none');
        errorMessage.innerHTML = '';
        createForm.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));

        // Validate Header Fields
        const meetingNumber = document.getElementById('input-meeting-number');
        if (!meetingNumber.value.trim()) {
            meetingNumber.classList.add('is-invalid');
            errors.push('Nomor Dokumen Notulen tidak boleh kosong.');
        }

        const title = document.getElementById('input-meeting-title');
        if (!title.value.trim()) {
            title.classList.add('is-invalid');
            errors.push('Topik / Judul Rapat wajib diisi.');
        }

        const meetingDate = document.getElementById('input-meeting-date');
        if (!meetingDate.value.trim()) {
            meetingDate.classList.add('is-invalid');
            errors.push('Tanggal Rapat wajib diisi.');
        }

        // Validate Action Items
        const rows = modalTbody.querySelectorAll('.modal-action-item-row');
        if (rows.length === 0) {
            errors.push('Minimal harus ada 1 butir tindak lanjut (Action Item).');
        } else {
            rows.forEach((row, idx) => {
                const issueInput = row.querySelector('.input-issue') || row.querySelector('textarea[name$="[issue]"]');
                if (issueInput && !issueInput.value.trim()) {
                    issueInput.classList.add('is-invalid');
                    errors.push(`Isu / Permasalahan pada baris #${idx + 1} wajib diisi.`);
                }
            });
        }

        // If validation errors exist, show custom Tabler UI Error Modal & inline alert
        if (errors.length > 0) {
            e.preventDefault();
            e.stopPropagation();

            let errorHtml = '<ul class="mb-0 ps-3 mt-1">';
            errors.forEach(err => {
                errorHtml += `<li>${err}</li>`;
            });
            errorHtml += '</ul>';

            errorMessage.innerHTML = errorHtml;
            errorAlert.classList.remove('d-none');
            errorAlert.scrollIntoView({ behavior: 'smooth', block: 'center' });

            window.showTablerErrorModal('Formulir Belum Lengkap', errors);
            return false;
        }

        // Reset dirty flag if valid
        isFormDirty = false;
    });

    function reindexModalRows() {
        const rows = modalTbody.querySelectorAll('.modal-action-item-row');
        rows.forEach((row, idx) => {
            row.querySelector('.row-number').textContent = idx + 1;
            row.querySelectorAll('input, select, textarea').forEach(input => {
                const name = input.getAttribute('name');
                if (name) {
                    input.setAttribute('name', name.replace(/items\[\d+\]/, `items[${idx}]`));
                }
            });
        });
        modalRowIndex = rows.length;
    }

    function attachModalRowEvents(row) {
        row.querySelector('.btn-remove-row').addEventListener('click', function() {
            if (modalTbody.querySelectorAll('.modal-action-item-row').length > 1) {
                row.remove();
                reindexModalRows();
                isFormDirty = true;
            } else {
                window.showTablerErrorModal('Perhatian', 'Minimal harus ada 1 baris tindak lanjut (Action Item).');
            }
        });

        const statusSel = row.querySelector('.select-status');
        const progIn = row.querySelector('.input-progress');
        if (statusSel && progIn) {
            statusSel.addEventListener('change', function() {
                if (this.value === 'Completed') {
                    progIn.value = 100;
                } else if (this.value === 'Open' && progIn.value == 100) {
                    progIn.value = 0;
                }
                isFormDirty = true;
            });
        }
    }

    // Attach to existing initial row
    document.querySelectorAll('.modal-action-item-row').forEach(attachModalRowEvents);

    // Add Row Button in Create Modal
    document.getElementById('btn-modal-add-item-row').addEventListener('click', function() {
        const newRow = document.createElement('tr');
        newRow.className = 'modal-action-item-row';
        newRow.dataset.row = modalRowIndex;
        newRow.innerHTML = `
            <td class="text-center row-number fw-bold">${modalRowIndex + 1}</td>
            <td>
              <input type="hidden" name="items[${modalRowIndex}][parent_action_item_id]" class="input-parent-id" value="">
              <textarea name="items[${modalRowIndex}][issue]" class="form-control form-control-sm input-issue" rows="2" placeholder="Tuliskan isu/masalah..." required></textarea>
              <div class="invalid-feedback">Isu wajib diisi.</div>
            </td>
            <td>
              <textarea name="items[${modalRowIndex}][discussion]" class="form-control form-control-sm" rows="2" placeholder="Rencana tindakan..."></textarea>
            </td>
            <td>
              <select name="items[${modalRowIndex}][category]" class="form-select form-select-sm">
                <option value="Breakdown & WO">Breakdown & WO</option>
                <option value="Sparepart & Logistic">Sparepart & Logistic</option>
                <option value="Manpower">Manpower</option>
                <option value="HSE & Safety">HSE & Safety</option>
                <option value="Operations & Plant">Operations & Plant</option>
                <option value="Budget & Admin">Budget & Admin</option>
                <option value="General" selected>General</option>
              </select>
            </td>
            <td>
              <input type="text" name="items[${modalRowIndex}][pic_name]" class="form-control form-control-sm" placeholder="Nama PIC...">
            </td>
            <td>
              <select name="items[${modalRowIndex}][priority]" class="form-select form-select-sm">
                <option value="Critical">🔴 Critical</option>
                <option value="High">🟠 High</option>
                <option value="Medium" selected>🔵 Medium</option>
                <option value="Low">⚪ Low</option>
              </select>
            </td>
            <td>
              <input type="date" name="items[${modalRowIndex}][due_date]" class="form-control form-control-sm">
            </td>
            <td>
              <select name="items[${modalRowIndex}][status]" class="form-select form-select-sm select-status">
                <option value="Open" selected>Open</option>
                <option value="In Progress">In Progress</option>
                <option value="Waiting Part">Waiting Part</option>
                <option value="Completed">Completed</option>
                <option value="Cancelled">Cancelled</option>
              </select>
            </td>
            <td>
              <div class="input-group input-group-sm">
                <input type="number" name="items[${modalRowIndex}][progress_percent]" class="form-control input-progress" value="0" min="0" max="100">
                <span class="input-group-text p-1">%</span>
              </div>
            </td>
            <td class="text-center">
              <button type="button" class="btn btn-sm btn-outline-danger btn-remove-row p-1" title="Hapus Baris">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon m-0" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="4" y1="7" x2="20" y2="7" /><line x1="10" y1="11" x2="10" y2="17" /><line x1="14" y1="11" x2="14" y2="17" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
              </button>
            </td>
        `;
        modalTbody.appendChild(newRow);
        attachModalRowEvents(newRow);
        modalRowIndex++;
        isFormDirty = true;
    });

    // -------------------------------------------------------------
    // Import Open Items Modal Logic for Create Modal
    // -------------------------------------------------------------
    const importModalEl = document.getElementById('modal-import-open-items');
    const importModal = new bootstrap.Modal(importModalEl);
    let loadedOpenItems = [];

    document.getElementById('btn-modal-import-open-items').addEventListener('click', function() {
        document.getElementById('import-loading').classList.remove('d-none');
        document.getElementById('import-empty').classList.add('d-none');
        document.getElementById('import-table-container').classList.add('d-none');
        document.getElementById('import-tbody').innerHTML = '';

        importModal.show();

        fetch('{{ route("meetings.get-open-action-items") }}')
            .then(res => res.json())
            .then(data => {
                document.getElementById('import-loading').classList.add('d-none');
                if (data.success && data.items.length > 0) {
                    loadedOpenItems = data.items;
                    document.getElementById('import-table-container').classList.remove('d-none');
                    let html = '';
                    data.items.forEach((item, i) => {
                        html += `
                            <tr>
                              <td class="text-center">
                                <input type="checkbox" class="form-check-input check-import-item" value="${item.id}" data-index="${i}">
                              </td>
                              <td>
                                <div class="fw-bold">${item.issue}</div>
                                ${item.discussion ? `<div class="text-muted small">${item.discussion}</div>` : ''}
                                ${item.latest_update ? `<div class="text-info small">Update: ${item.latest_update}</div>` : ''}
                              </td>
                              <td>
                                <span class="font-monospace small fw-bold text-primary">${item.meeting_number}</span>
                                <div class="text-muted small">${item.meeting_date}</div>
                              </td>
                              <td><span class="badge bg-secondary-lt">${item.category}</span></td>
                              <td>${item.pic_name || '-'}</td>
                              <td>${item.due_date || '-'}</td>
                              <td>
                                <span class="badge bg-warning-lt">${item.status} (${item.progress_percent}%)</span>
                              </td>
                            </tr>
                        `;
                    });
                    document.getElementById('import-tbody').innerHTML = html;
                } else {
                    document.getElementById('import-empty').classList.remove('d-none');
                }
            })
            .catch(err => {
                document.getElementById('import-loading').classList.add('d-none');
                window.showTablerErrorModal('Gagal Mengambil Data', 'Gagal memuat data isu terbuka: ' + err);
            });
    });

    // Check all import items
    document.getElementById('check-all-import').addEventListener('change', function() {
        const checked = this.checked;
        document.querySelectorAll('.check-import-item').forEach(cb => cb.checked = checked);
    });

    // Confirm Import Selected Items
    document.getElementById('btn-confirm-import').addEventListener('click', function() {
        const checkedBoxes = document.querySelectorAll('.check-import-item:checked');
        if (checkedBoxes.length === 0) {
            window.showTablerErrorModal('Peringatan', 'Silakan pilih minimal satu isu dari tabel untuk diimpor ke notulen ini.');
            return;
        }

        // If the first row is completely empty, clear it first
        const firstRow = modalTbody.querySelector('.modal-action-item-row');
        if (modalTbody.querySelectorAll('.modal-action-item-row').length === 1 && firstRow) {
            const firstIssue = firstRow.querySelector('.input-issue') || firstRow.querySelector('textarea[name$="[issue]"]');
            if (firstIssue && firstIssue.value.trim() === '') {
                firstRow.remove();
            }
        }

        checkedBoxes.forEach(cb => {
            const item = loadedOpenItems[cb.dataset.index];
            const newRow = document.createElement('tr');
            newRow.className = 'modal-action-item-row table-warning-lt';
            newRow.dataset.row = modalRowIndex;
            newRow.innerHTML = `
                <td class="text-center row-number fw-bold">${modalRowIndex + 1}</td>
                <td>
                  <input type="hidden" name="items[${modalRowIndex}][parent_action_item_id]" class="input-parent-id" value="${item.id}">
                  <textarea name="items[${modalRowIndex}][issue]" class="form-control form-control-sm input-issue" rows="2" required>${item.issue}</textarea>
                  <div class="invalid-feedback">Isu wajib diisi.</div>
                  <small class="text-warning fw-semibold font-monospace">Lanjutan dari ${item.meeting_number}</small>
                </td>
                <td>
                  <textarea name="items[${modalRowIndex}][discussion]" class="form-control form-control-sm" rows="2">${item.discussion || ''}</textarea>
                </td>
                <td>
                  <select name="items[${modalRowIndex}][category]" class="form-select form-select-sm">
                    ${['Breakdown & WO', 'Sparepart & Logistic', 'Manpower', 'HSE & Safety', 'Operations & Plant', 'Budget & Admin', 'General'].map(cat => `
                      <option value="${cat}" ${cat == item.category ? 'selected' : ''}>${cat}</option>
                    `).join('')}
                  </select>
                </td>
                <td>
                  <input type="text" name="items[${modalRowIndex}][pic_name]" class="form-control form-control-sm" value="${item.pic_name || ''}" placeholder="Nama PIC...">
                </td>
                <td>
                  <select name="items[${modalRowIndex}][priority]" class="form-select form-select-sm">
                    <option value="Critical" ${item.priority == 'Critical' ? 'selected' : ''}>🔴 Critical</option>
                    <option value="High" ${item.priority == 'High' ? 'selected' : ''}>🟠 High</option>
                    <option value="Medium" ${item.priority == 'Medium' ? 'selected' : ''}>🔵 Medium</option>
                    <option value="Low" ${item.priority == 'Low' ? 'selected' : ''}>⚪ Low</option>
                  </select>
                </td>
                <td>
                  <input type="date" name="items[${modalRowIndex}][due_date]" class="form-control form-control-sm" value="${item.due_date || ''}">
                </td>
                <td>
                  <select name="items[${modalRowIndex}][status]" class="form-select form-select-sm select-status">
                    <option value="Open" ${item.status == 'Open' ? 'selected' : ''}>Open</option>
                    <option value="In Progress" ${item.status == 'In Progress' ? 'selected' : ''}>In Progress</option>
                    <option value="Waiting Part" ${item.status == 'Waiting Part' ? 'selected' : ''}>Waiting Part</option>
                    <option value="Completed" ${item.status == 'Completed' ? 'selected' : ''}>Completed</option>
                    <option value="Cancelled" ${item.status == 'Cancelled' ? 'selected' : ''}>Cancelled</option>
                  </select>
                </td>
                <td>
                  <div class="input-group input-group-sm">
                    <input type="number" name="items[${modalRowIndex}][progress_percent]" class="form-control input-progress" value="${item.progress_percent || 0}" min="0" max="100">
                    <span class="input-group-text p-1">%</span>
                  </div>
                </td>
                <td class="text-center">
                  <button type="button" class="btn btn-sm btn-outline-danger btn-remove-row p-1" title="Hapus Baris">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon m-0" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="4" y1="7" x2="20" y2="7" /><line x1="10" y1="11" x2="10" y2="17" /><line x1="14" y1="11" x2="14" y2="17" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
                  </button>
                </td>
            `;
            modalTbody.appendChild(newRow);
            attachModalRowEvents(newRow);
            modalRowIndex++;
            isFormDirty = true;
        });

        reindexModalRows();
        importModal.hide();
    });
});
</script>
@endpush

@endsection
