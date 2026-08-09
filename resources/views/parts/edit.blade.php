@extends('layouts.tabler')
@section('title', 'Edit Part - CMMS')
@section('content')
<div class="page-header d-print-none">
  <div class="row align-items-center">
    <div class="col"><h2 class="page-title">Edit Part</h2></div>
    <div class="col-auto ms-auto"><a href="{{ route('parts.index') }}" class="btn btn-secondary">Kembali</a></div>
  </div>
</div>
<div class="row mt-3">
  <div class="col-md-8">
    <div class="card"><div class="card-body">
      <form action="{{ route('parts.update', $part) }}" method="post">
        @csrf @method('PUT')
          <div class="row">
            @if(is_null(auth()->user()->site_id))
            <div class="col-md-12 mb-3">
              <label class="form-label required">Site</label>
              <select name="site_id" class="form-select" required>
                <option value="">-- Pilih Site --</option>
                @foreach($sites as $site)
                  <option value="{{ $site->id }}" {{ old('site_id', $part->site_id) == $site->id ? 'selected' : '' }}>{{ $site->name }}</option>
                @endforeach
              </select>
            </div>
            @endif
            <div class="col-md-6 mb-3">
            <label class="form-label required">Part Number</label>
            <input type="text" class="form-control" name="part_number" value="{{ $part->part_number }}" required>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label required">Part Description</label>
            <input type="text" class="form-control" name="part_description" value="{{ $part->part_description }}" required>
          </div>
          <div class="col-md-4 mb-3">
            <label class="form-label">Satuan</label>
            <input type="text" class="form-control" name="satuan" value="{{ $part->satuan }}">
          </div>
          <div class="col-md-4 mb-3">
            <label class="form-label">Cost (Rp)</label>
            <input type="number" class="form-control" name="cost" value="{{ $part->cost }}" min="0" step="0.01">
          </div>
          <div class="col-md-4 mb-3">
            <label class="form-label">Kategori 1</label>
            <input type="text" class="form-control" name="kategori_1" value="{{ $part->kategori_1 }}">
          </div>
          <div class="col-md-4 mb-3">
            <label class="form-label">Kategori 2</label>
            <input type="text" class="form-control" name="kategori_2" value="{{ $part->kategori_2 }}">
          </div>
          <div class="col-md-4 mb-3">
            <label class="form-label">Kategori 3</label>
            <input type="text" class="form-control" name="kategori_3" value="{{ $part->kategori_3 }}">
          </div>
          <div class="col-md-4 mb-3">
            <label class="form-label">Kategori 4</label>
            <input type="text" class="form-control" name="kategori_4" value="{{ $part->kategori_4 }}">
          </div>
        </div>
        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
      </form>
    </div></div>
  </div>
</div>
@endsection