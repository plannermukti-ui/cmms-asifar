@extends('layouts.tabler')
@section('title', 'Jadwal PM - CMMS')

@section('content')
<div class="page-header d-print-none">
  <div class="row align-items-center">
    <div class="col">
      <h2 class="page-title">Jadwal Preventive Maintenance</h2>
    </div>
  </div>
</div>

<div class="card mt-3">
  <div class="card-body">
    <form method="GET" class="row g-2 align-items-end">
      <div class="col-md-3">
        <label class="form-label">Cari Nomor Unit</label>
        <input type="text" class="form-control form-control-sm" name="search" value="{{ request('search') }}" placeholder="Nomor Unit...">
      </div>
      <div class="col-md-3">
        <label class="form-label">Status Schedule</label>
        <select class="form-select form-select-sm" name="status">
          <option value="">Semua</option>
          <option value="Upcoming" {{ request('status') == 'Upcoming' ? 'selected' : '' }}>Upcoming</option>
          <option value="Overdue" {{ request('status') == 'Overdue' ? 'selected' : '' }}>Overdue</option>
          <option value="Completed" {{ request('status') == 'Completed' ? 'selected' : '' }}>Completed</option>
        </select>
      </div>
      <div class="col-md-2">
        <button type="submit" class="btn btn-secondary btn-sm w-100">Filter</button>
      </div>
    </form>
  </div>
</div>

<div class="card mt-2">
  <div class="table-responsive">
    <table class="table card-table table-vcenter text-nowrap">
      <thead>
        <tr>
          <th>Nomor Unit</th>
          <th>Template Service</th>
          <th>Last Executed</th>
          <th>Next Due</th>
          <th>Status</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($schedules as $schedule)
        <tr>
          <td class="fw-bold">{{ $schedule->masterUnit->nomor_unit ?? '-' }}</td>
          <td>{{ $schedule->pmTemplate->name ?? '-' }}</td>
          <td>{{ $schedule->last_executed_value ?? '-' }}</td>
          <td class="fw-bold text-primary">{{ $schedule->next_due_value }}</td>
          <td>
            @if($schedule->status == 'Upcoming')
                <span class="badge bg-blue-lt">Upcoming</span>
            @elseif($schedule->status == 'Overdue')
                <span class="badge bg-red-lt">Overdue</span>
            @else
                <span class="badge bg-green-lt">Completed</span>
            @endif
          </td>
          <td>
            @if($schedule->status != 'Completed')
                <form action="{{ route('pm-schedules.generate-wo', $schedule) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Generate Work Order untuk jadwal ini?');">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-success">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-tool" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 10h3v-3l-3.5 -3.5a6 6 0 0 1 8 8l6 6a2 2 0 0 1 -3 3l-6 -6a6 6 0 0 1 -8 -8l3.5 3.5" /></svg>
                        Generate WO
                    </button>
                </form>
            @else
                <span class="text-muted small">WO sudah terbit/selesai</span>
            @endif
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="6" class="text-center text-muted py-4">Belum ada jadwal PM.</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($schedules->hasPages())
  <div class="card-footer">
    {{ $schedules->links('pagination::bootstrap-5') }}
  </div>
  @endif
</div>
@endsection
