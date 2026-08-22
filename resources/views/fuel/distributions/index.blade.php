@extends('layouts.tabler')

@section('title', 'Distribusi BBM Unit Operasional (Shift Log) - FMS')

@section('content')
<div class="page-header d-print-none">
  <div class="container-xl">
    <div class="row g-2 align-items-center">
      <div class="col">
        <div class="page-pretitle text-uppercase font-monospace text-primary">Dispensing & Distribution</div>
        <h2 class="page-title d-flex align-items-center gap-2">
          <svg xmlns="http://www.w3.org/2000/svg" class="icon text-success" width="28" height="28" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 21v-14a3 3 0 0 1 3 -3h6a3 3 0 0 1 3 3v14" /><path d="M9 11l6 0" /><path d="M6 21l12 0" /><path d="M16 13l2.5 2.5a2 2 0 0 1 0 2.828l-1.328 1.328a2 2 0 0 1 -2.828 0l-2.344 -2.344" /><path d="M18 10v-4" /></svg>
          Sesi Distribusi BBM Fuel Truck ke Unit Operasional
        </h2>
      </div>
      <div class="col-auto ms-auto">
        @can('create_fuel_distributions')
        <a href="{{ route('fuel.distributions.create') }}" class="btn btn-success btn-sm">
          + Buka Sesi Distribusi Shift Baru
        </a>
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

    <!-- Filter Card -->
    <div class="card mb-3 shadow-sm border">
      <div class="card-body p-3">
        <form method="GET" action="{{ route('fuel.distributions.index') }}" class="row g-2 align-items-end">
          <div class="col-6 col-md-3">
            <label class="form-label small text-muted">Fuel Truck</label>
            <select name="fuel_truck_id" class="form-select form-select-sm">
              <option value="">-- Semua Fuel Truck --</option>
              @foreach($fuelTrucks as $ft)
                <option value="{{ $ft->id }}" {{ $truckId == $ft->id ? 'selected' : '' }}>{{ $ft->masterUnit->nomor_unit ?? '-' }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-6 col-md-2">
            <label class="form-label small text-muted">Status</label>
            <select name="status" class="form-select form-select-sm">
              <option value="">-- Semua Status --</option>
              <option value="Open" {{ $status == 'Open' ? 'selected' : '' }}>Open (Berjalan)</option>
              <option value="Closed" {{ $status == 'Closed' ? 'selected' : '' }}>Closed (Selesai)</option>
            </select>
          </div>
          <div class="col-6 col-md-2">
            <label class="form-label small text-muted">Dari Tanggal</label>
            <input type="date" name="date_from" value="{{ $dateFrom }}" class="form-control form-control-sm">
          </div>
          <div class="col-6 col-md-2">
            <label class="form-label small text-muted">Sampai Tanggal</label>
            <input type="date" name="date_to" value="{{ $dateTo }}" class="form-control form-control-sm">
          </div>
          <div class="col-12 col-md-3 d-flex gap-1">
            <button type="submit" class="btn btn-primary btn-sm w-50">Filter</button>
            <a href="{{ route('fuel.distributions.index') }}" class="btn btn-outline-secondary btn-sm w-50">Reset</a>
          </div>
        </form>
      </div>
    </div>

    <div class="card shadow-sm border">
      <div class="table-responsive">
        <table class="table table-vcenter table-hover card-table">
          <thead>
            <tr>
              <th>No. Sesi Shift</th>
              <th>Tanggal & Shift</th>
              <th>Unit Fuel Truck</th>
              <th>Fuelman / Supir FT</th>
              <th class="text-end">Totalizer Awal &rarr; Akhir</th>
              <th class="text-end">Delta Flowmeter (L)</th>
              <th class="text-end">Total Diisi ke Unit</th>
              <th class="text-center">Jml Unit</th>
              <th class="text-center">Status</th>
              <th class="text-end">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($shifts as $sh)
            <tr>
              <td>
                <a href="{{ route('fuel.distributions.show', $sh) }}" class="fw-bold font-monospace text-primary text-decoration-none">
                  {{ $sh->shift_doc_number }}
                </a>
              </td>
              <td>
                <div>{{ $sh->date ? $sh->date->format('d/m/Y') : '-' }}</div>
                <span class="badge bg-blue-lt small">{{ $sh->shift }}</span>
              </td>
              <td>
                <span class="badge bg-yellow-lt font-monospace me-1">FT</span>
                <span class="fw-bold text-body">{{ $sh->fuelTruck->masterUnit->nomor_unit ?? '-' }}</span>
              </td>
              <td><span class="fw-semibold">{{ $sh->fuelman_name }}</span></td>
              <td class="text-end font-monospace small">
                {{ number_format($sh->totalizer_start, 2) }} &rarr; 
                @if($sh->totalizer_end)
                  <strong>{{ number_format($sh->totalizer_end, 2) }}</strong>
                @else
                  <span class="text-muted"><em>(Belum Closed)</em></span>
                @endif
              </td>
              <td class="text-end font-monospace fw-bold text-primary">
                {{ $sh->totalizer_end ? number_format($sh->total_liters_flowmeter, 0, ',', '.') . ' L' : '-' }}
              </td>
              <td class="text-end font-monospace fw-bold text-success">
                {{ number_format($sh->total_liters_distributed, 0, ',', '.') }} L
              </td>
              <td class="text-center">
                <span class="badge bg-secondary-lt fw-bold">{{ $sh->distributions->count() }} Unit</span>
              </td>
              <td class="text-center">
                @if($sh->status == 'Closed')
                  <span class="badge bg-success text-white fw-bold px-2 py-0.5">Closed</span>
                @else
                  <span class="badge bg-warning text-dark fw-bold px-2 py-0.5">Open</span>
                @endif
              </td>
              <td class="text-end">
                <div class="d-flex justify-content-end gap-1">
                  <a href="{{ route('fuel.distributions.show', $sh) }}" class="btn btn-xs btn-outline-info">Detail / Isi Unit</a>
                  
                  @if($sh->status == 'Closed' && (auth()->user()->hasRole('Super Admin') || auth()->user()->can('manage_fuel_distributions')))
                  <form action="{{ route('fuel.distributions.reopen', $sh) }}" method="POST"
                        data-tabler-confirm="Buka kembali (Reopen) sesi shift <strong>{{ $sh->shift_doc_number }}</strong>?<br><br>Pemotongan stok unit Fuel Truck akan dibatalkan/dikembalikan sehingga data pengisian dapat direvisi kembali."
                        data-tabler-confirm-title="Buka Kembali Sesi Shift"
                        data-tabler-confirm-type="warning"
                        data-tabler-confirm-btn="Ya, Buka Kembali Shift">
                    @csrf
                    <button type="submit" class="btn btn-xs btn-outline-warning" title="Buka Kembali Shift">
                      Reopen
                    </button>
                  </form>
                  @endif

                  @if(auth()->user()->hasRole('Super Admin'))
                  <form action="{{ route('fuel.distributions.destroy', $sh) }}" method="POST"
                        data-tabler-confirm="Batalkan dan hapus seluruh sesi shift distribusi <strong>{{ $sh->shift_doc_number }}</strong>?<br><br>@if($sh->status === 'Closed')<span class='text-danger fw-bold'>Perhatian:</span> Stok unit Fuel Truck akan dikembalikan, seluruh kartu stok dibersihkan,@endif dan <strong>{{ $sh->distributions->count() }} data pengisian unit</strong> di dalamnya akan dihapus permanen."
                        data-tabler-confirm-title="Peringatan Rollback Sesi Shift Distribusi"
                        data-tabler-confirm-type="danger"
                        data-tabler-confirm-btn="Ya, Batalkan & Hapus Sesi Shift">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-xs btn-outline-danger" title="Batalkan & Hapus Shift">
                      Batal & Hapus
                    </button>
                  </form>
                  @endif
                </div>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="10" class="text-center text-muted py-4">Belum ada sesi shift distribusi fuel.</td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
      @if($shifts->hasPages())
      <div class="card-footer d-flex align-items-center justify-content-end p-2 border-0">
        {{ $shifts->links() }}
      </div>
      @endif
    </div>

  </div>
</div>
@endsection
