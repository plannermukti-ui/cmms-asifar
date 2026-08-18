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


@php
  $currentSort = request('sort', 'next_due_hm');
  $currentDir = strtolower(request('direction', 'asc')) === 'desc' ? 'desc' : 'asc';
  $nextDir = $currentDir === 'asc' ? 'desc' : 'asc';
@endphp

<div class="card mt-2">
  <form method="GET" action="{{ route('pm-schedules.index') }}" id="filter-form">
  @if(request('sort'))
    <input type="hidden" name="sort" value="{{ request('sort') }}">
  @endif
  @if(request('direction'))
    <input type="hidden" name="direction" value="{{ request('direction') }}">
  @endif
  <div class="table-responsive">
    <table class="table card-table table-vcenter table-hover text-nowrap">
      <thead class="table-light">
        <tr>
          {{-- Nomor Unit --}}
          <th title="Unit / Nomor Unit">
            <div class="d-flex align-items-center justify-content-between">
              <a href="{{ request()->fullUrlWithQuery(['sort' => 'unit', 'direction' => $currentSort === 'unit' ? $nextDir : 'asc']) }}" class="text-reset text-decoration-none flex-grow-1">
                <span>Nomor Unit</span>
                @if($currentSort === 'unit')
                  <span class="ms-1"><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm text-primary" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/>{!! $currentDir === 'asc' ? '<path d="M6 15l6 -6l6 6" />' : '<path d="M6 9l6 6l6 -6" />' !!}</svg></span>
                @endif
              </a>
              <div class="dropdown ms-2">
                <a href="#" class="text-reset text-decoration-none" data-bs-toggle="dropdown" data-bs-auto-close="outside">
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm {{ request('unit') ? 'text-primary' : 'text-muted opacity-50' }}" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 6l16 0" /><path d="M7 12l10 0" /><path d="M10 18l4 0" /></svg>
                </a>
                <div class="dropdown-menu p-2" style="min-width: 250px;">
                  <div class="mb-2 small fw-bold text-muted">Filter Unit</div>
                  <select name="unit" class="form-select form-select-sm" onchange="document.getElementById('filter-form').submit();">
                    <option value="">Semua Unit</option>
                    @foreach($filterUnits as $u)
                      <option value="{{ $u->nomor_unit }}" {{ request('unit') == $u->nomor_unit ? 'selected' : '' }}>{{ $u->nomor_unit }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>
          </th>

          {{-- Current HM Unit --}}
          <th title="Current Hour Meter Unit">
            <div class="d-flex align-items-center justify-content-between">
              <a href="{{ request()->fullUrlWithQuery(['sort' => 'current_hm', 'direction' => $currentSort === 'current_hm' ? $nextDir : 'asc']) }}" class="text-reset text-decoration-none flex-grow-1">
                <span>Current HM Unit</span>
                @if($currentSort === 'current_hm')
                  <span class="ms-1"><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm text-primary" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/>{!! $currentDir === 'asc' ? '<path d="M6 15l6 -6l6 6" />' : '<path d="M6 9l6 6l6 -6" />' !!}</svg></span>
                @endif
              </a>
            </div>
          </th>

          {{-- Template Service Terakhir --}}
          <th title="PM Template / Nama Service">
            <div class="d-flex align-items-center justify-content-between">
              <a href="{{ request()->fullUrlWithQuery(['sort' => 'template', 'direction' => $currentSort === 'template' ? $nextDir : 'asc']) }}" class="text-reset text-decoration-none flex-grow-1">
                <span>Template Service</span>
                @if($currentSort === 'template')
                  <span class="ms-1"><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm text-primary" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/>{!! $currentDir === 'asc' ? '<path d="M6 15l6 -6l6 6" />' : '<path d="M6 9l6 6l6 -6" />' !!}</svg></span>
                @endif
              </a>
              <div class="dropdown ms-2">
                <a href="#" class="text-reset text-decoration-none" data-bs-toggle="dropdown" data-bs-auto-close="outside">
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm {{ request('template') ? 'text-primary' : 'text-muted opacity-50' }}" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 6l16 0" /><path d="M7 12l10 0" /><path d="M10 18l4 0" /></svg>
                </a>
                <div class="dropdown-menu p-2" style="min-width: 250px;">
                  <div class="mb-2 small fw-bold text-muted">Filter Template</div>
                  <select name="template" class="form-select form-select-sm" onchange="document.getElementById('filter-form').submit();">
                    <option value="">Semua Template</option>
                    @foreach($filterTemplates as $t)
                      <option value="{{ $t->name }}" {{ request('template') == $t->name ? 'selected' : '' }}>{{ $t->name }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>
          </th>

          {{-- HM Service Terakhir --}}
          <th title="Hour Meter Service Terakhir">
            <div class="d-flex align-items-center justify-content-between">
              <a href="{{ request()->fullUrlWithQuery(['sort' => 'last_hm', 'direction' => $currentSort === 'last_hm' ? $nextDir : 'asc']) }}" class="text-reset text-decoration-none flex-grow-1">
                <span>HM Terakhir</span>
                @if($currentSort === 'last_hm')
                  <span class="ms-1"><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm text-primary" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/>{!! $currentDir === 'asc' ? '<path d="M6 15l6 -6l6 6" />' : '<path d="M6 9l6 6l6 -6" />' !!}</svg></span>
                @endif
              </a>
              <div class="dropdown ms-2">
                <a href="#" class="text-reset text-decoration-none" data-bs-toggle="dropdown" data-bs-auto-close="outside">
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm {{ request('last_hm') ? 'text-primary' : 'text-muted opacity-50' }}" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 6l16 0" /><path d="M7 12l10 0" /><path d="M10 18l4 0" /></svg>
                </a>
                <div class="dropdown-menu p-2" style="min-width: 200px;">
                  <div class="mb-2 small fw-bold text-muted">Cari HM Terakhir</div>
                  <input type="text" class="form-control form-control-sm" name="last_hm" value="{{ request('last_hm') }}" placeholder="Contoh: 10250..." onchange="document.getElementById('filter-form').submit();">
                </div>
              </div>
            </div>
          </th>

          {{-- Date Service Terakhir --}}
          <th title="Tanggal Service Terakhir">
            <div class="d-flex align-items-center justify-content-between">
              <a href="{{ request()->fullUrlWithQuery(['sort' => 'last_date', 'direction' => $currentSort === 'last_date' ? $nextDir : 'asc']) }}" class="text-reset text-decoration-none flex-grow-1">
                <span>Date Terakhir</span>
                @if($currentSort === 'last_date')
                  <span class="ms-1"><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm text-primary" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/>{!! $currentDir === 'asc' ? '<path d="M6 15l6 -6l6 6" />' : '<path d="M6 9l6 6l6 -6" />' !!}</svg></span>
                @endif
              </a>
              <div class="dropdown ms-2">
                <a href="#" class="text-reset text-decoration-none" data-bs-toggle="dropdown" data-bs-auto-close="outside">
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm {{ request('last_date') ? 'text-primary' : 'text-muted opacity-50' }}" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 6l16 0" /><path d="M7 12l10 0" /><path d="M10 18l4 0" /></svg>
                </a>
                <div class="dropdown-menu p-2" style="min-width: 200px;">
                  <div class="mb-2 small fw-bold text-muted">Cari Date Terakhir</div>
                  <input type="date" class="form-control form-control-sm" name="last_date" value="{{ request('last_date') }}" onchange="document.getElementById('filter-form').submit();">
                </div>
              </div>
            </div>
          </th>

          {{-- Next Due HM --}}
          <th title="Next Due Hour Meter">
            <div class="d-flex align-items-center justify-content-between">
              <a href="{{ request()->fullUrlWithQuery(['sort' => 'next_due_hm', 'direction' => $currentSort === 'next_due_hm' ? $nextDir : 'asc']) }}" class="text-reset text-decoration-none flex-grow-1">
                <span>Next Due HM</span>
                @if($currentSort === 'next_due_hm')
                  <span class="ms-1"><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm text-primary" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/>{!! $currentDir === 'asc' ? '<path d="M6 15l6 -6l6 6" />' : '<path d="M6 9l6 6l6 -6" />' !!}</svg></span>
                @endif
              </a>
              <div class="dropdown ms-2">
                <a href="#" class="text-reset text-decoration-none" data-bs-toggle="dropdown" data-bs-auto-close="outside">
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm {{ request('next_due_hm') ? 'text-primary' : 'text-muted opacity-50' }}" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 6l16 0" /><path d="M7 12l10 0" /><path d="M10 18l4 0" /></svg>
                </a>
                <div class="dropdown-menu p-2" style="min-width: 200px;">
                  <div class="mb-2 small fw-bold text-muted">Cari Next Due HM</div>
                  <input type="text" class="form-control form-control-sm" name="next_due_hm" value="{{ request('next_due_hm') }}" placeholder="Contoh: 10500..." onchange="document.getElementById('filter-form').submit();">
                </div>
              </div>
            </div>
          </th>

          {{-- Next Due Date --}}
          <th title="Next Due Date / Tanggal Service Berikutnya">
            <div class="d-flex align-items-center justify-content-between">
              <a href="{{ request()->fullUrlWithQuery(['sort' => 'next_due_date', 'direction' => $currentSort === 'next_due_date' ? $nextDir : 'asc']) }}" class="text-reset text-decoration-none flex-grow-1">
                <span>Next Due Date</span>
                @if($currentSort === 'next_due_date')
                  <span class="ms-1"><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm text-primary" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/>{!! $currentDir === 'asc' ? '<path d="M6 15l6 -6l6 6" />' : '<path d="M6 9l6 6l6 -6" />' !!}</svg></span>
                @endif
              </a>
              <div class="dropdown ms-2">
                <a href="#" class="text-reset text-decoration-none" data-bs-toggle="dropdown" data-bs-auto-close="outside">
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm {{ request('next_due_date') ? 'text-primary' : 'text-muted opacity-50' }}" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 6l16 0" /><path d="M7 12l10 0" /><path d="M10 18l4 0" /></svg>
                </a>
                <div class="dropdown-menu p-2" style="min-width: 200px;">
                  <div class="mb-2 small fw-bold text-muted">Cari Next Due Date</div>
                  <input type="date" class="form-control form-control-sm" name="next_due_date" value="{{ request('next_due_date') }}" onchange="document.getElementById('filter-form').submit();">
                </div>
              </div>
            </div>
          </th>

          {{-- Status --}}
          <th title="Status Jadwal PM">
            <div class="d-flex align-items-center justify-content-between">
              <a href="{{ request()->fullUrlWithQuery(['sort' => 'status', 'direction' => $currentSort === 'status' ? $nextDir : 'asc']) }}" class="text-reset text-decoration-none flex-grow-1">
                <span>Status</span>
                @if($currentSort === 'status')
                  <span class="ms-1"><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm text-primary" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/>{!! $currentDir === 'asc' ? '<path d="M6 15l6 -6l6 6" />' : '<path d="M6 9l6 6l6 -6" />' !!}</svg></span>
                @endif
              </a>
              <div class="dropdown ms-2">
                <a href="#" class="text-reset text-decoration-none" data-bs-toggle="dropdown" data-bs-auto-close="outside">
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm {{ request('status') ? 'text-primary' : 'text-muted opacity-50' }}" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 6l16 0" /><path d="M7 12l10 0" /><path d="M10 18l4 0" /></svg>
                </a>
                <div class="dropdown-menu p-2" style="min-width: 200px;">
                  <div class="mb-2 small fw-bold text-muted">Filter Status</div>
                  <select class="form-select form-select-sm" name="status" onchange="document.getElementById('filter-form').submit();">
                    <option value="">Semua Status</option>
                    <option value="Upcoming" {{ request('status') == 'Upcoming' ? 'selected' : '' }}>Upcoming</option>
                    <option value="Due" {{ request('status') == 'Due' ? 'selected' : '' }}>Due</option>
                    <option value="Overdue" {{ request('status') == 'Overdue' ? 'selected' : '' }}>Overdue</option>
                  </select>
                </div>
              </div>
            </div>
          </th>

          <th class="text-end align-bottom">
            @if(request()->hasAny(['unit', 'template', 'status', 'sort', 'direction']))
              <a href="{{ route('pm-schedules.index') }}" class="btn btn-outline-secondary btn-sm mb-1" title="Reset Filters">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon m-0" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -4v4h4" /><path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4" /></svg>
              </a>
            @else
              Aksi
            @endif
          </th>
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
  </form>
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
