@extends('layouts.tabler')

@section('title', 'Isi Ulang BBM Fuel Truck')

@section('content')
<div class="page-header d-print-none">
  <div class="container-xl">
    <div class="row g-2 align-items-center">
      <div class="col">
        <div class="page-pretitle text-uppercase font-monospace text-primary">Refill Form</div>
        <h2 class="page-title">Pengisian BBM dari Tangki ke Mobile Fuel Truck</h2>
      </div>
      <div class="col-auto ms-auto">
        <a href="{{ route('fuel.truck-fillings.index') }}" class="btn btn-outline-secondary btn-sm">
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
          <form action="{{ route('fuel.truck-fillings.store') }}" method="POST">
            @csrf
            <div class="card-header border-0 pb-1">
              <h3 class="card-title fw-bold text-warning">Form Pengisian Fuel Truck</h3>
            </div>
            <div class="card-body">
              <div class="row g-2 mb-3">
                <div class="col-6">
                  <label class="form-label required">Nomor Dokumen Refill</label>
                  <input type="text" class="form-control font-monospace bg-light" name="refill_number" value="{{ $autoNumber }}" readonly>
                </div>
                <div class="col-6">
                  <label class="form-label required">Waktu & Tanggal Pengisian</label>
                  <input type="datetime-local" class="form-control" name="fill_date" value="{{ now()->format('Y-m-d\TH:i') }}" required>
                </div>
              </div>

              <div class="row g-2 mb-3">
                <div class="col-6">
                  <label class="form-label required">Tangki Timbun Sumber</label>
                  <select name="fuel_storage_id" id="storage_select" class="form-select" required>
                    <option value="">-- Pilih Tangki Timbun --</option>
                    @foreach($storages as $st)
                      <option value="{{ $st->id }}" data-stock="{{ $st->current_stock }}" data-tot="{{ $st->current_totalizer }}">
                        {{ $st->code }} - {{ $st->name }} (Stok: {{ number_format($st->current_stock, 0) }} L)
                      </option>
                    @endforeach
                  </select>
                </div>
                <div class="col-6">
                  <label class="form-label required">Unit Fuel Truck Penerima</label>
                  <select name="fuel_truck_id" class="form-select" required>
                    <option value="">-- Pilih Fuel Truck --</option>
                    @foreach($fuelTrucks as $ft)
                      <option value="{{ $ft->id }}">
                        {{ $ft->masterUnit->nomor_unit ?? '-' }} (Kapasitas: {{ number_format($ft->capacity, 0) }} L, Sisa: {{ number_format($ft->current_stock, 0) }} L)
                      </option>
                    @endforeach
                  </select>
                </div>
              </div>

              <div class="row g-2 mb-3">
                <div class="col-6">
                  <label class="form-label required">Shift</label>
                  <select name="shift" class="form-select" required>
                    <option value="Shift 1">Shift 1 (Siang)</option>
                    <option value="Shift 2">Shift 2 (Malam)</option>
                  </select>
                </div>
                <div class="col-6">
                  <label class="form-label required">Jumlah Volume yang Diisi (Liter)</label>
                  <input type="number" step="0.01" class="form-control form-control-lg fw-bold text-warning" name="volume_liters" placeholder="Misal: 5000" required>
                </div>
              </div>

              <div class="row g-2 mb-3">
                <div class="col-6">
                  <label class="form-label">Totalizer Pompa Tangki Awal</label>
                  <input type="number" step="0.01" class="form-control font-monospace" name="storage_totalizer_before" id="st_tot_before" placeholder="Awal">
                </div>
                <div class="col-6">
                  <label class="form-label">Totalizer Pompa Tangki Akhir</label>
                  <input type="number" step="0.01" class="form-control font-monospace" name="storage_totalizer_after" placeholder="Akhir">
                </div>
              </div>

              <div class="row g-2 mb-3">
                <div class="col-12">
                  <label class="form-label">Nama Driver / Operator Fuel Truck</label>
                  <input type="text" class="form-control" name="driver_fuel_truck" placeholder="Nama Driver / Fuelman yang menerima">
                </div>
              </div>

              <div class="mb-3">
                <label class="form-label">Catatan Tambahan</label>
                <textarea class="form-control" name="notes" rows="2" placeholder="Catatan opsional"></textarea>
              </div>
            </div>
            <div class="card-footer d-flex justify-content-end gap-2">
              <a href="{{ route('fuel.truck-fillings.index') }}" class="btn btn-secondary">Batal</a>
              <button type="submit" class="btn btn-warning fw-bold">Simpan Pengisian Fuel Truck</button>
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
    const stSelect = document.getElementById('storage_select');
    const stTotBefore = document.getElementById('st_tot_before');
    if (stSelect) {
        stSelect.addEventListener('change', function () {
            const opt = this.options[this.selectedIndex];
            if (opt && opt.value) {
                stTotBefore.value = opt.getAttribute('data-tot') || '';
            }
        });
    }
});
</script>
@endpush
@endsection
