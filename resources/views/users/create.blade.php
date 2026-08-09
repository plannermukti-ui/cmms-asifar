@extends('layouts.tabler')

@section('title', 'Tambah User - CMMS Aisfar')

@section('content')
<div class="page-header d-print-none">
  <div class="row align-items-center">
    <div class="col">
      <h2 class="page-title">
        Tambah User
      </h2>
    </div>
    <div class="col-auto ms-auto d-print-none">
      <div class="btn-list">
        <a href="{{ route('users.index') }}" class="btn btn-secondary d-none d-sm-inline-block">
          Kembali
        </a>
      </div>
    </div>
  </div>
</div>

<div class="row row-cards mt-3">
  <div class="col-12">
    <div class="card">
      <div class="card-body">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('users.store') }}" method="post">
          @csrf
          <div class="row">
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label required">NIK</label>
                <input type="text" class="form-control" name="nik" value="{{ old('nik') }}" required>
              </div>
              <div class="mb-3">
                <label class="form-label required">Nama Lengkap</label>
                <input type="text" class="form-control" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required>
              </div>
              <div class="mb-3">
                <label class="form-label required">Email</label>
                <input type="email" class="form-control" name="email" value="{{ old('email') }}" required>
              </div>
              <div class="mb-3">
                <label class="form-label required">No WhatsApp</label>
                <input type="text" class="form-control" name="no_whatsapp" value="{{ old('no_whatsapp') }}" required>
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label">Departemen</label>
                <select name="department_id" class="form-select">
                  <option value="">-- Pilih Departemen --</option>
                  @foreach($departments as $dept)
                  <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->nama_department }}</option>
                  @endforeach
                </select>
              </div>
              <div class="mb-3">
                <label class="form-label">Jabatan</label>
                <select name="jabatan_id" class="form-select">
                  <option value="">-- Pilih Jabatan --</option>
                  @foreach($jabatans as $jab)
                  <option value="{{ $jab->id }}" {{ old('jabatan_id') == $jab->id ? 'selected' : '' }}>{{ $jab->nama_jabatan }}</option>
                  @endforeach
                </select>
              </div>
              <div class="mb-3">
                <label class="form-label">Site (Cabang)</label>
                <select name="site_id" class="form-select">
                  <option value="">-- Akses Semua Site (Superadmin) --</option>
                  @foreach($sites as $site)
                  <option value="{{ $site->id }}" {{ old('site_id') == $site->id ? 'selected' : '' }}>{{ $site->name }} ({{ $site->code }})</option>
                  @endforeach
                </select>
                <small class="form-hint">Kosongkan jika user ini adalah Superadmin (Head Office).</small>
              </div>
              <div class="mb-3">
                <label class="form-label required">Role</label>
                <select name="role" class="form-select" required>
                  <option value="">-- Pilih Role --</option>
                  @foreach($roles as $role)
                  <option value="{{ $role->name }}" {{ old('role') == $role->name ? 'selected' : '' }}>{{ $role->name }}</option>
                  @endforeach
                </select>
              </div>
              <div class="mb-3">
                <label class="form-label required">Status</label>
                <select name="status" class="form-select" required>
                  <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                  <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                  <option value="rejected" {{ old('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
              </div>
            </div>
          </div>
          
          <hr>
          
          <div class="row">
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label required">Password</label>
                <input type="password" class="form-control" name="password" required>
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label required">Konfirmasi Password</label>
                <input type="password" class="form-control" name="password_confirmation" required>
              </div>
            </div>
          </div>
          
          <div class="mt-4">
            <button type="submit" class="btn btn-primary">Simpan User</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
