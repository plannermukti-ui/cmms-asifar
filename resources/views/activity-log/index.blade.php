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
  <div class="card-body border-bottom py-3">
    <form class="row g-2" method="GET" action="{{ route('activity-log.index') }}">
      <div class="col-sm-6 col-lg-3">
        <select name="causer_id" class="form-select">
          <option value="">-- Semua User --</option>
          @foreach ($users as $u)
            <option value="{{ $u->id }}" {{ request('causer_id') == $u->id ? 'selected' : '' }}>{{ $u->nama_lengkap }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-sm-3 col-lg-2">
        <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}" placeholder="Dari Tanggal">
      </div>
      <div class="col-sm-3 col-lg-2">
        <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}" placeholder="Sampai Tanggal">
      </div>
      <div class="col-sm-12 col-lg-auto d-flex gap-2">
        <button type="submit" class="btn btn-primary">Filter</button>
        <a href="{{ route('activity-log.index') }}" class="btn btn-secondary">Reset</a>
      </div>
    </form>
  </div>
  <div class="table-responsive">
    <table class="table table-vcenter card-table table-hover mb-0">
      <colgroup>
        <col style="width: 18%">
        <col style="width: 25%">
        <col style="width: 21%">
        <col style="width: 18%">
        <col style="width: 18%">
      </colgroup>
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
          <td class="text-nowrap text-secondary">{{ $activity->created_at->format('d/m/Y H:i') }}</td>
          <td>
            @if ($activity->causer)
              <div class="d-flex align-items-center">
                <span class="avatar avatar-xs me-2 bg-azure-lt text-azure">{{ strtoupper(substr($activity->causer->nama_lengkap, 0, 1)) }}</span>
                <span class="fw-semibold text-truncate">{{ $activity->causer->nama_lengkap }}</span>
              </div>
            @else
              <span class="text-muted">System</span>
            @endif
          </td>
          <td>
            @php
              $event = $activity->event;
              $isAccessLog = in_array($activity->log_name, ['user_access', 'role_access']);
              $actionLabel = match($event) {
                'created' => 'Dibuat',
                'updated' => 'Diperbarui',
                'deleted' => 'Dihapus',
                default => $isAccessLog
                  ? ($activity->log_name === 'role_access' ? 'Hak Akses Role' : 'Hak Akses User')
                  : ($activity->description ?: 'Aktivitas Sistem'),
              };
              $badgeClass = match($event) {
                'created' => 'bg-success text-white',
                'updated' => 'bg-warning text-dark',
                'deleted' => 'bg-danger text-white',
                default => 'bg-secondary text-white',
              };
            @endphp
            <span class="badge d-inline-flex align-items-center text-nowrap {{ $badgeClass }}">{{ $actionLabel }}</span>
          </td>
          <td class="text-secondary">{{ $activity->subject_type ? \Illuminate\Support\Str::headline(class_basename($activity->subject_type)) : '-' }}</td>
          <td>
            @if ($activity->properties && count($activity->properties))
              <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#log-{{ $activity->id }}" aria-expanded="false">
                Lihat detail
              </button>
              <div class="collapse mt-2" id="log-{{ $activity->id }}">
                <pre class="small bg-light border p-2 rounded mb-0 text-wrap">{{ json_encode($activity->properties, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
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
