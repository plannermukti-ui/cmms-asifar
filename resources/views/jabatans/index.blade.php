@extends('layouts.tabler')

@section('title', 'Kelola Jabatan - CMMS Aisfar')

@section('content')
<div class="page-header d-print-none">
  <div class="row align-items-center">
    <div class="col">
      <h2 class="page-title">
        Jabatan
      </h2>
    </div>
    <div class="col-auto ms-auto d-print-none">
      <div class="btn-list">
        <a href="#" class="btn btn-primary d-none d-sm-inline-block" data-bs-toggle="modal" data-bs-target="#modal-tambah-jabatan">
          Tambah Jabatan
        </a>
      </div>
    </div>
  </div>
</div>

<div class="row row-cards mt-3">
  <div class="col-12">
    <div class="card">
      <div class="table-responsive">
        <table class="table table-vcenter card-table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Nama Jabatan</th>
              <th class="w-1">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($jabatans as $jab)
            <tr>
              <td>{{ $jab->id }}</td>
              <td>{{ $jab->nama_jabatan }}</td>
              <td class="d-flex gap-2">
                <a href="{{ route('jabatans.edit', $jab) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                <form action="{{ route('jabatans.destroy', $jab) }}" method="post" onsubmit="return confirm('Hapus jabatan?');">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                </form>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="3" class="text-center">Belum ada data.</td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
      <div class="card-footer d-flex align-items-center">
        {{ $jabatans->links('pagination::bootstrap-5') }}
      </div>
    </div>
  </div>
</div>
<div class="modal modal-blur fade" id="modal-tambah-jabatan" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Tambah Jabatan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('jabatans.store') }}" method="post">
        @csrf
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label required">Nama Jabatan</label>
            <input type="text" class="form-control" name="nama_jabatan" required>
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
