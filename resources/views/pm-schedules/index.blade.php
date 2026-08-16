@extends('layouts.tabler')
@section('title', 'Jadwal PM - CMMS')

@section('content')
<div class="page-header d-print-none">
  <div class="row align-items-center">
    <div class="col">
      <h2 class="page-title text-primary fw-bold">Jadwal Preventive Maintenance</h2>
      <div class="text-muted mt-1">Monitoring dan jadwal servis berkala unit armada</div>
    </div>
    <div class="col-auto ms-auto d-print-none">
      <div class="btn-list">
        <a href="{{ route('pm-schedules.all-history') }}" class="btn btn-outline-primary d-none d-sm-inline-block">
          <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 8l0 4l2 2" /><path d="M3.05 11a9 9 0 1 1 .5 4m-.5 5v-5h5" /></svg>
          Riwayat History Service (Global)
        </a>
      </div>
    </div>
  </div>
</div>

<div class="card mt-3">
  <div class="card-body">
    <form method="GET" action="{{ route('pm-schedules.index') }}" class="row g-2 align-items-end">
      {{-- Hidden sort parameters so filters maintain current sorting --}}
      @if(request('sort'))
        <input type="hidden" name="sort" value="{{ request('sort') }}">
      @endif
      @if(request('direction'))
        <input type="hidden" name="direction" value="{{ request('direction') }}">
      @endif

      <div class="col-md-3">
        <label class="form-label small fw-bold">Cari Unit / Template</label>
        <input type="text" class="form-control form-control-sm" name="search" value="{{ request('search') }}" placeholder="Nomor unit / template...">
      </div>

      <div class="col-md-3">
        <label class="form-label small fw-bold">Site</label>
        <select class="form-select form-select-sm" name="site_id">
          <option value="">Semua Site</option>
          @foreach($sites as $site)
            <option value="{{ $site->id }}" {{ request('site_id') == $site->id ? 'selected' : '' }}>{{ $site->name }}</option>
          @endforeach
        </select>
      </div>

      <div class="col-md-2">
        <label class="form-label small fw-bold">Status Schedule</label>
        <select class="form-select form-select-sm" name="status">
          <option value="">Semua Status</option>
          <option value="Upcoming" {{ request('status') == 'Upcoming' ? 'selected' : '' }}>Upcoming</option>
          <option value="Due" {{ request('status') == 'Due' ? 'selected' : '' }}>Due</option>
          <option value="Overdue" {{ request('status') == 'Overdue' ? 'selected' : '' }}>Overdue</option>
        </select>
      </div>

      <div class="col-md-4">
        <div class="d-flex gap-2">
          <button type="submit" class="btn btn-primary btn-sm flex-fill">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" /><path d="M21 21l-6 -6" /></svg>
            Filter
          </button>
          @if(request()->hasAny(['search', 'site_id', 'status', 'sort', 'direction']))
            <a href="{{ route('pm-schedules.index') }}" class="btn btn-outline-secondary btn-sm">
              Reset
            </a>
          @endif
        </div>
      </div>
    </form>
  </div>
</div>

@php
  $currentSort = request('sort', 'next_due_hm');
  $currentDir = strtolower(request('direction', 'asc')) === 'desc' ? 'desc' : 'asc';
  $nextDir = $currentDir === 'asc' ? 'desc' : 'asc';
@endphp

<div class="card mt-2">
  <div class="table-responsive">
    <table class="table card-table table-vcenter table-hover text-nowrap">
      <thead class="table-light">
        <tr>
          {{-- Nomor Unit --}}
          <th title="Unit / Nomor Unit">
            <a href="{{ request()->fullUrlWithQuery(['sort' => 'unit', 'direction' => $currentSort === 'unit' ? $nextDir : 'asc']) }}" class="text-reset text-decoration-none d-flex align-items-center justify-content-between">
              <span>Nomor Unit</span>
              <span class="ms-1">
                @if($currentSort === 'unit')
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm text-primary" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/>{!! $currentDir === 'asc' ? '<path d="M6 15l6 -6l6 6" />' : '<path d="M6 9l6 6l6 -6" />' !!}</svg>
                @else
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm text-muted opacity-50" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 9l4 -4l4 4m-4 -4v14" /><path d="M21 15l-4 4l-4 -4m4 4v-14" /></svg>
                @endif
              </span>
            </a>
          </th>

          {{-- Current HM Unit --}}
          <th title="Current Hour Meter Unit">
            <a href="{{ request()->fullUrlWithQuery(['sort' => 'current_hm', 'direction' => $currentSort === 'current_hm' ? $nextDir : 'asc']) }}" class="text-reset text-decoration-none d-flex align-items-center justify-content-between">
              <span>Current HM Unit</span>
              <span class="ms-1">
                @if($currentSort === 'current_hm')
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm text-primary" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/>{!! $currentDir === 'asc' ? '<path d="M6 15l6 -6l6 6" />' : '<path d="M6 9l6 6l6 -6" />' !!}</svg>
                @else
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm text-muted opacity-50" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 9l4 -4l4 4m-4 -4v14" /><path d="M21 15l-4 4l-4 -4m4 4v-14" /></svg>
                @endif
              </span>
            </a>
          </th>

          {{-- Template Service Terakhir --}}
          <th title="PM Template / Nama Service">
            <a href="{{ request()->fullUrlWithQuery(['sort' => 'template', 'direction' => $currentSort === 'template' ? $nextDir : 'asc']) }}" class="text-reset text-decoration-none d-flex align-items-center justify-content-between">
              <span>Template Service</span>
              <span class="ms-1">
                @if($currentSort === 'template')
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm text-primary" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/>{!! $currentDir === 'asc' ? '<path d="M6 15l6 -6l6 6" />' : '<path d="M6 9l6 6l6 -6" />' !!}</svg>
                @else
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm text-muted opacity-50" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 9l4 -4l4 4m-4 -4v14" /><path d="M21 15l-4 4l-4 -4m4 4v-14" /></svg>
                @endif
              </span>
            </a>
          </th>

          {{-- HM Service Terakhir --}}
          <th title="Hour Meter Service Terakhir">
            <a href="{{ request()->fullUrlWithQuery(['sort' => 'last_hm', 'direction' => $currentSort === 'last_hm' ? $nextDir : 'asc']) }}" class="text-reset text-decoration-none d-flex align-items-center justify-content-between">
              <span>HM Terakhir</span>
              <span class="ms-1">
                @if($currentSort === 'last_hm')
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm text-primary" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/>{!! $currentDir === 'asc' ? '<path d="M6 15l6 -6l6 6" />' : '<path d="M6 9l6 6l6 -6" />' !!}</svg>
                @else
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm text-muted opacity-50" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 9l4 -4l4 4m-4 -4v14" /><path d="M21 15l-4 4l-4 -4m4 4v-14" /></svg>
                @endif
              </span>
            </a>
          </th>

          {{-- Date Service Terakhir --}}
          <th title="Tanggal Service Terakhir">
            <a href="{{ request()->fullUrlWithQuery(['sort' => 'last_date', 'direction' => $currentSort === 'last_date' ? $nextDir : 'asc']) }}" class="text-reset text-decoration-none d-flex align-items-center justify-content-between">
              <span>Date Terakhir</span>
              <span class="ms-1">
                @if($currentSort === 'last_date')
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm text-primary" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/>{!! $currentDir === 'asc' ? '<path d="M6 15l6 -6l6 6" />' : '<path d="M6 9l6 6l6 -6" />' !!}</svg>
                @else
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm text-muted opacity-50" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 9l4 -4l4 4m-4 -4v14" /><path d="M21 15l-4 4l-4 -4m4 4v-14" /></svg>
                @endif
              </span>
            </a>
          </th>

          {{-- Next Due HM --}}
          <th title="Next Due Hour Meter">
            <a href="{{ request()->fullUrlWithQuery(['sort' => 'next_due_hm', 'direction' => $currentSort === 'next_due_hm' ? $nextDir : 'asc']) }}" class="text-reset text-decoration-none d-flex align-items-center justify-content-between">
              <span>Next Due HM</span>
              <span class="ms-1">
                @if($currentSort === 'next_due_hm')
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm text-primary" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/>{!! $currentDir === 'asc' ? '<path d="M6 15l6 -6l6 6" />' : '<path d="M6 9l6 6l6 -6" />' !!}</svg>
                @else
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm text-muted opacity-50" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 9l4 -4l4 4m-4 -4v14" /><path d="M21 15l-4 4l-4 -4m4 4v-14" /></svg>
                @endif
              </span>
            </a>
          </th>

          {{-- Next Due Date --}}
          <th title="Next Due Date / Tanggal Service Berikutnya">
            <a href="{{ request()->fullUrlWithQuery(['sort' => 'next_due_date', 'direction' => $currentSort === 'next_due_date' ? $nextDir : 'asc']) }}" class="text-reset text-decoration-none d-flex align-items-center justify-content-between">
              <span>Next Due Date</span>
              <span class="ms-1">
                @if($currentSort === 'next_due_date')
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm text-primary" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/>{!! $currentDir === 'asc' ? '<path d="M6 15l6 -6l6 6" />' : '<path d="M6 9l6 6l6 -6" />' !!}</svg>
                @else
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm text-muted opacity-50" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 9l4 -4l4 4m-4 -4v14" /><path d="M21 15l-4 4l-4 -4m4 4v-14" /></svg>
                @endif
              </span>
            </a>
          </th>

          {{-- Status --}}
          <th title="Status Jadwal PM">
            <a href="{{ request()->fullUrlWithQuery(['sort' => 'status', 'direction' => $currentSort === 'status' ? $nextDir : 'asc']) }}" class="text-reset text-decoration-none d-flex align-items-center justify-content-between">
              <span>Status</span>
              <span class="ms-1">
                @if($currentSort === 'status')
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm text-primary" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/>{!! $currentDir === 'asc' ? '<path d="M6 15l6 -6l6 6" />' : '<path d="M6 9l6 6l6 -6" />' !!}</svg>
                @else
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm text-muted opacity-50" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 9l4 -4l4 4m-4 -4v14" /><path d="M21 15l-4 4l-4 -4m4 4v-14" /></svg>
                @endif
              </span>
            </a>
          </th>

          <th class="text-end">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($schedules as $schedule)
        @php
          $dueDate = $schedule->next_due_date ? \Carbon\Carbon::parse($schedule->next_due_date) : $schedule->estimated_next_due_date;
          $isEstimated = empty($schedule->next_due_date) && !empty($schedule->estimated_next_due_date);
          $scheduleStatus = $schedule->status;
        @endphp
        <tr>
          <td class="fw-bold text-primary">{{ $schedule->masterUnit->nomor_unit ?? '-' }}</td>
          <td>
            <span class="badge bg-secondary-lt fw-semibold">
              {{ $schedule->masterUnit->latestHourMeter ? number_format($schedule->masterUnit->latestHourMeter->hm, 1) : 'N/A' }}
            </span>
          </td>
          <td>{{ $schedule->pmTemplate->name ?? '-' }}</td>
          <td>
            @if($schedule->last_executed_value)
              <span class="badge bg-info-lt fw-bold">{{ number_format($schedule->last_executed_value, 1) }}</span>
            @else
              <span class="text-muted small">No History</span>
            @endif
          </td>
          <td>
            @if($schedule->latestHistory)
              {{ \Carbon\Carbon::parse($schedule->latestHistory->executed_at)->format('d M Y') }}
            @else
              <span class="text-muted small">No History</span>
            @endif
          </td>
          <td class="text-danger fw-bold">{{ number_format($schedule->next_due_value, 1) }}</td>
          <td>
            @if($dueDate)
              <span class="fw-bold {{ $scheduleStatus === 'Overdue' ? 'text-danger' : ($scheduleStatus === 'Due' ? 'text-warning' : 'text-primary') }}">
                {{ $dueDate->format('d M Y') }}
              </span>
              @if($isEstimated)
                <span class="badge bg-secondary-lt text-xs ms-1" title="Estimasi berdasarkan rata-rata jam operasional">Est</span>
              @endif
            @else
              <span class="text-muted small">N/A</span>
            @endif
          </td>
          <td>
            @if($scheduleStatus == 'Upcoming')
                <span class="badge bg-blue-lt">Upcoming</span>
            @elseif($scheduleStatus == 'Due')
                <span class="badge bg-warning-lt">Due</span>
            @elseif($scheduleStatus == 'Overdue')
                <span class="badge bg-red-lt">Overdue</span>
            @else
                <span class="badge bg-green-lt">{{ $scheduleStatus }}</span>
            @endif
          </td>
          <td class="text-end">
            <div class="btn-list justify-content-end flex-nowrap">
              <form action="{{ route('pm-schedules.generate-wo', $schedule) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Generate Work Order untuk jadwal ini?');">
                  @csrf
                  <button type="submit" class="btn btn-sm btn-success">
                      <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-tool" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 10h3v-3l-3.5 -3.5a6 6 0 0 1 8 8l6 6a2 2 0 0 1 -3 3l-6 -6a6 6 0 0 1 -8 -8l3.5 3.5" /></svg>
                      Generate WO
                  </button>
              </form>
              <a href="{{ route('pm-schedules.history', $schedule) }}" class="btn btn-sm btn-outline-info">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon m-0" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 8l0 4l2 2" /><path d="M3.05 11a9 9 0 1 1 .5 4m-.5 5v-5h5" /></svg> History
              </a>
            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="9" class="text-center text-muted py-4">Belum ada jadwal PM yang cocok dengan filter.</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($schedules->hasPages())
  <div class="card-footer d-flex align-items-center justify-content-between">
    <div class="text-muted small">
      Menampilkan {{ $schedules->firstItem() ?? 0 }} - {{ $schedules->lastItem() ?? 0 }} dari {{ $schedules->total() }} data
    </div>
    {{ $schedules->links('pagination::bootstrap-5') }}
  </div>
  @endif
</div>
@endsection
