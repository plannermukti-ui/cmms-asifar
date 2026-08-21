@extends('layouts.tabler')
@section('title', 'Detail Job Work Order - ' . $jwo->no_jwo)

@section('content')
<style>
  .wo-report-card {
    background: var(--tblr-card-bg, #ffffff);
    font-size: 0.85rem;
    border: 1px solid rgba(0, 0, 0, 0.08);
    border-radius: 8px;
    color: #1e293b;
  }
  .jwo-grid-table {
    border-collapse: collapse;
    width: 100%;
  }
  .jwo-grid-table th, .jwo-grid-table td {
    padding: 0.35rem 0.6rem !important;
    font-size: 0.8rem;
    vertical-align: middle;
    border: 1px solid #e2e8f0 !important;
  }
  .jwo-grid-table td.bg-light {
    background-color: #f8fafc !important;
    color: #475569 !important;
    font-weight: 700;
    font-size: 0.75rem;
    letter-spacing: 0.02em;
  }
  .img-jwo-photo { 
    max-height: 280px; 
    border-radius: 6px;
  }
  .jwo-info-box {
    border-radius: 6px;
    padding: 10px 12px;
    border: 1px solid #e2e8f0;
  }
  .signature-card {
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    padding: 10px;
    background: #f8fafc;
    text-align: center;
  }

  /* =========================================================
     DARK MODE — harmoni visual seimbang dan elegan
     ========================================================= */
  [data-bs-theme="dark"] .wo-report-card {
    background: #182234 !important;
    border-color: rgba(255, 255, 255, 0.08) !important;
    color: #f1f5f9 !important;
  }
  [data-bs-theme="dark"] .jwo-grid-table td {
    border-color: rgba(255, 255, 255, 0.08) !important;
    color: #f8fafc !important;
  }
  [data-bs-theme="dark"] .jwo-grid-table td.bg-light {
    background-color: #131c2c !important;
    color: #94a3b8 !important;
    border-color: rgba(255, 255, 255, 0.08) !important;
  }
  [data-bs-theme="dark"] .wo-report-card .app-address {
    color: #94a3b8 !important;
  }
  [data-bs-theme="dark"] .jwo-no-badge {
    background: #131c2c !important;
    border-color: var(--app-accent, #f59e0b) !important;
    color: var(--app-accent, #f59e0b) !important;
  }
  [data-bs-theme="dark"] .jwo-problem-box {
    background: rgba(214, 57, 57, 0.08) !important;
    border-color: rgba(214, 57, 57, 0.25) !important;
    border-left: 3px solid #d63939 !important;
  }
  [data-bs-theme="dark"] .jwo-action-box {
    background: rgba(32, 107, 196, 0.08) !important;
    border-color: rgba(32, 107, 196, 0.25) !important;
    border-left: 3px solid #206bc4 !important;
  }
  [data-bs-theme="dark"] .signature-card {
    background: #131c2c !important;
    border-color: rgba(255, 255, 255, 0.08) !important;
    color: #f1f5f9 !important;
  }
  [data-bs-theme="dark"] .img-jwo-photo {
    border-color: rgba(255, 255, 255, 0.08) !important;
  }
  [data-bs-theme="dark"] .update-status-card {
    background: #182234 !important;
    border: 1px solid rgba(32, 107, 196, 0.3) !important;
  }

  /* =========================================================
     PRINT SPECIFIC (A4 Portrait Standar)
     ========================================================= */
  @media print {
    @page {
      size: A4 portrait;
      margin: 8mm 10mm 8mm 10mm;
    }
    html, body {
      background: #fff !important;
      color: #000 !important;
      font-size: 9pt !important;
      margin: 0 !important;
      padding: 0 !important;
    }
    .page-header, .navbar, .footer, .d-print-none, #chatWidgetContainer { display: none !important; }
    .card { border: none !important; box-shadow: none !important; padding: 0 !important; margin: 0 !important; }
    .wo-report-card { background-color: #fff !important; color: #000 !important; border: none !important; }
    .jwo-grid-table td.bg-light { background-color: #f1f5f9 !important; color: #000 !important; }
    .badge { background: transparent !important; border: 0 !important; color: #000 !important; font-weight: bold !important; padding: 0 !important; font-size: inherit !important; }
    .page-wrapper { margin: 0 !important; padding: 0 !important; }
    .table-sm th, .table-sm td { padding: 0.2rem 0.4rem !important; }
    .jwo-grid-table td { padding: 0.15rem 0.35rem !important; font-size: 8pt !important; border-color: #cbd5e1 !important; }
    .img-jwo-photo { max-height: 260px !important; }
    .signature-container { min-height: 45px !important; }
    .mb-4 { margin-bottom: 0.75rem !important; }
    .mb-3 { margin-bottom: 0.5rem !important; }
    .mt-4 { margin-top: 0.5rem !important; }
    .pb-3 { padding-bottom: 0.5rem !important; }
    h4 { font-size: 0.85rem !important; margin-bottom: 0.3rem !important; }
  }
</style>

<div class="page-header d-print-none mb-3">
  <div class="row align-items-center">
    <div class="col">
      <h2 class="page-title text-primary fw-bold d-flex align-items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="icon text-primary" width="28" height="28" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M12 21h-5a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v4.5" /><path d="M16.5 17.5m-2.5 0a2.5 2.5 0 1 0 5 0a2.5 2.5 0 1 0 -5 0" /><path d="M18.5 19.5l2.5 2.5" /></svg>
        Detail JWO: {{ $jwo->no_jwo }}
      </h2>
      <div class="text-secondary mt-1">Dokumen Resmi Job Work Order untuk Pekerjaan Servis / Fabrikasi Vendor Luar.</div>
    </div>
    <div class="col-auto ms-auto d-flex gap-2">
      <!-- Dropdown Bagikan Link -->
      <div class="dropdown">
        <button type="button" class="btn btn-outline-primary dropdown-toggle fw-bold shadow-sm" data-bs-toggle="dropdown">
          <svg class="icon icon-tabler icon-tabler-share me-1" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6 12m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" /><path d="M18 6m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" /><path d="M18 18m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" /><path d="M8.7 10.7l6.6 -3.4" /><path d="M8.7 13.3l6.6 3.4" /></svg>
          Bagikan Link
        </button>
        <div class="dropdown-menu dropdown-menu-end shadow-md">
          <a class="dropdown-item d-flex align-items-center gap-2" href="#" onclick="copyToClipboard('[JWO: {{ $jwo->no_jwo }}]({{ url()->current() }})', 'Link Format Chat berhasil disalin!'); return false;">
            <svg class="icon icon-tabler icon-tabler-message-share text-purple" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M20 11v-3a3 3 0 0 0 -3 -3h-10a3 3 0 0 0 -3 3v8a3 3 0 0 0 3 3h5" /><path d="M16 22l5 -5" /><path d="M21 21.5v-4.5h-4.5" /></svg>
            <span>Salin Format Chat (`[JWO](URL)`)</span>
          </a>
          <a class="dropdown-item d-flex align-items-center gap-2" href="#" onclick="copyToClipboard('{{ url()->current() }}', 'URL Web berhasil disalin!'); return false;">
            <svg class="icon icon-tabler icon-tabler-link text-secondary" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 15l6 -6" /><path d="M11 6l.463 -.463a5 5 0 0 1 7.071 7.071l-.534 .534" /><path d="M13 18l-.397 .534a5 5 0 0 1 -7.071 -7.071l.534 -.534" /></svg>
            <span>Salin URL Web Langsung</span>
          </a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('chat.index') }}" target="_blank" onclick="copyToClipboard('[JWO: {{ $jwo->no_jwo }}]({{ url()->current() }})', 'Link disalin! Membuka Live Chat...');">
            <svg class="icon icon-tabler icon-tabler-brand-hipchat text-success" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 3a9 9 0 1 0 4.5 16.852l3.5 1.148l-1.148 -3.5a9 9 0 0 0 -6.852 -14.5z" /></svg>
            <span>Buka Live Chat</span>
          </a>
        </div>
      </div>

      <a href="{{ route('jwos.edit', $jwo) }}" class="btn btn-outline-warning shadow-sm">
        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 20h4l10.5 -10.5a2.828 2.828 0 1 0 -4 -4l-10.5 10.5v4" /><path d="M13.5 6.5l4 4" /></svg>
        Edit
      </a>

      <button onclick="window.print()" class="btn btn-primary shadow-sm">
        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2" /><path d="M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4" /><path d="M7 13m0 2a2 2 0 0 1 2 -2h6a2 2 0 0 1 2 2v4a2 2 0 0 1 -2 2h-6a2 2 0 0 1 -2 -2z" /></svg>
        Cetak JWO
      </button>
      <a href="{{ route('jwos.index') }}" class="btn btn-outline-secondary">Kembali</a>
    </div>
  </div>
</div>

<!-- Form Update Status (D-Print-None) -->
<div class="card mb-3 d-print-none update-status-card">
    <div class="card-body py-2.5">
        <form action="{{ route('jwos.status', $jwo) }}" method="POST" class="d-flex align-items-center gap-3 flex-wrap">
            @csrf @method('PATCH')
            <div class="fw-bold d-flex align-items-center gap-1.5">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon text-primary" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 3a9 9 0 0 1 0 18a9 9 0 0 1 0 -18z" /><path d="M12 7v5l3 3" /></svg>
              <span>Update Status:</span>
            </div>
            <select name="status" class="form-select form-select-sm w-auto">
                <option value="Progress Site" {{ $jwo->status == 'Progress Site' ? 'selected' : '' }}>Progress Site</option>
                <option value="Sent" {{ $jwo->status == 'Sent' ? 'selected' : '' }}>Sent</option>
                <option value="Progress Vendor" {{ $jwo->status == 'Progress Vendor' ? 'selected' : '' }}>Progress Vendor</option>
                <option value="Completed" {{ $jwo->status == 'Completed' ? 'selected' : '' }}>Completed</option>
                <option value="Cancelled" {{ $jwo->status == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
            
            <div class="d-flex align-items-center gap-1.5" title="Tanggal Dikirim ke Vendor">
                <span class="small text-muted">Tgl Sent:</span>
                <input type="date" name="date_sent" class="form-control form-control-sm w-auto" value="{{ $jwo->date_sent ? $jwo->date_sent->format('Y-m-d') : '' }}">
            </div>

            <div class="d-flex align-items-center gap-1.5" title="Biaya Aktual Servis">
                <span class="small text-muted">Biaya (Rp):</span>
                <input type="number" name="cost" class="form-control form-control-sm w-auto" placeholder="Biaya (Rp)" value="{{ $jwo->cost }}" style="max-width: 150px;">
            </div>
            <button type="submit" class="btn btn-sm btn-primary shadow-none">Simpan Status</button>
        </form>
    </div>
</div>

<div class="card p-3 p-lg-4 wo-report-card shadow-sm">
  <!-- Header Dokumen Cetak -->
  <div class="row align-items-center border-bottom pb-2 mb-3">
    <div class="col-8">
      <div class="d-flex align-items-center">
        @php
            $appLogo = \App\Models\AppSetting::where('key', 'app_logo')->first()?->value;
            $appName = \App\Models\AppSetting::where('key', 'app_name')->first()?->value ?? 'CMMS AISFAR';
            $appAddress = \App\Models\AppSetting::where('key', 'app_address')->first()?->value ?? '';
            $siteCode = $jwo->unit->siteRelation->code ?? (is_string($jwo->unit->site) ? $jwo->unit->site : ($jwo->site->code ?? auth()->user()->site?->code ?? ''));
            if ($siteCode) {
                $appName .= ' - ' . $siteCode;
            }
        @endphp
        @if($appLogo)
            <img src="{{ asset('storage/logos/' . $appLogo) }}" alt="Logo" style="max-height: 40px;" class="me-3">
        @endif
        <div>
          <h3 class="m-0 fw-bold text-uppercase text-body">{{ $appName }}</h3>
          @if($appAddress)
            <div class="app-address text-muted small" style="font-size: 0.75rem; margin-bottom: 2px;">{{ $appAddress }}</div>
          @endif
          <div class="text-muted fw-bold small">JOB WORK ORDER (JWO)</div>
        </div>
      </div>
    </div>
    <div class="col-4 text-end">
        <div class="fs-4 fw-bold border px-2.5 py-1 rounded d-inline-block jwo-no-badge">NO: {{ $jwo->no_jwo }}</div>
        <div class="mt-1 text-muted" style="font-size: 0.75rem;">Tgl Cetak: {{ date('d M Y') }}</div>
    </div>
  </div>

  @php
      $badgeClass = match($jwo->status) {
          'Progress Site' => 'bg-cyan-lt text-cyan',
          'Sent' => 'bg-warning-lt text-warning',
          'Progress Vendor' => 'bg-azure-lt text-azure',
          'Completed' => 'bg-success-lt text-success',
          'Cancelled' => 'bg-danger-lt text-danger',
          default => 'bg-secondary-lt'
      };
  @endphp

  <!-- Grid Summary Table (Reference Style) -->
  <div class="mb-3">
    <table class="table table-bordered table-sm jwo-grid-table mb-0">
        <tbody>
            <tr>
                <td width="18%" class="bg-light">DATE</td>
                <td width="32%">: {{ $jwo->created_at ? $jwo->created_at->format('d-M-Y') : '-' }}</td>
                <td width="18%" class="bg-light">MODEL MESIN</td>
                <td width="32%">: {{ $jwo->unit->model->name ?? '-' }}</td>
            </tr>
            <tr>
                <td class="bg-light">NAMA BENGKEL</td>
                <td>: <strong>{{ $jwo->vendor->name ?? '-' }}</strong></td>
                <td class="bg-light">SERIAL NO.</td>
                <td>: {{ $jwo->unit->sn_chassis ?? '-' }}</td>
            </tr>
            <tr>
                <td class="bg-light">TANGGAL KIRIM</td>
                <td>: {{ $jwo->date_sent ? $jwo->date_sent->format('d-M-Y') : 'Belum Dikirim' }}</td>
                <td class="bg-light">KODE UNIT</td>
                <td>: <strong>{{ $jwo->unit->nomor_unit ?? '-' }}</strong></td>
            </tr>
            <tr>
                <td class="bg-light">ESTIMASI FINISH</td>
                <td>: {{ $jwo->date_expected ? $jwo->date_expected->format('d-M-Y') : '-' }}</td>
                <td class="bg-light">SMR / HRS</td>
                <td>: <strong>{{ $jwo->workOrder->hours_meter ?? '-' }}</strong></td>
            </tr>
            <tr>
                <td class="bg-light">EKS. LOKASI</td>
                <td>: {{ $jwo->unit->siteRelation->name ?? (is_string($jwo->unit->site) ? $jwo->unit->site : ($jwo->site->name ?? '-')) }}</td>
                <td class="bg-light">NO. WO</td>
                <td>: @if($jwo->workOrder) <a href="{{ route('work-orders.show', $jwo->workOrder) }}" class="badge bg-primary text-primary-fg text-decoration-none fw-bold"><svg class="icon icon-tabler icon-tabler-link d-print-none me-1" width="12" height="12" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 15l6 -6" /><path d="M11 6l.463 -.463a5 5 0 0 1 7.071 7.071l-.534 .534" /><path d="M13 18l-.397 .534a5 5 0 0 1 -7.071 -7.071l.534 -.534" /></svg>{{ $jwo->workOrder->no_wo }}</a> @else - @endif</td>
            </tr>
            <tr>
                <td class="bg-light">STATUS JWO</td>
                <td>: <span class="badge {{ $badgeClass }} px-2 py-0.5">{{ $jwo->status }}</span></td>
                <td class="bg-light">NAMA KOMPONEN</td>
                <td>: <strong>{{ $jwo->part ? ($jwo->part->part_description ? $jwo->part->part_description . ' (' . $jwo->part->part_number . ')' : ($jwo->part->part_number ?? '-')) : '-' }}</strong></td>
            </tr>
            <tr>
                <td class="bg-light">BIAYA AKTUAL</td>
                <td>: {{ $jwo->cost ? 'Rp ' . number_format($jwo->cost, 0, ',', '.') : '-' }}</td>
                <td class="bg-light">MODEL KOMPONEN</td>
                <td>: {{ $jwo->componentGroup->name ?? '-' }}</td>
            </tr>
        </tbody>
    </table>
  </div>

  <div class="mb-3 row g-2">
    <div class="col-md-6">
        <div class="jwo-info-box jwo-problem-box h-100">
            <h4 class="text-uppercase border-bottom pb-1 mb-1 fw-bold text-danger d-flex align-items-center gap-1.5" style="font-size: 0.8rem;">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon text-danger" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 9v2m0 4v.01" /><path d="M5 19h14a2 2 0 0 0 1.84 -2.75l-7.1 -12.25a2 2 0 0 0 -3.5 0l-7.1 12.25a2 2 0 0 0 1.75 2.75" /></svg>
                Problem / Kerusakan
            </h4>
            <p class="mb-0 small" style="white-space: pre-line">{{ $jwo->problem_description }}</p>
        </div>
    </div>
    <div class="col-md-6">
        <div class="jwo-info-box jwo-action-box h-100">
            <h4 class="text-uppercase border-bottom pb-1 mb-1 fw-bold text-primary d-flex align-items-center gap-1.5" style="font-size: 0.8rem;">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon text-primary" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 10h3v-3l-3.5 -3.5a6 6 0 0 1 8 8l6 6a2 2 0 0 1 -3 3l-6 -6a6 6 0 0 1 -8 -8l3.5 3.5" /></svg>
                Request Action / Tindakan
            </h4>
            <p class="mb-0 small" style="white-space: pre-line">{{ $jwo->request_action }}</p>
        </div>
    </div>
  </div>

  @if($jwo->photo_1 || $jwo->photo_2)
  @php
      $photoCount = ($jwo->photo_1 ? 1 : 0) + ($jwo->photo_2 ? 1 : 0);
      $colClass = $photoCount == 1 ? 'col-12 text-center' : 'col-6 text-center';
  @endphp
  <div class="mb-3">
    <div class="row g-2 justify-content-center">
        @if($jwo->photo_1)
        <div class="{{ $colClass }}">
            <img src="{{ asset('storage/jwo_photos/' . $jwo->photo_1) }}" alt="Photo 1" class="img-fluid rounded border shadow-sm img-jwo-photo" style="max-height: 170px; object-fit: contain; max-width: 100%;">
        </div>
        @endif
        @if($jwo->photo_2)
        <div class="{{ $colClass }}">
            <img src="{{ asset('storage/jwo_photos/' . $jwo->photo_2) }}" alt="Photo 2" class="img-fluid rounded border shadow-sm img-jwo-photo" style="max-height: 170px; object-fit: contain; max-width: 100%;">
        </div>
        @endif
    </div>
  </div>
  @endif

  @php
      $sigs = $jwo->signatures->keyBy('sign_type');
      $signatureConfig = [
        'dibuat' => [
            'title' => 'Dibuat Oleh (Perusahaan)',
            'allowed_roles' => ['Admin', 'Super Admin', 'User']
        ],
        'jwo_diperiksa' => [
            'title' => 'Diperiksa (Supervisor)',
            'allowed_roles' => ['Supervisor']
        ],
        'jwo_disetujui' => [
            'title' => 'Disetujui (Superintendent)',
            'allowed_roles' => ['Superintendent', 'Manager']
        ],
        'jwo_logistik' => [
            'title' => 'Logistik (Gudang)',
            'allowed_roles' => ['Logistik', 'Admin']
        ],
        'dikirim' => [
            'title' => 'Dikirim Melalui / Driver',
            'allowed_roles' => ['Driver', 'User', 'Admin']
        ],
        'diterima' => [
            'title' => 'Diterima Oleh (Vendor)',
            'allowed_roles' => ['Vendor', 'User', 'Admin']
        ]
      ];
      $currentUser = auth()->user();
  @endphp

  <!-- Signatures -->
  <div class="row mt-2 pt-1 g-2">
      @foreach($signatureConfig as $type => $config)
      @php
          $sig = $sigs->get($type);
          $canSign = false;
          if (!$sig && $currentUser) {
              if ($currentUser->hasRole('Super Admin')) {
                  $canSign = true;
              } else {
                  foreach ($config['allowed_roles'] as $role) {
                      if ($currentUser->hasRole($role)) {
                          $canSign = true; break;
                      }
                  }
              }
          }
      @endphp
      <div class="col-4 text-center mb-2">
          <div class="signature-card h-100">
              <div class="fw-bold mb-1" style="font-size: 0.75rem;">{{ $config['title'] }}</div>
              
              <div style="min-height: 45px;" class="signature-container d-flex flex-column align-items-center justify-content-center">
                  @if($sig)
                  <div class="d-inline-block border border-success border-2 rounded p-1 text-success" style="transform: rotate(-2deg); opacity: 0.9; background: rgba(47, 179, 68, 0.08);">
                      <div class="fw-bold" style="font-size: 0.6rem; letter-spacing: 0.4px;">DISETUJUI DIGITAL</div>
                      <div class="fw-bold text-truncate" style="max-width: 150px; margin: 0 auto; font-size: 0.72rem;">{{ $sig->user->nama_lengkap ?? $sig->user->name }}</div>
                      <div style="font-size: 0.6rem;">{{ $sig->created_at->format('d/m/Y H:i') }}</div>
                  </div>
                  <div class="mt-1 small" style="font-size: 0.72rem;">( {{ $sig->user->nama_lengkap ?? $sig->user->name }} )</div>
                  @elseif($canSign)
                  <form action="{{ route('signatures.sign') }}" method="POST" class="d-print-none mb-1">
                      @csrf
                      <input type="hidden" name="document_type" value="{{ get_class($jwo) }}">
                      <input type="hidden" name="document_id" value="{{ $jwo->id }}">
                      <input type="hidden" name="sign_type" value="{{ $type }}">
                      <button type="submit" class="btn btn-sm btn-outline-primary py-0 shadow-none" style="font-size: 0.75rem;" onclick="return confirm('Tanda tangani dokumen ini?')">✍️ Tanda Tangani</button>
                  </form>
                  <div class="d-none d-print-block mt-2" style="font-size: 0.75rem;">( ......................... )</div>
                  @else
                  <div class="mt-2 text-muted" style="font-size: 0.75rem;">( ......................... )</div>
                  @endif
              </div>
          </div>
      </div>
      @endforeach
  </div>
</div>

<script>
  function copyToClipboard(text, message) {
    navigator.clipboard.writeText(text).then(() => {
      let toast = document.getElementById('shareToast');
      if (!toast) {
        toast = document.createElement('div');
        toast.id = 'shareToast';
        toast.className = 'position-fixed bottom-0 end-0 p-3 d-print-none';
        toast.style.zIndex = '9999';
        toast.innerHTML = `
          <div class="toast align-items-center text-white bg-success border-0 show" role="alert">
            <div class="d-flex">
              <div class="toast-body d-flex align-items-center gap-2">
                <svg class="icon" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10" /></svg>
                <span id="shareToastMsg"></span>
              </div>
              <button type="button" class="btn-close btn-close-white me-2 m-auto" onclick="document.getElementById('shareToast').remove()"></button>
            </div>
          </div>`;
        document.body.appendChild(toast);
      }
      document.getElementById('shareToastMsg').textContent = message || 'Tautan berhasil disalin!';
      setTimeout(() => {
        if (document.getElementById('shareToast')) {
          document.getElementById('shareToast').remove();
        }
      }, 3000);
    });
  }
</script>
@endsection
