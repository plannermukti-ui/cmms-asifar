@extends('layouts.tabler')

@section('title', 'Master Data - CMMS Aisfar')

@section('content')
<div class="page-header d-print-none">
  <div class="row align-items-center">
    <div class="col">
      <h2 class="page-title">
        Master Data
      </h2>
    </div>
  </div>
</div>

<div class="row row-cards">
  @can('view_departments')
  <div class="col-md-4">
    <div class="card">
      <div class="card-body">
        <h3 class="card-title">Departemen</h3>
        <p class="text-secondary">Kelola daftar departemen yang ada di perusahaan.</p>
        <a href="{{ route('departments.index') }}" class="btn btn-primary w-100">Kelola Departemen</a>
      </div>
    </div>
  </div>
  @endcan

  @can('view_jabatans')
  <div class="col-md-4">
    <div class="card">
      <div class="card-body">
        <h3 class="card-title">Jabatan</h3>
        <p class="text-secondary">Kelola daftar jabatan yang tersedia untuk karyawan.</p>
        <a href="{{ route('jabatans.index') }}" class="btn btn-primary w-100">Kelola Jabatan</a>
      </div>
    </div>
  </div>
  @endcan

  @can('view_roles')
  <div class="col-md-4">
    <div class="card">
      <div class="card-body">
        <h3 class="card-title">Role / Akses</h3>
        <p class="text-secondary">Kelola tingkat hak akses pengguna aplikasi.</p>
        <a href="{{ route('roles.index') }}" class="btn btn-primary w-100">Kelola Role</a>
      </div>
    </div>
  </div>
  @endcan

  @can('view_modules')
  <div class="col-md-4">
    <div class="card">
      <div class="card-body">
        <h3 class="card-title">Modul Aplikasi</h3>
        <p class="text-secondary">Kelola modul-modul resmi yang tersedia di aplikasi.</p>
        <a href="{{ route('modules.index') }}" class="btn btn-primary w-100">Kelola Modul</a>
      </div>
    </div>
  </div>
  @endcan
</div>
@endsection
