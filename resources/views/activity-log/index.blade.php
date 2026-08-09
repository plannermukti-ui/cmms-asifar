@extends('layouts.tabler')

@section('title', 'Log Aktivitas - CMMS Aisfar')

@section('content')
<div class="page-header d-print-none">
  <div class="row align-items-center">
    <div class="col">
      <h2 class="page-title">Log Aktivitas</h2>
      <div class="text-secondary mt-1">Rekaman semua tindakan yang dilakukan pengguna di sistem.</div>
    </div>
  </div>
</div>

<div class="card mt-3">
  <div class="card-header">
    <form class="row g-2" method="GET" action="{{ route('activity-log.index') }}">
      <div class="col-md-3">
        <select name="causer_id" class="form-select">
          <option value="">-- Semua User --</option>
          @foreach ($users as $u)
            <option value="{{ $u->id }}" {{ request('causer_id') == $u->id ? 'selected' : '' }}>{{ $u->nama_lengkap }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-2">
        <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}" placeholder="Dari Tanggal">
      </div>
      <div class="col-md-2">
        <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}" placeholder="Sampai Tanggal">
      </div>
      <div class="col-md-auto">
        <button type="submit" class="btn btn-primary">Filter</button>
        <a href="{{ route('activity-log.index') }}" class="btn btn-secondary">Reset</a>
      </div>
    </form>
  </div>
  <div class="table-responsive">
    <table class="table table-vcenter card-table table-striped">
      <thead>
        <tr>
          <th>Waktu</th>
          <th>User</th>
          <th>Aksi</th>
          <th>Subjek</th>
          <th>Detail</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($activities as $activity)
        <tr>
          <td class="text-nowrap">{{ $activity->created_at->format('d/m/Y H:i') }}</td>
          <td>
            @if ($activity->causer)
              <span class="avatar avatar-xs me-2">{{ strtoupper(substr($activity->causer->nama_lengkap, 0, 1)) }}</span>
              {{ $activity->causer->nama_lengkap }}
            @else
              <span class="text-muted">System</span>
            @endif
          </td>
          <td>
            @php
              $badgeClass = match($activity->event ?? $activity->description) {
                'created' => 'bg-success',
                'updated' => 'bg-warning',
                'deleted' => 'bg-danger',
                default   => 'bg-secondary',
              };
            @endphp
            <span class="badge {{ $badgeClass }}">{{ $activity->event ?? $activity->description }}</span>
          </td>
          <td>{{ $activity->subject_type ? class_basename($activity->subject_type) : '-' }}</td>
          <td>
            @if ($activity->properties && count($activity->properties))
              <button class="btn btn-sm btn-ghost-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#log-{{ $activity->id }}">
                Detail
              </button>
              <div class="collapse mt-1" id="log-{{ $activity->id }}">
                <pre class="small bg-light p-2 rounded">{{ json_encode($activity->properties, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
              </div>
            @else
              <span class="text-muted">-</span>
            @endif
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="5" class="text-center text-muted py-4">Belum ada log aktivitas.</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  <div class="card-footer d-flex align-items-center">
    {{ $activities->links('pagination::bootstrap-5') }}
  </div>
</div>
@endsection
