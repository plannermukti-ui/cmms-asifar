@extends('layouts.tabler')

@section('title', 'Tambah Departemen - CMMS Aisfar')

@section('content')
<div class="page-header d-print-none">
  <div class="row align-items-center">
    <div class="col">
      <h2 class="page-title">Tambah Departemen</h2>
    </div>
    <div class="col-auto ms-auto d-print-none">
      <div class="btn-list">
        <a href="{{ route('departments.index') }}" class="btn btn-secondary">Kembali</a>
      </div>
    </div>
  </div>
</div>

<div class="row mt-3">
  <div class="col-md-6">
    <div class="card">
      <div class="card-body">
        <form action="{{ route('departments.store') }}" method="post">
          @csrf
          <div class="mb-3">
            <label class="form-label required">Nama Departemen</label>
            <input type="text" class="form-control @error('nama_department') is-invalid @enderror" name="nama_department" value="{{ old('nama_department') }}" required>
            @error('nama_department')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
