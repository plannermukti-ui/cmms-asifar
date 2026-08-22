@extends('layouts.tabler')

@section('title', 'Master Fuel Storage & Truk Supplier - FMS')

@section('content')
<div class="page-header d-print-none">
  <div class="container-xl">
    <div class="row g-2 align-items-center">
      <div class="col">
        <div class="page-pretitle text-uppercase font-monospace text-primary">Master Data FMS</div>
        <h2 class="page-title d-flex align-items-center gap-2">
          <svg xmlns="http://www.w3.org/2000/svg" class="icon text-azure" width="28" height="28" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 21v-14a3 3 0 0 1 3 -3h6a3 3 0 0 1 3 3v14" /><path d="M9 11l6 0" /><path d="M6 21l12 0" /></svg>
          Fuel Storage / Station & Truk Supplier
        </h2>
      </div>
      <div class="col-auto ms-auto d-flex gap-2">
        @can('create_fuel_storages')
        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal-add-storage">
          + Tambah Tangki Timbun / SPBU
        </button>
        <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal-add-supplier-truck">
          + Daftarkan Truk Supplier
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

    <!-- TABEL TANGKI TIMBUN / SPBU STATION -->
    <div class="card mb-4 shadow-sm border">
      <div class="card-header border-0 pb-1">
        <h3 class="card-title fw-bold text-primary">Daftar Tangki Timbun (Fuel Storage) & Fuel Station</h3>
      </div>
      <div class="table-responsive">
        <table class="table table-vcenter table-hover card-table">
          <thead>
            <tr>
              <th>Kode / Nama</th>
              <th>Tipe</th>
              <th>Site & Lokasi</th>
              <th class="text-end">Kapasitas (L)</th>
              <th class="text-end">Stok Aktual (L)</th>
              <th class="text-center">Level (%)</th>
              <th class="text-end">Totalizer Pompa</th>
              <th class="text-end">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($storages as $st)
            <tr>
              <td>
                <div class="fw-bold font-monospace text-primary">{{ $st->code }}</div>
                <div class="fw-semibold text-body">{{ $st->name }}</div>
              </td>
              <td><span class="badge bg-blue-lt">{{ $st->type }}</span></td>
              <td>
                <div>{{ $st->site->name ?? 'Semua Site' }}</div>
                <div class="text-muted small">{{ $st->location ?? '-' }}</div>
              </td>
              <td class="text-end font-monospace">{{ number_format($st->capacity, 0, ',', '.') }}</td>
              <td class="text-end font-monospace fw-bold text-azure">{{ number_format($st->current_stock, 0, ',', '.') }}</td>
              <td class="text-center" style="width: 120px;">
                <div class="d-flex align-items-center gap-2">
                  <div class="progress flex-grow-1" style="height: 8px;">
                    <div class="progress-bar {{ $st->fill_percentage <= 20 ? 'bg-danger' : ($st->fill_percentage <= 40 ? 'bg-warning' : 'bg-success') }}" 
                         style="width: {{ $st->fill_percentage }}%"></div>
                  </div>
                  <span class="small font-monospace">{{ $st->fill_percentage }}%</span>
                </div>
              </td>
              <td class="text-end font-monospace">{{ number_format($st->current_totalizer, 2, ',', '.') }}</td>
              <td class="text-end">
                <a href="{{ route('fuel.storages.show', $st) }}" class="btn btn-xs btn-outline-info">Detail</a>
                @can('edit_fuel_storages')
                <button type="button" class="btn btn-xs btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modal-edit-storage-{{ $st->id }}">Edit</button>
                @endcan
              </td>
            </tr>

            <!-- Modal Edit Storage -->
            <div class="modal modal-blur fade" id="modal-edit-storage-{{ $st->id }}" tabindex="-1" role="dialog" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                  <form action="{{ route('fuel.storages.update', $st) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="modal-header">
                      <h5 class="modal-title">Edit Fuel Storage: {{ $st->code }}</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                      <div class="row g-2 mb-3">
                        <div class="col-4">
                          <label class="form-label required">Kode</label>
                          <input type="text" class="form-control text-uppercase" name="code" value="{{ $st->code }}" required>
                        </div>
                        <div class="col-8">
                          <label class="form-label required">Nama Tangki/Station</label>
                          <input type="text" class="form-control" name="name" value="{{ $st->name }}" required>
                        </div>
                      </div>
                      <div class="row g-2 mb-3">
                        <div class="col-6">
                          <label class="form-label required">Tipe</label>
                          <select name="type" class="form-select" required>
                            <option value="Main Storage" {{ $st->type == 'Main Storage' ? 'selected' : '' }}>Main Storage Tank</option>
                            <option value="Fuel Station" {{ $st->type == 'Fuel Station' ? 'selected' : '' }}>Fuel Station / SPBU</option>
                            <option value="Temporary Tank" {{ $st->type == 'Temporary Tank' ? 'selected' : '' }}>Temporary Tank</option>
                          </select>
                        </div>
                        <div class="col-6">
                          <label class="form-label">Site</label>
                          <select name="site_id" class="form-select">
                            <option value="">-- Semua Site --</option>
                            @foreach($sites as $s)
                              <option value="{{ $s->id }}" {{ $st->site_id == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                            @endforeach
                          </select>
                        </div>
                      </div>
                      <div class="row g-2 mb-3">
                        <div class="col-6">
                          <label class="form-label required">Kapasitas (Liter)</label>
                          <input type="number" step="0.01" class="form-control" name="capacity" value="{{ $st->capacity }}" required>
                        </div>
                        <div class="col-6">
                          <label class="form-label">Alert Min. Stok (Liter)</label>
                          <input type="number" step="0.01" class="form-control" name="min_stock_alert" value="{{ $st->min_stock_alert }}">
                        </div>
                      </div>
                      @if(auth()->user()->hasRole('Super Admin'))
                      <div class="row g-2 mb-3 bg-red-lt p-2 rounded border border-danger-subtle">
                        <div class="col-6">
                          <label class="form-label text-danger fw-bold small">Stok Aktual (Liter) <span class="badge bg-danger text-white">Super Admin</span></label>
                          <input type="number" step="0.01" class="form-control form-control-sm font-monospace fw-bold" name="current_stock" value="{{ $st->current_stock }}">
                        </div>
                        <div class="col-6">
                          <label class="form-label text-danger fw-bold small">Totalizer Pompa <span class="badge bg-danger text-white">Super Admin</span></label>
                          <input type="number" step="0.01" class="form-control form-control-sm font-monospace fw-bold" name="current_totalizer" value="{{ $st->current_totalizer }}">
                        </div>
                      </div>
                      @endif
                      <div class="mb-3">
                        <label class="form-label">Lokasi / Area</label>
                        <input type="text" class="form-control" name="location" value="{{ $st->location }}">
                      </div>
                      <div class="mb-3">
                        <label class="form-label">Keterangan Tambahan</label>
                        <textarea class="form-control" name="remarks" rows="2">{{ $st->remarks }}</textarea>
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
              <td colspan="8" class="text-center text-muted py-4">Belum ada Tangki Timbun / Fuel Station terdaftar.</td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    <!-- TABEL TRUK TANGKI SUPPLIER VENDOR -->
    <div class="card shadow-sm border">
      <div class="card-header border-0 pb-1">
        <h3 class="card-title fw-bold text-azure">Daftar Truk Tangki Pengantar dari Supplier / Vendor</h3>
      </div>
      <div class="table-responsive">
        <table class="table table-vcenter table-hover card-table">
          <thead>
            <tr>
              <th>No. Plat Truk</th>
              <th>Vendor / Supplier</th>
              <th>Transportir</th>
              <th>Nama Supir</th>
              <th>No. Telepon</th>
              <th class="text-end">Kapasitas Tangki (L)</th>
              <th class="text-end">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($supplierTrucks as $truck)
            <tr>
              <td class="fw-bold font-monospace text-primary">{{ $truck->truck_plat_nomor }}</td>
              <td>{{ $truck->vendor->name ?? '-' }}</td>
              <td>{{ $truck->transportir_name ?? '-' }}</td>
              <td class="fw-semibold">{{ $truck->driver_name }}</td>
              <td>{{ $truck->driver_phone ?? '-' }}</td>
              <td class="text-end font-monospace">{{ number_format($truck->compartment_capacity, 0, ',', '.') }} L</td>
              <td class="text-end">
                @can('delete_fuel_storages')
                <form action="{{ route('fuel.supplier-trucks.destroy', $truck) }}" method="POST" class="d-inline"
                      data-tabler-confirm="Hapus truk supplier plat <strong>{{ $truck->truck_plat_nomor }}</strong> (Driver: {{ $truck->driver_name }}) dari database?"
                      data-tabler-confirm-title="Hapus Truk Supplier"
                      data-tabler-confirm-type="danger"
                      data-tabler-confirm-btn="Ya, Hapus">
                  @csrf @method('DELETE')
                  <button type="submit" class="btn btn-xs btn-outline-danger">Hapus</button>
                </form>
                @endcan
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="7" class="text-center text-muted py-4">Belum ada Truk Supplier terdaftar.</td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

  </div>
</div>

<!-- Modal Tambah Storage -->
<div class="modal modal-blur fade" id="modal-add-storage" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <form action="{{ route('fuel.storages.store') }}" method="POST">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title">Tambah Tangki Timbun / SPBU Station</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row g-2 mb-3">
            <div class="col-4">
              <label class="form-label required">Kode Tangki</label>
              <input type="text" class="form-control text-uppercase" name="code" placeholder="ST-01" required>
            </div>
            <div class="col-8">
              <label class="form-label required">Nama Tangki / Station</label>
              <input type="text" class="form-control" name="name" placeholder="Misal: Main Storage Tank 50KL" required>
            </div>
          </div>
          <div class="row g-2 mb-3">
            <div class="col-6">
              <label class="form-label required">Tipe</label>
              <select name="type" class="form-select" required>
                <option value="Main Storage">Main Storage Tank</option>
                <option value="Fuel Station">Fuel Station / SPBU</option>
                <option value="Temporary Tank">Temporary Tank</option>
              </select>
            </div>
            <div class="col-6">
              <label class="form-label">Site</label>
              <select name="site_id" class="form-select">
                <option value="">-- Semua Site --</option>
                @foreach($sites as $s)
                  <option value="{{ $s->id }}">{{ $s->name }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="row g-2 mb-3">
            <div class="col-6">
              <label class="form-label required">Kapasitas Maksimal (Liter)</label>
              <input type="number" step="0.01" class="form-control" name="capacity" placeholder="50000" required>
            </div>
            <div class="col-6">
              <label class="form-label">Stok Awal (Liter)</label>
              <input type="number" step="0.01" class="form-control" name="current_stock" value="0">
            </div>
          </div>
          <div class="row g-2 mb-3">
            <div class="col-6">
              <label class="form-label">Batas Alert Min. Stok (L)</label>
              <input type="number" step="0.01" class="form-control" name="min_stock_alert" value="5000">
            </div>
            <div class="col-6">
              <label class="form-label">Totalizer Pompa Awal</label>
              <input type="number" step="0.01" class="form-control" name="current_totalizer" value="0">
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label">Lokasi / Area</label>
            <input type="text" class="form-control" name="location" placeholder="Misal: Pit Barat / Workshop Area">
          </div>
          <div class="mb-3">
            <label class="form-label">Keterangan Tambahan</label>
            <textarea class="form-control" name="remarks" rows="2"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn me-auto" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan Tangki</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Tambah Truk Supplier -->
<div class="modal modal-blur fade" id="modal-add-supplier-truck" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <form action="{{ route('fuel.supplier-trucks.store') }}" method="POST">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title">Daftarkan Truk Tangki Supplier / Vendor</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label required">Vendor / Supplier BBM</label>
            <select name="vendor_id" class="form-select" required>
              <option value="">-- Pilih Vendor --</option>
              @foreach($vendors as $v)
                <option value="{{ $v->id }}">{{ $v->name }}</option>
              @endforeach
            </select>
          </div>
          <div class="row g-2 mb-3">
            <div class="col-6">
              <label class="form-label required">No. Plat Polisi Truk</label>
              <input type="text" class="form-control text-uppercase" name="truck_plat_nomor" placeholder="B 9876 XYZ" required>
            </div>
            <div class="col-6">
              <label class="form-label required">Kapasitas Tangki Truk (L)</label>
              <input type="number" step="0.01" class="form-control" name="compartment_capacity" placeholder="16000" required>
            </div>
          </div>
          <div class="row g-2 mb-3">
            <div class="col-6">
              <label class="form-label required">Nama Supir / Driver</label>
              <input type="text" class="form-control" name="driver_name" required>
            </div>
            <div class="col-6">
              <label class="form-label">No. Telepon Supir</label>
              <input type="text" class="form-control" name="driver_phone">
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label">Nama PT Transportir (Opsional)</label>
            <input type="text" class="form-control" name="transportir_name" placeholder="Misal: PT. Energi Transport Logistik">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn me-auto" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Daftarkan Truk</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
