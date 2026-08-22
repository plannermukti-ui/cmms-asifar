@extends('layouts.tabler')

@section('title', 'Berita Acara Flowmeter: ' . $adjustment->adjustment_number)

@section('content')
<div class="page-header d-print-none">
  <div class="container-xl">
    <div class="row g-2 align-items-center">
      <div class="col">
        <div class="page-pretitle text-uppercase font-monospace text-primary">Berita Acara Detail</div>
        <h2 class="page-title">{{ $adjustment->adjustment_number }}</h2>
      </div>
      <div class="col-auto ms-auto d-flex gap-2">
        <a href="{{ route('fuel.flowmeter-adjustments.pdf', $adjustment) }}" target="_blank" class="btn btn-outline-primary btn-sm d-flex align-items-center gap-1">
          <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><path d="M9 17h6" /><path d="M9 13h6" /></svg>
          Cetak Dokumen B.A (PDF)
        </a>
        <a href="{{ route('fuel.flowmeter-adjustments.index') }}" class="btn btn-outline-secondary btn-sm">
          &laquo; Kembali
        </a>
      </div>
    </div>
  </div>
</div>

<div class="page-body">
  <div class="container-xl">
    
    @if(session('success'))
      <div class="alert alert-success alert-dismissible mb-3">{{ session('success') }}</div>
    @endif

    <div class="row g-3">
      <div class="col-lg-6">
        <div class="card shadow-sm border h-100">
          <div class="card-header border-0 pb-1">
            <h3 class="card-title fw-bold text-primary">Informasi Perangkat & Kejadian</h3>
          </div>
          <div class="list-group list-group-flush">
            <div class="list-group-item d-flex justify-content-between">
              <span class="text-muted">Nomor Dokumen</span>
              <span class="fw-bold font-monospace">{{ $adjustment->adjustment_number }}</span>
            </div>
            <div class="list-group-item d-flex justify-content-between">
              <span class="text-muted">Tanggal Kejadian</span>
              <span class="fw-semibold">{{ $adjustment->incident_date ? $adjustment->incident_date->format('d F Y') : '-' }}</span>
            </div>
            <div class="list-group-item d-flex justify-content-between">
              <span class="text-muted">Jenis Kejadian</span>
              <span class="badge bg-purple-lt fs-5 fw-bold">{{ $adjustment->incident_type }}</span>
            </div>
            <div class="list-group-item d-flex justify-content-between">
              <span class="text-muted">Perangkat Terkait</span>
              <span class="fw-bold text-body">
                @if($adjustment->device_type == 'fuel_storage')
                  Tangki: {{ $device->code ?? '' }} - {{ $device->name ?? '' }}
                @else
                  Fuel Truck: {{ $device->masterUnit->nomor_unit ?? '-' }}
                @endif
              </span>
            </div>
            <div class="list-group-item d-flex justify-content-between">
              <span class="text-muted">Ditandatangani Oleh (Manager Site)</span>
              <span class="fw-bold text-success">{{ $adjustment->signed_by_manager_name }}</span>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-6">
        <div class="card shadow-sm border h-100">
          <div class="card-header border-0 pb-1">
            <h3 class="card-title fw-bold text-azure">Perubahan Angka Totalizer Flowmeter</h3>
          </div>
          <div class="card-body">
            <div class="row g-2 text-center mb-3">
              <div class="col-6">
                <div class="p-3 bg-danger-lt rounded border border-danger">
                  <div class="text-muted small">Totalizer Lama (Final)</div>
                  <div class="fs-2 fw-bold text-danger font-monospace">{{ number_format($adjustment->old_totalizer_final, 2) }}</div>
                  <div class="small font-monospace text-muted mt-1">{{ $adjustment->old_flowmeter_serial ? 'SN: ' . $adjustment->old_flowmeter_serial : '' }}</div>
                </div>
              </div>
              <div class="col-6">
                <div class="p-3 bg-success-lt rounded border border-success">
                  <div class="text-muted small">Totalizer Baru (Awal)</div>
                  <div class="fs-2 fw-bold text-success font-monospace">{{ number_format($adjustment->new_totalizer_initial, 2) }}</div>
                  <div class="small font-monospace text-muted mt-1">{{ $adjustment->new_flowmeter_serial ? 'SN: ' . $adjustment->new_flowmeter_serial : '' }}</div>
                </div>
              </div>
            </div>

            <div class="p-3 bg-body-tertiary rounded border">
              <div class="fw-bold small text-muted text-uppercase mb-1">Kronologis / Alasan Teknis:</div>
              <p class="mb-0 text-body">{{ $adjustment->reason }}</p>
            </div>
          </div>
        </div>
      </div>

      @if($adjustment->document_scan)
      <div class="col-12">
        <div class="card shadow-sm border">
          <div class="card-header border-0 pb-1">
            <h3 class="card-title fw-bold text-primary">Lampiran Scan Dokumen Berita Acara Fisik</h3>
          </div>
          <div class="card-body">
            <a href="{{ Storage::url($adjustment->document_scan) }}" target="_blank" class="btn btn-outline-primary btn-sm">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><path d="M12 17v-6" /><path d="M9.5 14.5l2.5 2.5l2.5 -2.5" /></svg>
              Buka / Download File Dokumen Scan
            </a>
          </div>
        </div>
      </div>
      @endif
    </div>

  </div>
</div>
@endsection
