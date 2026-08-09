@extends('layouts.tabler')

@section('title', 'Edit Tool - CMMS')

@section('content')
<div class="page-header d-print-none">
  <div class="row align-items-center">
    <div class="col">
      <h2 class="page-title">
        Edit Tool
      </h2>
    </div>
    <div class="col-auto ms-auto d-print-none">
      <a href="{{ route('tools.index') }}" class="btn btn-secondary">
        Kembali
      </a>
    </div>
  </div>
</div>

<div class="row mt-3">
  <div class="col-md-8">
    <div class="card">
      <div class="card-body">
        <form action="{{ route('tools.update', $tool) }}" method="post" enctype="multipart/form-data">
          @csrf
          @method('PUT')
          <div class="row">
            @if(is_null(auth()->user()->site_id))
            <div class="col-md-12 mb-3">
              <label class="form-label required">Site</label>
              <select name="site_id" class="form-select" required>
                <option value="">-- Pilih Site --</option>
                @foreach($sites as $site)
                  <option value="{{ $site->id }}" {{ old('site_id', $tool->site_id) == $site->id ? 'selected' : '' }}>{{ $site->name }}</option>
                @endforeach
              </select>
            </div>
            @endif
            <div class="col-md-6 mb-3">
              <label class="form-label required">Nama Tool</label>
              <input type="text" class="form-control" name="name" value="{{ old('name', $tool->name) }}" required>
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label required">Kategori</label>
              <select name="tool_category_id" class="form-select" required>
                <option value="">-- Pilih Kategori --</option>
                @foreach($categories as $category)
                  <option value="{{ $category->id }}" {{ old('tool_category_id', $tool->tool_category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-12 mb-3">
              <label class="form-label">Spesifikasi / Deskripsi</label>
              <textarea class="form-control" name="spesifikasi" rows="3">{{ old('spesifikasi', $tool->spesifikasi) }}</textarea>
            </div>
            <div class="col-md-12 mb-3">
              <label class="form-label">Foto Tool</label>
              @if($tool->foto)
                <div class="mb-2">
                  <img src="{{ asset('storage/' . $tool->foto) }}" alt="Foto Tool" class="img-thumbnail" style="max-height: 150px;">
                </div>
              @endif
              <input type="file" class="form-control" name="foto" accept="image/*">
              <small class="form-hint">Biarkan kosong jika tidak ingin mengubah foto.</small>
            </div>
          </div>
          
          <div class="form-footer text-end">
            <button type="submit" class="btn btn-primary">Update Tool</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection