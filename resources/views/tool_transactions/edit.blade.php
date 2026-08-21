@extends('layouts.tabler')

@section('title', 'Pengembalian Tool - CMMS')

@section('content')
<style>
  [data-bs-theme="dark"] .bg-blue-lt {
    background-color: rgba(32, 107, 196, 0.15) !important;
    color: #f1f5f9 !important;
    border: 1px solid rgba(32, 107, 196, 0.3) !important;
  }
  [data-bs-theme="dark"] .bg-blue-lt strong,
  [data-bs-theme="dark"] .bg-blue-lt span,
  [data-bs-theme="dark"] .bg-blue-lt div {
    color: #f1f5f9 !important;
  }
  [data-bs-theme="dark"] .form-control {
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
        Proses Pengembalian Tool
      </h2>
    </div>
    <div class="col-auto ms-auto d-print-none">
      <a href="{{ route('tool-transactions.index') }}" class="btn btn-secondary">
        Batal
      </a>
    </div>
  </div>
</div>

<div class="row mt-3">
  <div class="col-md-8">
    <div class="card">
      <div class="card-body">
        <div class="mb-4 bg-blue-lt p-3 rounded">
            <div class="row g-2">
              <div class="col-sm-6">
                <span class="text-muted small d-block">Mekanik:</span>
                <strong>{{ $toolTransaction->mechanic->nama_lengkap ?? '-' }}</strong>
              </div>
              <div class="col-sm-6">
                <span class="text-muted small d-block">Tool:</span>
                <strong>{{ $toolTransaction->tool->name ?? '-' }}</strong>
              </div>
              <div class="col-sm-6">
                <span class="text-muted small d-block">Waktu Pinjam:</span>
                <strong>{{ \Carbon\Carbon::parse($toolTransaction->tanggal_pinjam)->format('d M Y H:i') }}</strong>
              </div>
              <div class="col-sm-6">
                <span class="text-muted small d-block">Jumlah Dipinjam:</span>
                <span class="badge bg-primary px-2 py-1">{{ $toolTransaction->borrow_qty }} Unit</span>
              </div>
            </div>
        </div>
        
        <form action="{{ route('tool-transactions.update', $toolTransaction) }}" method="post">
          @csrf
          @method('PUT')
          
          <div class="row">
            <div class="col-md-4 mb-3">
              <label class="form-label required">Kembali (Kondisi Baik)</label>
              <input type="number" class="form-control" name="returned_good_qty" value="{{ old('returned_good_qty', $toolTransaction->borrow_qty) }}" min="0" required>
            </div>
            <div class="col-md-4 mb-3">
              <label class="form-label required">Kembali (Kondisi Rusak)</label>
              <input type="number" class="form-control" name="returned_broken_qty" value="{{ old('returned_broken_qty', 0) }}" min="0" required>
            </div>
            <div class="col-md-4 mb-3">
              <label class="form-label required">Hilang</label>
              <input type="number" class="form-control" name="returned_lost_qty" value="{{ old('returned_lost_qty', 0) }}" min="0" required>
            </div>
            <div class="col-12 mb-3">
              <label class="form-label">Catatan Pengembalian / Kronologi Kerusakan (jika ada)</label>
              <textarea class="form-control" name="catatan" rows="3">{{ old('catatan') }}</textarea>
              <small class="form-hint">Jika terdapat alat rusak atau hilang, sistem akan otomatis membuat Berita Acara (B.A) dengan status Pending.</small>
            </div>
          </div>
          
          <div class="form-footer text-end">
            <button type="submit" class="btn btn-success">Selesaikan Pengembalian</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection