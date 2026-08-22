@extends('layouts.tabler')

@section('title', 'Buat Mutasi BBM Antar Tangki')

@section('content')
<div class="page-header d-print-none">
  <div class="container-xl">
    <div class="row g-2 align-items-center">
      <div class="col">
        <div class="page-pretitle text-uppercase font-monospace text-primary">Transfer Form</div>
        <h2 class="page-title">Mutasi BBM Antar Tangki Timbun / Station</h2>
      </div>
      <div class="col-auto ms-auto">
        <a href="{{ route('fuel.transfers.index') }}" class="btn btn-outline-secondary btn-sm">
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
          <form action="{{ route('fuel.transfers.store') }}" method="POST">
            @csrf
            <div class="card-header border-0 pb-1">
              <h3 class="card-title fw-bold text-primary">Form Mutasi Tangki</h3>
            </div>
            <div class="card-body">
              <div class="row g-2 mb-3">
                <div class="col-6">
                  <label class="form-label required">Nomor Mutasi</label>
                  <input type="text" class="form-control font-monospace bg-light" name="transfer_number" value="{{ $autoNumber }}" readonly>
                </div>
                <div class="col-6">
                  <label class="form-label required">Waktu / Tanggal Mutasi</label>
                  <input type="datetime-local" class="form-control" name="transfer_date" value="{{ now()->format('Y-m-d\TH:i') }}" required>
                </div>
              </div>

              <div class="row g-2 mb-3">
                <div class="col-6">
                  <label class="form-label required">Metode Pemindahan</label>
                  <select name="transfer_method" id="transfer_method_select" class="form-select fw-bold" required>
                    <option value="Direct Pump">1. Pompa / Pipa Langsung (Direct Pump)</option>
                    <option value="Via Fuel Truck">2. Diangkut Menggunakan Fuel Truck</option>
                  </select>
                </div>
                <div class="col-6" id="fuel_truck_wrapper" style="display: none;">
                  <label class="form-label required text-warning fw-bold">Pilih Unit Fuel Truck Pengangkut</label>
                  <select name="fuel_truck_id" class="form-select">
                    <option value="">-- Pilih Fuel Truck --</option>
                    @foreach($fuelTrucks as $ft)
                      <option value="{{ $ft->id }}">
                        {{ $ft->masterUnit->nomor_unit ?? '-' }} (Kapasitas: {{ number_format($ft->capacity, 0) }} L)
                      </option>
                    @endforeach
                  </select>
                </div>
              </div>

              <div class="row g-2 mb-3">
                <div class="col-6">
                  <label class="form-label required">Tangki Asal (Sumber BBM)</label>
                  <select name="source_storage_id" id="source_storage" class="form-select" required>
                    <option value="">-- Pilih Tangki Asal --</option>
                    @foreach($storages as $st)
                      <option value="{{ $st->id }}" data-stock="{{ $st->current_stock }}" data-tot="{{ $st->current_totalizer }}">
                        {{ $st->code }} - {{ $st->name }} (Stok: {{ number_format($st->current_stock, 0) }} L)
                      </option>
                    @endforeach
                  </select>
                </div>
                <div class="col-6">
                  <label class="form-label required">Tangki Tujuan (Penerima)</label>
                  <select name="destination_storage_id" id="dest_storage" class="form-select" required>
                    <option value="">-- Pilih Tangki Tujuan --</option>
                    @foreach($storages as $st)
                      <option value="{{ $st->id }}" data-tot="{{ $st->current_totalizer }}">
                        {{ $st->code }} - {{ $st->name }}
                      </option>
                    @endforeach
                  </select>
                </div>
              </div>

              <div class="mb-3">
                <label class="form-label required">Jumlah Volume yang Dipindahkan (Liter)</label>
                <input type="number" step="0.01" class="form-control form-control-lg fw-bold text-azure" name="volume_liters" placeholder="Contoh: 5000" required>
              </div>

              <div class="row g-2 mb-3">
                <div class="col-6">
                  <label class="form-label">Totalizer Pompa Tangki Asal (Awal &rarr; Akhir)</label>
                  <div class="input-group">
                    <input type="number" step="0.01" class="form-control font-monospace" name="source_totalizer_before" id="src_tot_before" placeholder="Awal">
                    <input type="number" step="0.01" class="form-control font-monospace" name="source_totalizer_after" placeholder="Akhir">
                  </div>
                </div>
                <div class="col-6">
                  <label class="form-label">Totalizer Pompa Tangki Tujuan (Awal &rarr; Akhir)</label>
                  <div class="input-group">
                    <input type="number" step="0.01" class="form-control font-monospace" name="dest_totalizer_before" id="dest_tot_before" placeholder="Awal">
                    <input type="number" step="0.01" class="form-control font-monospace" name="dest_totalizer_after" placeholder="Akhir">
                  </div>
                </div>
              </div>

              <div class="mb-3">
                <label class="form-label">Nama Petugas / Operator Transfer</label>
                <input type="text" class="form-control" name="operator_name" placeholder="Nama Petugas">
              </div>

              <div class="mb-3">
                <label class="form-label">Keterangan Tambahan</label>
                <textarea class="form-control" name="notes" rows="2" placeholder="Alasan mutasi / catatan lapangan"></textarea>
              </div>
            </div>
            <div class="card-footer d-flex justify-content-end gap-2">
              <a href="{{ route('fuel.transfers.index') }}" class="btn btn-secondary">Batal</a>
              <button type="submit" class="btn btn-primary fw-bold">Eksekusi Mutasi BBM</button>
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
    const srcSelect = document.getElementById('source_storage');
    const destSelect = document.getElementById('dest_storage');
    const srcTot = document.getElementById('src_tot_before');
    const destTot = document.getElementById('dest_tot_before');
    const methodSelect = document.getElementById('transfer_method_select');
    const truckWrapper = document.getElementById('fuel_truck_wrapper');

    function toggleTruck() {
        if (methodSelect && truckWrapper) {
            truckWrapper.style.display = methodSelect.value === 'Via Fuel Truck' ? 'block' : 'none';
        }
    }

    if (methodSelect) {
        methodSelect.addEventListener('change', toggleTruck);
        toggleTruck();
    }

    if (srcSelect) {
        srcSelect.addEventListener('change', function () {
            const opt = this.options[this.selectedIndex];
            if (opt && opt.value) {
                srcTot.value = opt.getAttribute('data-tot') || '';
            }
        });
    }

    if (destSelect) {
        destSelect.addEventListener('change', function () {
            const opt = this.options[this.selectedIndex];
            if (opt && opt.value) {
                destTot.value = opt.getAttribute('data-tot') || '';
            }
        });
    }
});
</script>
@endpush
@endsection
