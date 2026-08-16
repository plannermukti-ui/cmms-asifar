@extends('layouts.tabler')

@section('title', 'Detail Permintaan Stok - CMMS')

@section('content')
<div class="page-header d-print-none">
  <div class="row align-items-center">
    <div class="col">
      <h2 class="page-title">
        Detail Permintaan Penambahan Stok Tool
      </h2>
    </div>
    <div class="col-auto ms-auto d-print-none">
      <a href="{{ route('tool-stock-requests.index') }}" class="btn btn-secondary">
        Kembali
      </a>
    </div>
  </div>
</div>

<div class="row row-cards mt-3">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Informasi Permintaan</h3>
            </div>
            <div class="card-body">
                <div class="datagrid">
                    <div class="datagrid-item">
                        <div class="datagrid-title">ID Request</div>
                        <div class="datagrid-content">REQ-TS-{{ str_pad($toolStockRequest->id, 4, '0', STR_PAD_LEFT) }}</div>
                    </div>
                    <div class="datagrid-item">
                        <div class="datagrid-title">Status</div>
                        <div class="datagrid-content">
                            @if($toolStockRequest->status == 'Pending')
                                <span class="badge bg-warning">Pending</span>
                            @elseif($toolStockRequest->status == 'Approved')
                                <span class="badge bg-success">Disetujui</span>
                            @else
                                <span class="badge bg-danger">Ditolak</span>
                            @endif
                        </div>
                    </div>
                    <div class="datagrid-item">
                        <div class="datagrid-title">Pemohon</div>
                        <div class="datagrid-content">{{ $toolStockRequest->requester->name ?? '-' }}</div>
                    </div>
                    <div class="datagrid-item">
                        <div class="datagrid-title">Penyetuju (Approver)</div>
                        <div class="datagrid-content">{{ $toolStockRequest->approver->name ?? '-' }}</div>
                    </div>
                    <div class="datagrid-item">
                        <div class="datagrid-title">Tanggal Pengajuan</div>
                        <div class="datagrid-content">{{ $toolStockRequest->created_at->format('d M Y H:i') }}</div>
                    </div>
                    <div class="datagrid-item">
                        <div class="datagrid-title">Catatan</div>
                        <div class="datagrid-content">{{ $toolStockRequest->notes ?? '-' }}</div>
                    </div>
                </div>
            </div>
            @if($toolStockRequest->status == 'Pending' && (auth()->id() == $toolStockRequest->approver_id || auth()->user()->hasRole('Super Admin')))
            <div class="card-footer d-flex gap-2">
                <form action="{{ route('tool-stock-requests.approve', $toolStockRequest) }}" method="POST" class="w-50" onsubmit="return confirm('Anda yakin ingin menyetujui permintaan ini? Stok akan otomatis bertambah.')">
                    @csrf
                    <button type="submit" class="btn btn-success w-100">Setujui (Approve)</button>
                </form>
                <form action="{{ route('tool-stock-requests.reject', $toolStockRequest) }}" method="POST" class="w-50" onsubmit="return confirm('Anda yakin ingin menolak permintaan ini?')">
                    @csrf
                    <button type="submit" class="btn btn-danger w-100">Tolak (Reject)</button>
                </form>
            </div>
            @endif
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Daftar Tool yang Diminta</h3>
            </div>
            <div class="table-responsive">
                <table class="table card-table table-vcenter">
                    <thead>
                        <tr>
                            <th>Tool</th>
                            <th>Lokasi</th>
                            <th>Kuantitas Diminta</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($toolStockRequest->items as $item)
                        <tr>
                            <td>{{ $item->tool->name ?? '-' }}</td>
                            <td>
                                @if($item->location_type == 'ToolRoom')
                                    Tool Room
                                @else
                                    Mekanik ({{ $item->mechanic->nama_lengkap ?? '-' }})
                                @endif
                            </td>
                            <td><strong>{{ $item->quantity }}</strong></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
