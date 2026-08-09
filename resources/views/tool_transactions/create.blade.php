@extends('layouts.tabler')

@section('title', 'Pinjam Tool - CMMS')

@section('content')
<div class="page-header d-print-none">
  <div class="row align-items-center">
    <div class="col">
      <h2 class="page-title">
        Transaksi Pinjam Tool
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
        <form action="{{ route('tool-transactions.store') }}" method="post">
          @csrf
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
                    {{ $tool->name }} (Stok tersedia: {{ $qty }})
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
                <option value="Pinjam Sementara" {{ old('tipe_transaksi') == 'Pinjam Sementara' ? 'selected' : '' }}>Pinjam Sementara (Diingatkan untuk kembali)</option>
                <option value="Pinjam Permanen" {{ old('tipe_transaksi') == 'Pinjam Permanen' ? 'selected' : '' }}>Pinjam Permanen (Menjadi stok mekanik)</option>
              </select>
            </div>
          </div>
          
          <div class="form-footer text-end">
            <button type="submit" class="btn btn-primary">Proses Pinjam</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection