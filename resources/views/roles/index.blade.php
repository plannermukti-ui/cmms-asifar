@extends('layouts.tabler')

@section('title', 'Kelola Role - CMMS Aisfar')

@section('content')
<div class="page-header d-print-none">
  <div class="row align-items-center">
    <div class="col">
      <h2 class="page-title">
        Role / Hak Akses
      </h2>
    </div>
    <div class="col-auto ms-auto d-print-none">
      <div class="btn-list">
        <a href="#" class="btn btn-primary d-none d-sm-inline-block" data-bs-toggle="modal" data-bs-target="#modal-tambah-role">
          Tambah Role
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
              <th>Nama Role</th>
              <th class="w-1">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($roles as $role)
            <tr>
              <td>{{ $role->id }}</td>
              <td>{{ $role->name }}</td>
              <td class="d-flex gap-2">
                <a href="{{ route('roles.edit', $role) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                <form action="{{ route('roles.destroy', $role) }}" method="post" onsubmit="return confirm('Hapus role?');">
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
        {{ $roles->links('pagination::bootstrap-5') }}
      </div>
    </div>
  </div>
</div>
<div class="modal modal-blur fade" id="modal-tambah-role" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Tambah Role</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('roles.store') }}" method="post">
        @csrf
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label required">Nama Role</label>
            <input type="text" class="form-control" name="name" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Permissions</label>
            <div class="table-responsive">
              <table class="table table-vcenter">
                @foreach($modules as $moduleKey => $moduleName)
                <tr>
                  <td><strong>{{ $moduleName }}</strong></td>
                  <td>
                    <div class="d-flex flex-wrap gap-3">
                      @foreach($actions as $action)
                        <label class="form-check">
                          <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $action }}_{{ $moduleKey }}">
                          <span class="form-check-label">{{ ucfirst($action) }}</span>
                        </label>
                      @endforeach
                    </div>
                  </td>
                </tr>
                @endforeach
              </table>
            </div>
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
