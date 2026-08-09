@extends('layouts.tabler')

@section('title', 'Master Tool - CMMS')

@section('content')
<div class="page-header d-print-none">
  <div class="row align-items-center">
    <div class="col">
      <h2 class="page-title">
        Master Tool & Stok
      </h2>
    </div>
    <div class="col-auto ms-auto d-print-none">
      <div class="btn-list">
        @can('view_tool_categories')
        <a href="{{ route('tool-categories.index') }}" class="btn btn-secondary d-none d-sm-inline-block">
          Kelola Kategori
        </a>
        @endcan
        @can('create_tools')
        <a href="#" class="btn btn-primary d-none d-sm-inline-block" data-bs-toggle="modal" data-bs-target="#modal-tambah-tool">
          <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="12" y1="5" x2="12" y2="19" /><line x1="5" y1="12" x2="19" y2="12" /></svg>
          Tambah Tool
        </a>
        @endcan
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
              <th>Foto</th>
              <th>Nama Tool</th>
              <th>Kategori</th>
              <th>Spesifikasi</th>
              <th>Stok Total</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($tools as $tool)
            <tr>
              <td>{{ $tool->id }}</td>
              <td>
                @if($tool->foto)
                  <img src="{{ asset('storage/' . $tool->foto) }}" alt="Foto" class="avatar">
                @else
                  <span class="avatar bg-blue-lt">TL</span>
                @endif
              </td>
              <td class="fw-bold">{{ $tool->name }}</td>
              <td>{{ $tool->category ? $tool->category->name : '-' }}</td>
              <td>{{ $tool->spesifikasi ?? '-' }}</td>
              <td>
                @php
                    $stokToolroom = $tool->stocks()->where('location_type', 'ToolRoom')->sum('quantity');
                    $stokMekanik = $tool->stocks()->where('location_type', 'Mechanic')->sum('quantity');
                @endphp
                <span class="badge bg-primary">TR: {{ $stokToolroom }}</span>
                <span class="badge bg-indigo">MEK: {{ $stokMekanik }}</span>
              </td>
              <td>
                @can('view_tool_stocks')
                <a href="{{ route('tool-stocks.index', ['tool_id' => $tool->id]) }}" class="btn btn-sm btn-info">Cek Stok</a>
                @endcan
                @can('edit_tools')
                <a href="{{ route('tools.edit', $tool) }}" class="btn btn-sm btn-primary">Edit</a>
                @endcan
                @can('delete_tools')
                <form action="{{ route('tools.destroy', $tool) }}" method="post" class="d-inline" onsubmit="return confirm('Yakin menghapus tool ini?');">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                </form>
                @endcan
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="7" class="text-center">Belum ada data tool.</td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
      @if($tools->hasPages())
      <div class="card-footer d-flex align-items-center">
        {{ $tools->links('pagination::bootstrap-5') }}
      </div>
      @endif
    </div>
  </div>
</div>
<div class="modal modal-blur fade" id="modal-tambah-tool" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Tambah Tool</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('tools.store') }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="modal-body">
          <div class="row">
            @if(is_null(auth()->user()->site_id))
            <div class="col-md-12 mb-3">
              <label class="form-label required">Site</label>
              <select name="site_id" class="form-select" required>
                <option value="">-- Pilih Site --</option>
                @foreach($sites as $site)
                  <option value="{{ $site->id }}">{{ $site->name }}</option>
                @endforeach
              </select>
            </div>
            @endif
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
              <label class="form-label">Foto Tool</label>
              <input type="file" class="form-control" name="foto" accept="image/*">
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn me-auto" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan Tool</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection