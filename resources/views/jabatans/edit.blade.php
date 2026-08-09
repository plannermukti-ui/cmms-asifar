@extends('layouts.tabler')

@section('title', 'Edit Jabatan - CMMS Aisfar')

@section('content')
<div class="page-header d-print-none">
  <div class="row align-items-center">
    <div class="col">
      <h2 class="page-title">Edit Jabatan</h2>
    </div>
    <div class="col-auto ms-auto d-print-none">
      <div class="btn-list">
        <a href="{{ route('jabatans.index') }}" class="btn btn-secondary">Kembali</a>
      </div>
    </div>
  </div>
</div>

<div class="row mt-3">
  <div class="col-md-6">
    <div class="card">
      <div class="card-body">
        <form action="{{ route('jabatans.update', $jabatan) }}" method="post">
          @csrf
          @method('PUT')
          <div class="mb-3">
            <label class="form-label required">Nama Jabatan</label>
            <input type="text" class="form-control @error('nama_jabatan') is-invalid @enderror" name="nama_jabatan" value="{{ old('nama_jabatan', $jabatan->nama_jabatan) }}" required>
            @error('nama_jabatan')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          <button type="submit" class="btn btn-primary">Update</button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
