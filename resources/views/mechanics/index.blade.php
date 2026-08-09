@extends('layouts.tabler')

@section('title', 'Data Mekanik - CMMS')

@section('content')
<div class="page-header d-print-none">
  <div class="row align-items-center">
    <div class="col">
      <h2 class="page-title">
        Data Mekanik
      </h2>
    </div>
    <div class="col-auto ms-auto d-print-none">
      <div class="btn-list">
        @can('create_mechanics')
        <a href="#" class="btn btn-primary d-none d-sm-inline-block" data-bs-toggle="modal" data-bs-target="#modal-tambah-mekanik">
          <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="12" y1="5" x2="12" y2="19" /><line x1="5" y1="12" x2="19" y2="12" /></svg>
          Tambah Mekanik
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
              <th>Nama Lengkap</th>
              <th>Jabatan</th>
              <th>Status</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($mechanics as $mech)
            <tr>
              <td>{{ $mech->id }}</td>
              <td>{{ $mech->nama_lengkap }}</td>
              <td>{{ $mech->jabatan ? $mech->jabatan->nama_jabatan : '-' }}</td>
              <td>
                @if($mech->is_active)
                  <span class="badge bg-success me-1"></span> Aktif
                @else
                  <span class="badge bg-danger me-1"></span> Non-Aktif
                @endif
              </td>
              <td>
                @can('edit_mechanics')
                <a href="{{ route('mechanics.edit', $mech) }}" class="btn btn-sm btn-primary">Edit</a>
                @endcan
                @can('delete_mechanics')
                <form action="{{ route('mechanics.destroy', $mech) }}" method="post" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus mekanik ini?');">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                </form>
                @endcan
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="5" class="text-center">Belum ada data mekanik.</td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
      @if($mechanics->hasPages())
      <div class="card-footer d-flex align-items-center">
        {{ $mechanics->links('pagination::bootstrap-5') }}
      </div>
      @endif
    </div>
  </div>
</div>
<div class="modal modal-blur fade" id="modal-tambah-mekanik" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Tambah Mekanik</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('mechanics.store') }}" method="post">
        @csrf
        <div class="modal-body">
          @if(is_null(auth()->user()->site_id))
          <div class="mb-3">
            <label class="form-label required">Site</label>
            <select name="site_id" class="form-select" required>
              <option value="">-- Pilih Site --</option>
              @foreach($sites as $site)
                <option value="{{ $site->id }}">{{ $site->name }}</option>
              @endforeach
            </select>
          </div>
          @endif
          <div class="mb-3">
            <label class="form-label required">Nama Lengkap</label>
            <input type="text" class="form-control" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Jabatan</label>
            <select name="jabatan_id" class="form-select">
              <option value="">-- Pilih Jabatan --</option>
              @foreach($jabatans as $jabatan)
                <option value="{{ $jabatan->id }}" {{ old('jabatan_id') == $jabatan->id ? 'selected' : '' }}>{{ $jabatan->nama_jabatan }}</option>
              @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label required">Status</label>
            <select name="is_active" class="form-select" required>
              <option value="1" {{ old('is_active') == '1' ? 'selected' : '' }}>Aktif</option>
              <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Non-Aktif</option>
            </select>
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