@extends('layouts.tabler')

@section('title', 'Buat Berita Acara Pergantian/Kerusakan Flowmeter')

@section('content')
<div class="page-header d-print-none">
  <div class="container-xl">
    <div class="row g-2 align-items-center">
      <div class="col">
        <div class="page-pretitle text-uppercase font-monospace text-primary">Flowmeter Incident Report</div>
        <h2 class="page-title">Berita Acara Pergantian / Kerusakan / Kalibrasi Flowmeter</h2>
      </div>
      <div class="col-auto ms-auto">
        <a href="{{ route('fuel.flowmeter-adjustments.index') }}" class="btn btn-outline-secondary btn-sm">
          &laquo; Batal
        </a>
      </div>
    </div>
  </div>
</div>

<div class="page-body">
  <div class="container-xl">
    
    <div class="row justify-content-center">
      <div class="col-lg-9">
        <div class="card shadow-sm border">
          <form action="{{ route('fuel.flowmeter-adjustments.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card-header border-0 pb-1">
              <h3 class="card-title fw-bold text-purple">Form Berita Acara Flowmeter (B.A)</h3>
            </div>
            <div class="card-body">
              <div class="row g-2 mb-3">
                <div class="col-6">
                  <label class="form-label required">Nomor Berita Acara (Auto)</label>
                  <input type="text" class="form-control font-monospace bg-light" name="adjustment_number" value="{{ $autoNumber }}" readonly>
                </div>
                <div class="col-6">
                  <label class="form-label required">Tanggal Kejadian / Penggantian</label>
                  <input type="date" class="form-control" name="incident_date" value="{{ now()->toDateString() }}" required>
                </div>
              </div>

              <div class="row g-2 mb-3">
                <div class="col-6">
                  <label class="form-label required">Tipe Perangkat / Device</label>
                  <select name="device_type" id="device_type_select" class="form-select" required>
                    <option value="fuel_truck">Fuel Truck (Mobile Dispenser)</option>
                    <option value="fuel_storage">Fuel Storage / Station (Tangki Timbun)</option>
                  </select>
                </div>
                <div class="col-6">
                  <label class="form-label required">Pilih Unit / Tangki</label>
                  <select name="device_id" id="device_id_select" class="form-select" required>
                    <!-- Options populated via JS -->
                  </select>
                </div>
              </div>

              <div class="mb-3">
                <label class="form-label required">Jenis Kejadian</label>
                <select name="incident_type" class="form-select" required>
                  <option value="Replacement">Pergantian Flowmeter Baru (Unit Rusak / Ganti Baru)</option>
                  <option value="Damage / Breakdown">Kerusakan / Macet Flowmeter (Breakdown)</option>
                  <option value="Recalibration / Adjustment">Kalibrasi Ulang / Tera Meteran (Adjustment)</option>
                </select>
              </div>

              <!-- Parameter Flowmeter Lama vs Baru -->
              <div class="p-3 bg-body-tertiary rounded border mb-3">
                <div class="row g-3">
                  <div class="col-md-6 border-end">
                    <div class="fw-bold text-danger mb-2 small text-uppercase">Flowmeter Lama (Sebelum Diganti/Rusak)</div>
                    <div class="mb-2">
                      <label class="form-label small">No. Seri Flowmeter Lama</label>
                      <input type="text" class="form-control form-control-sm font-monospace" name="old_flowmeter_serial" id="old_serial" placeholder="Serial No...">
                    </div>
                    <div>
                      <label class="form-label small required">Totalizer Akhir (Final Sebelum Ganti)</label>
                      <input type="number" step="0.01" class="form-control form-control-sm font-monospace fw-bold" name="old_totalizer_final" id="old_totalizer" placeholder="0.00" required>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="fw-bold text-success mb-2 small text-uppercase">Flowmeter Baru (Setelah Pergantian)</div>
                    <div class="mb-2">
                      <label class="form-label small">No. Seri Flowmeter Baru</label>
                      <input type="text" class="form-control form-control-sm font-monospace" name="new_flowmeter_serial" placeholder="Serial No Baru...">
                    </div>
                    <div>
                      <label class="form-label small required">Totalizer Awal Flowmeter Baru</label>
                      <input type="number" step="0.01" class="form-control form-control-sm font-monospace fw-bold text-success" name="new_totalizer_initial" placeholder="0.00" required>
                      <small class="text-muted">Totalizer unit akan diset ke angka ini.</small>
                    </div>
                  </div>
                </div>
              </div>

              <div class="mb-3">
                <label class="form-label required">Alasan / Kronologis & Tindakan Teknis</label>
                <textarea class="form-control" name="reason" rows="3" placeholder="Jelaskan detail alasan pergantian, gejala kerusakan, atau hasil kalibrasi..." required></textarea>
              </div>

              <div class="row g-2 mb-3">
                <div class="col-6">
                  <label class="form-label required">Nama Manager Site yang Menandatangani</label>
                  <input type="text" class="form-control" name="signed_by_manager_name" placeholder="Nama Lengkap Manager Site" required>
                </div>
                <div class="col-6">
                  <label class="form-label">Akun User Manager Site (Opsional)</label>
                  <select name="manager_user_id" class="form-select">
                    <option value="">-- Pilih User Sistem --</option>
                    @foreach($managers as $m)
                      <option value="{{ $m->id }}">{{ $m->nama_lengkap ?? $m->name }} ({{ $m->role->name ?? 'User' }})</option>
                    @endforeach
                  </select>
                </div>
              </div>

              <div class="mb-3">
                <label class="form-label">Upload Scan Dokumen Berita Acara Fisik / Foto Flowmeter (Max 5MB)</label>
                <input type="file" class="form-control" name="document_scan" accept=".pdf,image/*">
              </div>
            </div>
            <div class="card-footer d-flex justify-content-end gap-2">
              <a href="{{ route('fuel.flowmeter-adjustments.index') }}" class="btn btn-secondary">Batal</a>
              <button type="submit" class="btn btn-primary fw-bold">Simpan Berita Acara & Terapkan Totalizer</button>
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
    const storages = @json($storages);
    const fuelTrucks = @json($fuelTrucks);
    const typeSelect = document.getElementById('device_type_select');
    const idSelect = document.getElementById('device_id_select');
    const oldSerial = document.getElementById('old_serial');
    const oldTotalizer = document.getElementById('old_totalizer');

    function populateDevices() {
        const type = typeSelect.value;
        idSelect.innerHTML = '<option value="">-- Pilih Perangkat --</option>';
        if (type === 'fuel_truck') {
            fuelTrucks.forEach(t => {
                const opt = document.createElement('option');
                opt.value = t.id;
                opt.text = (t.master_unit ? t.master_unit.nomor_unit : 'FT #' + t.id) + ' (Tot: ' + Number(t.current_totalizer).toFixed(2) + ')';
                opt.dataset.serial = t.flowmeter_serial_number || '';
                opt.dataset.tot = t.current_totalizer || 0;
                idSelect.appendChild(opt);
            });
        } else {
            storages.forEach(s => {
                const opt = document.createElement('option');
                opt.value = s.id;
                opt.text = s.code + ' - ' + s.name + ' (Tot: ' + Number(s.current_totalizer).toFixed(2) + ')';
                opt.dataset.serial = '';
                opt.dataset.tot = s.current_totalizer || 0;
                idSelect.appendChild(opt);
            });
        }
    }

    typeSelect.addEventListener('change', populateDevices);
    populateDevices();

    idSelect.addEventListener('change', function () {
        const opt = this.options[this.selectedIndex];
        if (opt && opt.value) {
            oldSerial.value = opt.dataset.serial || '';
            oldTotalizer.value = opt.dataset.tot || 0;
        }
    });
});
</script>
@endpush
@endsection
