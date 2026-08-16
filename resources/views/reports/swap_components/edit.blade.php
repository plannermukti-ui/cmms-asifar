@extends('layouts.tabler')

@section('title', 'Edit Swap Component Data')

@section('content')
<div class="page-header d-print-none">
  <div class="container-xl">
    <div class="row g-2 align-items-center">
      <div class="col">
        <h2 class="page-title fw-bold text-purple">
          Edit Swap Component Data
        </h2>
      </div>
      <div class="col-auto ms-auto d-print-none">
        <div class="btn-list">
          <a href="{{ route('swap-components.index') }}" class="btn btn-outline-secondary">
            Batal & Kembali
          </a>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="page-body">
  <div class="container-xl">
    <div class="row">
      <div class="col-md-8 mx-auto">
        <div class="card shadow-sm border-0">
          <form action="{{ route('swap-components.update', $swap->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="card-body">
              <h3 class="card-title text-muted mb-3 border-bottom pb-2">Detail Data Swap</h3>
              
              <div class="mb-3 bg-light p-3 rounded">
                <div class="row">
                    <div class="col-6">
                        <strong>Work Order:</strong> {{ $swap->subtask->task->workOrder->no_wo ?? '-' }}
                    </div>
                    <div class="col-6">
                        <strong>Part:</strong> {{ $swap->part->part_number ?? '-' }} ({{ $swap->part->part_description ?? '-' }})
                    </div>
                </div>
              </div>

              <div class="row g-3">
                <div class="col-md-6">
                  <label class="form-label required">Tipe Swap</label>
                  <select name="swap_type" class="form-select" required>
                    <option value="">-- Pilih --</option>
                    <option value="Swap To" {{ $swap->swap_type == 'Swap To' ? 'selected' : '' }}>Swap To</option>
                    <option value="Swap From" {{ $swap->swap_type == 'Swap From' ? 'selected' : '' }}>Swap From</option>
                  </select>
                </div>
                <div class="col-md-6">
                  <label class="form-label required">Unit Tujuan/Asal</label>
                  <select name="swap_unit_id" class="form-select" required>
                    <option value="">-- Pilih Unit --</option>
                    @foreach($units as $unit)
                      <option value="{{ $unit->id }}" {{ $swap->swap_unit_id == $unit->id ? 'selected' : '' }}>
                        {{ $unit->nomor_unit }} - {{ $unit->model->name ?? '' }}
                      </option>
                    @endforeach
                  </select>
                </div>
                
                <div class="col-md-6">
                  <label class="form-label">PR/MOL</label>
                  <input type="text" class="form-control" name="mol_pr" value="{{ old('mol_pr', $swap->mol_pr) }}">
                </div>
                <div class="col-md-6">
                  <label class="form-label required">Status</label>
                  <select name="swap_status" class="form-select" required>
                    <option value="">-- Pilih --</option>
                    <option value="Waiting Part" {{ $swap->swap_status == 'Waiting Part' ? 'selected' : '' }}>Waiting Part</option>
                    <option value="Completed" {{ $swap->swap_status == 'Completed' ? 'selected' : '' }}>Completed</option>
                    <option value="Cancel" {{ $swap->swap_status == 'Cancel' ? 'selected' : '' }}>Cancel</option>
                  </select>
                </div>

                <div class="col-md-12">
                  <label class="form-label">Remarks / Keterangan</label>
                  <textarea name="swap_remarks" rows="3" class="form-control">{{ old('swap_remarks', $swap->swap_remarks) }}</textarea>
                </div>
              </div>
            </div>
            <div class="card-footer text-end bg-light">
              <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
