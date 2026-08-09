@extends('layouts.tabler')
@section('title', 'Buat PM Template - CMMS')

@section('content')
<div class="page-header d-print-none">
  <div class="row align-items-center">
    <div class="col-auto">
      <a href="{{ route('pm-templates.index') }}" class="btn btn-icon btn-outline-secondary">
        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 14l-4 -4l4 -4" /><path d="M5 10h11a4 4 0 1 1 0 8h-1" /></svg>
      </a>
    </div>
    <div class="col">
      <h2 class="page-title">Buat PM Template Baru</h2>
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

<form action="{{ route('pm-templates.store') }}" method="POST" id="pmForm">
@csrf

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
                <option value="{{ $site->id }}" {{ old('site_id') == $site->id ? 'selected' : '' }}>{{ $site->name }} ({{ $site->code }})</option>
            @endforeach
        </select>
      </div>
      @endif

      <div class="col-md-6">
        <label class="form-label required">Model Unit</label>
        <select name="unit_model_id" required class="form-select">
            <option value="">-- Pilih Model Unit --</option>
            @foreach($unitModels as $model)
                <option value="{{ $model->id }}" {{ old('unit_model_id') == $model->id ? 'selected' : '' }}>{{ $model->name }} ({{ $model->type->name ?? '' }})</option>
            @endforeach
        </select>
      </div>
      <div class="col-md-6">
        <label class="form-label required">Nama Template</label>
        <input type="text" name="name" value="{{ old('name') }}" required placeholder="Contoh: Service 250H" class="form-control">
      </div>
      <div class="col-md-6">
        <label class="form-label required">Tipe Interval</label>
        <select name="interval_type" required class="form-select">
            <option value="hour_meter" {{ old('interval_type') == 'hour_meter' ? 'selected' : '' }}>Hour Meter (HM)</option>
            <option value="kilometer" {{ old('interval_type') == 'kilometer' ? 'selected' : '' }}>Kilometer (KM)</option>
            <option value="days" {{ old('interval_type') == 'days' ? 'selected' : '' }}>Hari (Days)</option>
        </select>
      </div>
      <div class="col-md-6">
        <label class="form-label required">Nilai Interval</label>
        <input type="number" name="interval_value" value="{{ old('interval_value') }}" required placeholder="Contoh: 250" min="1" class="form-control">
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
    <!-- Tasks will be injected here -->
  </div>
  <div class="card-footer text-end">
    <button type="submit" class="btn btn-success">
      <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-check me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10" /></svg>
      Simpan Template
    </button>
  </div>
</div>

</form>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let taskIndex = 0;
        const container = document.getElementById('tasksContainer');

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
                    <!-- Subtasks will be injected here -->
                </div>
            `;
            container.appendChild(div);
            addSubtask(taskId); // add one default empty subtask
        }

        window.addSubtask = function(taskId) {
            const subContainer = document.getElementById(`subtasks-${taskId}`);
            const subIndex = subContainer.querySelectorAll('.subtask-item').length;
            const subId = `sub-${taskId}-${Date.now()}`;
            
            const div = document.createElement('div');
            div.className = 'subtask-item d-flex align-items-center gap-2 mb-2';
            div.id = subId;
            div.innerHTML = `
                <input type="text" name="tasks[${taskId}][subtasks][${subIndex}][subtask_name]" required placeholder="Contoh: Cek level oli mesin" class="form-control form-control-sm">
                <button type="button" onclick="document.getElementById('${subId}').remove()" class="btn btn-sm btn-outline-danger px-2 py-1">✕</button>
            `;
            subContainer.appendChild(div);
        }

        // Add initial task
        addTask();
    });
</script>
@endpush
