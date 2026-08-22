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

      <div class="mt-3">
        @include('partials.permissions-matrix', ['selectedPermissions' => old('permissions', $rolePermissions)])
      </div>

      <!-- Pengaturan Mobile Nav -->
      <div class="card mt-3 border-primary" style="border-top-width: 3px;">
        <div class="card-header">
          <h3 class="card-title text-primary">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6 5a2 2 0 0 1 2 -2h8a2 2 0 0 1 2 2v14a2 2 0 0 1 -2 2h-8a2 2 0 0 1 -2 -2v-14z" /><path d="M11 4h2" /><path d="M12 17v.01" /><path d="M10 17h4v2h-4z" /></svg>
            Menu Navigasi Mobile (Maksimal 5)
          </h3>
        </div>
        <div class="card-body">
          <p class="text-muted small">Pilih maksimal 5 menu utama yang akan muncul di bilah navigasi bawah (Bottom Navbar) saat pengguna dengan Role ini mengakses sistem menggunakan Smartphone. Pilih menu yang paling relevan dengan operasional Role ini di lapangan.</p>
          <div class="row g-2">
            @foreach($modules as $key => $label)
            <div class="col-md-3 col-sm-6">
              <label class="form-check form-switch cursor-pointer">
                <input class="form-check-input mobile-menu-checkbox" type="checkbox" name="mobile_menus[]" value="{{ $key }}" {{ in_array($key, old('mobile_menus', $mobileMenus ?? [])) ? 'checked' : '' }}>
                <span class="form-check-label user-select-none">{{ $label }}</span>
              </label>
            </div>
            @endforeach
          </div>
        </div>
      </div>
      
      <script>
      document.addEventListener('DOMContentLoaded', function() {
          const limit = 5;
          const checkboxes = document.querySelectorAll('.mobile-menu-checkbox');
          
          function updateCheckboxes() {
              const checkedCount = document.querySelectorAll('.mobile-menu-checkbox:checked').length;
              checkboxes.forEach(cb => {
                  if (!cb.checked) {
                      cb.disabled = checkedCount >= limit;
                  }
              });
          }
          
          checkboxes.forEach(cb => {
              cb.addEventListener('change', updateCheckboxes);
          });
          
          updateCheckboxes(); // Initial check
      });
      </script>
      
      <div class="card mt-3">
        <div class="card-body text-end">
          <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection
