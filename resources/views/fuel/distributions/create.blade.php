@extends('layouts.tabler')

@section('title', 'Buka Sesi Distribusi Shift Baru - FMS')

@section('content')
<div class="page-header d-print-none">
  <div class="container-xl">
    <div class="row g-2 align-items-center">
      <div class="col">
        <div class="page-pretitle text-uppercase font-monospace text-primary">Shift Session Form</div>
        <h2 class="page-title">Buka Sesi Distribusi Fuel Truck per Shift</h2>
      </div>
      <div class="col-auto ms-auto">
        <a href="{{ route('fuel.distributions.index') }}" class="btn btn-outline-secondary btn-sm">
          &laquo; Batal
        </a>
      </div>
    </div>
  </div>
</div>

<div class="page-body">
  <div class="container-xl">
    
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <div class="card shadow-sm border">
          <form action="{{ route('fuel.distributions.store') }}" method="POST">
            @csrf
            <div class="card-header border-0 pb-1">
              <h3 class="card-title fw-bold text-success">Form Buka Sesi Shift Pengisian</h3>
            </div>
            <div class="card-body">
              <div class="row g-2 mb-3">
                <div class="col-6">
                  <label class="form-label required">Nomor Dokumen Shift</label>
                  <input type="text" class="form-control font-monospace bg-light" name="shift_doc_number" value="{{ $autoNumber }}" readonly>
                </div>
                <div class="col-6">
                  <label class="form-label required">Tanggal Shift</label>
                  <input type="date" class="form-control" name="date" value="{{ now()->toDateString() }}" required>
                </div>
              </div>

              <div class="row g-2 mb-3">
                <div class="col-6">
                  <label class="form-label required">Pilih Unit Fuel Truck</label>
                  <select name="fuel_truck_id" id="truck_select" class="form-select" required>
                    <option value="">-- Pilih Fuel Truck --</option>
                    @foreach($fuelTrucks as $ft)
                      <option value="{{ $ft->id }}" data-tot="{{ $ft->current_totalizer }}">
                        {{ $ft->masterUnit->nomor_unit ?? '-' }} (Stok Saat Ini: {{ number_format($ft->current_stock, 0) }} L)
                      </option>
                    @endforeach
                  </select>
                </div>
                <div class="col-6">
                  <label class="form-label required">Shift Kerja</label>
                  <select name="shift" class="form-select" required>
                    <option value="Shift 1">Shift 1 (Siang: 06:00 - 18:00)</option>
                    <option value="Shift 2">Shift 2 (Malam: 18:00 - 06:00)</option>
                  </select>
                </div>
              </div>

              <div class="row g-2 mb-3">
                <div class="col-6">
                  <label class="form-label required">Nama Petugas Fuelman / Supir FT</label>
                  <input type="text" class="form-control" name="fuelman_name" placeholder="Nama Petugas Fuelman" required>
                </div>
                <div class="col-6">
                  <label class="form-label required">Angka Totalizer Flowmeter Awal Shift</label>
                  <input type="number" step="0.01" class="form-control font-monospace fw-bold text-primary" name="totalizer_start" id="tot_start" placeholder="0.00" required>
                  <small class="text-muted">Totalizer awal flowmeter saat truk mulai beroperasi.</small>
                </div>
              </div>

              <div class="mb-3">
                <label class="form-label">Catatan Awal Shift</label>
                <textarea class="form-control" name="notes" rows="2" placeholder="Catatan kondisi flowmeter atau armada jika ada"></textarea>
              </div>

              <div class="alert alert-info small py-2 mb-0">
                <strong>Panduan:</strong> Totalizer dicatat di awal shift dan di akhir shift. Setelah sesi shift dibuka, Anda dapat memasukkan rincian pengisian ke setiap unit operasional (Unit, Jam, HM/KM, Operator, Liter) dengan mudah dan cepat.
              </div>
            </div>
            <div class="card-footer d-flex justify-content-end gap-2">
              <a href="{{ route('fuel.distributions.index') }}" class="btn btn-secondary">Batal</a>
              <button type="submit" class="btn btn-success fw-bold">Buka Sesi Shift & Input Unit</button>
            </div>
          </form>
        </div>
      </div>
    </div>

  </div>
</div>

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {
    const truckSelect = document.getElementById('truck_select');
    const totStart = document.getElementById('tot_start');
    if (truckSelect) {
        truckSelect.addEventListener('change', function () {
            const opt = this.options[this.selectedIndex];
            if (opt && opt.value) {
                totStart.value = opt.getAttribute('data-tot') || '0';
            }
        });
    }
});
</script>
@endpush
@endsection
