@extends('layouts.tabler')

@section('title', 'Tambah Master Unit - CMMS Aisfar')

@section('content')
<div class="page-header d-print-none">
  <div class="row align-items-center">
    <div class="col">
      <h2 class="page-title">Tambah Master Unit Baru</h2>
    </div>
    <div class="col-auto ms-auto d-print-none">
      <a href="{{ route('master-units.index') }}" class="btn btn-secondary">Kembali</a>
    </div>
  </div>
</div>

@if ($errors->any())
    <div class="alert alert-danger mt-3">
        <ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
@endif

<div class="card mt-3">
  <form action="{{ route('master-units.store') }}" method="POST">
    @csrf
    <div class="card-body">
      <div class="row row-cards">
        <!-- Kolom 1 -->
        <div class="col-md-4">
          <div class="mb-3">
            <label class="form-label required">Nomor Unit</label>
            <input type="text" name="nomor_unit" class="form-control" placeholder="A-07, MDT006, dll" value="{{ old('nomor_unit') }}" required>
          </div>
            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label required">Status</label>
                <select class="form-select" name="status" required>
                  <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                  <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                  <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                </select>
              </div>
            </div>
          <div class="mb-3">
            <label class="form-label required">Tipe Unit</label>
            <div class="input-group">
              <select name="unit_type_id" id="unit_type_id" class="form-select" required>
                <option value="">-- Pilih Tipe --</option>
                @foreach($unitTypes as $type)
                  <option value="{{ $type->id }}" {{ old('unit_type_id') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                @endforeach
              </select>
              <button class="btn btn-outline-secondary" type="button" data-bs-toggle="modal" data-bs-target="#modal-add-type" title="Tambah Tipe Baru">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon m-0" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
              </button>
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label">Model Unit</label>
            <div class="input-group">
              <select name="unit_model_id" id="unit_model_id" class="form-select">
                <option value="">-- Pilih Model --</option>
                @foreach($unitModels as $model)
                  <option value="{{ $model->id }}" data-type-id="{{ $model->unit_type_id }}" {{ old('unit_model_id') == $model->id ? 'selected' : '' }}>{{ $model->name }}</option>
                @endforeach
              </select>
              <button class="btn btn-outline-secondary" type="button" data-bs-toggle="modal" data-bs-target="#modal-add-model" title="Tambah Model Baru">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon m-0" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
              </button>
            </div>
            <small class="text-muted d-block mt-1">Pilih Tipe Unit terlebih dahulu sebelum menambah Model baru.</small>
          </div>
          @if(is_null(auth()->user()->site_id))
          <div class="mb-3">
            <label class="form-label required">Site Lokasi</label>
            <select name="site_id" class="form-select" required>
              <option value="">-- Pilih Site --</option>
              @foreach ($sites as $site)
                <option value="{{ $site->id }}" {{ old('site_id') == $site->id ? 'selected' : '' }}>{{ $site->name }}</option>
              @endforeach
            </select>
          </div>
          @endif
          <div class="mb-3">
            <label class="form-label">Lokasi Saat Ini</label>
            <input type="text" name="location" class="form-control" value="{{ old('location') }}">
          </div>
          <div class="mb-3">
            <label class="form-label">Asal Unit (Dari)</label>
            <input type="text" name="dari" class="form-control" value="{{ old('dari') }}">
          </div>
          <div class="mb-3">
            <label class="form-label">Tanggal Diterima</label>
            <input type="date" name="date_receive" class="form-control" value="{{ old('date_receive') }}">
          </div>
        </div>

        <!-- Kolom 2 -->
        <div class="col-md-4">
          <div class="mb-3">
            <label class="form-label">SN Chassis</label>
            <input type="text" name="sn_chassis" class="form-control" value="{{ old('sn_chassis') }}">
          </div>
          <div class="mb-3">
            <label class="form-label">Model Mesin</label>
            <input type="text" name="engine_model" class="form-control" value="{{ old('engine_model') }}">
          </div>
          <div class="mb-3">
            <label class="form-label">SN Engine</label>
            <input type="text" name="sn_engine" class="form-control" value="{{ old('sn_engine') }}">
          </div>
          <div class="mb-3">
            <label class="form-label">Merek Mesin (Engine Make)</label>
            <input type="text" name="engine_make" class="form-control" value="{{ old('engine_make') }}">
          </div>
          <div class="mb-3">
            <label class="form-label">Tahun Perakitan</label>
            <input type="text" name="perakitan" class="form-control" placeholder="YYYY" value="{{ old('perakitan') }}">
          </div>
          <div class="mb-3">
            <label class="form-label">Kapasitas</label>
            <input type="text" name="capacity" class="form-control" value="{{ old('capacity') }}">
          </div>
          <div class="mb-3">
            <label class="form-label">Horse Power (HP)</label>
            <input type="text" name="hp" class="form-control" value="{{ old('hp') }}">
          </div>
        </div>

        <!-- Kolom 3 -->
        <div class="col-md-4">
          <div class="mb-3">
            <label class="form-label">Kilo Watt (kW)</label>
            <input type="text" name="kw" class="form-control" value="{{ old('kw') }}">
          </div>
          <div class="mb-3">
            <label class="form-label">Nomor Polisi</label>
            <input type="text" name="no_polisi" class="form-control" value="{{ old('no_polisi') }}">
          </div>
          <div class="mb-3">
            <label class="form-label">Attachments/Perlengkapan</label>
            <input type="text" name="attachments" class="form-control" value="{{ old('attachments') }}">
          </div>
          <div class="mb-3">
            <label class="form-label">Remarks (Keterangan)</label>
            <textarea name="remarks" class="form-control" rows="3">{{ old('remarks') }}</textarea>
          </div>
          <div class="mb-3">
            <div class="form-label">Status Service</div>
            <label class="form-check form-switch">
              <input class="form-check-input" type="checkbox" name="service" value="1" {{ old('service') ? 'checked' : '' }}>
              <span class="form-check-label">Sedang diservis?</span>
            </label>
          </div>
          <div class="mb-3">
            <div class="form-label">Status Aktif</div>
            <label class="form-check form-switch">
              <input class="form-check-input" type="checkbox" name="active" value="1" {{ old('active', 1) ? 'checked' : '' }}>
              <span class="form-check-label">Unit Aktif</span>
            </label>
          </div>
        </div>
      </div>
    </div>
    <div class="card-footer text-end">
      <button type="submit" class="btn btn-primary">Simpan Data Unit</button>
    </div>
  </form>
</div>

<!-- Modal Tambah Tipe Unit (AJAX) -->
<div class="modal modal-blur fade" id="modal-add-type" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Tambah Tipe Baru</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label required">Nama Tipe Unit</label>
          <input type="text" id="ajax_new_type_name" class="form-control" placeholder="Masukkan nama tipe">
          <div class="invalid-feedback" id="error_new_type"></div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn me-auto" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-primary" id="btn-save-type">Simpan & Pilih</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Tambah Model Unit (AJAX) -->
<div class="modal modal-blur fade" id="modal-add-model" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Tambah Model Baru</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label required">Nama Model Unit</label>
          <input type="text" id="ajax_new_model_name" class="form-control" placeholder="Masukkan nama model">
          <div class="invalid-feedback" id="error_new_model"></div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn me-auto" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-primary" id="btn-save-model">Simpan & Pilih</button>
      </div>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const typeSelect = document.getElementById('unit_type_id');
    const modelSelect = document.getElementById('unit_model_id');

    // Filter model based on selected type
    function filterModels() {
        const selectedTypeId = typeSelect.value;
        Array.from(modelSelect.options).forEach(option => {
            if (option.value === "") return; // Skip empty option
            if (option.getAttribute('data-type-id') === selectedTypeId) {
                option.style.display = '';
            } else {
                option.style.display = 'none';
                if(modelSelect.value === option.value) {
                    modelSelect.value = ''; // Reset selection if filtered out
                }
            }
        });
    }

    typeSelect.addEventListener('change', filterModels);
    // Initial filter if old value is present
    if (typeSelect.value) filterModels();

    // AJAX Save Tipe Unit
    document.getElementById('btn-save-type').addEventListener('click', function() {
        const nameInput = document.getElementById('ajax_new_type_name');
        const name = nameInput.value;
        const errorDiv = document.getElementById('error_new_type');
        
        if(!name) {
            nameInput.classList.add('is-invalid');
            errorDiv.textContent = 'Nama tipe wajib diisi';
            return;
        }

        fetch("{{ route('unit-types.store') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ name: name })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Add new option and select it
                const option = new Option(data.data.name, data.data.id, true, true);
                typeSelect.add(option);
                filterModels(); // re-filter models
                
                // Close modal
                nameInput.value = '';
                nameInput.classList.remove('is-invalid');
                bootstrap.Modal.getInstance(document.getElementById('modal-add-type')).hide();
            } else if (data.errors) {
                nameInput.classList.add('is-invalid');
                errorDiv.textContent = data.errors.name[0];
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan sistem.');
        });
    });

    // AJAX Save Model Unit
    document.getElementById('btn-save-model').addEventListener('click', function() {
        const nameInput = document.getElementById('ajax_new_model_name');
        const name = nameInput.value;
        const typeId = typeSelect.value;
        const errorDiv = document.getElementById('error_new_model');
        
        if(!typeId) {
            alert('Silakan pilih Tipe Unit terlebih dahulu pada form utama!');
            bootstrap.Modal.getInstance(document.getElementById('modal-add-model')).hide();
            return;
        }

        if(!name) {
            nameInput.classList.add('is-invalid');
            errorDiv.textContent = 'Nama model wajib diisi';
            return;
        }

        fetch("{{ route('unit-models.store') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ name: name, unit_type_id: typeId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Add new option and select it
                const option = new Option(data.data.name, data.data.id, true, true);
                option.setAttribute('data-type-id', data.data.unit_type_id);
                modelSelect.add(option);
                
                // Close modal
                nameInput.value = '';
                nameInput.classList.remove('is-invalid');
                bootstrap.Modal.getInstance(document.getElementById('modal-add-model')).hide();
            } else if (data.errors) {
                nameInput.classList.add('is-invalid');
                errorDiv.textContent = data.errors.name[0];
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan sistem.');
        });
    });
});
</script>
@endpush
