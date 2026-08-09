@extends('layouts.tabler')
@section('title', 'Master Vendor / Bengkel')

@section('content')
<div class="page-header d-print-none">
  <div class="row align-items-center">
    <div class="col">
      <h2 class="page-title">Master Vendor / Bengkel Luar</h2>
    </div>
    <div class="col-auto ms-auto d-print-none">
      <div class="btn-list">
        <a href="#" class="btn btn-primary d-none d-sm-inline-block" data-bs-toggle="modal" data-bs-target="#modal-vendor">
          <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
          Tambah Vendor
        </a>
      </div>
    </div>
  </div>
</div>

<div class="card">
  <div class="card-header">
    <h3 class="card-title">Daftar Vendor</h3>
    <div class="card-actions">
        <form action="{{ route('vendors.index') }}" method="GET" class="d-flex">
            <input type="text" name="search" class="form-control form-control-sm me-2" placeholder="Cari vendor..." value="{{ request('search') }}">
            <button type="submit" class="btn btn-sm btn-primary">Cari</button>
        </form>
    </div>
  </div>
  <div class="table-responsive">
    <table class="table card-table table-vcenter text-nowrap">
      <thead>
        <tr>
          <th>ID</th>
          <th>Nama Vendor</th>
          <th>Contact Person</th>
          <th>No. Telp</th>
          <th>Email</th>
          <th>Alamat</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($vendors as $vendor)
        <tr>
          <td>{{ $vendor->id }}</td>
          <td class="fw-bold">{{ $vendor->name }}</td>
          <td>{{ $vendor->contact_person ?? '-' }}</td>
          <td>{{ $vendor->phone ?? '-' }}</td>
          <td>{{ $vendor->email ?? '-' }}</td>
          <td>{{ Str::limit($vendor->address, 30) ?? '-' }}</td>
          <td>
            <a href="#" class="btn btn-sm btn-info btn-edit" 
               data-id="{{ $vendor->id }}"
               data-name="{{ $vendor->name }}"
               data-cp="{{ $vendor->contact_person }}"
               data-phone="{{ $vendor->phone }}"
               data-email="{{ $vendor->email }}"
               data-address="{{ $vendor->address }}"
               data-bs-toggle="modal" data-bs-target="#modal-vendor-edit">Edit</a>
            <form action="{{ route('vendors.destroy', $vendor) }}" method="post" class="d-inline" onsubmit="return confirm('Hapus vendor ini?');">
              @csrf @method('DELETE')
              <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
            </form>
          </td>
        </tr>
        @empty
        <tr><td colspan="7" class="text-center">Belum ada data vendor.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  <div class="card-footer d-flex align-items-center">
    {{ $vendors->links('pagination::bootstrap-5') }}
  </div>
</div>

<!-- Modal Create -->
<div class="modal modal-blur fade" id="modal-vendor" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <form action="{{ route('vendors.store') }}" method="POST">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title">Tambah Vendor</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label required">Nama Vendor</label>
            <input type="text" class="form-control" name="name" required>
          </div>
          <div class="row g-2 mb-3">
            <div class="col-6">
                <label class="form-label">Contact Person</label>
                <input type="text" class="form-control" name="contact_person">
            </div>
            <div class="col-6">
                <label class="form-label">No. Telepon</label>
                <input type="text" class="form-control" name="phone">
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" class="form-control" name="email">
          </div>
          <div class="mb-3">
            <label class="form-label">Alamat Lengkap</label>
            <textarea class="form-control" name="address" rows="3"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn me-auto" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan Vendor</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Edit -->
<div class="modal modal-blur fade" id="modal-vendor-edit" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <form action="" method="POST" id="form-edit">
        @csrf @method('PUT')
        <div class="modal-header">
          <h5 class="modal-title">Edit Vendor</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label required">Nama Vendor</label>
            <input type="text" class="form-control" name="name" id="edit_name" required>
          </div>
          <div class="row g-2 mb-3">
            <div class="col-6">
                <label class="form-label">Contact Person</label>
                <input type="text" class="form-control" name="contact_person" id="edit_cp">
            </div>
            <div class="col-6">
                <label class="form-label">No. Telepon</label>
                <input type="text" class="form-control" name="phone" id="edit_phone">
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" class="form-control" name="email" id="edit_email">
          </div>
          <div class="mb-3">
            <label class="form-label">Alamat Lengkap</label>
            <textarea class="form-control" name="address" id="edit_address" rows="3"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn me-auto" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Update Vendor</button>
        </div>
      </form>
    </div>
  </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const editBtns = document.querySelectorAll('.btn-edit');
    const formEdit = document.getElementById('form-edit');
    
    editBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            formEdit.action = `/vendors/${this.dataset.id}`;
            document.getElementById('edit_name').value = this.dataset.name;
            document.getElementById('edit_cp').value = this.dataset.cp;
            document.getElementById('edit_phone').value = this.dataset.phone;
            document.getElementById('edit_email').value = this.dataset.email;
            document.getElementById('edit_address').value = this.dataset.address;
        });
    });
});
</script>
@endpush
@endsection
