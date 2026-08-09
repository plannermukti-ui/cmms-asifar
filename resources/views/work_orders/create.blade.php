@extends('layouts.tabler')
@section('title', 'Buat Work Order - CMMS')

@section('content')
<div class="page-header d-print-none">
  <div class="row align-items-center">
    <div class="col"><h2 class="page-title">Buat Work Order Baru</h2></div>
    <div class="col-auto ms-auto">
      <a href="{{ route('work-orders.index') }}" class="btn btn-secondary">Kembali</a>
    </div>
  </div>
</div>

<form action="{{ route('work-orders.store') }}" method="post" id="wo-form">
@csrf

@if(session('error_popup'))
    <div class="modal modal-blur fade" id="modal-error-popup" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          <div class="modal-status bg-danger"></div>
          <div class="modal-body text-center py-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon mb-2 text-danger icon-lg" width="48" height="48" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 9v4" /><path d="M12 17h.01" /><path d="M5 19h14a2 2 0 0 0 1.84 -2.75l-7.1 -12.25a2 2 0 0 0 -3.5 0l-7.1 12.25a2 2 0 0 0 1.75 2.75" /></svg>
            <h3 class="text-danger fw-bold mb-2">Penanganan Unit Aktif</h3>
            <div class="text-muted">{{ session('error_popup') }}</div>
          </div>
          <div class="modal-footer">
            <div class="w-100">
              <button type="button" class="btn btn-danger w-100 fw-bold" data-bs-dismiss="modal">
                Saya Mengerti
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var errorModal = new bootstrap.Modal(document.getElementById('modal-error-popup'));
            errorModal.show();
        });
    </script>
@endif

@if ($errors->any())
  <div class="alert alert-danger alert-dismissible" role="alert">
    <div class="d-flex">
      <div>
        <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="9" /><line x1="12" y1="8" x2="12" y2="12" /><line x1="12" y1="16" x2="12.01" y2="16" /></svg>
      </div>
      <div>
        <h4 class="alert-title">Terdapat Kesalahan</h4>
        <div class="text-muted">
          <ul class="mb-0 ms-0 ps-3">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      </div>
    </div>
    <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
  </div>
@endif

@if(isset($praWorkOrder))
<div class="alert alert-info alert-dismissible" role="alert">
  <div class="d-flex">
    <div>
      <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 9v2m0 4v.01" /><path d="M5 19h14a2 2 0 0 0 1.84 -2.75l-7.1 -12.25a2 2 0 0 0 -3.5 0l-7.1 12.25a2 2 0 0 0 1.75 2.75" /></svg>
    </div>
    <div>
      <strong>Pra-Work Order Terdeteksi!</strong> Data Unit, Waktu BD, HM, dan Problem akan otomatis terisi berdasarkan Laporan Kerusakan.
    </div>
  </div>
</div>
<input type="hidden" name="pra_work_order_id" value="{{ $praWorkOrder->id }}">
@endif

<div class="row mt-3 row-deck g-3">
  <!-- Card 1: Identitas Work Order -->
  <div class="col-lg-3 mb-3">
    <div class="card h-100 shadow-sm border-0">
      <div class="card-status-top bg-primary"></div>
      <div class="card-header p-2 bg-transparent">
        <h3 class="card-title d-flex align-items-center fs-4 fw-bold text-primary">
          <span class="badge bg-primary text-white me-2">01</span> Identitas WO
        </h3>
      </div>
      <div class="card-body p-2">
        <div class="row g-2">
          <div class="col-6">
            <label class="form-label small fw-semibold text-muted mb-1">No WO</label>
            <input type="text" class="form-control form-control-sm bg-light fw-bold text-primary" name="no_wo" value="{{ $no_wo }}" readonly>
          </div>
          <div class="col-6">
            <label class="form-label required small fw-semibold text-muted mb-1">Status WO</label>
            <select name="status_wo" class="form-select form-select-sm border-primary" required>
              @foreach(['Open','Inprogress','Completed','Cancel','Backlog'] as $s)
                <option value="{{ $s }}" {{ old('status_wo','Open') == $s ? 'selected' : '' }}>{{ $s }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-6">
            <label class="form-label required small fw-semibold text-muted mb-1">Tipe WO</label>
            <select name="tipe_wo" class="form-select form-select-sm" required>
              <option value="BD" {{ old('tipe_wo', isset($praWorkOrder) ? 'BD' : '') == 'BD' ? 'selected' : '' }}>BD (Breakdown)</option>
              <option value="Plan" {{ old('tipe_wo') == 'Plan' ? 'selected' : '' }}>Plan</option>
            </select>
          </div>
          <div class="col-6">
            <label class="form-label required small fw-semibold text-muted mb-1">Downtime Code</label>
            <select name="downtime_code" class="form-select form-select-sm" required>
              <option value="Schedule" {{ old('downtime_code') == 'Schedule' ? 'selected' : '' }}>Schedule</option>
              <option value="Unschedule" {{ old('downtime_code', isset($praWorkOrder) ? 'Unschedule' : 'Unschedule') == 'Unschedule' ? 'selected' : '' }}>Unschedule</option>
              <option value="Accident" {{ old('downtime_code') == 'Accident' ? 'selected' : '' }}>Accident</option>
            </select>
          </div>
          <div class="col-12 mt-2">
            <label class="form-check form-switch m-0 pt-1">
              <input class="form-check-input" type="checkbox" name="opportunity" value="1" {{ old('opportunity') ? 'checked' : '' }}>
              <span class="form-check-label fw-bold text-dark small">Opportunity (Yes / No)</span>
            </label>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Card 2: Identitas Unit -->
  <div class="col-lg-3 mb-3">
    <div class="card h-100 shadow-sm border-0">
      <div class="card-status-top bg-info"></div>
      <div class="card-header p-2 bg-transparent">
        <h3 class="card-title d-flex align-items-center fs-4 fw-bold text-info">
          <span class="badge bg-info text-white me-2">02</span> Identitas Unit
        </h3>
      </div>
      <div class="card-body p-2">
        <div class="row g-2">
          <div class="col-6">
            <label class="form-label required small fw-semibold text-muted mb-1">Site</label>
            <select id="site-select" class="form-select form-select-sm border-info" required>
              <option value="">-- Site --</option>
              @foreach($sites as $site)
                <option value="{{ $site->id }}" {{ old('site_id', isset($praWorkOrder) ? $praWorkOrder->site_id : '') == $site->id ? 'selected' : '' }}>{{ $site->name }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-6">
            <label class="form-label required small fw-semibold text-muted mb-1">Tipe Unit</label>
            <select id="type-select" class="form-select form-select-sm" required>
              <option value="">Pilih</option>
              @if(isset($praWorkOrder) && $praWorkOrder->masterUnit && $praWorkOrder->masterUnit->type)
                <option value="{{ $praWorkOrder->masterUnit->unit_type_id }}" selected>{{ $praWorkOrder->masterUnit->type->name }}</option>
              @endif
            </select>
          </div>
          <div class="col-6">
            <label class="form-label required small fw-semibold text-muted mb-1">Nomor Unit</label>
            <select id="unit-select" name="master_unit_id" class="form-select form-select-sm" required>
              <option value="">Pilih</option>
              @if(isset($praWorkOrder) && $praWorkOrder->masterUnit)
                <option value="{{ $praWorkOrder->master_unit_id }}" selected>{{ $praWorkOrder->masterUnit->nomor_unit }}</option>
              @endif
            </select>
          </div>
          <div class="col-6">
            <label class="form-label small fw-semibold text-muted mb-1">Model</label>
            <input type="text" id="model-display" class="form-control form-control-sm bg-light" readonly placeholder="-" value="{{ isset($praWorkOrder) && $praWorkOrder->masterUnit && $praWorkOrder->masterUnit->model ? $praWorkOrder->masterUnit->model->name : '' }}">
          </div>
          <div class="col-12">
            <label class="form-label small fw-semibold text-muted mb-1">Lokasi Kerusakan</label>
            <input type="text" name="lokasi_kerusakan" class="form-control form-control-sm" placeholder="Misal: Pit 2, KM 5, Jetty" value="{{ old('lokasi_kerusakan', isset($praWorkOrder) ? $praWorkOrder->lokasi_kerusakan : '') }}">
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Card 3: Waktu Breakdown -->
  <div class="col-lg-3 mb-3">
    <div class="card h-100 shadow-sm border-0">
      <div class="card-status-top bg-warning"></div>
      <div class="card-header p-2 bg-transparent">
        <h3 class="card-title d-flex align-items-center fs-4 fw-bold text-warning">
          <span class="badge bg-warning text-dark me-2">03</span> Waktu Breakdown
        </h3>
      </div>
      <div class="card-body p-2">
        <div class="row g-2">
          <div class="col-6">
            <label class="form-label small fw-semibold text-muted mb-1">Waktu BD</label>
            <input type="datetime-local" class="form-control form-control-sm" name="waktu_bd" id="waktu_bd" value="{{ old('waktu_bd', isset($praWorkOrder) ? $praWorkOrder->waktu_bd->format('Y-m-d\TH:i') : '') }}">
          </div>
          <div class="col-6">
            <label class="form-label small fw-semibold text-muted mb-1">Waktu RFU</label>
            <input type="datetime-local" class="form-control form-control-sm" name="waktu_rfu" id="waktu_rfu" value="{{ old('waktu_rfu') }}">
          </div>
          <div class="col-6">
            <label class="form-label small fw-semibold text-muted mb-1">Durasi (Hrs)</label>
            <input type="text" class="form-control form-control-sm bg-warning-lt fw-bold text-dark" id="durasi-display" readonly placeholder="(Auto)">
          </div>
          <div class="col-6">
            <label class="form-label small fw-semibold text-muted mb-1">Hours Meter</label>
            <input type="number" class="form-control form-control-sm" name="hours_meter" step="0.1" value="{{ old('hours_meter', isset($praWorkOrder) ? $praWorkOrder->hours_meter : '') }}" placeholder="0.0">
          </div>
          <div class="col-12">
            <label class="form-label small fw-semibold text-muted mb-1">Tipe Breakdown</label>
            <div class="input-group input-group-sm">
              <select name="breakdown_type_id" class="form-select" id="breakdown-type-select">
                <option value="">Pilih</option>
                @foreach($breakdownTypes as $bt)
                  <option value="{{ $bt->id }}" {{ old('breakdown_type_id') == $bt->id ? 'selected' : '' }}>{{ $bt->code ? $bt->code . ' - ' : '' }}{{ $bt->name }}</option>
                @endforeach
              </select>
              <button type="button" class="btn btn-outline-warning text-dark fw-bold" onclick="inlineAdd('breakdown_types','breakdown-type-select')">+</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Card 4: Klasifikasi -->
  <div class="col-lg-3 mb-3">
    <div class="card h-100 shadow-sm border-0">
      <div class="card-status-top bg-teal"></div>
      <div class="card-header p-2 bg-transparent">
        <h3 class="card-title d-flex align-items-center fs-4 fw-bold text-teal">
          <span class="badge bg-teal text-white me-2">04</span> Klasifikasi
        </h3>
      </div>
      <div class="card-body p-2">
        <div class="row g-2">
          <div class="col-6">
            <label class="form-label small fw-semibold text-muted mb-1">Comp. Group</label>
            <div class="input-group input-group-sm">
              <select name="component_group_id" class="form-select" id="cg-select">
                <option value="">Pilih</option>
                @foreach($componentGroups as $cg)
                  <option value="{{ $cg->id }}" {{ old('component_group_id') == $cg->id ? 'selected' : '' }}>{{ $cg->name }}</option>
                @endforeach
              </select>
              <button type="button" class="btn btn-outline-teal fw-bold px-2" onclick="inlineAdd('component_groups','cg-select')">+</button>
            </div>
          </div>
          @for($i = 1; $i <= 5; $i++)
          <div class="col-6">
            <label class="form-label small fw-semibold text-muted mb-1">Kategori {{ $i }}</label>
            <div class="input-group input-group-sm">
              <select name="wo_category_{{ $i }}_id" class="form-select" id="cat{{ $i }}-select">
                <option value="">Pilih</option>
                @foreach(${'categories'.$i} as $cat)
                  <option value="{{ $cat->id }}" {{ old('wo_category_'.$i.'_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
              </select>
              <button type="button" class="btn btn-outline-teal fw-bold px-2" onclick="inlineAddCat({{ $i }},'cat{{ $i }}-select')">+</button>
            </div>
          </div>
          @endfor
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Card 5: Tasks -->
<div class="card mb-3 shadow-sm border-0">
  <div class="card-status-top bg-indigo"></div>
  <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
    <h3 class="card-title text-indigo fw-bold m-0 d-flex align-items-center">
      <svg xmlns="http://www.w3.org/2000/svg" class="icon text-indigo me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 3v5m4 -2l-4 2l-4 -2" /><path d="M12 12v9" /><path d="M12 12l8 -4.5" /><path d="M12 12l-8 -4.5" /><path d="M12 16.5l8 -4.5" /><path d="M12 16.5l-8 -4.5" /></svg>
      Tasks & Problem List
    </h3>
    <button type="button" class="btn btn-indigo shadow-sm" onclick="addTask()">
      <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="12" y1="5" x2="12" y2="19" /><line x1="5" y1="12" x2="19" y2="12" /></svg>
      Tambah Task Baru
    </button>
  </div>
  <div class="card-body p-3" id="tasks-container">
    <!-- Tasks will be injected here by JS -->
  </div>
</div>

<div class="mb-5 text-end">
  <button type="submit" class="btn btn-success btn-lg shadow-sm px-5 fw-bold">
    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-check me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10" /></svg>
    Simpan Work Order
  </button>
</div>

</form>

@endsection

@push('scripts')
<script>
// ========== Cascading Dropdowns ==========
const siteSelect = document.getElementById('site-select');
const typeSelect = document.getElementById('type-select');
const unitSelect = document.getElementById('unit-select');
const modelDisplay = document.getElementById('model-display');

siteSelect.addEventListener('change', function() {
    typeSelect.innerHTML = '<option value="">Memuat...</option>';
    unitSelect.innerHTML = '<option value="">-- Pilih Tipe Unit dahulu --</option>';
    modelDisplay.value = '';
    if (!this.value) return;
    fetch('/api/wo/unit-types?site_id=' + this.value)
        .then(r => r.json())
        .then(data => {
            typeSelect.innerHTML = '<option value="">-- Pilih Tipe Unit --</option>';
            data.forEach(t => {
                typeSelect.innerHTML += `<option value="${t.id}">${t.name}</option>`;
            });
        });
});

typeSelect.addEventListener('change', function() {
    unitSelect.innerHTML = '<option value="">Memuat...</option>';
    modelDisplay.value = '';
    if (!this.value) return;
    fetch('/api/wo/units?site_id=' + siteSelect.value + '&unit_type_id=' + this.value)
        .then(r => r.json())
        .then(data => {
            unitSelect.innerHTML = '<option value="">-- Pilih Nomor Unit --</option>';
            data.forEach(u => {
                unitSelect.innerHTML += `<option value="${u.id}">${u.nomor_unit}</option>`;
            });
        });
});

unitSelect.addEventListener('change', function() {
    modelDisplay.value = '';
    if (!this.value) return;
    fetch('/api/wo/unit-detail?unit_id=' + this.value)
        .then(r => r.json())
        .then(data => {
            modelDisplay.value = data.model_name || '-';
        });
});

// ========== Durasi Auto-Calc ==========
function calcDurasi() {
    const bd = document.getElementById('waktu_bd').value;
    const rfu = document.getElementById('waktu_rfu').value;
    if (!bd) { document.getElementById('durasi-display').value = ''; return; }
    const start = new Date(bd);
    const end = rfu ? new Date(rfu) : new Date();
    const hrs = ((end - start) / 3600000).toFixed(1);
    document.getElementById('durasi-display').value = hrs + ' jam';
}
document.getElementById('waktu_bd').addEventListener('change', calcDurasi);
document.getElementById('waktu_rfu').addEventListener('change', calcDurasi);
setInterval(function() {
    if (document.getElementById('waktu_bd').value && !document.getElementById('waktu_rfu').value) calcDurasi();
}, 60000);
calcDurasi();

// ========== Inline Add ==========
function inlineAdd(table, selectId) {
    let code = '';
    if (table === 'breakdown_types') {
        code = prompt('Masukkan kode (opsional):') || '';
    }
    const name = prompt('Masukkan nama baru:');
    if (!name) return;
    fetch('/api/wo/inline-add', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ table: table, name: name, code: code })
    }).then(r => r.json()).then(data => {
        const sel = document.getElementById(selectId);
        const text = data.code ? data.code + ' - ' + data.name : data.name;
        const opt = new Option(text, data.id, true, true);
        sel.appendChild(opt);
    }).catch(e => alert('Gagal menambahkan: ' + e));
}

function inlineAddCat(level, selectId) {
    const name = prompt('Masukkan nama Kategori ' + level + ' baru:');
    if (!name) return;
    fetch('/api/wo/inline-add', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ table: 'wo_categories', name: name, level: level })
    }).then(r => r.json()).then(data => {
        const sel = document.getElementById(selectId);
        const opt = new Option(data.name, data.id, true, true);
        sel.appendChild(opt);
    }).catch(e => alert('Gagal menambahkan: ' + e));
}

// Also for component_groups used inside tasks
function inlineAddForSelect(table, selectEl) {
    const name = prompt('Masukkan nama baru:');
    if (!name) return;
    fetch('/api/wo/inline-add', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ table: table, name: name })
    }).then(r => r.json()).then(data => {
        const opt = new Option(data.name, data.id, true, true);
        selectEl.appendChild(opt);
    }).catch(e => alert('Gagal menambahkan: ' + e));
}

function inlineAddPart(selectEl) {
    const pn = prompt('Masukkan Part Number baru:');
    if (!pn) return;
    const desc = prompt('Masukkan Part Description:');
    if (!desc) return;
    fetch('/api/wo/inline-add', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ table: 'parts', name: pn, part_number: pn, part_description: desc })
    }).then(r => r.json()).then(data => {
        const opt = new Option(data.part_number + ' - ' + data.part_description, data.id, true, true);
        selectEl.appendChild(opt);
    }).catch(e => alert('Gagal menambahkan: ' + e));
}

// ========== Dynamic Task/Subtask Builder ==========
let taskIndex = 0;

// Component groups options (for reuse in tasks)
const cgOptions = `<option value="">-- Pilih --</option>@foreach($componentGroups as $cg)<option value="{{ $cg->id }}">{{ $cg->name }}</option>@endforeach`;
const mechanicOptions = `@foreach($mechanics as $m)<option value="{{ $m->id }}">{{ $m->nama_lengkap }}</option>@endforeach`;
const partOptions = `<option value="">-- Pilih Part --</option>@foreach($parts as $p)<option value="{{ $p->id }}">{{ $p->part_number }} - {{ $p->part_description }}</option>@endforeach`;
const toolTxOptions = `<option value="">-- Pilih Transaksi --</option>@foreach($toolTransactions as $tx)<option value="{{ $tx->id }}">{{ $tx->tool->name ?? '' }} → {{ $tx->mechanic->nama_lengkap ?? '' }} ({{ $tx->tanggal_pinjam }})</option>@endforeach`;
const statusOptions = `<option value="Open">Open</option><option value="Inprogress">Inprogress</option><option value="Completed">Completed</option><option value="Cancel">Cancel</option><option value="Backlog">Backlog</option>`;

function addTask() {
    const ti = taskIndex++;
    const container = document.getElementById('tasks-container');
    const div = document.createElement('div');
    div.className = 'border-start border-4 border-indigo rounded-3 p-3 mb-3 bg-blue-lt shadow-sm position-relative';
    div.id = 'task-' + ti;
    div.innerHTML = `
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0 text-indigo fw-bold d-flex align-items-center">
                <span class="badge bg-indigo text-white me-2">Task #${ti+1}</span>
                Problem / Gejala Kerusakan
            </h4>
            <button type="button" class="btn btn-sm btn-outline-danger rounded-pill" onclick="document.getElementById('task-${ti}').remove()">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-trash me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="4" y1="7" x2="20" y2="7" /><line x1="10" y1="11" x2="10" y2="17" /><line x1="14" y1="11" x2="14" y2="17" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
                Hapus Task
            </button>
        </div>
        <div class="row g-2">
            <div class="col-md-6 mb-2">
                <label class="form-label required small fw-semibold text-muted">Deskripsi Problem</label>
                <textarea class="form-control form-control-sm bg-white" name="tasks[${ti}][problem]" rows="2" placeholder="Uraikan problem/gejala..." required></textarea>
            </div>
            <div class="col-md-6 mb-2">
                <div class="row g-2">
                    <div class="col-12">
                        <label class="form-label small fw-semibold text-muted">Component Group</label>
                        <div class="input-group input-group-sm">
                            <select name="tasks[${ti}][component_group_id]" class="form-select bg-white" id="task-cg-${ti}">${cgOptions}</select>
                            <button type="button" class="btn btn-outline-indigo" onclick="inlineAddForSelect('component_groups', document.getElementById('task-cg-${ti}'))">+</button>
                        </div>
                    </div>
                    <div class="col-6">
                        <label class="form-label small fw-semibold text-muted">Date Problem</label>
                        <input type="datetime-local" class="form-control form-control-sm bg-white" name="tasks[${ti}][date_problem]">
                    </div>
                    <div class="col-6">
                        <label class="form-label small fw-semibold text-muted">Status Task</label>
                        <select name="tasks[${ti}][status]" class="form-select form-select-sm bg-white">${statusOptions}</select>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-3 pt-3 border-top border-blue-subtle">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <strong class="text-indigo small text-uppercase">SubTasks (Tindakan / Action)</strong>
                <button type="button" class="btn btn-sm btn-outline-success bg-white shadow-xs" onclick="addSubtask(${ti})">
                    + Tambah SubTask
                </button>
            </div>
            <div id="subtasks-container-${ti}"></div>
        </div>
    `;
    container.appendChild(div);
}

let subtaskCounters = {};
function addSubtask(taskIdx) {
    if (!subtaskCounters[taskIdx]) subtaskCounters[taskIdx] = 0;
    const si = subtaskCounters[taskIdx]++;
    const container = document.getElementById('subtasks-container-' + taskIdx);
    const div = document.createElement('div');
    div.className = 'border-start border-3 border-success rounded-3 p-3 mb-2 bg-white shadow-xs ms-2';
    div.id = `subtask-${taskIdx}-${si}`;
    div.innerHTML = `
        <div class="d-flex justify-content-between align-items-center mb-2">
            <span class="badge bg-success-lt text-success fw-bold">SubTask #${si+1}</span>
            <button type="button" class="btn btn-sm btn-outline-danger border-0 p-1" onclick="document.getElementById('subtask-${taskIdx}-${si}').remove()">✕ Hapus SubTask</button>
        </div>
        <div class="row g-2 mb-2">
            <div class="col-md-6">
                <label class="form-label required small fw-semibold text-muted mb-1">Action / Tindakan</label>
                <textarea class="form-control form-control-sm" name="tasks[${taskIdx}][subtasks][${si}][action]" rows="2" placeholder="Uraian perbaikan..." required></textarea>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold text-muted mb-1">Date Action</label>
                <input type="datetime-local" class="form-control form-control-sm" name="tasks[${taskIdx}][subtasks][${si}][date_action]">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold text-muted mb-1">Status</label>
                <select name="tasks[${taskIdx}][subtasks][${si}][status]" class="form-select form-select-sm">${statusOptions}</select>
            </div>
        </div>
        <div class="row g-3 mt-1">
            <!-- Manpower -->
            <div class="col-md-4">
                <div class="p-2 rounded bg-light border">
                    <label class="form-label small fw-bold text-azure mb-1 d-flex justify-content-between">
                        <span>👷 Manpower (Mekanik)</span>
                        <button type="button" class="btn btn-xs btn-azure py-0 px-1" onclick="addManpower(${taskIdx},${si})">+</button>
                    </label>
                    <div id="manpower-${taskIdx}-${si}"></div>
                </div>
            </div>
            <!-- Parts -->
            <div class="col-md-4">
                <div class="p-2 rounded bg-light border">
                    <label class="form-label small fw-bold text-orange mb-1 d-flex justify-content-between">
                        <span>⚙️ Spareparts</span>
                        <button type="button" class="btn btn-xs btn-orange py-0 px-1" onclick="addPartRow(${taskIdx},${si})">+</button>
                    </label>
                    <div id="parts-${taskIdx}-${si}"></div>
                </div>
            </div>
            <!-- Tools -->
            <div class="col-md-4">
                <div class="p-2 rounded bg-light border">
                    <label class="form-label small fw-bold text-purple mb-1 d-flex justify-content-between">
                        <span>🔧 Tools (Peminjaman)</span>
                        <button type="button" class="btn btn-xs btn-purple py-0 px-1" onclick="addToolRow(${taskIdx},${si})">+</button>
                    </label>
                    <div id="tools-${taskIdx}-${si}"></div>
                </div>
            </div>
        </div>
    `;
    container.appendChild(div);
}

let mpCounters = {};
function addManpower(taskIdx, subtaskIdx) {
    const key = taskIdx + '-' + subtaskIdx;
    if (!mpCounters[key]) mpCounters[key] = 0;
    const mi = mpCounters[key]++;
    const container = document.getElementById('manpower-' + key);
    const row = document.createElement('div');
    row.className = 'd-flex gap-1 mb-1 align-items-center';
    row.innerHTML = `
        <select name="tasks[${taskIdx}][subtasks][${subtaskIdx}][manpower_ids][]" class="form-select form-select-sm">
            <option value="">-- Pilih Mekanik --</option>${mechanicOptions}
        </select>
        <button type="button" class="btn btn-sm btn-outline-danger p-1" onclick="this.parentElement.remove()">✕</button>
    `;
    container.appendChild(row);
}

let partCounters = {};
function addPartRow(taskIdx, subtaskIdx) {
    const key = taskIdx + '-' + subtaskIdx;
    if (!partCounters[key]) partCounters[key] = 0;
    const pi = partCounters[key]++;
    const container = document.getElementById('parts-' + key);
    const row = document.createElement('div');
    row.className = 'd-flex gap-1 mb-1 align-items-center';
    row.innerHTML = `
        <div class="input-group input-group-sm" style="flex:2">
            <select name="tasks[${taskIdx}][subtasks][${subtaskIdx}][parts][${pi}][part_id]" class="form-select form-select-sm" id="part-sel-${key}-${pi}">${partOptions}</select>
            <button type="button" class="btn btn-outline-orange btn-sm px-1" onclick="inlineAddPart(document.getElementById('part-sel-${key}-${pi}'))">+</button>
        </div>
        <input type="number" name="tasks[${taskIdx}][subtasks][${subtaskIdx}][parts][${pi}][qty]" class="form-control form-control-sm" style="width:50px" min="1" value="1" placeholder="Qty">
        <input type="text" name="tasks[${taskIdx}][subtasks][${subtaskIdx}][parts][${pi}][satuan]" class="form-control form-control-sm" style="width:60px" placeholder="Sat">
        <button type="button" class="btn btn-sm btn-outline-danger p-1" onclick="this.parentElement.remove()">✕</button>
    `;
    container.appendChild(row);
}

let toolCounters = {};
function addToolRow(taskIdx, subtaskIdx) {
    const key = taskIdx + '-' + subtaskIdx;
    if (!toolCounters[key]) toolCounters[key] = 0;
    const ti = toolCounters[key]++;
    const container = document.getElementById('tools-' + key);
    const row = document.createElement('div');
    row.className = 'd-flex gap-1 mb-1 align-items-center';
    row.innerHTML = `
        <select name="tasks[${taskIdx}][subtasks][${subtaskIdx}][tool_transaction_ids][]" class="form-select form-select-sm">${toolTxOptions}</select>
        <button type="button" class="btn btn-sm btn-outline-danger p-1" onclick="this.parentElement.remove()">✕</button>
    `;
    container.appendChild(row);
}

@if(isset($praWorkOrder))
window.addEventListener('DOMContentLoaded', function() {
    addTask();
    const firstTaskDesc = document.querySelector('textarea[name="tasks[0][problem]"]');
    if (firstTaskDesc) {
        firstTaskDesc.value = `{!! addslashes($praWorkOrder->problem) !!}`;
    }
});
@else
window.addEventListener('DOMContentLoaded', function() {
    addTask();
});
@endif
</script>
@endpush
