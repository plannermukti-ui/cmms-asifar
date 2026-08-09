@extends('layouts.tabler')

@section('title', 'Kategori Tool - CMMS')

@section('content')
<div class="page-header d-print-none">
  <div class="row align-items-center">
    <div class="col">
      <h2 class="page-title">
        Kategori Tool
      </h2>
    </div>
    <div class="col-auto ms-auto d-print-none">
      <div class="btn-list">
        @can('create_tool_categories')
        <a href="#" class="btn btn-primary d-none d-sm-inline-block" data-bs-toggle="modal" data-bs-target="#modal-tambah-kategori">
          <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="12" y1="5" x2="12" y2="19" /><line x1="5" y1="12" x2="19" y2="12" /></svg>
          Tambah Kategori
        </a>
        @endcan
        <a href="{{ route('tools.index') }}" class="btn btn-secondary d-none d-sm-inline-block">
          Kembali ke Master Tool
        </a>
      </div>
    </div>
  </div>
</div>

<div class="row row-cards mt-3">
  <div class="col-12">
    <div class="card">
      <div class="table-responsive">
        <table class="table card-table table-vcenter text-nowrap datatable">
          <thead>
            <tr>
              <th>ID</th>
              <th>Nama Kategori</th>
              <th>Deskripsi</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($categories as $category)
            <tr>
              <td>{{ $category->id }}</td>
              <td>{{ $category->name }}</td>
              <td>{{ $category->description ?? '-' }}</td>
              <td>
                @can('edit_tool_categories')
                <a href="{{ route('tool-categories.edit', $category) }}" class="btn btn-sm btn-primary">Edit</a>
                @endcan
                @can('delete_tool_categories')
                <form action="{{ route('tool-categories.destroy', $category) }}" method="post" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus kategori ini?');">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                </form>
                @endcan
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="4" class="text-center">Belum ada data kategori tool.</td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
      @if($categories->hasPages())
      <div class="card-footer d-flex align-items-center">
        {{ $categories->links('pagination::bootstrap-5') }}
      </div>
      @endif
    </div>
  </div>
</div>
<div class="modal modal-blur fade" id="modal-tambah-kategori" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Tambah Kategori Tool</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('tool-categories.store') }}" method="post">
        @csrf
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label required">Nama Kategori</label>
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
