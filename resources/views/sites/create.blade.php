@extends('layouts.tabler')

@section('title', 'Tambah Site Baru')

@section('content')
<div class="page-header d-print-none">
    <div class="row align-items-center">
        <div class="col">
            <h2 class="page-title">Tambah Site Baru</h2>
            <div class="text-secondary mt-1">Tambahkan lokasi operasional baru ke dalam sistem.</div>
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
        <form action="{{ route('sites.store') }}" method="POST">
            @csrf
            
            <div class="mb-3">
                <label class="form-label required">Nama Site</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" placeholder="Contoh: Site Kalimantan Timur" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label required">Kode Site</label>
                <input type="text" class="form-control @error('code') is-invalid @enderror" name="code" value="{{ old('code') }}" placeholder="Contoh: KMT" required>
                @error('code')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Deskripsi</label>
                <textarea class="form-control @error('description') is-invalid @enderror" name="description" rows="3" placeholder="Informasi tambahan mengenai site ini">{{ old('description') }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary">Simpan Site</button>
                <a href="{{ route('sites.index') }}" class="btn btn-link">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
