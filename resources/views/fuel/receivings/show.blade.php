@extends('layouts.tabler')

@section('title', 'Detail Penerimaan BBM: ' . $receiving->receiving_number)

@section('content')
<div class="page-header d-print-none">
  <div class="container-xl">
    <div class="row g-2 align-items-center">
      <div class="col">
        <div class="page-pretitle text-uppercase font-monospace text-primary">Inbound Detail</div>
        <h2 class="page-title">{{ $receiving->receiving_number }}</h2>
      </div>
      <div class="col-auto ms-auto d-flex flex-wrap gap-2">
        <a href="{{ route('fuel.receivings.pdf', $receiving) }}" target="_blank" class="btn btn-outline-primary btn-sm d-flex align-items-center gap-1">
          <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><path d="M9 17h6" /><path d="M9 13h6" /></svg>
          Cetak BAP (PDF)
        </a>
        
        @if(auth()->user()->hasRole('Super Admin'))
        <button type="button" class="btn btn-outline-danger btn-sm d-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#modal-rollback-receiving">
          <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
          Batalkan & Hapus Transaksi (Rollback)
        </button>
        @endif

        <a href="{{ route('fuel.receivings.index') }}" class="btn btn-outline-secondary btn-sm">
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
    @if(session('warning'))
      <div class="alert alert-warning alert-dismissible mb-3">{{ session('warning') }}</div>
    @endif
    @if(session('error'))
      <div class="alert alert-danger alert-dismissible mb-3">{{ session('error') }}</div>
    @endif

    <!-- Status Banner & Approval Action -->
    <div class="card mb-3 shadow-sm border">
      <div class="card-body p-3 d-flex flex-wrap justify-content-between align-items-center gap-3">
        <div class="d-flex flex-wrap align-items-center gap-3">
          <div>
            <div class="text-muted small">Status Penerimaan</div>
            @if($receiving->status == 'Approved')
              <span class="badge bg-success text-white fw-bold fs-4 px-3 py-1">APPROVED (SELESAI)</span>
            @elseif($receiving->status == 'Rejected')
              <span class="badge bg-danger text-white fw-bold fs-4 px-3 py-1">REJECTED (DITOLAK)</span>
            @else
              <span class="badge bg-warning text-dark fw-bold fs-4 px-3 py-1">SUBMITTED (MENUNGGU PERSETUJUAN)</span>
            @endif
          </div>
          <div class="border-start ps-3">
            <div class="text-muted small">Diterima Oleh (Petugas)</div>
            <div class="fw-bold text-body">{{ $receiving->receiver->nama_lengkap ?? $receiving->receiver->name ?? '-' }}</div>
            <div class="text-muted" style="font-size: 0.72rem;">{{ $receiving->created_at ? $receiving->created_at->format('d/m/Y H:i') : '' }}</div>
          </div>
          <div class="border-start ps-3">
            <div class="text-muted small">Ditujukan untuk Approver</div>
            <div class="fw-bold text-primary">{{ $receiving->intendedApprover->nama_lengkap ?? $receiving->intendedApprover->name ?? '-' }}</div>
            <div class="text-muted" style="font-size: 0.72rem;">{{ $receiving->intendedApprover->jabatan ?? $receiving->intendedApprover->role->name ?? '' }}</div>
          </div>
          @if($receiving->approved_by)
          <div class="border-start ps-3">
            <div class="text-muted small">{{ $receiving->status == 'Approved' ? 'Telah Disetujui Oleh' : 'Telah Ditolak Oleh' }}</div>
            <div class="fw-bold text-body">{{ $receiving->approver->nama_lengkap ?? $receiving->approver->name ?? '-' }}</div>
            <div class="text-muted" style="font-size: 0.72rem;">{{ $receiving->approved_at ? $receiving->approved_at->format('d/m/Y H:i') : '' }}</div>
          </div>
          @endif
        </div>

        @php
            $canApprove = $receiving->status === 'Submitted' && (
                auth()->user()->hasRole('Super Admin') || 
                auth()->id() == $receiving->intended_approver_id || 
                auth()->user()->can('approve_fuel_receivings')
            );
        @endphp

        @if($canApprove)
        <div class="d-flex gap-2">
          <form action="{{ route('fuel.receivings.approve', $receiving) }}" method="POST"
                data-tabler-confirm="Setujui penerimaan BBM <strong>{{ $receiving->receiving_number }}</strong>?<br><br>Stok tangki <strong>{{ $receiving->storage->name }}</strong> akan otomatis bertambah sebesar <strong>{{ number_format($receiving->received_volume_liters, 0) }} Liter</strong>."
                data-tabler-confirm-title="Persetujuan Penerimaan BBM (Approval)"
                data-tabler-confirm-type="success"
                data-tabler-confirm-btn="Ya, Setujui Penerimaan">
            @csrf
            <button type="submit" class="btn btn-success fw-bold">
              ✓ Setujui (Approve)
            </button>
          </form>
          <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#modal-reject-receiving">
            ✕ Tolak
          </button>
        </div>
        @endif
      </div>
    </div>

    <!-- Data Details Grid -->
    <div class="row g-3">
      <!-- Kolom 1: Vendor & Truk -->
      <div class="col-lg-6">
        <div class="card shadow-sm border h-100">
          <div class="card-header border-0 pb-1">
            <h3 class="card-title fw-bold text-primary">Informasi Supplier & Transportir</h3>
          </div>
          <div class="list-group list-group-flush">
            <div class="list-group-item d-flex justify-content-between">
              <span class="text-muted">Vendor / Supplier</span>
              <span class="fw-bold fs-4 text-body">{{ $receiving->vendor->name ?? '-' }}</span>
            </div>
            <div class="list-group-item d-flex justify-content-between">
              <span class="text-muted">No. Surat Jalan / DO</span>
              <span class="fw-bold font-monospace">{{ $receiving->delivery_order_number }}</span>
            </div>
            <div class="list-group-item d-flex justify-content-between">
              <span class="text-muted">No. Purchase Order (PO)</span>
              <span class="font-monospace">{{ $receiving->po_number ?? '-' }}</span>
            </div>
            <div class="list-group-item d-flex justify-content-between">
              <span class="text-muted">Waktu Terima Fisik</span>
              <span>{{ $receiving->date_receive ? $receiving->date_receive->format('d/m/Y H:i') : '-' }} WITA</span>
            </div>
            <div class="list-group-item d-flex justify-content-between">
              <span class="text-muted">No. Plat Truk Tangki</span>
              <span class="fw-bold font-monospace text-primary">{{ $receiving->truck_plat_nomor ?? '-' }}</span>
            </div>
            <div class="list-group-item d-flex justify-content-between">
              <span class="text-muted">Nama Supir / Driver</span>
              <span class="fw-semibold">{{ $receiving->driver_name ?? '-' }}</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Kolom 2: Tangki, Sonding & Volume -->
      <div class="col-lg-6">
        <div class="card shadow-sm border h-100">
          <div class="card-header border-0 pb-1">
            <h3 class="card-title fw-bold text-azure">Pengukuran Sonding & Volume Terima</h3>
          </div>
          <div class="list-group list-group-flush">
            <div class="list-group-item d-flex justify-content-between">
              <span class="text-muted">Tangki Timbun Penerima</span>
              <span class="fw-bold text-primary">{{ $receiving->storage->code ?? '' }} - {{ $receiving->storage->name ?? '' }}</span>
            </div>
            <div class="list-group-item d-flex justify-content-between">
              <span class="text-muted">Sonding Awal & Akhir</span>
              <span>{{ $receiving->sonding_awal_cm ?? 0 }} cm &rarr; {{ $receiving->sonding_akhir_cm ?? 0 }} cm</span>
            </div>
            <div class="list-group-item d-flex justify-content-between">
              <span class="text-muted">Densitas & Suhu</span>
              <span>{{ $receiving->density ? $receiving->density . ' g/ml' : '-' }} • {{ $receiving->temperature ? $receiving->temperature . ' °C' : '-' }}</span>
            </div>
            <div class="list-group-item d-flex justify-content-between">
              <span class="text-muted">Totalizer Pompa Penerima</span>
              <span class="font-monospace">{{ number_format($receiving->totalizer_before ?? 0, 2) }} &rarr; {{ number_format($receiving->totalizer_after ?? 0, 2) }}</span>
            </div>
            <div class="list-group-item d-flex justify-content-between bg-body-tertiary">
              <span class="text-muted">Volume Surat Jalan DO</span>
              <span class="fw-bold">{{ number_format($receiving->do_volume_liters, 0, ',', '.') }} Liter</span>
            </div>
            <div class="list-group-item d-flex justify-content-between bg-primary-lt">
              <span class="fw-bold text-primary">Volume Aktual Diterima</span>
              <span class="fw-bold fs-3 text-primary">{{ number_format($receiving->received_volume_liters, 0, ',', '.') }} Liter</span>
            </div>
            <div class="list-group-item d-flex justify-content-between">
              <span class="text-muted">Selisih / Losses (Aktual - DO)</span>
              <span class="fw-bold {{ $receiving->losses_volume_liters < 0 ? 'text-danger' : 'text-success' }}">
                {{ ($receiving->losses_volume_liters > 0 ? '+' : '') . number_format($receiving->losses_volume_liters, 0, ',', '.') }} Liter
              </span>
            </div>
          </div>
        </div>
      </div>

      <!-- Lampiran Dokumen Scan dengan Pop-up Modal Preview -->
      @if($receiving->document_scan)
      @php
          $fileUrl = \Illuminate\Support\Facades\Storage::url($receiving->document_scan);
          $ext = strtolower(pathinfo($receiving->document_scan, PATHINFO_EXTENSION));
          $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif']);
      @endphp
      <div class="col-12">
        <div class="card shadow-sm border">
          <div class="card-header border-0 pb-1 d-flex justify-content-between align-items-center">
            <h3 class="card-title fw-bold text-primary">Lampiran Dokumen Surat Jalan / BAP Fisik</h3>
            <button type="button" class="btn btn-primary btn-sm d-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#modal-preview-document">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" /></svg>
              Buka Pop-up Preview Dokumen
            </button>
          </div>
          <div class="card-body">
            <div class="d-flex align-items-center gap-3">
              <span class="badge bg-blue-lt fs-5 font-monospace text-uppercase">{{ $ext }}</span>
              <span class="text-secondary small">{{ basename($receiving->document_scan) }}</span>
              <a href="{{ $fileUrl }}" target="_blank" class="btn btn-outline-secondary btn-xs ms-auto">
                Buka di Tab Baru &raquo;
              </a>
            </div>
          </div>
        </div>
      </div>

      <!-- Pop-up Modal Preview Document -->
      <div class="modal modal-blur fade" id="modal-preview-document" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title d-flex align-items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon text-primary" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /></svg>
                Preview Lampiran: {{ basename($receiving->document_scan) }}
              </h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-2 text-center" style="min-height: 500px; background-color: #0f172a;">
              @if($isImage)
                <img src="{{ $fileUrl }}" alt="Lampiran DO" class="img-fluid rounded shadow" style="max-height: 75vh; object-fit: contain;">
              @else
                <iframe src="{{ $fileUrl }}" width="100%" height="650px" style="border: none; border-radius: 4px;"></iframe>
              @endif
            </div>
            <div class="modal-footer">
              <a href="{{ $fileUrl }}" download class="btn btn-primary me-auto">
                Download File
              </a>
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
          </div>
        </div>
      </div>
      @endif

      @if($receiving->notes)
      <div class="col-12">
        <div class="card shadow-sm border">
          <div class="card-body p-3 small text-secondary">
            <strong>Catatan:</strong> {{ $receiving->notes }}
          </div>
        </div>
      </div>
      @endif
    </div>

  </div>
</div>

<!-- Modal Reject -->
<div class="modal modal-blur fade" id="modal-reject-receiving" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <form action="{{ route('fuel.receivings.reject', $receiving) }}" method="POST">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title text-danger">Tolak Penerimaan BBM</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label required">Alasan Penolakan</label>
            <textarea class="form-control" name="rejected_reason" rows="3" placeholder="Misal: Segel tangki rusak / selisih volume terlalu besar" required></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn me-auto" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-danger">Konfirmasi Tolak</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Rollback & Hapus (Super Admin Only) -->
@if(auth()->user()->hasRole('Super Admin'))
<div class="modal modal-blur fade" id="modal-rollback-receiving" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <form action="{{ route('fuel.receivings.destroy', $receiving) }}" method="POST">
        @csrf
        @method('DELETE')
        <div class="modal-header bg-danger text-white">
          <h5 class="modal-title d-flex align-items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 9v4" /><path d="M10.363 3.591l-8.106 13.534a1.914 1.914 0 0 0 1.636 2.871h16.214a1.914 1.914 0 0 0 1.636 -2.871l-8.106 -13.534a1.914 1.914 0 0 0 -3.274 0z" /><path d="M12 16h.01" /></svg>
            Batalkan & Hapus Transaksi (Super Admin)
          </h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="text-danger fw-bold fs-3 mb-2">Peringatan Penghapusan Transaksi Inbound!</div>
          <p class="text-body mb-3">
            Anda akan membatalkan dan menghapus dokumen penerimaan BBM <strong>{{ $receiving->receiving_number }}</strong>.
          </p>
          
          <div class="alert alert-warning border shadow-none small mb-0">
            <strong>Dampak Rollback Otomatis:</strong>
            <ul class="mb-0 ps-3 mt-1">
              @if($receiving->status === 'Approved')
                <li>Stok pada tangki <strong>{{ $receiving->storage->name ?? 'Tangki' }}</strong> akan otomatis <strong>dikurangi kembali sebesar {{ number_format($receiving->received_volume_liters, 0) }} Liter</strong>.</li>
                <li>Seluruh riwayat mutasi kartu stok terkait nomor transaksi ini akan otomatis <strong>dihapus</strong>.</li>
              @endif
              <li>File lampiran dokumen dan record transaksi penerimaan ini akan dihapus permanen.</li>
            </ul>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn me-auto" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-danger fw-bold">Ya, Batalkan & Hapus Seluruh Transaksi</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endif

@endsection
