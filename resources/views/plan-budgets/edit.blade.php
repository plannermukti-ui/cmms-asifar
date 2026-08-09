@extends('layouts.tabler')

@section('title', 'Edit Plan Budget Bulanan - CMMS Aisfar')

@section('content')
<div class="page-header d-print-none">
  <div class="row align-items-center">
    <div class="col">
      <h2 class="page-title">Edit Plan Budget Bulanan</h2>
    </div>
    <div class="col-auto ms-auto d-print-none">
      <div class="btn-list">
        <a href="{{ route('plan-budgets.index') }}" class="btn btn-secondary">
          Kembali
        </a>
      </div>
    </div>
  </div>
</div>

<div class="card mt-3">
  @if ($errors->any())
    <div class="alert alert-danger m-3">
      <ul class="mb-0">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form action="{{ route('plan-budgets.update', $planBudget) }}" method="POST" id="planForm">
    @csrf
    @method('PUT')
    
    <div class="card-body">
      <div class="row mb-4 align-items-end">
        <div class="col-md-4 mb-3 mb-md-0">
          <label class="form-label">Site (Cabang)</label>
          <div class="form-control-plaintext border-bottom fw-bold">{{ $planBudget->site->name ?? '-' }}</div>
        </div>
        <div class="col-md-4 mb-3 mb-md-0">
          <label class="form-label">Periode</label>
          <div class="form-control-plaintext border-bottom fw-bold">{{ \Carbon\Carbon::createFromFormat('Y-m', $planBudget->period)->format('F Y') }}</div>
        </div>
        <div class="col-md-4">
          <label class="form-label required">Status</label>
          <select name="status" class="form-select">
            <option value="Draft" {{ old('status', $planBudget->status) == 'Draft' ? 'selected' : '' }}>Draft</option>
            <option value="Approved" {{ old('status', $planBudget->status) == 'Approved' ? 'selected' : '' }}>Approved</option>
          </select>
        </div>
      </div>

      <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="card-title mb-0">Daftar Unit Plan & Parts</h3>
        <button type="button" id="addUnitBtn" class="btn btn-success btn-sm">
          Tambah Unit
        </button>
      </div>

      <div class="table-responsive">
        <table class="table table-vcenter card-table table-bordered">
          <thead class="bg-light">
            <tr>
              <th class="required" style="width: 30%">Unit</th>
              <th class="required" style="width: 15%">Target PA (%)</th>
              <th class="required">Rencana Part (Cost akan dihitung otomatis)</th>
              <th class="w-1 text-center">Aksi</th>
            </tr>
          </thead>
          <tbody id="unitsContainer">
            @php
              $currentUnits = old('units', $planBudget->units->toArray());
            @endphp
            
            @foreach($currentUnits as $index => $u)
              <tr class="unit-row" data-index="{{ $index }}">
                <td class="align-top">
                  <select name="units[{{$index}}][master_unit_id]" required class="form-select mb-2">
                    <option value="">Pilih Unit</option>
                    @foreach($units as $unit)
                      <option value="{{ $unit->id }}" {{ $u['master_unit_id'] == $unit->id ? 'selected' : '' }}>{{ $unit->nomor_unit }} - {{ $unit->model->model_no ?? '' }}</option>
                    @endforeach
                  </select>
                </td>
                <td class="align-top">
                  <input type="number" step="0.01" min="0" max="100" name="units[{{$index}}][target_pa]" value="{{ $u['target_pa'] }}" required class="form-control" placeholder="e.g. 85.5">
                </td>
                <td class="align-top p-0">
                  <table class="table table-sm table-borderless mb-0">
                    <tbody class="parts-list">
                      @if(isset($u['parts']))
                        @foreach($u['parts'] as $pIndex => $p)
                          <tr>
                            <td style="width: 70%">
                              <select name="units[{{$index}}][parts][{{$pIndex}}][part_id]" class="form-select form-select-sm" required>
                                <option value="">Pilih Part</option>
                                @foreach($parts as $part)
                                  <option value="{{ $part->id }}" {{ $p['part_id'] == $part->id ? 'selected' : '' }}>{{ $part->part_number }} - {{ $part->part_description }} (Rp{{ number_format($part->cost, 0, ',', '.') }})</option>
                                @endforeach
                              </select>
                            </td>
                            <td>
                              <input type="number" name="units[{{$index}}][parts][{{$pIndex}}][qty]" value="{{ $p['qty'] }}" class="form-control form-control-sm" placeholder="Qty" required min="1">
                            </td>
                            <td>
                              <button type="button" class="btn btn-danger btn-sm remove-part-btn">X</button>
                            </td>
                          </tr>
                        @endforeach
                      @endif
                    </tbody>
                    <tfoot>
                      <tr>
                        <td colspan="3"><button type="button" class="btn btn-sm btn-outline-primary add-part-btn">Tambah Part</button></td>
                      </tr>
                    </tfoot>
                  </table>
                </td>
                <td class="text-center align-top pt-3">
                  <button type="button" class="btn btn-danger btn-icon btn-sm remove-unit-btn" aria-label="Hapus Unit">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M18 6l-12 12" /><path d="M6 6l12 12" /></svg>
                  </button>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
    <div class="card-footer text-end">
      <button type="submit" class="btn btn-primary">Update Plan Budget</button>
    </div>
  </form>
</div>

<template id="unitRowTemplate">
  <tr class="unit-row" data-index="__INDEX__">
    <td class="align-top">
      <select name="units[__INDEX__][master_unit_id]" required class="form-select mb-2">
        <option value="">Pilih Unit</option>
        @foreach($units as $unit)
          <option value="{{ $unit->id }}">{{ $unit->nomor_unit }} - {{ $unit->model->model_no ?? '' }}</option>
        @endforeach
      </select>
    </td>
    <td class="align-top">
      <input type="number" step="0.01" min="0" max="100" name="units[__INDEX__][target_pa]" required class="form-control" placeholder="e.g. 85.5">
    </td>
    <td class="align-top p-0">
      <table class="table table-sm table-borderless mb-0">
        <tbody class="parts-list">
        </tbody>
        <tfoot>
          <tr>
            <td colspan="3"><button type="button" class="btn btn-sm btn-outline-primary add-part-btn">Tambah Part</button></td>
          </tr>
        </tfoot>
      </table>
    </td>
    <td class="text-center align-top pt-3">
      <button type="button" class="btn btn-danger btn-icon btn-sm remove-unit-btn" aria-label="Hapus Unit">
        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M18 6l-12 12" /><path d="M6 6l12 12" /></svg>
      </button>
    </td>
  </tr>
</template>

<template id="partRowTemplate">
  <tr>
    <td style="width: 70%">
      <select name="units[__UINDEX__][parts][__PINDEX__][part_id]" class="form-select form-select-sm" required>
        <option value="">Pilih Part</option>
        @foreach($parts as $part)
          <option value="{{ $part->id }}">{{ $part->part_number }} - {{ $part->part_description }} (Rp{{ number_format($part->cost, 0, ',', '.') }})</option>
        @endforeach
      </select>
    </td>
    <td>
      <input type="number" name="units[__UINDEX__][parts][__PINDEX__][qty]" class="form-control form-control-sm" placeholder="Qty" required min="1">
    </td>
    <td>
      <button type="button" class="btn btn-danger btn-sm remove-part-btn">X</button>
    </td>
  </tr>
</template>
@endsection

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    let unitIndex = {{ count($currentUnits) }};
    let partIndex = 1000;
    const unitTemplate = document.getElementById('unitRowTemplate').innerHTML;
    const partTemplate = document.getElementById('partRowTemplate').innerHTML;
    const container = document.getElementById('unitsContainer');

    document.getElementById('addUnitBtn').addEventListener('click', function() {
      const rowHtml = unitTemplate.replace(/__INDEX__/g, unitIndex);
      container.insertAdjacentHTML('beforeend', rowHtml);
      unitIndex++;
    });

    container.addEventListener('click', function(e) {
      if (e.target.closest('.remove-unit-btn')) {
        if (container.querySelectorAll('.unit-row').length > 1) {
          e.target.closest('tr.unit-row').remove();
        } else {
          alert('Minimal harus ada 1 unit.');
        }
      }

      if (e.target.closest('.add-part-btn')) {
        const uIndex = e.target.closest('tr.unit-row').dataset.index;
        const rowHtml = partTemplate.replace(/__UINDEX__/g, uIndex).replace(/__PINDEX__/g, partIndex);
        e.target.closest('table').querySelector('.parts-list').insertAdjacentHTML('beforeend', rowHtml);
        partIndex++;
      }

      if (e.target.closest('.remove-part-btn')) {
        e.target.closest('tr').remove();
      }
    });
  });
</script>
@endpush
