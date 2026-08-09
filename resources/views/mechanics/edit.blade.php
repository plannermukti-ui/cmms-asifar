@extends('layouts.tabler')

@section('title', 'Edit Mekanik - CMMS')

@section('content')
<div class="page-header d-print-none">
  <div class="row align-items-center">
    <div class="col">
      <h2 class="page-title">
        Edit Mekanik
      </h2>
    </div>
    <div class="col-auto ms-auto d-print-none">
      <a href="{{ route('mechanics.index') }}" class="btn btn-secondary">
        Kembali
      </a>
    </div>
  </div>
</div>

<div class="row mt-3">
  <div class="col-md-6">
    <div class="card">
      <div class="card-body">
        <form action="{{ route('mechanics.update', $mechanic) }}" method="post">
          @csrf
          @method('PUT')
          <div class="mb-3">
            @if(is_null(auth()->user()->site_id))
            <div class="mb-3">
              <label class="form-label required">Site</label>
              <select name="site_id" class="form-select" required>
                <option value="">-- Pilih Site --</option>
                @foreach($sites as $site)
                  <option value="{{ $site->id }}" {{ old('site_id', $mechanic->site_id) == $site->id ? 'selected' : '' }}>{{ $site->name }}</option>
                @endforeach
              </select>
            </div>
            @endif
            <label class="form-label required">Nama Lengkap</label>
            <input type="text" class="form-control" name="nama_lengkap" value="{{ old('nama_lengkap', $mechanic->nama_lengkap) }}" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Jabatan</label>
            <select name="jabatan_id" class="form-select">
              <option value="">-- Pilih Jabatan --</option>
              @foreach($jabatans as $jabatan)
                <option value="{{ $jabatan->id }}" {{ old('jabatan_id', $mechanic->jabatan_id) == $jabatan->id ? 'selected' : '' }}>{{ $jabatan->nama_jabatan }}</option>
              @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label required">Status</label>
            <select name="is_active" class="form-select" required>
              <option value="1" {{ old('is_active', $mechanic->is_active) == 1 ? 'selected' : '' }}>Aktif</option>
              <option value="0" {{ old('is_active', $mechanic->is_active) == 0 ? 'selected' : '' }}>Non-Aktif</option>
            </select>
          </div>
          
          <div class="form-footer">
            <button type="submit" class="btn btn-primary">Update</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection