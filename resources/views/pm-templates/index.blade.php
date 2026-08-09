@extends('layouts.tabler')
@section('title', 'Daftar PM Template - CMMS')

@section('content')
<div class="page-header d-print-none">
  <div class="row align-items-center">
    <div class="col">
      <h2 class="page-title">Pengaturan PM Template</h2>
    </div>
    <div class="col-auto ms-auto d-print-none">
      <a href="{{ route('pm-templates.create') }}" class="btn btn-primary">
        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="12" y1="5" x2="12" y2="19" /><line x1="5" y1="12" x2="19" y2="12" /></svg>
        Buat Template Baru
      </a>
    </div>
  </div>
</div>

<div class="card mt-3">
  <div class="table-responsive">
    <table class="table card-table table-vcenter text-nowrap">
      <thead>
        <tr>
          <th>Site</th>
          <th>Model Unit</th>
          <th>Nama Template</th>
          <th>Tipe Interval</th>
          <th>Nilai Interval</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($templates as $template)
        <tr>
          <td>
            @if($template->site)
                <span class="badge bg-purple-lt">{{ $template->site->name }}</span>
            @else
                <span class="badge bg-secondary-lt">Global</span>
            @endif
          </td>
          <td>{{ $template->unitModel->name ?? '-' }}</td>
          <td class="fw-bold">{{ $template->name }}</td>
          <td>
            @if($template->interval_type == 'hour_meter')
                <span class="badge bg-blue-lt">Hour Meter</span>
            @elseif($template->interval_type == 'kilometer')
                <span class="badge bg-green-lt">Kilometer</span>
            @else
                <span class="badge bg-yellow-lt">Hari</span>
            @endif
          </td>
          <td>{{ $template->interval_value }}</td>
          <td>
            <a href="{{ route('pm-templates.edit', $template) }}" class="btn btn-sm btn-primary">Edit</a>
            <form action="{{ route('pm-templates.destroy', $template) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Yakin ingin menghapus template ini?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
            </form>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="5" class="text-center text-muted py-4">Belum ada data PM Template.</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($templates->hasPages())
  <div class="card-footer">
    {{ $templates->links('pagination::bootstrap-5') }}
  </div>
  @endif
</div>
@endsection
