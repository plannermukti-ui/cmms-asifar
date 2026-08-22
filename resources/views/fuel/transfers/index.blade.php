@extends('layouts.tabler')

@section('title', 'Mutasi Antar Tangki Timbun (Transfer) - FMS')

@section('content')
<div class="page-header d-print-none">
  <div class="container-xl">
    <div class="row g-2 align-items-center">
      <div class="col">
        <div class="page-pretitle text-uppercase font-monospace text-primary">Storage Transfer</div>
        <h2 class="page-title d-flex align-items-center gap-2">
          <svg xmlns="http://www.w3.org/2000/svg" class="icon text-primary" width="28" height="28" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 10h14l-4 -4" /><path d="M17 14h-14l4 4" /></svg>
          Mutasi BBM Antar Tangki Timbun / Station
        </h2>
      </div>
      <div class="col-auto ms-auto">
        @can('create_fuel_transfers')
        <a href="{{ route('fuel.transfers.create') }}" class="btn btn-primary btn-sm">
          + Buat Mutasi Baru
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
              <th>No. Mutasi</th>
              <th>Waktu / Tanggal</th>
              <th>Metode</th>
              <th>Tangki Asal (Sumber)</th>
              <th>Tangki Tujuan</th>
              <th class="text-end">Volume (Liter)</th>
              <th>Operator / Petugas</th>
              <th>Status</th>
              <th>Keterangan</th>
              @if(auth()->user()->hasRole('Super Admin'))
              <th class="text-end">Aksi</th>
              @endif
            </tr>
          </thead>
          <tbody>
            @forelse($transfers as $tr)
            <tr>
              <td class="fw-bold font-monospace text-primary">{{ $tr->transfer_number }}</td>
              <td>{{ $tr->transfer_date ? $tr->transfer_date->format('d/m/Y H:i') : '-' }}</td>
              <td>
                @if($tr->transfer_method == 'Via Fuel Truck')
                  <span class="badge bg-warning-lt fw-bold">🚚 Fuel Truck ({{ $tr->fuelTruck->masterUnit->nomor_unit ?? '-' }})</span>
                @else
                  <span class="badge bg-blue-lt">Direct Pompa</span>
                @endif
              </td>
              <td>
                <span class="badge bg-danger-lt font-monospace me-1">{{ $tr->sourceStorage->code ?? '-' }}</span>
                <span class="text-body fw-semibold">{{ $tr->sourceStorage->name ?? '-' }}</span>
              </td>
              <td>
                <span class="badge bg-success-lt font-monospace me-1">{{ $tr->destinationStorage->code ?? '-' }}</span>
                <span class="text-body fw-semibold">{{ $tr->destinationStorage->name ?? '-' }}</span>
              </td>
              <td class="text-end font-monospace fw-bold text-azure fs-4">
                {{ number_format($tr->volume_liters, 0, ',', '.') }} L
              </td>
              <td>{{ $tr->operator_name ?? $tr->creator->nama_lengkap ?? '-' }}</td>
              <td><span class="badge bg-success text-white fw-bold px-2 py-0.5">{{ $tr->status }}</span></td>
              <td class="small text-secondary">{{ $tr->notes ?? '-' }}</td>
              @if(auth()->user()->hasRole('Super Admin'))
              <td class="text-end">
                <form action="{{ route('fuel.transfers.destroy', $tr) }}" method="POST"
                      data-tabler-confirm="Batalkan dan hapus transaksi mutasi <strong>{{ $tr->transfer_number }}</strong>?<br><br>Stok tangki asal <strong>{{ $tr->sourceStorage->name }}</strong> akan dikembalikan <strong class='text-success'>+{{ number_format($tr->volume_liters, 0) }} L</strong> dan stok tangki tujuan <strong>{{ $tr->destinationStorage->name }}</strong> akan dipotong kembali."
                      data-tabler-confirm-title="Peringatan Rollback Mutasi Tangki"
                      data-tabler-confirm-type="danger"
                      data-tabler-confirm-btn="Ya, Batalkan & Hapus Mutasi">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-xs btn-outline-danger" title="Batalkan & Hapus Mutasi">
                    Batal & Hapus
                  </button>
                </form>
              </td>
              @endif
            </tr>
            @empty
            <tr>
              <td colspan="10" class="text-center text-muted py-4">Belum ada data mutasi antar tangki.</td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
      @if($transfers->hasPages())
      <div class="card-footer d-flex align-items-center justify-content-end p-2 border-0">
        {{ $transfers->links() }}
      </div>
      @endif
    </div>

  </div>
</div>
@endsection
