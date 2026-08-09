@extends('layouts.tabler')

@section('title', 'Populasi Asset (Unit) - CMMS Aisfar')

@section('content')
<div class="page-header d-print-none">
  <div class="row align-items-center">
    <div class="col">
      <h2 class="page-title">Master Unit (Populasi Asset)</h2>
    </div>
    <div class="col-auto ms-auto d-print-none">
      <a href="{{ route('master-units.create') }}" class="btn btn-primary">
        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
        Tambah Unit Baru
      </a>
    </div>
  </div>
</div>

@if (session('success'))
    <div class="alert alert-success mt-3">{{ session('success') }}</div>
@endif

<div class="card mt-3">
  <div class="card-header border-0 pb-0">
    <form action="{{ route('master-units.index') }}" method="GET" class="d-flex">
      <input type="text" name="search" class="form-control form-control-sm me-2" placeholder="Cari Nomor Unit, No Polisi, atau Chassis..." value="{{ request('search') }}">
      <button type="submit" class="btn btn-sm btn-primary">Cari</button>
      @if(request('search'))
        <a href="{{ route('master-units.index') }}" class="btn btn-sm btn-light ms-2">Reset</a>
      @endif
    </form>
  </div>
  <div class="table-responsive mt-3">
    <table class="table table-vcenter card-table table-striped">
      <thead>
        <tr>
          <th>Nomor Unit</th>
          <th>Tipe / Model</th>
          <th>SN Chassis / Engine</th>
          <th>No. Polisi</th>
          <th>Status</th>
          <th class="w-1">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($masterUnits as $unit)
        <tr>
          <td><strong>{{ $unit->nomor_unit }}</strong><br><span class="text-muted">{{ $unit->site }}</span></td>
          <td>
            <span class="badge bg-blue-lt">{{ $unit->type->name ?? '-' }}</span>
            <br>
            <small class="text-muted">{{ $unit->model->name ?? '-' }}</small>
          </td>
          <td>
            Chassis: {{ $unit->sn_chassis ?? '-' }}<br>
            Engine: {{ $unit->sn_engine ?? '-' }}
          </td>
          <td>{{ $unit->no_polisi ?? '-' }}</td>
          <td>
            @if($unit->active)
              <span class="badge bg-green">Active</span>
            @else
              <span class="badge bg-red">Inactive</span>
            @endif
            @if($unit->service)
              <span class="badge bg-orange">In Service</span>
            @endif
          </td>
          <td>
            <div class="btn-list flex-nowrap">
              <a href="{{ route('master-units.show', $unit) }}" class="btn btn-sm btn-outline-info">Detail</a>
              <a href="{{ route('master-units.edit', $unit) }}" class="btn btn-sm btn-outline-primary">Edit</a>
              <form action="{{ route('master-units.destroy', $unit) }}" method="POST" onsubmit="return confirm('Hapus unit ini secara permanen?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
              </form>
            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="6" class="text-center text-muted py-4">Data Unit belum tersedia.</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($masterUnits->hasPages())
    <div class="card-footer d-flex align-items-center">
      {{ $masterUnits->withQueryString()->links('pagination::bootstrap-5') }}
    </div>
  @endif
</div>
<div class="modal modal-blur fade" id="modal-tambah-unit" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Tambah Unit Baru</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('master-units.store') }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label required">Code Unit</label>
              <input type="text" class="form-control" name="code_unit" value="{{ old('code_unit') }}" required>
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label required">Type Unit</label>
              <select class="form-select" name="unit_type_id" required>
                <option value="">-- Pilih Type --</option>
                @foreach ($unitTypes as $type)
                  <option value="{{ $type->id }}" {{ old('unit_type_id') == $type->id ? 'selected' : '' }}>{{ $type->name_type }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label required">Model Unit</label>
              <select class="form-select" name="unit_model_id" required>
                <option value="">-- Pilih Model --</option>
                @foreach ($unitModels as $model)
                  <option value="{{ $model->id }}" {{ old('unit_model_id') == $model->id ? 'selected' : '' }}>{{ $model->name_model }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label required">Nomor Serial (S/N)</label>
              <input type="text" class="form-control" name="serial_number" value="{{ old('serial_number') }}" required>
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Engine Model</label>
              <input type="text" class="form-control" name="engine_model" value="{{ old('engine_model') }}">
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Tahun Pembuatan</label>
              <input type="number" class="form-control" name="tahun_pembuatan" value="{{ old('tahun_pembuatan') }}">
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label required">Status</label>
              <select class="form-select" name="status" required>
                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
              </select>
            </div>
            @if(is_null(auth()->user()->site_id))
            <div class="col-md-6 mb-3">
              <label class="form-label required">Site (Cabang)</label>
              <select class="form-select" name="site_id" required>
                <option value="">-- Pilih Site --</option>
                @foreach ($sites as $site)
                  <option value="{{ $site->id }}" {{ old('site_id') == $site->id ? 'selected' : '' }}>{{ $site->name }}</option>
                @endforeach
              </select>
            </div>
            @endif
            <div class="col-md-12 mb-3">
              <label class="form-label">Foto Asset (Opsional)</label>
              <input type="file" class="form-control" name="foto" accept="image/*">
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn me-auto" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan Unit</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
