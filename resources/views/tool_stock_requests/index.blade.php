@extends('layouts.tabler')

@section('title', 'Approval Stok Tool - CMMS')

@section('content')
<div class="page-header d-print-none">
  <div class="row align-items-center">
    <div class="col">
      <h2 class="page-title">
        Daftar Permintaan Persetujuan Stok Tool
      </h2>
    </div>
  </div>
</div>

<div class="card mt-3">
    <div class="card-header">
        <form action="{{ route('tool-stock-requests.index') }}" method="GET" class="d-flex w-100 gap-2">
            <input type="text" name="search" class="form-control" placeholder="Cari ID / Nama..." value="{{ request('search') }}" style="max-width: 250px;">
            <select name="status" class="form-select" style="max-width: 200px;">
                <option value="">Semua Status</option>
                <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                <option value="Approved" {{ request('status') == 'Approved' ? 'selected' : '' }}>Disetujui</option>
                <option value="Rejected" {{ request('status') == 'Rejected' ? 'selected' : '' }}>Ditolak</option>
            </select>
            <button type="submit" class="btn btn-primary">Filter</button>
            @if(request()->hasAny(['search', 'status']))
                <a href="{{ route('tool-stock-requests.index') }}" class="btn btn-secondary">Reset</a>
            @endif
        </form>
    </div>
    <div class="table-responsive">
        <table class="table card-table table-vcenter text-nowrap">
            <thead>
                <tr>
                    <th>ID Request</th>
                    <th>Pemohon</th>
                    <th>Penyetuju</th>
                    <th>Tanggal Request</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($requests as $req)
                <tr>
                    <td>REQ-TS-{{ str_pad($req->id, 4, '0', STR_PAD_LEFT) }}</td>
                    <td>{{ $req->requester->nama_lengkap ?? $req->requester->name ?? '-' }}</td>
                    <td>{{ $req->approver->nama_lengkap ?? $req->approver->name ?? '-' }}</td>
                    <td>{{ $req->created_at->format('d M Y H:i') }}</td>
                    <td>
                        @if($req->status == 'Pending')
                            <span class="badge bg-warning">Pending</span>
                        @elseif($req->status == 'Approved')
                            <span class="badge bg-success">Disetujui</span>
                        @else
                            <span class="badge bg-danger">Ditolak</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('tool-stock-requests.show', $req) }}" class="btn btn-sm btn-outline-info">Lihat Detail</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center">Belum ada permintaan penambahan stok tool.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($requests->hasPages())
      <div class="card-footer d-flex align-items-center">
        {{ $requests->links('pagination::bootstrap-5') }}
      </div>
    @endif
</div>
@endsection
