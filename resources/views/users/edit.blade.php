@extends('layouts.tabler')

@section('title', 'Edit User - CMMS Aisfar')

@section('content')
<div class="page-header d-print-none">
  <div class="row align-items-center">
    <div class="col">
      <h2 class="page-title">
        Edit User: {{ $user->nama_lengkap }}
      </h2>
    </div>
    <div class="col-auto ms-auto d-print-none">
      <div class="btn-list">
        <a href="{{ route('users.index') }}" class="btn btn-secondary d-none d-sm-inline-block">
          Kembali
        </a>
      </div>
    </div>
  </div>
</div>

<div class="row row-cards mt-3">
  <div class="col-12">
    <div class="card">
      <div class="card-body">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('users.update', $user) }}" method="post">
          @csrf
          @method('PUT')
          <div class="row">
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label required">NIK</label>
                <input type="text" class="form-control" name="nik" value="{{ old('nik', $user->nik) }}" required>
              </div>
              <div class="mb-3">
                <label class="form-label required">Nama Lengkap</label>
                <input type="text" class="form-control" name="nama_lengkap" value="{{ old('nama_lengkap', $user->nama_lengkap) }}" required>
              </div>
              <div class="mb-3">
                <label class="form-label required">Email</label>
                <input type="email" class="form-control" name="email" value="{{ old('email', $user->email) }}" required>
              </div>
              <div class="mb-3">
                <label class="form-label required">No WhatsApp</label>
                <input type="text" class="form-control" name="no_whatsapp" value="{{ old('no_whatsapp', $user->no_whatsapp) }}" required>
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label">Departemen</label>
                <select name="department_id" class="form-select">
                  <option value="">-- Pilih Departemen --</option>
                  @foreach($departments as $dept)
                  <option value="{{ $dept->id }}" {{ old('department_id', $user->department_id) == $dept->id ? 'selected' : '' }}>{{ $dept->nama_department }}</option>
                  @endforeach
                </select>
              </div>
              <div class="mb-3">
                <label class="form-label">Jabatan</label>
                <select name="jabatan_id" class="form-select">
                  <option value="">-- Pilih Jabatan --</option>
                  @foreach($jabatans as $jab)
                  <option value="{{ $jab->id }}" {{ old('jabatan_id', $user->jabatan_id) == $jab->id ? 'selected' : '' }}>{{ $jab->nama_jabatan }}</option>
                  @endforeach
                </select>
              </div>
              <div class="mb-3">
                <label class="form-label">Site (Cabang)</label>
                <select name="site_id" class="form-select">
                  <option value="">-- Akses Semua Site (Superadmin) --</option>
                  @foreach($sites as $site)
                  <option value="{{ $site->id }}" {{ old('site_id', $user->site_id) == $site->id ? 'selected' : '' }}>{{ $site->name }} ({{ $site->code }})</option>
                  @endforeach
                </select>
                <small class="form-hint">Kosongkan jika user ini adalah Superadmin (Head Office).</small>
              </div>
              <div class="mb-3">
                <label class="form-label required">Role</label>
                <select name="role" class="form-select" required>
                  <option value="">-- Pilih Role --</option>
                  @foreach($roles as $role)
                  <option value="{{ $role->name }}" {{ old('role', $user->roles->first()?->name) == $role->name ? 'selected' : '' }}>{{ $role->name }}</option>
                  @endforeach
                </select>
              </div>
              <div class="mb-3">
                <label class="form-label required">Status</label>
                <select name="status" class="form-select" required>
                  <option value="active" {{ old('status', $user->status) == 'active' ? 'selected' : '' }}>Active</option>
                  <option value="pending" {{ old('status', $user->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                  <option value="rejected" {{ old('status', $user->status) == 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
              </div>
            </div>
          </div>
          
          @if(auth()->id() == $user->id)
          <hr>
          
          <div class="row">
            <div class="col-md-12 mb-3">
              <p class="text-secondary">Biarkan kosong jika tidak ingin mengubah password.</p>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label">Password Baru</label>
                <input type="password" class="form-control" name="password">
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label">Konfirmasi Password Baru</label>
                <input type="password" class="form-control" name="password_confirmation">
              </div>
            </div>
          </div>
          @endif
          
          <hr>
          
          <div class="row">
            <div class="col-md-12">
              <h3 class="mb-3">Spesifik Hak Akses (Opsional)</h3>
              <p class="text-muted">Jika minimal satu hak akses dicentang lalu disimpan, daftar ini menjadi <b>pengaturan utama</b> user dan menggantikan hak akses dari <b>Role</b>. Kosongkan seluruh pilihan untuk kembali menggunakan hak akses Role.</p>
              <div class="alert alert-info d-flex flex-wrap align-items-center gap-2 py-2" role="alert">
                <span class="fw-semibold">Salin hak akses spesifik dari user lain:</span>
                <select id="copyPermissionsFrom" class="form-select form-select-sm" style="max-width: 360px;">
                  <option value="">-- Pilih user sumber --</option>
                  @foreach($permissionSources as $source)
                    <option value="{{ $source['id'] }}">{{ $source['label'] }}</option>
                  @endforeach
                </select>
                <button type="button" id="copyPermissionsButton" class="btn btn-sm btn-info">Salin Hak Akses</button>
                <span class="small text-secondary">Hanya hak akses spesifik yang akan disalin; role user ini tidak berubah.</span>
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
                                {{ in_array($permissionName, old('permissions', $userDirectPermissions)) ? 'checked' : '' }}>
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
                              {{ in_array('download_backup', old('permissions', $userDirectPermissions)) ? 'checked' : '' }}>
                            <span class="form-check-label">Download Backup</span>
                          </label>
                          <label class="form-check form-switch m-0">
                            <input class="form-check-input" type="checkbox" name="permissions[]" value="send_chat"
                              {{ in_array('send_chat', old('permissions', $userDirectPermissions)) ? 'checked' : '' }}>
                            <span class="form-check-label">Kirim Pesan Chat</span>
                          </label>
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          
          <div class="mt-4">
            <button type="submit" class="btn btn-primary">Update User</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  const sources = @json($permissionSources);
  const selector = document.getElementById('copyPermissionsFrom');
  const button = document.getElementById('copyPermissionsButton');

  button.addEventListener('click', function () {
    const source = sources.find(item => String(item.id) === selector.value);
    if (!source) {
      alert('Pilih user sumber terlebih dahulu.');
      return;
    }

    if (!confirm(`Salin ${source.permissions.length} hak akses spesifik dari ${source.label}? Pilihan saat ini akan diganti.`)) {
      return;
    }

    document.querySelectorAll('input[name="permissions[]"]').forEach(input => {
      input.checked = source.permissions.includes(input.value);
    });
  });
});
</script>
@endpush
