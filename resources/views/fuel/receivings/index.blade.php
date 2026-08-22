@extends('layouts.tabler')

@section('title', 'Penerimaan BBM dari Vendor (Inbound) - FMS')

@section('content')
<div class="page-header d-print-none">
  <div class="container-xl">
    <div class="row g-2 align-items-center">
      <div class="col">
        <div class="page-pretitle text-uppercase font-monospace text-primary">Inbound Management</div>
        <h2 class="page-title d-flex align-items-center gap-2">
          <svg xmlns="http://www.w3.org/2000/svg" class="icon text-primary" width="28" height="28" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 17m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M17 17m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M5 17h-2v-4m-1 -8h11v12m-4 0h6m4 0h2v-6h-8m0 -5h5l3 5" /><path d="M9 12l2 2l4 -4" /></svg>
          Penerimaan BBM dari Vendor (Inbound)
        </h2>
      </div>
      <div class="col-auto ms-auto">
        @can('create_fuel_receivings')
        <a href="{{ route('fuel.receivings.create') }}" class="btn btn-primary btn-sm">
          + Buat Penerimaan BBM
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
    @if(session('warning'))
      <div class="alert alert-warning alert-dismissible mb-3">{{ session('warning') }}</div>
    @endif
    @if(session('error'))
      <div class="alert alert-danger alert-dismissible mb-3">{{ session('error') }}</div>
    @endif

    <!-- Filter Card -->
    <div class="card mb-3 shadow-sm border">
      <div class="card-body p-3">
        <form method="GET" action="{{ route('fuel.receivings.index') }}" class="row g-2 align-items-end">
          <div class="col-6 col-md-3">
            <label class="form-label small text-muted">Site</label>
            <select name="site_id" class="form-select form-select-sm">
              <option value="">-- Semua Site --</option>
              @foreach($sites as $s)
                <option value="{{ $s->id }}" {{ $siteId == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-6 col-md-2">
            <label class="form-label small text-muted">Status</label>
            <select name="status" class="form-select form-select-sm">
              <option value="">-- Semua Status --</option>
              <option value="Submitted" {{ $status == 'Submitted' ? 'selected' : '' }}>Submitted (Pending)</option>
              <option value="Approved" {{ $status == 'Approved' ? 'selected' : '' }}>Approved</option>
              <option value="Rejected" {{ $status == 'Rejected' ? 'selected' : '' }}>Rejected</option>
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
            <a href="{{ route('fuel.receivings.index') }}" class="btn btn-outline-secondary btn-sm w-50">Reset</a>
          </div>
        </form>
      </div>
    </div>

    <!-- Table -->
    <div class="card shadow-sm border">
      <div class="table-responsive">
        <table class="table table-vcenter table-hover card-table">
          <thead>
            <tr>
              <th>No. Penerimaan</th>
              <th>Waktu / Tanggal</th>
              <th>Vendor / Supplier</th>
              <th>Truk & Driver</th>
              <th>Tangki Penerima</th>
              <th class="text-end">Vol. DO (L)</th>
              <th class="text-end">Vol. Diterima (L)</th>
              <th class="text-end">Selisih/Loss</th>
              <th class="text-center">Status</th>
              <th class="text-end">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($receivings as $rec)
            <tr>
              <td>
                <a href="{{ route('fuel.receivings.show', $rec) }}" class="fw-bold font-monospace text-primary text-decoration-none">
                  {{ $rec->receiving_number }}
                </a>
                <div class="text-muted small">DO: {{ $rec->delivery_order_number }}</div>
              </td>
              <td>
                <div>{{ $rec->date_receive ? $rec->date_receive->format('d/m/Y') : '-' }}</div>
                <div class="text-muted small">{{ $rec->date_receive ? $rec->date_receive->format('H:i') : '' }} WITA</div>
              </td>
              <td>
                <div class="fw-semibold">{{ $rec->vendor->name ?? '-' }}</div>
                <div class="text-muted small">PO: {{ $rec->po_number ?? '-' }}</div>
              </td>
              <td>
                <div class="fw-bold font-monospace">{{ $rec->truck_plat_nomor ?? '-' }}</div>
                <div class="text-muted small">Supir: {{ $rec->driver_name ?? '-' }}</div>
              </td>
              <td>
                <span class="badge bg-blue-lt font-monospace">{{ $rec->storage->code ?? '-' }}</span>
                <div class="small">{{ $rec->storage->name ?? '-' }}</div>
              </td>
              <td class="text-end font-monospace">{{ number_format($rec->do_volume_liters, 0, ',', '.') }}</td>
              <td class="text-end font-monospace fw-bold text-azure">{{ number_format($rec->received_volume_liters, 0, ',', '.') }}</td>
              <td class="text-end font-monospace {{ $rec->losses_volume_liters < 0 ? 'text-danger' : 'text-success' }}">
                {{ ($rec->losses_volume_liters > 0 ? '+' : '') . number_format($rec->losses_volume_liters, 0, ',', '.') }} L
              </td>
              <td class="text-center">
                @if($rec->status == 'Approved')
                  <span class="badge bg-success text-white fw-bold px-2 py-0.5">Approved</span>
                @elseif($rec->status == 'Rejected')
                  <span class="badge bg-danger text-white fw-bold px-2 py-0.5">Rejected</span>
                @else
                  <span class="badge bg-warning text-dark fw-bold px-2 py-0.5">Submitted</span>
                @endif
              </td>
              <td class="text-end">
                <div class="d-flex justify-content-end gap-1">
                  <a href="{{ route('fuel.receivings.show', $rec) }}" class="btn btn-xs btn-outline-info">Detail</a>
                  @if(auth()->user()->hasRole('Super Admin'))
                  <form action="{{ route('fuel.receivings.destroy', $rec) }}" method="POST"
                        data-tabler-confirm="Batalkan dan hapus transaksi penerimaan <strong>{{ $rec->receiving_number }}</strong>?<br><br>@if($rec->status === 'Approved')<span class='text-danger fw-bold'>Perhatian:</span> Stok tangki akan otomatis dikurangi kembali <strong>{{ number_format($rec->received_volume_liters, 0) }} Liter</strong> dan seluruh kartu stok dibersihkan.@endif"
                        data-tabler-confirm-title="Peringatan Rollback Transaksi Inbound"
                        data-tabler-confirm-type="danger"
                        data-tabler-confirm-btn="Ya, Batalkan & Hapus Transaksi">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-xs btn-outline-danger" title="Batalkan & Hapus Transaksi (Rollback)">
                      Batal & Hapus
                    </button>
                  </form>
                  @endif
                </div>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="10" class="text-center text-muted py-4">Tidak ada data penerimaan BBM ditemukan.</td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
      @if($receivings->hasPages())
      <div class="card-footer d-flex align-items-center justify-content-end p-2 border-0">
        {{ $receivings->links() }}
      </div>
      @endif
    </div>

  </div>
</div>
@endsection
