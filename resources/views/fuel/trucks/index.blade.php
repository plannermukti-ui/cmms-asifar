@extends('layouts.tabler')

@section('title', 'Master Fuel Truck (Mobile Dispenser) - FMS')

@section('content')
<div class="page-header d-print-none">
  <div class="container-xl">
    <div class="row g-2 align-items-center">
      <div class="col">
        <div class="page-pretitle text-uppercase font-monospace text-primary">Master Data FMS</div>
        <h2 class="page-title d-flex align-items-center gap-2">
          <svg xmlns="http://www.w3.org/2000/svg" class="icon text-warning" width="28" height="28" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 17m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M17 17m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M5 17h-2v-11a1 1 0 0 1 1 -1h9v12m-4 0h6m4 0h2v-6h-8m0 -5h5l3 5" /></svg>
          Daftar Unit Fuel Truck (Mobile Dispenser)
        </h2>
      </div>
      <div class="col-auto ms-auto">
        @can('create_fuel_trucks')
        <button type="button" class="btn btn-warning btn-sm fw-bold" data-bs-toggle="modal" data-bs-target="#modal-add-fuel-truck">
          + Tetapkan Unit Fuel Truck
        </button>
        @endcan
      </div>
    </div>
  </div>
</div>

<div class="page-body">
  <div class="container-xl">
    
    @if(session('success'))
      <div class="alert alert-success alert-dismissible mb-3">{{ session('success') }}</div>
    @endif
    @if(session('error'))
      <div class="alert alert-danger alert-dismissible mb-3">{{ session('error') }}</div>
    @endif

    <div class="card shadow-sm border">
      <div class="table-responsive">
        <table class="table table-vcenter table-hover card-table">
          <thead>
            <tr>
              <th>Nomor Unit FT</th>
              <th>Tipe & Model Unit</th>
              <th>Site</th>
              <th class="text-end">Kapasitas (L)</th>
              <th class="text-end">Stok BBM Saat Ini</th>
              <th class="text-center">Level (%)</th>
              <th class="text-end">Totalizer Terkini</th>
              <th>No Seri Flowmeter</th>
              <th class="text-end">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($fuelTrucks as $truck)
            <tr>
              <td>
                <span class="badge bg-yellow-lt font-monospace px-2 py-0.5 me-1">FT</span>
                <span class="fw-bold fs-4 text-body">{{ $truck->masterUnit->nomor_unit ?? '-' }}</span>
              </td>
              <td>
                <div>{{ $truck->masterUnit->type->name ?? '-' }}</div>
                <div class="text-muted small">{{ $truck->masterUnit->model->name ?? '-' }}</div>
              </td>
              <td>{{ $truck->site->name ?? $truck->masterUnit->site->name ?? '-' }}</td>
              <td class="text-end font-monospace">{{ number_format($truck->capacity, 0, ',', '.') }} L</td>
              <td class="text-end font-monospace fw-bold text-warning">{{ number_format($truck->current_stock, 0, ',', '.') }} L</td>
              <td class="text-center" style="width: 110px;">
                <div class="d-flex align-items-center gap-2">
                  <div class="progress flex-grow-1" style="height: 8px;">
                    <div class="progress-bar {{ $truck->fill_percentage <= 20 ? 'bg-danger' : ($truck->fill_percentage <= 40 ? 'bg-warning' : 'bg-warning') }}" 
                         style="width: {{ $truck->fill_percentage }}%"></div>
                  </div>
                  <span class="small font-monospace">{{ $truck->fill_percentage }}%</span>
                </div>
              </td>
              <td class="text-end font-monospace fw-bold text-primary">{{ number_format($truck->current_totalizer, 2, ',', '.') }}</td>
              <td class="font-monospace small">{{ $truck->flowmeter_serial_number ?? '-' }}</td>
              <td class="text-end">
                <a href="{{ route('fuel.trucks.show', $truck) }}" class="btn btn-xs btn-outline-info">Detail</a>
                @can('edit_fuel_trucks')
                <button type="button" class="btn btn-xs btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modal-edit-truck-{{ $truck->id }}">Edit</button>
                @endcan
              </td>
            </tr>

            <!-- Modal Edit Fuel Truck -->
            <div class="modal modal-blur fade" id="modal-edit-truck-{{ $truck->id }}" tabindex="-1" role="dialog" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                  <form action="{{ route('fuel.trucks.update', $truck) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="modal-header">
                      <h5 class="modal-title">Edit Fuel Truck: {{ $truck->masterUnit->nomor_unit ?? '' }}</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                      <div class="mb-3">
                        <label class="form-label required">Kapasitas Tangki (Liter)</label>
                        <input type="number" step="0.01" class="form-control" name="capacity" value="{{ $truck->capacity }}" required>
                      </div>
                      @if(auth()->user()->hasRole('Super Admin'))
                      <div class="row g-2 mb-3 bg-red-lt p-2 rounded border border-danger-subtle">
                        <div class="col-6">
                          <label class="form-label text-danger fw-bold small">Stok Aktual (Liter) <span class="badge bg-danger text-white">Super Admin</span></label>
                          <input type="number" step="0.01" class="form-control form-control-sm font-monospace fw-bold" name="current_stock" value="{{ $truck->current_stock }}">
                        </div>
                        <div class="col-6">
                          <label class="form-label text-danger fw-bold small">Totalizer Nozzle <span class="badge bg-danger text-white">Super Admin</span></label>
                          <input type="number" step="0.01" class="form-control form-control-sm font-monospace fw-bold" name="current_totalizer" value="{{ $truck->current_totalizer }}">
                        </div>
                      </div>
                      @endif
                      <div class="row g-2 mb-3">
                        <div class="col-6">
                          <label class="form-label">No. Seri Flowmeter</label>
                          <input type="text" class="form-control" name="flowmeter_serial_number" value="{{ $truck->flowmeter_serial_number }}">
                        </div>
                        <div class="col-6">
                          <label class="form-label">Merk Dispenser</label>
                          <input type="text" class="form-control" name="dispenser_brand" value="{{ $truck->dispenser_brand }}">
                        </div>
                      </div>
                      <div class="mb-3">
                        <label class="form-label">Site</label>
                        <select name="site_id" class="form-select">
                          <option value="">-- Sesuai Master Unit --</option>
                          @foreach($sites as $s)
                            <option value="{{ $s->id }}" {{ $truck->site_id == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn me-auto" data-bs-dismiss="modal">Batal</button>
                      <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
            @empty
            <tr>
              <td colspan="9" class="text-center text-muted py-4">Belum ada Unit Master yang ditetapkan sebagai Fuel Truck.</td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

  </div>
</div>

<!-- Modal Tambah Fuel Truck -->
<div class="modal modal-blur fade" id="modal-add-fuel-truck" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <form action="{{ route('fuel.trucks.store') }}" method="POST">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title">Tetapkan Master Unit sebagai Fuel Truck</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label required">Pilih Unit (dari Master Unit)</label>
            <select name="master_unit_id" class="form-select" required>
              <option value="">-- Pilih Unit --</option>
              @foreach($availableUnits as $u)
                <option value="{{ $u->id }}">{{ $u->nomor_unit }} ({{ $u->type->name ?? '-' }} • {{ $u->model->name ?? '-' }})</option>
              @endforeach
            </select>
            <small class="text-muted">Hanya unit aktif yang belum terdaftar sebagai Fuel Truck yang ditampilkan.</small>
          </div>
          <div class="row g-2 mb-3">
            <div class="col-6">
              <label class="form-label required">Kapasitas Tangki (L)</label>
              <input type="number" step="0.01" class="form-control" name="capacity" placeholder="8000" required>
            </div>
            <div class="col-6">
              <label class="form-label">Stok Awal di Tangki (L)</label>
              <input type="number" step="0.01" class="form-control" name="current_stock" value="0">
            </div>
          </div>
          <div class="row g-2 mb-3">
            <div class="col-6">
              <label class="form-label required">Totalizer Flowmeter Awal</label>
              <input type="number" step="0.01" class="form-control font-monospace" name="initial_totalizer" placeholder="0.00" required>
            </div>
            <div class="col-6">
              <label class="form-label">No Seri Flowmeter</label>
              <input type="text" class="form-control font-monospace" name="flowmeter_serial_number" placeholder="FM-12345">
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label">Merk / Tipe Dispenser</label>
            <input type="text" class="form-control" name="dispenser_brand" placeholder="Misal: Fill-Rite / TCS 700">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn me-auto" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-warning fw-bold">Tetapkan Fuel Truck</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
