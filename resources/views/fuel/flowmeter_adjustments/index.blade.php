@extends('layouts.tabler')

@section('title', 'Berita Acara Flowmeter - FMS')

@section('content')
<div class="page-header d-print-none">
  <div class="container-xl">
    <div class="row g-2 align-items-center">
      <div class="col">
        <div class="page-pretitle text-uppercase font-monospace text-primary">Flowmeter Calibration & Maintenance</div>
        <h2 class="page-title d-flex align-items-center gap-2">
          <svg xmlns="http://www.w3.org/2000/svg" class="icon text-purple" width="28" height="28" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><path d="M9 17h6" /><path d="M9 13h6" /></svg>
          Berita Acara Pergantian / Kerusakan / Kalibrasi Flowmeter
        </h2>
      </div>
      <div class="col-auto ms-auto">
        @can('create_fuel_flowmeter_adjustments')
        <a href="{{ route('fuel.flowmeter-adjustments.create') }}" class="btn btn-primary btn-sm">
          + Buat Berita Acara Flowmeter
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
              <th>No. Berita Acara</th>
              <th>Tanggal</th>
              <th>Perangkat / Device</th>
              <th>Jenis Kejadian</th>
              <th class="text-end">Totalizer Lama (Final)</th>
              <th class="text-end">Totalizer Baru (Awal)</th>
              <th>Ditandatangani Oleh (Manager Site)</th>
              <th class="text-end">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($adjustments as $adj)
            <tr>
              <td>
                <a href="{{ route('fuel.flowmeter-adjustments.show', $adj) }}" class="fw-bold font-monospace text-primary text-decoration-none">
                  {{ $adj->adjustment_number }}
                </a>
              </td>
              <td>{{ $adj->incident_date ? $adj->incident_date->format('d/m/Y') : '-' }}</td>
              <td>
                @if($adj->device_type == 'fuel_storage')
                  <span class="badge bg-blue-lt me-1">Tangki</span>
                  <span>{{ $adj->device->name ?? 'Storage #' . $adj->device_id }}</span>
                @else
                  <span class="badge bg-yellow-lt me-1">Fuel Truck</span>
                  <span>{{ $adj->device->masterUnit->nomor_unit ?? 'FT #' . $adj->device_id }}</span>
                @endif
              </td>
              <td>
                <span class="badge bg-purple-lt fw-bold">{{ $adj->incident_type }}</span>
              </td>
              <td class="text-end font-monospace">{{ number_format($adj->old_totalizer_final, 2) }}</td>
              <td class="text-end font-monospace fw-bold text-success">{{ number_format($adj->new_totalizer_initial, 2) }}</td>
              <td>
                <div class="fw-semibold">{{ $adj->signed_by_manager_name }}</div>
                <div class="text-muted small">{{ $adj->signed_at ? $adj->signed_at->format('d/m/Y H:i') : '' }}</div>
              </td>
              <td class="text-end">
                <a href="{{ route('fuel.flowmeter-adjustments.show', $adj) }}" class="btn btn-xs btn-outline-info">Detail</a>
                <a href="{{ route('fuel.flowmeter-adjustments.pdf', $adj) }}" target="_blank" class="btn btn-xs btn-outline-primary">PDF</a>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="8" class="text-center text-muted py-4">Belum ada Berita Acara Pergantian / Kerusakan Flowmeter.</td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
      @if($adjustments->hasPages())
      <div class="card-footer d-flex align-items-center justify-content-end p-2 border-0">
        {{ $adjustments->links() }}
      </div>
      @endif
    </div>

  </div>
</div>
@endsection
