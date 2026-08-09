@extends('layouts.tabler')

@section('title', 'Edit Kategori Tool - CMMS')

@section('content')
<div class="page-header d-print-none">
  <div class="row align-items-center">
    <div class="col">
      <h2 class="page-title">
        Edit Kategori Tool
      </h2>
    </div>
    <div class="col-auto ms-auto d-print-none">
      <a href="{{ route('tool-categories.index') }}" class="btn btn-secondary">
        Kembali
      </a>
    </div>
  </div>
</div>

<div class="row mt-3">
  <div class="col-md-6">
    <div class="card">
      <div class="card-body">
        <form action="{{ route('tool-categories.update', $toolCategory) }}" method="post">
          @csrf
          @method('PUT')
          <div class="mb-3">
            <label class="form-label required">Nama Kategori</label>
            <input type="text" class="form-control" name="name" value="{{ old('name', $toolCategory->name) }}" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Deskripsi</label>
            <textarea class="form-control" name="description" rows="3">{{ old('description', $toolCategory->description) }}</textarea>
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
