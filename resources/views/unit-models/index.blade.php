@extends('layouts.tabler')

@section('title', 'Model Unit - CMMS Aisfar')

@section('content')
<div class="page-header d-print-none">
  <div class="row align-items-center">
    <div class="col">
      <h2 class="page-title">Master Model Unit</h2>
    </div>
    <div class="col-auto ms-auto d-print-none">
      <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-tambah">
        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
        Tambah Model
      </a>
    </div>
  </div>
</div>

@if (session('success'))
    <div class="alert alert-success mt-3">{{ session('success') }}</div>
@endif

<div class="card mt-3">
  <div class="table-responsive">
    <table class="table table-vcenter card-table">
      <thead>
        <tr>
          <th>Tipe Unit</th>
          <th>Nama Model</th>
          <th class="w-1">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($unitModels as $model)
        <tr>
          <td>{{ $model->type->name ?? '-' }}</td>
          <td>{{ $model->name }}</td>
          <td>
            <div class="btn-list flex-nowrap">
              <a href="#" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modal-edit-{{ $model->id }}">Edit</a>
              <form action="{{ route('unit-models.destroy', $model) }}" method="POST" onsubmit="return confirm('Hapus model ini?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
              </form>
            </div>
          </td>
        </tr>

        <!-- Modal Edit -->
        <div class="modal modal-blur fade" id="modal-edit-{{ $model->id }}" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">Edit Model Unit</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <form action="{{ route('unit-models.update', $model) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                  <div class="mb-3">
                    <label class="form-label required">Tipe Unit</label>
                    <select name="unit_type_id" class="form-select" required>
                      @foreach($unitTypes as $type)
                        <option value="{{ $type->id }}" {{ $model->unit_type_id == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                      @endforeach
                    </select>
                  </div>
                  <div class="mb-3">
                    <label class="form-label required">Nama Model</label>
                    <input type="text" name="name" class="form-control" value="{{ $model->name }}" required>
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
          <td colspan="3" class="text-center text-muted py-4">Belum ada data model unit.</td>
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
        <h5 class="modal-title">Tambah Model Unit</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('unit-models.store') }}" method="POST">
        @csrf
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label required">Tipe Unit</label>
            <select name="unit_type_id" class="form-select" required>
              <option value="">-- Pilih Tipe Unit --</option>
              @foreach($unitTypes as $type)
                <option value="{{ $type->id }}">{{ $type->name }}</option>
              @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label required">Nama Model</label>
            <input type="text" name="name" class="form-control" required>
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
