@extends('layouts.tabler')
@section('title', 'Edit PM Template - CMMS')

@section('content')
<div class="page-header d-print-none">
  <div class="row align-items-center">
    <div class="col-auto">
      <a href="{{ route('pm-templates.index') }}" class="btn btn-icon btn-outline-secondary">
        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 14l-4 -4l4 -4" /><path d="M5 10h11a4 4 0 1 1 0 8h-1" /></svg>
      </a>
    </div>
    <div class="col">
      <h2 class="page-title">Edit PM Template: {{ $pmTemplate->name }}</h2>
    </div>
  </div>
</div>

@if ($errors->any())
<div class="alert alert-danger mt-3">
    <ul class="mb-0">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form action="{{ route('pm-templates.update', $pmTemplate) }}" method="POST" id="pmForm">
@csrf
@method('PUT')

<div class="card mt-3">
  <div class="card-header">
    <h3 class="card-title">Informasi Template</h3>
  </div>
  <div class="card-body">
    <div class="row g-3">
      @if(auth()->user()->site_id)
        <!-- Hidden input for site_id if user belongs to a specific site -->
        <input type="hidden" name="site_id" value="{{ auth()->user()->site_id }}">
      @else
      <div class="col-md-6">
        <label class="form-label required">Site (Lokasi)</label>
        <select name="site_id" required class="form-select">
            <option value="">-- Pilih Site --</option>
            @foreach($sites as $site)
                <option value="{{ $site->id }}" {{ old('site_id', $pmTemplate->site_id) == $site->id ? 'selected' : '' }}>{{ $site->name }} ({{ $site->code }})</option>
            @endforeach
        </select>
      </div>
      @endif

      <div class="col-md-6">
        <label class="form-label required">Model Unit</label>
        <select name="unit_model_id" required class="form-select">
            <option value="">-- Pilih Model Unit --</option>
            @foreach($unitModels as $model)
                <option value="{{ $model->id }}" {{ old('unit_model_id', $pmTemplate->unit_model_id) == $model->id ? 'selected' : '' }}>{{ $model->name }} ({{ $model->type->name ?? '' }})</option>
            @endforeach
        </select>
      </div>
      <div class="col-md-6">
        <label class="form-label required">Nama Template</label>
        <input type="text" name="name" value="{{ old('name', $pmTemplate->name) }}" required placeholder="Contoh: Service 250H" class="form-control">
      </div>
      <div class="col-md-4 mb-3">
        <label class="form-label required">Tipe Interval</label>
        <select name="interval_type" class="form-select" required>
          <option value="hour_meter" {{ old('interval_type', $pmTemplate->interval_type) == 'hour_meter' ? 'selected' : '' }}>Hour Meter (HM)</option>
          <option value="kilometer" {{ old('interval_type', $pmTemplate->interval_type) == 'kilometer' ? 'selected' : '' }}>Kilometer (KM)</option>
          <option value="days" {{ old('interval_type', $pmTemplate->interval_type) == 'days' ? 'selected' : '' }}>Hari (Days)</option>
        </select>
      </div>
      <div class="col-md-4 mb-3">
        <label class="form-label required">Nilai Interval</label>
        <input type="number" name="interval_value" class="form-control" value="{{ old('interval_value', $pmTemplate->interval_value) }}" required min="1">
      </div>
      <div class="col-md-4 mb-3">
        <label class="form-label required">Opr Hrs/Day</label>
        <input type="number" step="0.1" name="opr_hrs_per_day" class="form-control" value="{{ old('opr_hrs_per_day', $pmTemplate->opr_hrs_per_day ?? 20) }}" required min="1">
        <small class="form-hint">Estimasi jam operasi unit per hari.</small>
      </div>
    </div>
  </div>
</div>

<div class="card mt-3">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h3 class="card-title">Konfigurasi Pekerjaan (Tasks & Subtasks)</h3>
    <button type="button" id="addTaskBtn" class="btn btn-sm btn-primary">
      <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="12" y1="5" x2="12" y2="19" /><line x1="5" y1="12" x2="19" y2="12" /></svg>
      Tambah Task Utama
    </button>
  </div>
  <div class="card-body p-3" id="tasksContainer">
    @foreach($pmTemplate->tasks as $tIndex => $task)
        <div class="border-start border-4 border-primary rounded-3 p-3 mb-3 bg-light shadow-sm position-relative" id="task-{{ $tIndex }}">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <div class="w-100 me-3">
                    <label class="form-label required">Task Utama</label>
                    <input type="text" name="tasks[{{ $tIndex }}][task_name]" value="{{ $task->task_name }}" required class="form-control">
                </div>
                <button type="button" class="btn btn-sm btn-outline-danger mt-4" onclick="document.getElementById('task-{{ $tIndex }}').remove()">
                    Hapus
                </button>
            </div>
            
            <div class="ms-3 ps-3 border-start border-2 mt-3" id="subtasks-{{ $tIndex }}">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <strong class="text-muted small text-uppercase">Subtasks / Detail Checklist</strong>
                    <button type="button" onclick="addSubtask({{ $tIndex }})" class="btn btn-sm btn-outline-secondary">
                        + Tambah Subtask
                    </button>
                </div>
                
                @foreach($task->subtasks as $sIndex => $subtask)
                    <div class="subtask-item mb-3 p-2 border rounded" id="sub-{{ $tIndex }}-{{ $sIndex }}">
                        <div class="d-flex align-items-start gap-2">
                            <div class="flex-grow-1">
                                <input type="text" name="tasks[{{ $tIndex }}][subtasks][{{ $sIndex }}][subtask_name]" value="{{ $subtask->subtask_name }}" required class="form-control form-control-sm mb-2">
                                <select name="tasks[{{ $tIndex }}][subtasks][{{ $sIndex }}][parts][]" class="form-select form-select-sm" multiple data-placeholder="Pilih Parts (Opsional)">
                                    <option value="">-- Pilih Part --</option>
                                    @php $selectedParts = $subtask->parts->pluck('id')->toArray(); @endphp
                                    @foreach($parts as $part)
                                        <option value="{{ $part->id }}" {{ in_array($part->id, $selectedParts) ? 'selected' : '' }}>
                                            {{ $part->part_number }} - {{ $part->part_description }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="button" onclick="document.getElementById('sub-{{ $tIndex }}-{{ $sIndex }}').remove()" class="btn btn-sm btn-outline-danger px-2 py-1 mt-1">✕</button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach
  </div>
  <div class="card-footer text-end">
    <button type="submit" class="btn btn-success">
      <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-check me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10" /></svg>
      Perbarui Template
    </button>
  </div>
</div>

</form>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let taskIndex = {{ $pmTemplate->tasks->count() }};
        const container = document.getElementById('tasksContainer');
        const partsData = @json($parts);

        document.getElementById('addTaskBtn').addEventListener('click', function() {
            addTask();
        });

        function addTask() {
            const taskId = taskIndex++;
            const div = document.createElement('div');
            div.className = 'border-start border-4 border-primary rounded-3 p-3 mb-3 bg-light shadow-sm position-relative';
            div.id = 'task-' + taskId;
            div.innerHTML = `
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div class="w-100 me-3">
                        <label class="form-label required">Task Utama</label>
                        <input type="text" name="tasks[${taskId}][task_name]" required placeholder="Contoh: Periksa area mesin" class="form-control">
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-danger mt-4" onclick="document.getElementById('task-${taskId}').remove()">
                        Hapus
                    </button>
                </div>
                
                <div class="ms-3 ps-3 border-start border-2 mt-3" id="subtasks-${taskId}">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <strong class="text-muted small text-uppercase">Subtasks / Detail Checklist</strong>
                        <button type="button" onclick="addSubtask(${taskId})" class="btn btn-sm btn-outline-secondary">
                            + Tambah Subtask
                        </button>
                    </div>
                </div>
            `;
            container.appendChild(div);
            addSubtask(taskId); // add one default empty subtask
        }

        window.addSubtask = function(taskId) {
            const subContainer = document.getElementById(`subtasks-${taskId}`);
            const subIndex = subContainer.querySelectorAll('.subtask-item').length;
            const subId = `sub-${taskId}-${Date.now()}`;
            
            let partsOptions = '<option value="">-- Pilih Part --</option>';
            partsData.forEach(p => {
                partsOptions += `<option value="${p.id}">${p.part_number} - ${p.part_description}</option>`;
            });

            const div = document.createElement('div');
            div.className = 'subtask-item mb-3 p-2 border rounded';
            div.id = subId;
            div.innerHTML = `
                <div class="d-flex align-items-start gap-2">
                    <div class="flex-grow-1">
                        <input type="text" name="tasks[${taskId}][subtasks][${subIndex}][subtask_name]" required placeholder="Contoh: Cek level oli mesin" class="form-control form-control-sm mb-2">
                        <select name="tasks[${taskId}][subtasks][${subIndex}][parts][]" class="form-select form-select-sm" multiple data-placeholder="Pilih Parts (Opsional)">
                            ${partsOptions}
                        </select>
                        <small class="text-muted">Tahan CTRL/CMD untuk memilih lebih dari satu part.</small>
                    </div>
                    <button type="button" onclick="document.getElementById('${subId}').remove()" class="btn btn-sm btn-outline-danger px-2 py-1 mt-1">✕</button>
                </div>
            `;
            subContainer.appendChild(div);
        }
    });
</script>
@endpush
