@extends('layouts.tabler')

@section('title', 'Tambah Hour Meter - CMMS Aisfar')

@section('content')
<div class="page-header d-print-none">
  <div class="row align-items-center">
    <div class="col">
      <h2 class="page-title">Tambah Hour Meter</h2>
    </div>
    <div class="col-auto ms-auto d-print-none">
      <a href="{{ route('hour-meters.index') }}" class="btn btn-secondary">Kembali</a>
    </div>
  </div>
</div>

<div class="card mt-3">
  <div class="card-body">
    <form action="{{ route('hour-meters.store') }}" method="post">
      @csrf
      <div class="row">
        <div class="col-md-4 mb-3">
          <label class="form-label required">Date</label>
          <input type="date" class="form-control" name="date" value="{{ old('date', date('Y-m-d')) }}" required>
        </div>
        <div class="col-md-4 mb-3">
          <label class="form-label required">Unit</label>
          <select name="master_unit_id" id="master_unit_id" class="form-select" required>
            <option value="">-- Pilih Unit --</option>
            @foreach($masterUnits as $unit)
              <option value="{{ $unit->id }}" 
                      data-model="{{ $unit->model ? $unit->model->name : '-' }}" 
                      data-site="{{ $unit->site ? $unit->site->name : '-' }}"
                      {{ old('master_unit_id') == $unit->id ? 'selected' : '' }}>
                {{ $unit->nomor_unit }}
              </option>
            @endforeach
          </select>
        </div>
        <div class="col-md-4 mb-3">
          <label class="form-label">Model (Otomatis)</label>
          <input type="text" class="form-control" id="model_display" readonly disabled value="-">
        </div>
        @if(is_null(auth()->user()->site_id))
        <div class="col-md-4 mb-3">
          <label class="form-label">Site (Otomatis)</label>
          <input type="text" class="form-control" id="site_display" readonly disabled value="-">
        </div>
        @endif
        <div class="col-md-4 mb-3">
          <label class="form-label required">HM</label>
          <input type="number" class="form-control" name="hm" step="0.1" min="0" value="{{ old('hm') }}" required>
        </div>
      </div>
      <div class="mt-4">
        <button type="submit" class="btn btn-primary">Simpan Hour Meter</button>
      </div>
    </form>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const unitSelect = document.getElementById('master_unit_id');
    const modelDisplay = document.getElementById('model_display');
    const siteDisplay = document.getElementById('site_display');

    function updateDisplays() {
        const selectedOption = unitSelect.options[unitSelect.selectedIndex];
        if (selectedOption && selectedOption.value) {
            if(modelDisplay) modelDisplay.value = selectedOption.getAttribute('data-model');
            if(siteDisplay) siteDisplay.value = selectedOption.getAttribute('data-site');
        } else {
            if(modelDisplay) modelDisplay.value = '-';
            if(siteDisplay) siteDisplay.value = '-';
        }
    }

    unitSelect.addEventListener('change', updateDisplays);
    
    // Trigger on load for old input
    updateDisplays();
});
</script>
@endsection
