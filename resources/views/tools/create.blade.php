@extends('layouts.tabler')

@section('title', 'Tambah Tool - CMMS')

@section('content')
<div class="page-header d-print-none">
  <div class="row align-items-center">
    <div class="col">
      <h2 class="page-title">
        Tambah Tool
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
        <form action="{{ route('tools.store') }}" method="post" enctype="multipart/form-data">
          @csrf
          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label required">Nama Tool</label>
              <input type="text" class="form-control" name="name" value="{{ old('name') }}" required>
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label required">Kategori</label>
              <select name="tool_category_id" class="form-select" required>
                <option value="">-- Pilih Kategori --</option>
                @foreach($categories as $category)
                  <option value="{{ $category->id }}" {{ old('tool_category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-12 mb-3">
              <label class="form-label">Spesifikasi / Deskripsi</label>
              <textarea class="form-control" name="spesifikasi" rows="3">{{ old('spesifikasi') }}</textarea>
            </div>
            <div class="col-md-12 mb-3">
              <label class="form-label">Cost / Harga Satuan (Rp)</label>
              <input type="number" class="form-control" name="price" value="{{ old('price', 0) }}" min="0" step="0.01">
            </div>
            <div class="col-md-12 mb-3">
              <label class="form-label">Foto Tool</label>
              <input type="file" class="form-control" name="foto" accept="image/*">
            </div>
          </div>
          
          <div class="form-footer text-end">
            <button type="submit" class="btn btn-primary">Simpan Tool</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection