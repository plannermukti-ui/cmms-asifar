@extends('layouts.tabler')

@section('title', 'Plan Budget Bulanan - CMMS Aisfar')

@section('content')
<style>
  [data-bs-theme="dark"] .table thead th {
    background-color: #131c2c !important;
    color: #cbd5e1 !important;
    border-color: rgba(255, 255, 255, 0.08) !important;
  }
  [data-bs-theme="dark"] .table td {
    border-color: rgba(255, 255, 255, 0.08) !important;
  }
  [data-bs-theme="dark"] .modal-content {
    background-color: #182234 !important;
    border-color: rgba(255, 255, 255, 0.08) !important;
  }
  [data-bs-theme="dark"] .modal-header,
  [data-bs-theme="dark"] .modal-footer {
    border-color: rgba(255, 255, 255, 0.08) !important;
  }
</style>

<div class="page-header d-print-none">
  <div class="row align-items-center">
    <div class="col">
      <h2 class="page-title">Plan Budget Bulanan</h2>
    </div>
    <div class="col-auto ms-auto d-print-none">
      <div class="btn-list">
        <button type="button" class="btn btn-primary d-none d-sm-inline-block" data-bs-toggle="modal" data-bs-target="#createPlanBudgetModal">
          <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
          Tambah Plan Budget
        </button>
      </div>
    </div>
  </div>
</div>

<div class="card mt-3">
  <div class="card-header border-0 pb-0">
    <form action="{{ route('plan-budgets.index') }}" method="GET" class="w-100">
      <div class="row g-2 align-items-end">
        <div class="col-md-3">
          <label class="form-label">Site (Cabang)</label>
          <select name="site_id" class="form-select" onchange="this.form.submit()">
            <option value="">Semua Site</option>
            @foreach($sites as $site)
              <option value="{{ $site->id }}" {{ request('site_id') == $site->id ? 'selected' : '' }}>{{ $site->name }}</option>
            @endforeach
          </select>
        </div>
      </div>
    </form>
  </div>

  @if (session('success'))
    <div class="alert alert-success alert-dismissible m-3" role="alert">
      <div class="d-flex">
        <div>{{ session('success') }}</div>
      </div>
      <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
    </div>
  @endif

  @if (session('error'))
    <div class="alert alert-danger alert-dismissible m-3" role="alert">
      <div class="d-flex">
        <div>{{ session('error') }}</div>
      </div>
      <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
    </div>
  @endif

  <div class="table-responsive mt-3">
    <table class="table table-vcenter card-table table-striped table-hover text-nowrap">
      <thead>
        <tr>
          <th>Site</th>
          <th>Periode</th>
          <th>Status</th>
          <th>Dibuat Oleh</th>
          <th class="w-1">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($planBudgets as $plan)
          <tr>
            <td>{{ $plan->site->name ?? '-' }}</td>
            <td><strong>{{ \Carbon\Carbon::createFromFormat('Y-m', $plan->period)->format('F Y') }}</strong></td>
            <td>
              <span class="badge {{ $plan->status == 'Approved' ? 'bg-green' : 'bg-yellow' }}">
                {{ $plan->status }}
              </span>
            </td>
            <td class="text-muted">{{ $plan->creator->name ?? '-' }}</td>
            <td>
              <div class="btn-list flex-nowrap">
                <a href="{{ route('plan-budgets.show', $plan) }}?mode=plan" class="btn btn-sm btn-info" title="Lihat & Cetak Dokumen Pengajuan Rencana">
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><path d="M9 15l2 2l4 -4" /></svg>
                  Pengajuan (Plan)
                </a>
                <a href="{{ route('plan-budgets.show', $plan) }}?mode=eval" class="btn btn-sm btn-success" title="Evaluasi Realisasi Goal (Plan vs Actual)">
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 12m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v6a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" /><path d="M9 8m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v10a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" /><path d="M15 4m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v14a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" /></svg>
                  Evaluasi (Goal)
                </a>
                @if ($plan->status == 'Draft')
                  <a href="{{ route('plan-budgets.edit', $plan) }}" class="btn btn-sm btn-primary">Edit</a>
                  <form action="{{ route('plan-budgets.destroy', $plan) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus Plan Budget ini?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                  </form>
                @endif
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="5" class="text-center text-muted py-4">Belum ada data Plan Budget</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($planBudgets->hasPages())
    <div class="card-footer d-flex align-items-center">
      {{ $planBudgets->withQueryString()->links('pagination::bootstrap-5') }}
    </div>
  @endif
</div>

<!-- MODAL TAMBAH PLAN BUDGET BULANAN -->
<div class="modal modal-blur fade" id="createPlanBudgetModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Tambah Plan Budget Bulanan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('plan-budgets.store') }}" method="POST" id="planForm">
        @csrf
        <div class="modal-body">
          @if ($errors->any())
            <div class="alert alert-danger mb-3">
              <ul class="mb-0">
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          <div class="row mb-4">
            <div class="col-md-6 mb-3 mb-md-0">
              <label class="form-label">Site (Cabang)</label>
              <select name="site_id" class="form-select">
                <option value="">-- Pilih Site --</option>
                @foreach($sites as $site)
                  <option value="{{ $site->id }}" {{ old('site_id') == $site->id ? 'selected' : '' }}>{{ $site->name }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label required">Periode (Bulan & Tahun)</label>
              <input type="month" name="period" value="{{ old('period', \Carbon\Carbon::now()->format('Y-m')) }}" required class="form-control">
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
                @if(old('units'))
                  @foreach(old('units') as $index => $u)
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
                @else
                  <tr class="unit-row" data-index="0">
                    <td class="align-top">
                      <select name="units[0][master_unit_id]" required class="form-select mb-2">
                        <option value="">Pilih Unit</option>
                        @foreach($units as $unit)
                          <option value="{{ $unit->id }}">{{ $unit->nomor_unit }} - {{ $unit->model->model_no ?? '' }}</option>
                        @endforeach
                      </select>
                    </td>
                    <td class="align-top">
                      <input type="number" step="0.01" min="0" max="100" name="units[0][target_pa]" required class="form-control" placeholder="e.g. 85.5">
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
                @endif
              </tbody>
            </table>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary me-auto" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan Plan Budget</button>
        </div>
      </form>
    </div>
  </div>
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
    let unitIndex = {{ old('units') ? count(old('units')) : 1 }};
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

    @if (request('open_create') || $errors->any())
      var createModal = new bootstrap.Modal(document.getElementById('createPlanBudgetModal'));
      createModal.show();
    @endif
  });
</script>
@endpush
