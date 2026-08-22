@extends('layouts.tabler')

@section('title', 'Log Pengisian Fuel Truck - FMS')

@section('content')
<div class="page-header d-print-none">
  <div class="container-xl">
    <div class="row g-2 align-items-center">
      <div class="col">
        <div class="page-pretitle text-uppercase font-monospace text-primary">Mobile Refill</div>
        <h2 class="page-title d-flex align-items-center gap-2">
          <svg xmlns="http://www.w3.org/2000/svg" class="icon text-warning" width="28" height="28" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 17m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M17 17m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M5 17h-2v-11a1 1 0 0 1 1 -1h9v12m-4 0h6m4 0h2v-6h-8m0 -5h5l3 5" /></svg>
          Log Pengisian BBM ke Fuel Truck
        </h2>
      </div>
      <div class="col-auto ms-auto">
        @can('create_fuel_truck_fillings')
        <a href="{{ route('fuel.truck-fillings.create') }}" class="btn btn-warning btn-sm fw-bold">
          + Isi Ulang Fuel Truck
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

    <div class="card shadow-sm border">
      <div class="table-responsive">
        <table class="table table-vcenter table-hover card-table">
          <thead>
            <tr>
              <th>No. Pengisian</th>
              <th>Waktu & Shift</th>
              <th>Unit Fuel Truck</th>
              <th>Tangki Sumber</th>
              <th class="text-end">Volume Diisi (L)</th>
              <th class="text-end">Stok FT Sebelum &rarr; Sesudah</th>
              <th>Driver / Operator FT</th>
              <th>Petugas Input</th>
              @if(auth()->user()->hasRole('Super Admin'))
              <th class="text-end">Aksi</th>
              @endif
            </tr>
          </thead>
          <tbody>
            @forelse($fillings as $fl)
            <tr>
              <td class="fw-bold font-monospace text-primary">{{ $fl->refill_number }}</td>
              <td>
                <div>{{ $fl->fill_date ? $fl->fill_date->format('d/m/Y H:i') : '-' }}</div>
                <span class="badge bg-blue-lt small">{{ $fl->shift }}</span>
              </td>
              <td>
                <span class="badge bg-yellow-lt font-monospace me-1">FT</span>
                <span class="fw-bold text-body">{{ $fl->fuelTruck->masterUnit->nomor_unit ?? '-' }}</span>
              </td>
              <td>
                <span class="badge bg-secondary-lt font-monospace">{{ $fl->storage->code ?? '-' }}</span>
                <span class="small">{{ $fl->storage->name ?? '-' }}</span>
              </td>
              <td class="text-end font-monospace fw-bold fs-4 text-warning">
                {{ number_format($fl->volume_liters, 0, ',', '.') }} L
              </td>
              <td class="text-end font-monospace small">
                {{ number_format($fl->truck_stock_before, 0) }} &rarr; <strong class="text-success">{{ number_format($fl->truck_stock_after, 0) }} L</strong>
              </td>
              <td>{{ $fl->driver_fuel_truck ?? '-' }}</td>
              <td>{{ $fl->creator->nama_lengkap ?? $fl->creator->name ?? '-' }}</td>
              @if(auth()->user()->hasRole('Super Admin'))
              <td class="text-end">
                <form action="{{ route('fuel.truck-fillings.destroy', $fl) }}" method="POST"
                      data-tabler-confirm="Batalkan dan hapus transaksi pengisian Fuel Truck <strong>{{ $fl->refill_number }}</strong>?<br><br>Stok tangki sumber <strong>{{ $fl->storage->name }}</strong> akan dikembalikan <strong class='text-success'>+{{ number_format($fl->volume_liters, 0) }} L</strong> dan stok unit Fuel Truck <strong>{{ $fl->fuelTruck->masterUnit->nomor_unit ?? '' }}</strong> akan dipotong kembali."
                      data-tabler-confirm-title="Peringatan Rollback Pengisian Fuel Truck"
                      data-tabler-confirm-type="danger"
                      data-tabler-confirm-btn="Ya, Batalkan & Hapus Transaksi">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-xs btn-outline-danger" title="Batalkan & Hapus Pengisian">
                    Batal & Hapus
                  </button>
                </form>
              </td>
              @endif
            </tr>
            @empty
            <tr>
              <td colspan="9" class="text-center text-muted py-4">Belum ada riwayat pengisian Fuel Truck.</td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
      @if($fillings->hasPages())
      <div class="card-footer d-flex align-items-center justify-content-end p-2 border-0">
        {{ $fillings->links() }}
      </div>
      @endif
    </div>

  </div>
</div>
@endsection
