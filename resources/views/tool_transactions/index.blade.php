@extends('layouts.tabler')

@section('title', 'Peminjaman Tool - CMMS')

@section('content')
<div class="page-header d-print-none">
  <div class="row align-items-center">
    <div class="col">
      <h2 class="page-title">
        Peminjaman Tool (Kasir)
      </h2>
    </div>
    <div class="col-auto ms-auto d-print-none">
      <div class="btn-list">
        @can('create_tool_transactions')
        <a href="#" class="btn btn-primary d-none d-sm-inline-block" data-bs-toggle="modal" data-bs-target="#modal-pinjam-tool">
          <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="12" y1="5" x2="12" y2="19" /><line x1="5" y1="12" x2="19" y2="12" /></svg>
          Pinjam Tool
        </a>
        @endcan
      </div>
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
              <th>ID</th>
              <th>Waktu Pinjam</th>
              <th>Mekanik</th>
              <th>Tool</th>
              <th>Jumlah</th>
              <th>Tipe</th>
              <th>Status</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($transactions as $trx)
            <tr>
              <td>{{ $trx->id }}</td>
              <td>{{ \Carbon\Carbon::parse($trx->tanggal_pinjam)->format('d M Y H:i') }}</td>
              <td>{{ $trx->mechanic->nama_lengkap ?? '-' }}</td>
              <td class="fw-bold">{{ $trx->tool->name ?? '-' }}</td>
              <td>{{ $trx->borrow_qty }}</td>
              <td>{{ $trx->tipe_transaksi }}</td>
              <td>
                @if($trx->status === 'Borrowed')
                  <span class="badge bg-warning">Dipinjam</span>
                @else
                  <span class="badge bg-success">Dikembalikan</span><br>
                  <small class="text-muted">{{ \Carbon\Carbon::parse($trx->tanggal_kembali)->format('d M Y H:i') }}</small>
                @endif
              </td>
              <td>
                @if($trx->status === 'Borrowed')
                    @can('edit_tool_transactions')
                    <a href="{{ route('tool-transactions.edit', $trx) }}" class="btn btn-sm btn-success">Proses Kembali</a>
                    @endcan
                @endif
                @can('delete_tool_transactions')
                <form action="{{ route('tool-transactions.destroy', $trx) }}" method="post" class="d-inline" onsubmit="return confirm('Yakin menghapus transaksi ini?');">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                </form>
                @endcan
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="8" class="text-center">Belum ada data transaksi.</td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
      @if($transactions->hasPages())
      <div class="card-footer d-flex align-items-center">
        {{ $transactions->links('pagination::bootstrap-5') }}
      </div>
      @endif
    </div>
  </div>
</div>
<div class="modal modal-blur fade" id="modal-pinjam-tool" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Transaksi Pinjam Tool</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('tool-transactions.store') }}" method="post">
        @csrf
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label required">Mekanik Peminjam</label>
              <select name="mechanic_id" class="form-select" required>
                <option value="">-- Pilih Mekanik --</option>
                @foreach($mechanics as $mechanic)
                  <option value="{{ $mechanic->id }}" {{ old('mechanic_id') == $mechanic->id ? 'selected' : '' }}>{{ $mechanic->nama_lengkap }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label required">Tool (Pilih dari stok ToolRoom)</label>
              <select name="tool_id" class="form-select" required>
                <option value="">-- Pilih Tool --</option>
                @foreach($tools as $tool)
                  @php
                    $qty = $tool->stocks->where('location_type', 'ToolRoom')->first()->quantity ?? 0;
                  @endphp
                  <option value="{{ $tool->id }}" {{ old('tool_id') == $tool->id ? 'selected' : '' }}>
                    {{ $tool->name }} (Stok: {{ $qty }})
                  </option>
                @endforeach
              </select>
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label required">Jumlah Pinjam</label>
              <input type="number" class="form-control" name="borrow_qty" value="{{ old('borrow_qty', 1) }}" min="1" required>
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label required">Tipe Transaksi</label>
              <select name="tipe_transaksi" class="form-select" required>
                <option value="Pinjam Sementara" {{ old('tipe_transaksi') == 'Pinjam Sementara' ? 'selected' : '' }}>Pinjam Sementara</option>
                <option value="Pinjam Permanen" {{ old('tipe_transaksi') == 'Pinjam Permanen' ? 'selected' : '' }}>Pinjam Permanen</option>
              </select>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn me-auto" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Proses Pinjam</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection