@extends('layouts.tabler')

@section('title', 'Master Modul - CMMS Aisfar')

@section('content')
<div class="page-header d-print-none">
  <div class="row align-items-center">
    <div class="col">
      <h2 class="page-title">Master Data Modul</h2>
      <div class="text-secondary mt-1">Kelola data modul utama aplikasi (digunakan di Approval Matrix).</div>
    </div>
    <div class="col-auto ms-auto d-print-none">
      <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-tambah">
        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
        Tambah Modul
      </a>
    </div>
  </div>
</div>

@if (session('success'))
    <div class="alert alert-success mt-3">{{ session('success') }}</div>
@endif
@if ($errors->any())
    <div class="alert alert-danger mt-3">
        <ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
@endif

<div class="card mt-3">
  <div class="table-responsive">
    <table class="table table-vcenter card-table">
      <thead>
        <tr>
          <th>Nama Modul</th>
          <th>Keterangan</th>
          <th class="w-1">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($modules as $modul)
        <tr>
          <td>{{ $modul->name }}</td>
          <td>{{ $modul->description ?? '-' }}</td>
          <td>
            <div class="btn-list flex-nowrap">
              <a href="#" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modal-edit-{{ $modul->id }}">Edit</a>
              <form action="{{ route('modules.destroy', $modul) }}" method="POST" onsubmit="return confirm('Hapus modul ini?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
              </form>
            </div>
          </td>
        </tr>

        <!-- Modal Edit -->
        <div class="modal modal-blur fade" id="modal-edit-{{ $modul->id }}" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">Edit Modul</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <form action="{{ route('modules.update', $modul) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                  <div class="mb-3">
                    <label class="form-label required">Nama Modul</label>
                    <input type="text" name="name" class="form-control" value="{{ $modul->name }}" required>
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Keterangan</label>
                    <input type="text" name="description" class="form-control" value="{{ $modul->description }}">
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn me-auto" data-bs-dismiss="modal">Batal</button>
                  <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
              </form>
            </div>
          </div>
        </div>
        @empty
        <tr>
          <td colspan="3" class="text-center text-muted py-4">Belum ada data modul.</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

<!-- Modal Tambah -->
<div class="modal modal-blur fade" id="modal-tambah" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Tambah Modul</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('modules.store') }}" method="POST">
        @csrf
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label required">Nama Modul</label>
            <input type="text" name="name" class="form-control" placeholder="Contoh: Work Order" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Keterangan</label>
            <input type="text" name="description" class="form-control" placeholder="Opsional">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn me-auto" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>
<div class="modal modal-blur fade" id="modal-tambah-modul" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Tambah Modul</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('modules.store') }}" method="post">
        @csrf
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label required">Nama Modul</label>
            <input type="text" class="form-control" name="name" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Deskripsi</label>
            <textarea class="form-control" name="description" rows="3"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn me-auto" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
