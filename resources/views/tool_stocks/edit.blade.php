@extends('layouts.tabler')
@section('title', 'Edit Stok Tool Manual')
@section('content')
<div class="page-header d-print-none">
  <div class="row align-items-center">
    <div class="col"><h2 class="page-title">Edit Kuantitas Stok</h2></div>
    <div class="col-auto ms-auto"><a href="{{ route('tool-stocks.index') }}" class="btn btn-secondary">Batal</a></div>
  </div>
</div>
<div class="row mt-3">
  <div class="col-md-6">
    <div class="card"><div class="card-body">
      <form action="{{ route('tool-stocks.update', $toolStock) }}" method="post">
          @csrf
          @method('PUT')
          <p>Tool: <strong>{{ $toolStock->tool->name ?? '-' }}</strong></p>
          <p>Lokasi: <strong>{{ $toolStock->location_type }} {{ $toolStock->location_type == 'Mechanic' ? '('.($toolStock->mechanic->nama_lengkap ?? '-').')' : '' }}</strong></p>
          
          <div class="mb-3 mt-3">
            <label class="form-label required">Kuantitas Terkini</label>
            <input type="number" class="form-control" name="quantity" min="0" required value="{{ $toolStock->quantity }}">
          </div>
          <button type="submit" class="btn btn-primary">Update Stok</button>
      </form>
    </div></div>
  </div>
</div>
@endsection