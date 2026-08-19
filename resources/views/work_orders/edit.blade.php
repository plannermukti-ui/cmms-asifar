@extends('layouts.tabler')
@section('title', 'Edit Work Order - CMMS')

@section('content')
<div class="page-header d-print-none">
  <div class="row align-items-center">
    <div class="col"><h2 class="page-title">Edit Work Order: {{ $workOrder->no_wo }}</h2></div>
    <div class="col-auto ms-auto">
      <a href="{{ route('work-orders.index') }}" class="btn btn-secondary">Kembali</a>
    </div>
  </div>
</div>

<form action="{{ route('work-orders.update', $workOrder) }}" method="post" id="wo-form">
@csrf
@method('PUT')

<div class="row mt-3">
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
            <input type="text" class="form-control form-control-sm bg-light fw-bold text-primary" value="{{ $workOrder->no_wo }}" readonly>
          </div>
          <div class="col-6">
            <label class="form-label required small fw-semibold text-muted mb-1">Status WO</label>
            <select name="status_wo" class="form-select form-select-sm border-primary" required>
              @foreach(['Open','Inprogress','Completed','Cancel','Backlog'] as $s)
                <option value="{{ $s }}" {{ $workOrder->status_wo == $s ? 'selected' : '' }}>{{ $s }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-6">
            <label class="form-label required small fw-semibold text-muted mb-1">Tipe WO</label>
            <select name="tipe_wo" class="form-select form-select-sm" required>
              <option value="BD" {{ $workOrder->tipe_wo == 'BD' ? 'selected' : '' }}>BD (Breakdown)</option>
              <option value="Plan" {{ $workOrder->tipe_wo == 'Plan' ? 'selected' : '' }}>Plan</option>
            </select>
          </div>
          <div class="col-6">
            <label class="form-label required small fw-semibold text-muted mb-1">Downtime Code</label>
            <select name="downtime_code" class="form-select form-select-sm" required>
              @foreach(['Schedule','Unschedule','Accident'] as $dc)
                <option value="{{ $dc }}" {{ $workOrder->downtime_code == $dc ? 'selected' : '' }}>{{ $dc }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-12 mt-2">
            <label class="form-check form-switch m-0 pt-1">
              <input class="form-check-input" type="checkbox" name="opportunity" value="1" {{ $workOrder->opportunity ? 'checked' : '' }}>
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
                <option value="{{ $site->id }}" {{ ($workOrder->unit->site_id ?? '') == $site->id ? 'selected' : '' }}>{{ $site->name }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-6">
            <label class="form-label required small fw-semibold text-muted mb-1">Tipe Unit</label>
            <select id="type-select" class="form-select form-select-sm" required>
              <option value="{{ $workOrder->unit->unit_type_id ?? '' }}">{{ $workOrder->unit->type->name ?? 'Pilih' }}</option>
            </select>
          </div>
          <div class="col-6">
            <label class="form-label required small fw-semibold text-muted mb-1">Nomor Unit</label>
            <select id="unit-select" name="master_unit_id" class="form-select form-select-sm" required>
              <option value="{{ $workOrder->master_unit_id }}">{{ $workOrder->unit->nomor_unit ?? 'Pilih' }}</option>
            </select>
          </div>
          <div class="col-6">
            <label class="form-label small fw-semibold text-muted mb-1">Model</label>
            <input type="text" id="model-display" class="form-control form-control-sm bg-light" readonly value="{{ $workOrder->unit->model->name ?? '' }}">
          </div>
          <div class="col-12">
            <label class="form-label small fw-semibold text-muted mb-1">Lokasi Kerusakan</label>
            <input type="text" name="lokasi_kerusakan" class="form-control form-control-sm" placeholder="Misal: Pit 2, KM 5, Jetty" value="{{ old('lokasi_kerusakan', $workOrder->lokasi_kerusakan) }}">
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
            <input type="datetime-local" class="form-control form-control-sm" name="waktu_bd" id="waktu_bd" value="{{ $workOrder->waktu_bd ? $workOrder->waktu_bd->format('Y-m-d\TH:i') : '' }}">
          </div>
          <div class="col-6">
            <label class="form-label small fw-semibold text-muted mb-1">Waktu RFU</label>
            <input type="datetime-local" class="form-control form-control-sm @error('waktu_rfu') is-invalid @enderror" name="waktu_rfu" id="waktu_rfu" value="{{ old('waktu_rfu', $workOrder->waktu_rfu ? $workOrder->waktu_rfu->format('Y-m-d\TH:i') : '') }}">
            @error('waktu_rfu')
              <div class="invalid-feedback small">{{ $message }}</div>
            @enderror
          </div>
          <div class="col-6">
            <label class="form-label small fw-semibold text-muted mb-1">Durasi (Hrs)</label>
            <input type="text" class="form-control form-control-sm bg-warning-lt fw-bold text-dark" id="durasi-display" readonly>
          </div>
          <div class="col-6">
            <label class="form-label small fw-semibold text-muted mb-1">Hours Meter</label>
            <input type="number" class="form-control form-control-sm" name="hours_meter" step="0.1" value="{{ $workOrder->hours_meter }}">
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
          @for($i = 1; $i <= 5; $i++)
          <div class="col-6">
            <label class="form-label small fw-semibold text-muted mb-1">Kategori {{ $i }}</label>
            <div class="input-group input-group-sm">
              <select name="wo_category_{{ $i }}_id" class="form-select" id="cat{{ $i }}-select">
                <option value="">Pilih</option>
                @foreach(${'categories'.$i} as $cat)
                  <option value="{{ $cat->id }}" {{ $workOrder->{'wo_category_'.$i.'_id'} == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
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
<div class="card mb-3">
  <div class="card-header">
    <h3 class="card-title">Tasks (Problem)</h3>
    <div class="card-actions">
      <button type="button" class="btn btn-sm btn-primary" onclick="addTask()">+ Tambah Task</button>
    </div>
  </div>
  <div class="card-body" id="tasks-container">
    <!-- Existing tasks will be loaded by JS below -->
  </div>
</div>

<div class="mb-5 text-end">
  <button type="submit" class="btn btn-primary btn-lg">Simpan Perubahan</button>
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
        .then(r => r.json()).then(data => {
            typeSelect.innerHTML = '<option value="">-- Pilih Tipe Unit --</option>';
            data.forEach(t => typeSelect.innerHTML += `<option value="${t.id}">${t.name}</option>`);
        });
});
typeSelect.addEventListener('change', function() {
    unitSelect.innerHTML = '<option value="">Memuat...</option>';
    modelDisplay.value = '';
    if (!this.value) return;
    fetch('/api/wo/units?site_id=' + siteSelect.value + '&unit_type_id=' + this.value)
        .then(r => r.json()).then(data => {
            unitSelect.innerHTML = '<option value="">-- Pilih Nomor Unit --</option>';
            data.forEach(u => unitSelect.innerHTML += `<option value="${u.id}">${u.nomor_unit}</option>`);
        });
});
unitSelect.addEventListener('change', function() {
    modelDisplay.value = '';
    if (!this.value) return;
    fetch('/api/wo/unit-detail?unit_id=' + this.value)
        .then(r => r.json()).then(data => modelDisplay.value = data.model_name || '-');
});

// ========== Durasi Auto-Calc ==========
function calcDurasi() {
    const bd = document.getElementById('waktu_bd').value;
    const rfu = document.getElementById('waktu_rfu').value;
    if (!bd) { document.getElementById('durasi-display').value = ''; return; }
    const start = new Date(bd);
    const end = rfu ? new Date(rfu) : new Date();
    document.getElementById('durasi-display').value = ((end - start) / 3600000).toFixed(1) + ' jam';
}
document.getElementById('waktu_bd').addEventListener('change', calcDurasi);
document.getElementById('waktu_rfu').addEventListener('change', calcDurasi);
setInterval(function() { if (document.getElementById('waktu_bd').value && !document.getElementById('waktu_rfu').value) calcDurasi(); }, 60000);
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
        method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ table, name, code })
    }).then(r => r.json()).then(data => {
        const text = data.code ? data.code + ' - ' + data.name : data.name;
        document.getElementById(selectId).appendChild(new Option(text, data.id, true, true));
    }).catch(e => alert('Gagal: ' + e));
}
function inlineAddCat(level, selectId) {
    const name = prompt('Masukkan nama Kategori ' + level + ' baru:');
    if (!name) return;
    fetch('/api/wo/inline-add', {
        method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ table: 'wo_categories', name, level })
    }).then(r => r.json()).then(data => {
        document.getElementById(selectId).appendChild(new Option(data.name, data.id, true, true));
    }).catch(e => alert('Gagal: ' + e));
}
function inlineAddForSelect(table, selectEl) {
    const name = prompt('Masukkan nama baru:');
    if (!name) return;
    fetch('/api/wo/inline-add', {
        method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ table, name })
    }).then(r => r.json()).then(data => selectEl.appendChild(new Option(data.name, data.id, true, true))).catch(e => alert('Gagal: ' + e));
}
function inlineAddPart(selectEl) {
    const pn = prompt('Part Number:'); if (!pn) return;
    const desc = prompt('Part Description:'); if (!desc) return;
    fetch('/api/wo/inline-add', {
        method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ table: 'parts', name: pn, part_number: pn, part_description: desc })
    }).then(r => r.json()).then(data => selectEl.appendChild(new Option(data.part_number + ' - ' + data.part_description, data.id, true, true))).catch(e => alert('Gagal: ' + e));
}

// ========== Dynamic Task/Subtask ==========
let taskIndex = 0;
const cgOptions = `<option value="">-- Pilih --</option>@foreach($componentGroups as $cg)<option value="{{ $cg->id }}">{{ $cg->name }}</option>@endforeach`;
const mechanicOptions = `@foreach($mechanics as $m)<option value="{{ $m->id }}">{{ $m->nama_lengkap }}</option>@endforeach`;
const partOptions = `<option value="">-- Pilih Part --</option>@foreach($parts as $p)<option value="{{ $p->id }}">{{ $p->part_number }} - {{ $p->part_description }}</option>@endforeach`;
const unitOptions = `<option value="">-- Pilih Unit --</option>@foreach($units as $u)<option value="{{ $u->id }}">{{ $u->nomor_unit }} - {{ $u->model->name ?? '' }}</option>@endforeach`;
const toolTxOptions = `<option value="">-- Pilih --</option>@foreach($toolTransactions as $tx)<option value="{{ $tx->id }}">{{ $tx->tool->name ?? '' }} → {{ $tx->mechanic->nama_lengkap ?? '' }}</option>@endforeach`;
const statusOptions = `<option value="Open">Open</option><option value="Inprogress">Inprogress</option><option value="Completed">Completed</option><option value="Cancel">Cancel</option><option value="Backlog">Backlog</option>`;

function addTask(data) {
    const ti = taskIndex++;
    const container = document.getElementById('tasks-container');
    const div = document.createElement('div');
    div.className = 'border rounded p-2 mb-2 bg-light'; div.id = 'task-' + ti;
    div.innerHTML = `
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h4 class="mb-0">Task #${ti+1}</h4>
            <button type="button" class="btn btn-sm btn-outline-danger" onclick="document.getElementById('task-${ti}').remove()">Hapus Task</button>
        </div>
        <div class="row g-3">
            <div class="col-lg-5">
                <div class="h-100 p-2 rounded bg-white border">
                    <div class="mb-2"><label class="form-label required">Problem</label><textarea class="form-control" name="tasks[${ti}][problem]" rows="2" required>${data?.problem||''}</textarea></div>
                    <div class="mb-2"><label class="form-label">Component Group</label><div class="input-group"><select name="tasks[${ti}][component_group_id]" class="form-select" id="task-cg-${ti}">${cgOptions}</select><button type="button" class="btn btn-outline-primary" onclick="inlineAddForSelect('component_groups', document.getElementById('task-cg-${ti}'))">+</button></div></div>
                    <div class="row g-2">
                        <div class="col-6"><label class="form-label">Date Problem</label><input type="datetime-local" class="form-control" name="tasks[${ti}][date_problem]" value="${data?.date_problem||''}"><div class="invalid-feedback d-block mt-1" data-field="date_problem"></div></div>
                        <div class="col-6"><label class="form-label">Status</label><select name="tasks[${ti}][status]" class="form-select" id="task-status-${ti}">${statusOptions}</select></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="h-100 p-2 rounded bg-white border border-success">
                    <div class="d-flex justify-content-between align-items-center mb-2"><strong>SubTasks</strong><button type="button" class="btn btn-sm btn-outline-success" onclick="addSubtask(${ti})">+ SubTask</button></div>
                    <div id="subtasks-container-${ti}"></div>
                </div>
            </div>
        </div>
    `;
    container.appendChild(div);
    bindTaskDateProblemField(div);
    if (data?.component_group_id) document.getElementById('task-cg-'+ti).value = data.component_group_id;
    if (data?.status) document.getElementById('task-status-'+ti).value = data.status;
    if (data?.subtasks) data.subtasks.forEach(st => addSubtask(ti, st));
}

function bindTaskDateProblemField(taskEl) {
    const taskDateInput = taskEl.querySelector('input[name$="[date_problem]"]');
    const taskFeedback = taskEl.querySelector('[data-field="date_problem"]');
    const showTaskError = () => {
        const taskDate = taskDateInput && taskDateInput.value ? new Date(taskDateInput.value) : null;
        const workOrderDateInput = document.getElementById('waktu_bd');
        const workOrderDate = workOrderDateInput && workOrderDateInput.value ? new Date(workOrderDateInput.value) : null;
        if (taskFeedback) {
            taskFeedback.textContent = '';
            if (workOrderDate && taskDate && taskDate < workOrderDate) {
                taskFeedback.textContent = 'Date Problem tidak boleh kurang dari Waktu BD.';
            }
        }
        taskEl.querySelectorAll('.subtask-item').forEach(function(subtaskEl) {
            const feedback = subtaskEl.querySelector('[data-field="date_action"]');
            if (feedback) {
                feedback.textContent = '';
                const actionDate = subtaskEl.querySelector('input[name$="[date_action]"]')?.value;
                const actionDateValue = actionDate ? new Date(actionDate) : null;
                if (taskDate && actionDateValue && actionDateValue < taskDate) {
                    feedback.textContent = 'Date Action tidak boleh kurang dari Date Problem.';
                }
            }
        });
    };

    [taskDateInput, document.getElementById('waktu_bd')].forEach(input => input && input.addEventListener('change', showTaskError));
    [taskDateInput, document.getElementById('waktu_bd')].forEach(input => input && input.addEventListener('input', showTaskError));
    showTaskError();
}

let subtaskCounters = {};
function bindSubtaskDateFields(subtaskEl) {
    const dateAction = subtaskEl.querySelector('input[name$="[date_action]"]');
    const dateFinish = subtaskEl.querySelector('input[name$="[date_finish]"]');
    const duration = subtaskEl.querySelector('input[name$="[duration_hours]"]');
    const syncDuration = () => {
        const start = dateAction && dateAction.value ? new Date(dateAction.value) : null;
        const end = dateFinish && dateFinish.value ? new Date(dateFinish.value) : null;
        let value = '';
        if (start) {
            const reference = end || new Date();
            value = (((reference - start) / 3600000)).toFixed(2);
        }
        if (duration) duration.value = value;
    };
    [dateAction, dateFinish].forEach(input => input && input.addEventListener('change', syncDuration));
    [dateAction, dateFinish].forEach(input => input && input.addEventListener('input', syncDuration));
    syncDuration();
}

function addSubtask(taskIdx, data) {
    if (!subtaskCounters[taskIdx]) subtaskCounters[taskIdx] = 0;
    const si = subtaskCounters[taskIdx]++;
    const container = document.getElementById('subtasks-container-'+taskIdx);
    const div = document.createElement('div');
    div.className = 'border rounded p-2 mb-1 bg-white subtask-item'; div.id = `subtask-${taskIdx}-${si}`;
    div.innerHTML = `
        <div class="d-flex justify-content-between align-items-center mb-2"><strong class="text-muted">SubTask #${si+1}</strong><button type="button" class="btn btn-sm btn-outline-danger" onclick="document.getElementById('subtask-${taskIdx}-${si}').remove()">×</button></div>
        <div class="row g-1 mb-1 align-items-end" style="display:flex; flex-wrap:wrap; gap:0.35rem;">
            <div style="flex:1 1 220px; min-width:220px;" class="mb-2"><label class="form-label required">Action</label><textarea class="form-control" name="tasks[${taskIdx}][subtasks][${si}][action]" rows="1" required>${data?.action||''}</textarea></div>
            <div style="flex:1 1 140px; min-width:140px;" class="mb-2"><label class="form-label">Date Action</label><input type="datetime-local" class="form-control" name="tasks[${taskIdx}][subtasks][${si}][date_action]" value="${data?.date_action||''}"><div class="invalid-feedback d-block mt-1" data-field="date_action"></div></div>
            <div style="flex:1 1 140px; min-width:140px;" class="mb-2"><label class="form-label">Date Finish</label><input type="datetime-local" class="form-control" name="tasks[${taskIdx}][subtasks][${si}][date_finish]" value="${data?.date_finish||''}"></div>
            <div style="flex:0 0 90px; min-width:90px;" class="mb-2"><label class="form-label">Durasi</label><input type="number" step="0.01" class="form-control" name="tasks[${taskIdx}][subtasks][${si}][duration_hours]" value="${data?.duration_hours||''}" readonly></div>
        </div>
        <div class="row g-1 mb-1 align-items-end" style="display:flex; flex-wrap:wrap; gap:0.35rem;">
            <div style="flex:1 1 220px; min-width:220px;" class="mb-2"><label class="form-label">Tipe Breakdown</label><select name="tasks[${taskIdx}][subtasks][${si}][breakdown_type_id]" class="form-select" id="st-bd-${taskIdx}-${si}"><option value="">-- Pilih --</option>@foreach($breakdownTypes as $bt)<option value="{{ $bt->id }}">{{ $bt->code ? $bt->code . ' - ' : '' }}{{ $bt->name }}</option>@endforeach</select></div>
            <div style="flex:1 1 220px; min-width:220px;" class="mb-2"><label class="form-label">Status</label><select name="tasks[${taskIdx}][subtasks][${si}][status]" class="form-select" id="st-status-${taskIdx}-${si}">${statusOptions}</select></div>
        </div>
        <div class="mt-2"><label class="form-label"><strong>Manpower</strong></label><div id="manpower-${taskIdx}-${si}"></div><button type="button" class="btn btn-sm btn-outline-info mt-1" onclick="addManpower(${taskIdx},${si})">+ Mekanik</button></div>
        <div class="mt-2"><label class="form-label"><strong>Parts</strong></label><div id="parts-${taskIdx}-${si}"></div><button type="button" class="btn btn-sm btn-outline-info mt-1" onclick="addPartRow(${taskIdx},${si})">+ Part</button></div>
        <div class="mt-2"><label class="form-label"><strong>Tool</strong></label><div id="tools-${taskIdx}-${si}"></div><button type="button" class="btn btn-sm btn-outline-info mt-1" onclick="addToolRow(${taskIdx},${si})">+ Tool</button></div>
    `;
    container.appendChild(div);
    bindSubtaskDateFields(div);
    if (data?.status) document.getElementById(`st-status-${taskIdx}-${si}`).value = data.status;
    if (data?.breakdown_type_id) document.getElementById(`st-bd-${taskIdx}-${si}`).value = data.breakdown_type_id;
    if (data?.manpower) data.manpower.forEach(mp => addManpower(taskIdx, si, mp.mechanic_id));
    if (data?.parts) data.parts.forEach(p => addPartRow(taskIdx, si, p));
    if (data?.tools) data.tools.forEach(t => addToolRow(taskIdx, si, t.tool_transaction_id));
}

let mpCounters = {};
function addManpower(taskIdx, subtaskIdx, mechanicId) {
    const key = taskIdx+'-'+subtaskIdx;
    if (!mpCounters[key]) mpCounters[key] = 0;
    mpCounters[key]++;
    const container = document.getElementById('manpower-'+key);
    const row = document.createElement('div'); row.className = 'd-flex gap-2 mb-1 align-items-center';
    row.innerHTML = `<select name="tasks[${taskIdx}][subtasks][${subtaskIdx}][manpower_ids][]" class="form-select form-select-sm"><option value="">-- Pilih --</option>${mechanicOptions}</select><button type="button" class="btn btn-sm btn-outline-danger" onclick="this.parentElement.remove()">×</button>`;
    container.appendChild(row);
    if (mechanicId) row.querySelector('select').value = mechanicId;
}

let partCounters = {};
function addPartRow(taskIdx, subtaskIdx, data) {
    const key = taskIdx+'-'+subtaskIdx;
    if (!partCounters[key]) partCounters[key] = 0;
    const pi = partCounters[key]++;
    const container = document.getElementById('parts-'+key);
    const row = document.createElement('div'); row.className = 'border rounded p-2 mb-2 bg-light';
    
    // Default values
    const status = data?.part_status || 'Replace';
    
    row.innerHTML = `
        <div class="d-flex gap-2 mb-2 align-items-end">
            <div class="input-group input-group-sm" style="flex:3">
                <select name="tasks[${taskIdx}][subtasks][${subtaskIdx}][parts][${pi}][part_id]" class="form-select form-select-sm" id="part-sel-${key}-${pi}" required>
                    ${partOptions}
                </select>
                <button type="button" class="btn btn-outline-primary btn-sm" onclick="inlineAddPart(document.getElementById('part-sel-${key}-${pi}'))">+</button>
            </div>
            <div style="flex:1">
                <label class="form-label small text-muted mb-0">Status</label>
                <select name="tasks[${taskIdx}][subtasks][${subtaskIdx}][parts][${pi}][part_status]" class="form-select form-select-sm" id="part-status-${key}-${pi}" onchange="togglePartStatusFields('${key}-${pi}')">
                    <option value="Replace">Replace</option>
                    <option value="Repair">Repair</option>
                    <option value="Order Part">Order Part</option>
                    <option value="Swap / Canibal">Swap / Canibal</option>
                </select>
            </div>
            <div style="flex:1">
                <label class="form-label small text-muted mb-0">Qty</label>
                <input type="number" name="tasks[${taskIdx}][subtasks][${subtaskIdx}][parts][${pi}][qty]" class="form-control form-control-sm" min="1" value="${data?.qty||1}">
            </div>
            <div style="flex:1">
                <label class="form-label small text-muted mb-0">Satuan</label>
                <input type="text" name="tasks[${taskIdx}][subtasks][${subtaskIdx}][parts][${pi}][satuan]" class="form-control form-control-sm" value="${data?.satuan||''}">
            </div>
            <div>
                <button type="button" class="btn btn-sm btn-outline-danger" onclick="this.parentElement.parentElement.parentElement.remove()">×</button>
            </div>
        </div>
        
        <!-- Order Part Fields -->
        <div class="row g-2 mb-1 d-none" id="order-fields-${key}-${pi}">
            <div class="col-6">
                <input type="text" class="form-control form-control-sm" name="tasks[${taskIdx}][subtasks][${subtaskIdx}][parts][${pi}][mol_pr_order]" placeholder="MOL/PR" value="${status === 'Order Part' ? (data?.mol_pr||'') : ''}">
            </div>
            <div class="col-6">
                <select class="form-select form-select-sm" name="tasks[${taskIdx}][subtasks][${subtaskIdx}][parts][${pi}][order_status]">
                    <option value="">-- Status Orderan --</option>
                    <option value="Waiting Approve" ${data?.order_status === 'Waiting Approve' ? 'selected' : ''}>Waiting Approve</option>
                    <option value="Waiting PO" ${data?.order_status === 'Waiting PO' ? 'selected' : ''}>Waiting PO</option>
                    <option value="On The Way" ${data?.order_status === 'On The Way' ? 'selected' : ''}>On The Way</option>
                </select>
            </div>
        </div>

        <!-- Swap Component Fields -->
        <div class="row g-2 mb-1 d-none" id="swap-fields-${key}-${pi}">
            <div class="col-3">
                <select class="form-select form-select-sm" name="tasks[${taskIdx}][subtasks][${subtaskIdx}][parts][${pi}][swap_type]">
                    <option value="">-- Tipe Swap --</option>
                    <option value="Swap To" ${data?.swap_type === 'Swap To' ? 'selected' : ''}>Swap To</option>
                    <option value="Swap From" ${data?.swap_type === 'Swap From' ? 'selected' : ''}>Swap From</option>
                </select>
            </div>
            <div class="col-3">
                <select class="form-select form-select-sm" name="tasks[${taskIdx}][subtasks][${subtaskIdx}][parts][${pi}][swap_unit_id]">
                    ${unitOptions}
                </select>
            </div>
            <div class="col-2">
                <input type="text" class="form-control form-control-sm" name="tasks[${taskIdx}][subtasks][${subtaskIdx}][parts][${pi}][mol_pr_swap]" placeholder="MOL/PR" value="${status === 'Swap / Canibal' ? (data?.mol_pr||'') : ''}">
            </div>
            <div class="col-2">
                <select class="form-select form-select-sm" name="tasks[${taskIdx}][subtasks][${subtaskIdx}][parts][${pi}][swap_status]">
                    <option value="">-- Status --</option>
                    <option value="Waiting Part" ${data?.swap_status === 'Waiting Part' ? 'selected' : ''}>Waiting Part</option>
                    <option value="Completed" ${data?.swap_status === 'Completed' ? 'selected' : ''}>Completed</option>
                    <option value="Cancel" ${data?.swap_status === 'Cancel' ? 'selected' : ''}>Cancel</option>
                </select>
            </div>
            <div class="col-2">
                <input type="text" class="form-control form-control-sm" name="tasks[${taskIdx}][subtasks][${subtaskIdx}][parts][${pi}][swap_remarks]" placeholder="Remarks" value="${data?.swap_remarks||''}">
            </div>
        </div>
    `;
    container.appendChild(row);
    if (data?.part_id) row.querySelector(`#part-sel-${key}-${pi}`).value = data.part_id;
    if (data?.part_status) row.querySelector(`#part-status-${key}-${pi}`).value = data.part_status;
    if (data?.swap_unit_id) row.querySelector(`[name="tasks[${taskIdx}][subtasks][${subtaskIdx}][parts][${pi}][swap_unit_id]"]`).value = data.swap_unit_id;
    
    togglePartStatusFields(`${key}-${pi}`);
}

function togglePartStatusFields(id) {
    const status = document.getElementById(`part-status-${id}`).value;
    const orderFields = document.getElementById(`order-fields-${id}`);
    const swapFields = document.getElementById(`swap-fields-${id}`);
    
    orderFields.classList.add('d-none');
    swapFields.classList.add('d-none');
    
    if (status === 'Order Part') {
        orderFields.classList.remove('d-none');
    } else if (status === 'Swap / Canibal') {
        swapFields.classList.remove('d-none');
    }
}

let toolCounters = {};
function addToolRow(taskIdx, subtaskIdx, txId) {
    const key = taskIdx+'-'+subtaskIdx;
    if (!toolCounters[key]) toolCounters[key] = 0;
    toolCounters[key]++;
    const container = document.getElementById('tools-'+key);
    const row = document.createElement('div'); row.className = 'd-flex gap-2 mb-1 align-items-center';
    row.innerHTML = `<select name="tasks[${taskIdx}][subtasks][${subtaskIdx}][tool_transaction_ids][]" class="form-select form-select-sm">${toolTxOptions}</select><button type="button" class="btn btn-sm btn-outline-danger" onclick="this.parentElement.remove()">×</button>`;
    container.appendChild(row);
    if (txId) row.querySelector('select').value = txId;
}

// Load existing tasks
document.addEventListener('DOMContentLoaded', function() {
    const oldTasks = @json(old('tasks'));
    let tasksToLoad = [];
    
    if (oldTasks && Object.keys(oldTasks).length > 0) {
        // If old tasks exist (from validation error), convert from object/array to array
        tasksToLoad = Array.isArray(oldTasks) ? oldTasks : Object.values(oldTasks);
        
        // Ensure subtasks inside oldTasks are arrays too
        tasksToLoad.forEach(t => {
            if (t.subtasks && !Array.isArray(t.subtasks)) {
                t.subtasks = Object.values(t.subtasks);
            }
            if (t.subtasks) {
                t.subtasks.forEach(s => {
                    if (s.parts && !Array.isArray(s.parts)) s.parts = Object.values(s.parts);
                });
            }
        });
    } else {
        tasksToLoad = @json($existingTasks);
    }
    
    tasksToLoad.forEach(t => addTask(t));
});
</script>
@endpush
