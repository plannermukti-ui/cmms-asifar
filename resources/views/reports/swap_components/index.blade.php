@extends('layouts.tabler')

@section('title', 'Swap Component Report')

@section('content')
<div class="page-header d-print-none">
  <div class="container-xl">
    <div class="row g-2 align-items-center">
      <div class="col">
        <h2 class="page-title text-purple fw-bold">
          <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-replace me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 3m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" /><path d="M15 15m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" /><path d="M21 11v-3a2 2 0 0 0 -2 -2h-6l3 3m0 -6l-3 3" /><path d="M3 13v3a2 2 0 0 0 2 2h6l-3 -3m0 6l3 -3" /></svg>
          Swap Component Report
        </h2>
        <div class="text-muted mt-1">Daftar komponen yang berstatus Swap / Canibal pada Work Order.</div>
      </div>
    </div>
  </div>
</div>

<div class="page-body">
  <div class="container-xl">
    @if(session('success'))
      <div class="alert alert-success alert-dismissible" role="alert">
        {{ session('success') }}
        <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
      </div>
    @endif

    <div class="card shadow-sm border-0">
      <div class="card-header bg-light">
        <h3 class="card-title">Filter Laporan</h3>
      </div>
      <div class="card-body p-3">
        <form method="GET" action="{{ route('swap-components.index') }}" class="row g-2 align-items-end">
          <div class="col-md-3">
            <label class="form-label small">Tgl Awal (Waktu BD)</label>
            <input type="date" class="form-control form-control-sm" name="start_date" value="{{ request('start_date') }}">
          </div>
          <div class="col-md-3">
            <label class="form-label small">Tgl Akhir (Waktu BD)</label>
            <input type="date" class="form-control form-control-sm" name="end_date" value="{{ request('end_date') }}">
          </div>
          <div class="col-md-3">
            <label class="form-label small">Status Swap</label>
            <select class="form-select form-select-sm" name="status">
              <option value="">Semua Status</option>
              <option value="Waiting Part" {{ request('status') == 'Waiting Part' ? 'selected' : '' }}>Waiting Part</option>
              <option value="Completed" {{ request('status') == 'Completed' ? 'selected' : '' }}>Completed</option>
              <option value="Cancel" {{ request('status') == 'Cancel' ? 'selected' : '' }}>Cancel</option>
            </select>
          </div>
          <div class="col-md-3">
            <button type="submit" class="btn btn-primary btn-sm w-100">Cari / Filter</button>
          </div>
        </form>
      </div>
    </div>

    <div class="card mt-3 shadow-sm border-0">
      <div class="table-responsive">
        <table class="table table-vcenter card-table table-striped table-hover small">
          <thead>
            <tr>
              <th>No WO</th>
              <th>Part Terpakai (Swap)</th>
              <th>Tipe Swap</th>
              <th>Unit Tujuan/Asal</th>
              <th>PR/MOL</th>
              <th>Remarks</th>
              <th>Status</th>
              <th class="w-1 text-center">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($swaps as $swap)
              @php $wo = $swap->subtask->task->workOrder ?? null; @endphp
              <tr>
                <td>
                  @if($wo)
                    <a href="{{ route('work-orders.show', $wo) }}" class="fw-bold">{{ $wo->no_wo }}</a><br>
                    <span class="text-muted">{{ $wo->unit->nomor_unit ?? '-' }}</span>
                  @else
                    -
                  @endif
                </td>
                <td>
                  <span class="fw-bold">{{ $swap->part->part_number ?? '-' }}</span><br>
                  <span class="text-muted">{{ $swap->part->part_description ?? '-' }}</span>
                  <div class="mt-1"><span class="badge bg-purple-lt">Qty: {{ $swap->qty }} {{ $swap->satuan }}</span></div>
                </td>
                <td>{{ $swap->swap_type ?? '-' }}</td>
                <td>
                  @if($swap->swapUnit)
                    <span class="badge bg-info-lt fw-bold fs-6">{{ $swap->swapUnit->nomor_unit }}</span>
                  @else
                    -
                  @endif
                </td>
                <td>{{ $swap->mol_pr ?? '-' }}</td>
                <td>{{ $swap->swap_remarks ?? '-' }}</td>
                <td>
                  @if($swap->swap_status == 'Waiting Part')
                    <span class="badge bg-warning">{{ $swap->swap_status }}</span>
                  @elseif($swap->swap_status == 'Completed')
                    <span class="badge bg-success">{{ $swap->swap_status }}</span>
                  @elseif($swap->swap_status == 'Cancel')
                    <span class="badge bg-danger">{{ $swap->swap_status }}</span>
                  @else
                    <span class="badge bg-secondary">{{ $swap->swap_status ?? 'Open' }}</span>
                  @endif
                </td>
                <td>
                  <div class="d-flex gap-1">
                    <a href="{{ route('swap-components.edit', $swap->id) }}" class="btn btn-sm btn-outline-primary" title="Edit Swap Data">Edit</a>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="8" class="text-center text-muted py-4">Belum ada data Swap Component yang dicatat.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
      @if($swaps->hasPages())
        <div class="card-footer d-flex align-items-center justify-content-center p-2">
          {{ $swaps->links('pagination::bootstrap-5') }}
        </div>
      @endif
    </div>
  </div>
</div>
@endsection
