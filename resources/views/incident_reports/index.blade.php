@extends('layouts.tabler')

@section('title', 'Berita Acara - CMMS')

@section('content')
<div class="page-header d-print-none">
  <div class="row align-items-center">
    <div class="col">
      <h2 class="page-title">
        Berita Acara Kerusakan / Kehilangan
      </h2>
    </div>
  </div>
</div>

<div class="row row-cards mt-3">
  <div class="col-12">
    <div class="card">
      <div class="table-responsive">
        <table class="table card-table table-vcenter text-nowrap datatable">
          <thead>
            <tr>
              <th>ID BA</th>
              <th>Tanggal</th>
              <th>Mekanik</th>
              <th>Tool</th>
              <th>Status Approval</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($reports as $report)
            <tr>
              <td>#BA-{{ str_pad($report->id, 4, '0', STR_PAD_LEFT) }}</td>
              <td>{{ \Carbon\Carbon::parse($report->created_at)->format('d M Y') }}</td>
              <td>{{ $report->mechanic->nama_lengkap ?? '-' }}</td>
              <td>{{ $report->transaction->tool->name ?? '-' }}</td>
              <td>
                @if($report->status_approval === 'Approved')
                  <span class="badge bg-success">Approved</span>
                @elseif($report->status_approval === 'Rejected')
                  <span class="badge bg-danger">Rejected</span>
                @else
                  <span class="badge bg-warning">Pending</span>
                @endif
              </td>
              <td>
                <a href="{{ route('incident-reports.show', $report) }}" class="btn btn-sm btn-info">Detail / Cetak</a>
                @can('edit_incident_reports')
                  @if($report->status_approval === 'Pending')
                  <a href="{{ route('incident-reports.edit', $report) }}" class="btn btn-sm btn-primary">Review</a>
                  @endif
                @endcan
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="6" class="text-center">Belum ada Berita Acara.</td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
      @if($reports->hasPages())
      <div class="card-footer d-flex align-items-center">
        {{ $reports->links('pagination::bootstrap-5') }}
      </div>
      @endif
    </div>
  </div>
</div>
@endsection