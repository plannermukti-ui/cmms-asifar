@extends('layouts.tabler')

@section('title', 'Peminjaman Tool - CMMS')

@section('content')
<style>
  [data-bs-theme="dark"] .modal-content {
    background-color: #182234 !important;
    border-color: rgba(255, 255, 255, 0.08) !important;
    color: #f1f5f9 !important;
  }
  [data-bs-theme="dark"] .modal-header,
  [data-bs-theme="dark"] .modal-footer {
    border-color: rgba(255, 255, 255, 0.08) !important;
  }
  [data-bs-theme="dark"] .bg-blue-lt {
    background-color: rgba(32, 107, 196, 0.15) !important;
    color: #f1f5f9 !important;
    border: 1px solid rgba(32, 107, 196, 0.3) !important;
  }
  [data-bs-theme="dark"] .bg-blue-lt .text-body,
  [data-bs-theme="dark"] .bg-blue-lt .fw-bold {
    color: #f1f5f9 !important;
  }
  [data-bs-theme="dark"] .bg-blue-lt .text-muted {
    color: #94a3b8 !important;
  }
  [data-bs-theme="dark"] .form-control,
  [data-bs-theme="dark"] .form-select {
    background-color: #131c2c !important;
    border-color: rgba(255, 255, 255, 0.12) !important;
    color: #f1f5f9 !important;
  }
  [data-bs-theme="dark"] .form-label {
    color: #cbd5e1 !important;
  }
  [data-bs-theme="dark"] .form-hint {
    color: #94a3b8 !important;
  }
</style>

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
                <div class="btn-list flex-nowrap">
                  @if($trx->status === 'Borrowed')
                      @can('edit_tool_transactions')
                      <button type="button" class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#modal-kembali-tool-{{ $trx->id }}">
                        Proses Kembali
                      </button>
                      @endcan
                  @endif
                  @can('delete_tool_transactions')
                  <form action="{{ route('tool-transactions.destroy', $trx) }}" method="post" class="d-inline" onsubmit="return confirm('Yakin menghapus transaksi ini?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                  </form>
                  @endcan
                </div>
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
            
            <div class="col-12 mt-2 mb-2">
              <hr>
              <strong>Referensi Penggunaan (Opsional)</strong>
            </div>

            <div class="col-md-6 mb-3">
              <label class="form-label">No Workorder (Open / In Progress)</label>
              <select id="workorder_select" name="wo_id" class="form-select">
                <option value="">-- Tidak Terkait Workorder --</option>
                @foreach($openWorkOrders as $wo)
                  <option value="{{ $wo->id }}">{{ $wo->no_wo }} - {{ $wo->unit->nomor_unit ?? '' }}</option>
                @endforeach
              </select>
            </div>
            
            <div class="col-md-6 mb-3">
              <label class="form-label">Pilih Subtask</label>
              <select name="wo_subtask_id" id="subtask_select" class="form-select" disabled>
                <option value="">-- Pilih Workorder Terlebih Dahulu --</option>
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

{{-- Modals for Return Process (Pop-up) --}}
@foreach($transactions as $trx)
@if($trx->status === 'Borrowed')
<div class="modal modal-blur fade" id="modal-kembali-tool-{{ $trx->id }}" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-rotate-2 text-success me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M15 4.55a8 8 0 0 0 -6 14.9" /><path d="M9 15v5h5" /></svg>
          Proses Pengembalian Tool #TRX-{{ str_pad($trx->id, 4, '0', STR_PAD_LEFT) }}
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('tool-transactions.update', $trx) }}" method="post">
        @csrf
        @method('PUT')
        <div class="modal-body">
          <div class="mb-3 p-3 rounded bg-blue-lt border border-blue-subtle">
            <div class="row g-2">
              <div class="col-sm-6">
                <div class="text-muted small">Mekanik Peminjam:</div>
                <div class="fw-bold">{{ $trx->mechanic->nama_lengkap ?? '-' }}</div>
              </div>
              <div class="col-sm-6">
                <div class="text-muted small">Nama Tool:</div>
                <div class="fw-bold">{{ $trx->tool->name ?? '-' }}</div>
              </div>
              <div class="col-sm-6">
                <div class="text-muted small">Waktu Pinjam:</div>
                <div class="fw-bold">{{ \Carbon\Carbon::parse($trx->tanggal_pinjam)->format('d M Y H:i') }}</div>
              </div>
              <div class="col-sm-6">
                <div class="text-muted small">Jumlah Dipinjam:</div>
                <div><span class="badge bg-primary px-2 py-1">{{ $trx->borrow_qty }} Unit</span></div>
              </div>
            </div>
          </div>
          
          <div class="row g-3">
            <div class="col-md-4">
              <label class="form-label required">Kembali (Kondisi Baik)</label>
              <input type="number" class="form-control" name="returned_good_qty" value="{{ old('returned_good_qty', $trx->borrow_qty) }}" min="0" required>
            </div>
            <div class="col-md-4">
              <label class="form-label required">Kembali (Kondisi Rusak)</label>
              <input type="number" class="form-control" name="returned_broken_qty" value="{{ old('returned_broken_qty', 0) }}" min="0" required>
            </div>
            <div class="col-md-4">
              <label class="form-label required">Hilang</label>
              <input type="number" class="form-control" name="returned_lost_qty" value="{{ old('returned_lost_qty', 0) }}" min="0" required>
            </div>
            <div class="col-12">
              <label class="form-label">Catatan Pengembalian / Kronologi Kerusakan (jika ada)</label>
              <textarea class="form-control" name="catatan" rows="3" placeholder="Tuliskan kronologi jika ada alat rusak atau hilang...">{{ old('catatan') }}</textarea>
              <small class="form-hint text-muted">Jika terdapat alat rusak atau hilang, sistem akan otomatis membuat Berita Acara (B.A) dengan status Pending.</small>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn me-auto" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-success">Selesaikan Pengembalian</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endif
@endforeach
@endsection

@section('scripts')
<script>
  // Data Workorder dan Subtask dalam bentuk JSON
  const openWorkOrders = @json($openWorkOrders);

  document.getElementById('workorder_select').addEventListener('change', function() {
      const woId = this.value;
      const subtaskSelect = document.getElementById('subtask_select');
      
      // Bersihkan opsi saat ini
      subtaskSelect.innerHTML = '<option value="">-- Pilih Subtask --</option>';
      
      if (!woId) {
          subtaskSelect.disabled = true;
          return;
      }
      
      // Cari Workorder yang dipilih
      const selectedWo = openWorkOrders.find(wo => wo.id == woId);
      
      if (selectedWo && selectedWo.tasks && selectedWo.tasks.length > 0) {
          subtaskSelect.disabled = false;
          let hasSubtasks = false;
          
          selectedWo.tasks.forEach(function(task) {
              if (task.subtasks && task.subtasks.length > 0) {
                  hasSubtasks = true;
                  task.subtasks.forEach(function(subtask) {
                      const option = document.createElement('option');
                      option.value = subtask.id;
                      option.textContent = subtask.action;
                      subtaskSelect.appendChild(option);
                  });
              }
          });
          
          if (!hasSubtasks) {
              subtaskSelect.innerHTML = '<option value="">-- Workorder ini belum memiliki subtask --</option>';
              subtaskSelect.disabled = true;
          }
      } else {
          subtaskSelect.innerHTML = '<option value="">-- Workorder ini tidak memiliki subtask --</option>';
          subtaskSelect.disabled = true;
      }
  });
</script>
@endsection