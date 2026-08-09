@extends('layouts.tabler')
@section('title', 'Tambah Stok Tool Manual')
@section('content')
<div class="page-header d-print-none">
  <div class="row align-items-center">
    <div class="col"><h2 class="page-title">Tambah Stok Manual</h2></div>
    <div class="col-auto ms-auto"><a href="{{ route('tool-stocks.index') }}" class="btn btn-secondary">Batal</a></div>
  </div>
</div>
<div class="row mt-3">
  <div class="col-md-6">
    <div class="card"><div class="card-body">
      <form action="{{ route('tool-stocks.store') }}" method="post">
          @csrf
          <div class="mb-3">
            <label class="form-label required">Tool</label>
            <select name="tool_id" class="form-select" required>
                @foreach($tools as $tool) <option value="{{ $tool->id }}">{{ $tool->name }}</option> @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label required">Lokasi Stok</label>
            <select name="location_type" class="form-select" id="locationTypeSelect" onchange="toggleMekanik()" required>
                <option value="ToolRoom">Tool Room</option>
                <option value="Mechanic">Mekanik</option>
            </select>
          </div>
          <div class="mb-3" id="mechanicWrap" style="display:none;">
            <label class="form-label required">Mekanik</label>
            <select name="mechanic_id" class="form-select">
                <option value="">-- Pilih Mekanik --</option>
                @foreach($mechanics as $mechanic) <option value="{{ $mechanic->id }}">{{ $mechanic->nama_lengkap }}</option> @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label required">Kuantitas</label>
            <input type="number" class="form-control" name="quantity" min="0" required value="0">
          </div>
          <button type="submit" class="btn btn-primary">Simpan</button>
      </form>
    </div></div>
  </div>
</div>
<script>
    function toggleMekanik() {
        var val = document.getElementById('locationTypeSelect').value;
        var wrap = document.getElementById('mechanicWrap');
        if(val === 'Mechanic') { wrap.style.display = 'block'; } else { wrap.style.display = 'none'; }
    }
</script>
@endsection