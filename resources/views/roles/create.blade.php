@extends('layouts.tabler')

@section('title', 'Tambah Role - CMMS Aisfar')

@section('content')
<div class="page-header d-print-none">
  <div class="row align-items-center">
    <div class="col">
      <h2 class="page-title">Tambah Role Baru</h2>
    </div>
    <div class="col-auto ms-auto d-print-none">
      <div class="btn-list">
        <a href="{{ route('roles.index') }}" class="btn btn-secondary">Kembali</a>
      </div>
    </div>
  </div>
</div>

<div class="row mt-3">
  <div class="col-md-12">
    <form action="{{ route('roles.store') }}" method="post">
      @csrf
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Informasi Role</h3>
        </div>
        <div class="card-body">
          <div class="mb-3">
            <label class="form-label required">Nama Role</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required placeholder="Contoh: Admin, Staff, Manager">
            @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>
      </div>

      <div class="mt-3">
        @include('partials.permissions-matrix', ['selectedPermissions' => old('permissions', [])])
      </div>
      
      <div class="card mt-3">
        <div class="card-body text-end">
          <button type="submit" class="btn btn-primary">Simpan Role</button>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection
