@extends('layouts.tabler')

@section('title', 'Edit Site')

@section('content')
<div class="page-header d-print-none">
    <div class="row align-items-center">
        <div class="col">
            <h2 class="page-title">Edit Site: {{ $site->name }}</h2>
            <div class="text-secondary mt-1">Perbarui informasi lokasi operasional.</div>
        </div>
        <div class="col-auto ms-auto d-print-none">
            <a href="{{ route('sites.index') }}" class="btn btn-secondary">
                Kembali
            </a>
        </div>
    </div>
</div>

<div class="card mt-3">
    <div class="card-body">
        <form action="{{ route('sites.update', $site) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-3">
                <label class="form-label required">Nama Site</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $site->name) }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label required">Kode Site</label>
                <input type="text" class="form-control @error('code') is-invalid @enderror" name="code" value="{{ old('code', $site->code) }}" required>
                @error('code')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Deskripsi</label>
                <textarea class="form-control @error('description') is-invalid @enderror" name="description" rows="3">{{ old('description', $site->description) }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                <a href="{{ route('sites.index') }}" class="btn btn-link">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
