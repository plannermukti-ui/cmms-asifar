@extends('layouts.tabler')

@section('title', 'Buat Penerimaan BBM dari Vendor (Inbound)')

@section('content')
<div class="page-header d-print-none">
  <div class="container-xl">
    <div class="row g-2 align-items-center">
      <div class="col">
        <div class="page-pretitle text-uppercase font-monospace text-primary">Inbound Form</div>
        <h2 class="page-title">Penerimaan BBM dari Vendor / Truk Supplier</h2>
      </div>
      <div class="col-auto ms-auto">
        <a href="{{ route('fuel.receivings.index') }}" class="btn btn-outline-secondary btn-sm">
          &laquo; Batal / Kembali
        </a>
      </div>
    </div>
  </div>
</div>

<div class="page-body">
  <div class="container-xl">
    
    <form action="{{ route('fuel.receivings.store') }}" method="POST" enctype="multipart/form-data">
      @csrf

      <div class="row g-3">
        <!-- Kolom Kiri: Informasi Dokumen & Vendor -->
        <div class="col-lg-6">
          <div class="card shadow-sm border h-100">
            <div class="card-header border-0 pb-1">
              <h3 class="card-title fw-bold text-primary">1. Informasi Vendor, Truk & Dokumen DO</h3>
            </div>
            <div class="card-body">
              <div class="row g-2 mb-3">
                <div class="col-6">
                  <label class="form-label required">Nomor Penerimaan (Auto)</label>
                  <input type="text" class="form-control font-monospace bg-light" name="receiving_number" value="{{ $autoNumber }}" readonly>
                </div>
                <div class="col-6">
                  <label class="form-label required">Tanggal & Jam Terima</label>
                  <input type="datetime-local" class="form-control" name="date_receive" value="{{ now()->format('Y-m-d\TH:i') }}" required>
                </div>
              </div>

              <div class="mb-3">
                <label class="form-label required">Vendor / Supplier BBM</label>
                <select name="vendor_id" id="vendor_select" class="form-select" required>
                  <option value="">-- Pilih Vendor --</option>
                  @foreach($vendors as $v)
                    <option value="{{ $v->id }}">{{ $v->name }}</option>
                  @endforeach
                </select>
              </div>

              <div class="mb-3">
                <label class="form-label">Truk Tangki Pengantar Terdaftar (Opsional)</label>
                <select name="fuel_supplier_truck_id" id="supplier_truck_select" class="form-select">
                  <option value="">-- Pilih Truk Terdaftar / Manual --</option>
                  @foreach($supplierTrucks as $st)
                    <option value="{{ $st->id }}" data-plat="{{ $st->truck_plat_nomor }}" data-driver="{{ $st->driver_name }}" data-vendor="{{ $st->vendor_id }}">
                      {{ $st->truck_plat_nomor }} ({{ $st->driver_name }} - {{ $st->vendor->name ?? '' }})
                    </option>
                  @endforeach
                </select>
              </div>

              <div class="row g-2 mb-3">
                <div class="col-6">
                  <label class="form-label required">No. Plat Truk Tangki</label>
                  <input type="text" class="form-control text-uppercase" name="truck_plat_nomor" id="truck_plat_input" placeholder="B 9876 XYZ" required>
                </div>
                <div class="col-6">
                  <label class="form-label required">Nama Supir / Driver</label>
                  <input type="text" class="form-control" name="driver_name" id="driver_name_input" placeholder="Nama Supir" required>
                </div>
              </div>

              <div class="row g-2 mb-3">
                <div class="col-6">
                  <label class="form-label required">No. Surat Jalan / DO Supplier</label>
                  <input type="text" class="form-control" name="delivery_order_number" placeholder="DO-12345678" required>
                </div>
                <div class="col-6">
                  <label class="form-label">No. Purchase Order (PO)</label>
                  <input type="text" class="form-control" name="po_number" placeholder="PO-2026-001">
                </div>
              </div>

              <div class="mb-3">
                <label class="form-label required text-primary fw-bold">Pilih Atasan / Approver yang Akan Menyetujui</label>
                <select name="intended_approver_id" class="form-select" required>
                  <option value="">-- Pilih Pejabat / Atasan Approval --</option>
                  @foreach($approverUsers as $au)
                    <option value="{{ $au->id }}">
                      {{ $au->nama_lengkap ?? $au->name }} ({{ $au->jabatan ?? $au->role->name ?? 'User' }})
                    </option>
                  @endforeach
                </select>
                <small class="text-muted">Notifikasi dan wewenang persetujuan penerimaan ini akan ditujukan ke pejabat di atas.</small>
              </div>

              <div class="mb-3">
                <label class="form-label">Upload Scan Surat Jalan / BAP / Foto Sonding (Max 10MB)</label>
                <input type="file" class="form-control" name="document_scan" accept=".pdf,image/*">
              </div>
            </div>
          </div>
        </div>

        <!-- Kolom Kanan: Tangki Tujuan, Sonding & Flowmeter -->
        <div class="col-lg-6">
          <div class="card shadow-sm border h-100">
            <div class="card-header border-0 pb-1">
              <h3 class="card-title fw-bold text-azure">2. Tangki Tujuan, Hasil Sonding & Volume</h3>
            </div>
            <div class="card-body">
              <div class="mb-3">
                <label class="form-label required">Tangki Timbun / Station Penerima</label>
                <select name="fuel_storage_id" id="storage_select" class="form-select" required>
                  <option value="">-- Pilih Tangki Timbun --</option>
                  @foreach($storages as $st)
                    <option value="{{ $st->id }}" data-tot="{{ $st->current_totalizer }}">
                      {{ $st->code }} - {{ $st->name }} (Sisa Stok: {{ number_format($st->current_stock, 0, ',', '.') }} / {{ number_format($st->capacity, 0, ',', '.') }} L)
                    </option>
                  @endforeach
                </select>
              </div>

              <!-- Sonding Parameters -->
              <div class="p-3 bg-body-tertiary rounded border mb-3">
                <div class="fw-bold small text-body mb-2 text-uppercase">Hasil Pengukuran Fisik / Sonding Tangki</div>
                <div class="row g-2 mb-2">
                  <div class="col-6">
                    <label class="form-label small">Sonding Awal (cm)</label>
                    <input type="number" step="0.01" class="form-control form-control-sm" name="sonding_awal_cm" placeholder="0.00">
                  </div>
                  <div class="col-6">
                    <label class="form-label small">Sonding Akhir (cm)</label>
                    <input type="number" step="0.01" class="form-control form-control-sm" name="sonding_akhir_cm" placeholder="0.00">
                  </div>
                </div>
                <div class="row g-2">
                  <div class="col-6">
                    <label class="form-label small">Densitas BBM (g/ml)</label>
                    <input type="number" step="0.0001" class="form-control form-control-sm" name="density" placeholder="0.8450">
                  </div>
                  <div class="col-6">
                    <label class="form-label small">Suhu / Temperatur (°C)</label>
                    <input type="number" step="0.1" class="form-control form-control-sm" name="temperature" placeholder="29.5">
                  </div>
                </div>
              </div>

              <!-- Volume & Flowmeter -->
              <div class="row g-2 mb-3">
                <div class="col-6">
                  <label class="form-label required">Volume di Surat Jalan DO (Liter)</label>
                  <input type="number" step="0.01" class="form-control fw-bold" name="do_volume_liters" id="do_volume_input" placeholder="16000" required>
                </div>
                <div class="col-6">
                  <label class="form-label required">Volume Aktual Diterima (Liter)</label>
                  <input type="number" step="0.01" class="form-control fw-bold text-azure" name="received_volume_liters" id="rec_volume_input" placeholder="16000" required>
                </div>
              </div>

              <div class="row g-2 mb-3">
                <div class="col-6">
                  <label class="form-label">Totalizer Pompa Awal</label>
                  <input type="number" step="0.01" class="form-control font-monospace" name="totalizer_before" id="tot_before_input">
                </div>
                <div class="col-6">
                  <label class="form-label">Totalizer Pompa Akhir</label>
                  <input type="number" step="0.01" class="form-control font-monospace" name="totalizer_after" id="tot_after_input">
                </div>
              </div>

              <div class="mb-3">
                <label class="form-label">Catatan Tambahan</label>
                <textarea class="form-control" name="notes" rows="2" placeholder="Catatan kondisi segel tangki, kendala, dll"></textarea>
              </div>

              <div class="alert alert-info small py-2 mb-0">
                <strong>Catatan:</strong> Setelah disimpan, data berstatus <em>Submitted</em>. Stok tangki akan otomatis bertambah setelah dokumen ini disetujui (<em>Approved</em>) oleh Atasan.
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="d-flex justify-content-end gap-2 mt-3">
        <a href="{{ route('fuel.receivings.index') }}" class="btn btn-secondary">Batal</a>
        <button type="submit" class="btn btn-primary px-4 fw-bold">Simpan Penerimaan BBM</button>
      </div>
    </form>

  </div>
</div>

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {
    const truckSelect = document.getElementById('supplier_truck_select');
    const platInput = document.getElementById('truck_plat_input');
    const driverInput = document.getElementById('driver_name_input');
    const vendorSelect = document.getElementById('vendor_select');
    const storageSelect = document.getElementById('storage_select');
    const totBeforeInput = document.getElementById('tot_before_input');

    if (truckSelect) {
        truckSelect.addEventListener('change', function () {
            const opt = this.options[this.selectedIndex];
            if (opt && opt.value) {
                platInput.value = opt.getAttribute('data-plat') || '';
                driverInput.value = opt.getAttribute('data-driver') || '';
                const vId = opt.getAttribute('data-vendor');
                if (vId && vendorSelect) vendorSelect.value = vId;
            }
        });
    }

    if (storageSelect) {
        storageSelect.addEventListener('change', function () {
            const opt = this.options[this.selectedIndex];
            if (opt && opt.value) {
                totBeforeInput.value = opt.getAttribute('data-tot') || '0';
            }
        });
    }
});
</script>
@endpush
@endsection
