@extends('layouts.tabler')

@section('title', 'Hour Meter - CMMS Aisfar')

@section('content')
<div class="page-header d-print-none">
  <div class="row align-items-center">
    <div class="col">
      <h2 class="page-title">Hour Meter (HM)</h2>
      <div class="text-secondary mt-1">Pembaruan Hour Meter unit harian.</div>
    </div>
    <div class="col-auto ms-auto d-print-none">
      @can('create_hour_meters')
      <a href="#" class="btn btn-success d-none d-sm-inline-block" data-bs-toggle="modal" data-bs-target="#modal-import">
        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><path d="M12 11v6" /><path d="M9.5 13.5l2.5 -2.5l2.5 2.5" /></svg>
        Import Excel
      </a>
      <button type="button" class="btn btn-primary d-none d-sm-inline-block shadow-sm" data-bs-toggle="modal" data-bs-target="#modal-tambah-hm">
        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="12" y1="5" x2="12" y2="19" /><line x1="5" y1="12" x2="19" y2="12" /></svg>
        Tambah HM
      </button>
      @endcan
    </div>
  </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible" role="alert">
  <div class="d-flex">
    <div>
      <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10" /></svg>
    </div>
    <div>
      {{ session('success') }}
    </div>
  </div>
  <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
</div>
@endif

<div class="card mt-3">
  <div class="card-body">
    <form method="GET" action="{{ route('hour-meters.index') }}" class="row g-2 align-items-end">
      <div class="col-md-3">
        <label class="form-label small fw-bold">Dari Tanggal</label>
        <input type="date" name="start_date" class="form-control form-control-sm" value="{{ request('start_date') }}">
      </div>
      <div class="col-md-3">
        <label class="form-label small fw-bold">Sampai Tanggal</label>
        <input type="date" name="end_date" class="form-control form-control-sm" value="{{ request('end_date') }}">
      </div>
      <div class="col-md-3">
        <label class="form-label small fw-bold">Unit</label>
        <select name="master_unit_id" class="form-select form-select-sm select2-filter">
          <option value="">Semua Unit</option>
          @foreach($masterUnits as $unit)
            <option value="{{ $unit->id }}" {{ request('master_unit_id') == $unit->id ? 'selected' : '' }}>{{ $unit->nomor_unit }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-3">
        <div class="d-flex gap-2">
          <button type="submit" class="btn btn-primary btn-sm flex-fill">
            Filter
          </button>
          @if(request()->hasAny(['start_date', 'end_date', 'master_unit_id']))
            <a href="{{ route('hour-meters.index') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
          @endif
        </div>
      </div>
    </form>
  </div>
</div>

<div class="card mt-3">
  <div class="table-responsive">
    <table class="table card-table table-vcenter text-nowrap">
      <thead>
        @php
            $currentSort = request('sort_by', 'date');
            $currentOrder = request('sort_order', 'desc');
            $nextOrder = $currentOrder === 'asc' ? 'desc' : 'asc';
            
            function sortIcon($col, $currentSort, $currentOrder) {
                if ($col !== $currentSort) return '';
                return $currentOrder === 'asc' ? '↑' : '↓';
            }
        @endphp
        <tr>
          <th><a href="{{ request()->fullUrlWithQuery(['sort_by' => 'date', 'sort_order' => $currentSort == 'date' ? $nextOrder : 'desc']) }}" class="text-reset">Date {{ sortIcon('date', $currentSort, $currentOrder) }}</a></th>
          <th><a href="{{ request()->fullUrlWithQuery(['sort_by' => 'unit', 'sort_order' => $currentSort == 'unit' ? $nextOrder : 'asc']) }}" class="text-reset">Unit {{ sortIcon('unit', $currentSort, $currentOrder) }}</a></th>
          <th>Model</th>
          @if(is_null(auth()->user()->site_id))
          <th>Site</th>
          @endif
          <th><a href="{{ request()->fullUrlWithQuery(['sort_by' => 'hm', 'sort_order' => $currentSort == 'hm' ? $nextOrder : 'desc']) }}" class="text-reset">HM {{ sortIcon('hm', $currentSort, $currentOrder) }}</a></th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($hourMeters as $hm)
        <tr>
          <td>{{ \Carbon\Carbon::parse($hm->date)->format('d/m/Y') }}</td>
          <td class="fw-bold">{{ $hm->masterUnit->nomor_unit ?? '-' }}</td>
          <td>{{ $hm->masterUnit->model->name ?? '-' }}</td>
          @if(is_null(auth()->user()->site_id))
          <td>{{ $hm->site->name ?? '-' }}</td>
          @endif
          <td>{{ number_format($hm->hm, 1, ',', '.') }}</td>
          <td>
            @can('edit_hour_meters')
            <a href="{{ route('hour-meters.edit', $hm) }}" class="btn btn-sm btn-primary">Edit</a>
            @endcan
            @can('delete_hour_meters')
            <form action="{{ route('hour-meters.destroy', $hm) }}" method="post" class="d-inline" onsubmit="return confirm('Hapus data ini?');">
              @csrf @method('DELETE')
              <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
            </form>
            @endcan
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="{{ is_null(auth()->user()->site_id) ? 6 : 5 }}" class="text-center">Belum ada data hour meter.</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($hourMeters->hasPages())
  <div class="card-footer">{{ $hourMeters->links('pagination::bootstrap-5') }}</div>
  @endif
</div>

{{-- Modal Import --}}
<div class="modal modal-blur fade" id="modal-import" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
      <form action="{{ route('hour-meters.import') }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title">Import Hour Meter Masal</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="alert alert-info" role="alert">
            <h4 class="alert-title">Tutorial Import Excel</h4>
            <div class="text-secondary">
              <ol class="mb-0">
                <li>Klik tombol <strong>Download Template</strong> di bawah ini untuk mendapatkan format yang benar.</li>
                <li>Buka file template menggunakan Microsoft Excel atau aplikasi spreadsheet lainnya.</li>
                <li>Isi data pada kolom yang tersedia:
                  <ul>
                    <li><strong>Date</strong>: Tanggal pencatatan Hour Meter (contoh: 2026-08-05).</li>
                    <li><strong>Unit</strong>: Nomor Unit (contoh: EX-01). Sistem akan otomatis mencari relasi Model & Site berdasarkan Nomor Unit ini.</li>
                    <li><strong>HM</strong>: Nilai Hour Meter (contoh: 12500.5).</li>
                  </ul>
                </li>
                <li>Simpan file hasil editan Anda, lalu unggah (upload) pada form di bawah ini.</li>
                <li>Klik <strong>Proses Import</strong>.</li>
              </ol>
            </div>
          </div>
          
          <div class="mb-3">
            <a href="{{ route('hour-meters.download-template') }}" class="btn btn-outline-primary">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2" /><path d="M7 11l5 5l5 -5" /><path d="M12 4l0 12" /></svg>
              Download Template Excel
            </a>
          </div>

          <div class="mb-3">
            <label class="form-label required">File Excel (.xlsx, .csv)</label>
            <input type="file" class="form-control" name="file" accept=".xlsx,.xls,.csv" required>
            <div class="form-hint">Pastikan format kolom sesuai dengan template yang di-download.</div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn me-auto" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Proses Import</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Tambah Hour Meter -->
<div class="modal modal-blur fade" id="modal-tambah-hm" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
      <form action="{{ route('hour-meters.store') }}" method="POST">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title fw-bold">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon text-primary me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="9" /><polyline points="12 7 12 12 15 15" /></svg>
            Tambah Hour Meter (HM) Baru
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label required">Tanggal</label>
              <input type="date" class="form-control" name="date" value="{{ date('Y-m-d') }}" required>
            </div>
            <div class="col-md-6">
              <label class="form-label required">Pilih Unit</label>
              <select name="master_unit_id" id="modal_master_unit_id" class="form-select" required>
                <option value="">-- Pilih Unit --</option>
                @foreach($masterUnits as $unit)
                  <option value="{{ $unit->id }}" 
                          data-model="{{ $unit->model ? $unit->model->name : '-' }}" 
                          data-site="{{ $unit->siteRelation->name ?? $unit->site ?? '-' }}">
                    {{ $unit->nomor_unit }}
                  </option>
                @endforeach
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label">Model (Otomatis)</label>
              <input type="text" class="form-control bg-light" id="modal_model_display" readonly disabled value="-">
            </div>
            @if(is_null(auth()->user()->site_id))
            <div class="col-md-6">
              <label class="form-label">Site (Otomatis)</label>
              <input type="text" class="form-control bg-light" id="modal_site_display" readonly disabled value="-">
            </div>
            @endif
            <div class="col-md-6">
              <label class="form-label required">Hour Meter (HM)</label>
              <input type="number" class="form-control fw-bold fs-3 text-primary" name="hm" step="0.1" min="0" placeholder="0.0" required>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn me-auto" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary fw-bold shadow-sm">Simpan Hour Meter</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof $ !== 'undefined' && $.fn.select2) {
            $('.select2-filter').select2({
                theme: 'bootstrap-5',
                width: '100%'
            });
        }

        const modalUnitSelect = document.getElementById('modal_master_unit_id');
        const modalModelDisplay = document.getElementById('modal_model_display');
        const modalSiteDisplay = document.getElementById('modal_site_display');

        if (modalUnitSelect) {
            modalUnitSelect.addEventListener('change', function() {
                const opt = this.options[this.selectedIndex];
                if (opt && opt.value) {
                    if (modalModelDisplay) modalModelDisplay.value = opt.getAttribute('data-model') || '-';
                    if (modalSiteDisplay) modalSiteDisplay.value = opt.getAttribute('data-site') || '-';
                } else {
                    if (modalModelDisplay) modalModelDisplay.value = '-';
                    if (modalSiteDisplay) modalSiteDisplay.value = '-';
                }
            });
        }
    });
</script>
@endpush
