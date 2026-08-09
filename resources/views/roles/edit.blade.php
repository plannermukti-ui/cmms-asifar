@extends('layouts.tabler')

@section('title', 'Edit Role - CMMS Aisfar')

@section('content')
<div class="page-header d-print-none">
  <div class="row align-items-center">
    <div class="col">
      <h2 class="page-title">Edit Role: {{ $role->name }}</h2>
    </div>
    <div class="col-auto ms-auto d-print-none">
      <div class="btn-list">
        <a href="{{ route('roles.index') }}" class="btn btn-secondary">Kembali</a>
      </div>
    </div>
  </div>
</div>

<div class="row mt-3">
  <div class="col-md-12">
    <form action="{{ route('roles.update', $role) }}" method="post">
      @csrf
      @method('PUT')
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Informasi Role</h3>
        </div>
        <div class="card-body">
          <div class="mb-3">
            <label class="form-label required">Nama Role</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $role->name) }}" required>
            @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>
      </div>

      <div class="card mt-3">
        <div class="card-header">
          <h3 class="card-title">Matriks Hak Akses (Permissions)</h3>
        </div>
        <div class="table-responsive">
          <table class="table table-vcenter card-table table-striped">
            <thead>
              <tr>
                <th>Modul Aplikasi</th>
                @foreach($actions as $action)
                  <th class="text-center">{{ ucfirst($action) }}</th>
                @endforeach
              </tr>
            </thead>
            <tbody>
              @foreach($modules as $moduleKey => $moduleName)
                <tr>
                  <td class="fw-bold">{{ $moduleName }}</td>
                  @foreach($actions as $action)
                    @php $permissionName = $action . '_' . $moduleKey; @endphp
                    <td class="text-center">
                      <label class="form-check form-switch d-flex justify-content-center m-0">
                        <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $permissionName }}"
                          {{ in_array($permissionName, old('permissions', $rolePermissions)) ? 'checked' : '' }}>
                      </label>
                    </td>
                  @endforeach
                </tr>
              @endforeach
              <!-- Additional custom permissions -->
              <tr>
                <td class="fw-bold">Fitur Khusus</td>
                <td colspan="4">
                  <div class="d-flex gap-4">
                    <label class="form-check form-switch m-0">
                      <input class="form-check-input" type="checkbox" name="permissions[]" value="download_backup"
                        {{ in_array('download_backup', old('permissions', $rolePermissions)) ? 'checked' : '' }}>
                      <span class="form-check-label">Download Backup</span>
                    </label>
                    <label class="form-check form-switch m-0">
                      <input class="form-check-input" type="checkbox" name="permissions[]" value="send_chat"
                        {{ in_array('send_chat', old('permissions', $rolePermissions)) ? 'checked' : '' }}>
                      <span class="form-check-label">Kirim Pesan Chat</span>
                    </label>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="card-footer text-end">
          <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection
