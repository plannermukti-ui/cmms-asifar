@extends('layouts.tabler')
@section('title', 'Detail Job Work Order - ' . $jwo->no_jwo)

@section('content')
<style>
  .wo-report-card {
    background: var(--tblr-card-bg, #fff);
    font-size: 0.85rem;
  }
  .jwo-grid-table th, .jwo-grid-table td {
    padding: 0.25rem 0.5rem !important;
    font-size: 0.78rem;
    vertical-align: middle;
  }
  .img-jwo-photo { max-height: 280px; }
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
    .page-header, .navbar, .footer, .d-print-none { display: none !important; }
    .card { border: none !important; box-shadow: none !important; padding: 0 !important; margin: 0 !important; }
    .wo-report-card { background-color: #fff !important; color: #000 !important; }
    .jwo-grid-table td.bg-light { background-color: #f1f5f9 !important; color: #000 !important; }
    .badge { background: transparent !important; border: 0 !important; color: #000 !important; font-weight: bold !important; padding: 0 !important; font-size: inherit !important; }
    .page-wrapper { margin: 0 !important; padding: 0 !important; }
    .table-sm th, .table-sm td { padding: 0.2rem 0.4rem !important; }
    .jwo-grid-table td { padding: 0.15rem 0.35rem !important; font-size: 8pt !important; }
    .img-jwo-photo { max-height: 260px !important; }
    .signature-container { min-height: 45px !important; }
    .mb-4 { margin-bottom: 0.75rem !important; }
    .mb-3 { margin-bottom: 0.5rem !important; }
    .mt-4 { margin-top: 0.5rem !important; }
    .pb-3 { padding-bottom: 0.5rem !important; }
    h4 { font-size: 0.85rem !important; margin-bottom: 0.3rem !important; }
  }

  /* =========================================================
     DARK MODE — pastikan laporan JWO tetap terbaca
     ========================================================= */
  [data-bs-theme="dark"] .wo-report-card {
    color: #f1f5f9 !important;
  }
  [data-bs-theme="dark"] .jwo-grid-table td.bg-light {
    background-color: rgba(15, 23, 42, 0.9) !important;
    color: #fbbf24 !important;
  }
  [data-bs-theme="dark"] .wo-report-card .app-address {
    color: #94a3b8 !important;
  }
  [data-bs-theme="dark"] .wo-report-card .border-dark {
    border-color: rgba(245, 158, 11, 0.55) !important;
  }
  [data-bs-theme="dark"] .wo-report-card .badge.bg-primary.text-white {
    color: #0f172a !important;
  }
</style>

<div class="page-header d-print-none mb-3">
  <div class="row align-items-center">
    <div class="col">
      <h2 class="page-title text-primary fw-bold">Detail JWO: {{ $jwo->no_jwo }}</h2>
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

      <button onclick="window.print()" class="btn btn-success">
        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2" /><path d="M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4" /><path d="M7 13m0 2a2 2 0 0 1 2 -2h6a2 2 0 0 1 2 2v4a2 2 0 0 1 -2 2h-6a2 2 0 0 1 -2 -2z" /></svg>
        Cetak JWO
      </button>
      <a href="{{ route('jwos.index') }}" class="btn btn-outline-secondary">Kembali</a>
    </div>
  </div>
</div>

<!-- Form Update Status (D-Print-None) -->
<div class="card mb-3 d-print-none bg-blue-lt">
    <div class="card-body py-2">
        <form action="{{ route('jwos.status', $jwo) }}" method="POST" class="d-flex align-items-center gap-3 flex-wrap">
            @csrf @method('PATCH')
            <div class="fw-bold">Update Status:</div>
            <select name="status" class="form-select form-select-sm w-auto">
                <option value="Progress Site" {{ $jwo->status == 'Progress Site' ? 'selected' : '' }}>Progress Site</option>
                <option value="Sent" {{ $jwo->status == 'Sent' ? 'selected' : '' }}>Sent</option>
                <option value="Progress Vendor" {{ $jwo->status == 'Progress Vendor' ? 'selected' : '' }}>Progress Vendor</option>
                <option value="Completed" {{ $jwo->status == 'Completed' ? 'selected' : '' }}>Completed</option>
                <option value="Cancelled" {{ $jwo->status == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
            
            <div class="d-flex align-items-center gap-1" title="Tanggal Dikirim ke Vendor">
                <span class="small text-muted">Tgl Sent:</span>
                <input type="date" name="date_sent" class="form-control form-control-sm w-auto" value="{{ $jwo->date_sent ? $jwo->date_sent->format('Y-m-d') : '' }}">
            </div>

            <input type="number" name="cost" class="form-control form-control-sm w-auto" placeholder="Biaya (Rp)" value="{{ $jwo->cost }}" style="max-width: 150px;">
            <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
        </form>
    </div>
</div>

<div class="card p-3 wo-report-card">
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
            <div class="app-address" style="font-size: 0.7rem; color: #555; margin-bottom: 2px;">{{ $appAddress }}</div>
          @endif
          <div class="text-muted fw-bold small">JOB WORK ORDER (JWO)</div>
        </div>
      </div>
    </div>
    <div class="col-4 text-end">
        <div class="fs-4 fw-bold border px-2 py-1 rounded d-inline-block border-dark">NO: {{ $jwo->no_jwo }}</div>
        <div class="mt-1 text-muted style-small" style="font-size: 0.75rem;">Tgl Cetak: {{ date('d M Y') }}</div>
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
                <td width="18%" class="bg-light fw-bold">DATE</td>
                <td width="32%">: {{ $jwo->created_at ? $jwo->created_at->format('d-M-Y') : '-' }}</td>
                <td width="18%" class="bg-light fw-bold">MODEL MESIN</td>
                <td width="32%">: {{ $jwo->unit->model->name ?? '-' }}</td>
            </tr>
            <tr>
                <td class="bg-light fw-bold">NAMA BENGKEL</td>
                <td>: <strong>{{ $jwo->vendor->name ?? '-' }}</strong></td>
                <td class="bg-light fw-bold">SERIAL NO.</td>
                <td>: {{ $jwo->unit->sn_chassis ?? '-' }}</td>
            </tr>
            <tr>
                <td class="bg-light fw-bold">TANGGAL KIRIM</td>
                <td>: {{ $jwo->date_sent ? $jwo->date_sent->format('d-M-Y') : 'Belum Dikirim' }}</td>
                <td class="bg-light fw-bold">KODE UNIT</td>
                <td>: <strong>{{ $jwo->unit->nomor_unit ?? '-' }}</strong></td>
            </tr>
            <tr>
                <td class="bg-light fw-bold">ESTIMASI FINISH</td>
                <td>: {{ $jwo->date_expected ? $jwo->date_expected->format('d-M-Y') : '-' }}</td>
                <td class="bg-light fw-bold">SMR / HRS</td>
                <td>: <strong>{{ $jwo->workOrder->hours_meter ?? '-' }}</strong></td>
            </tr>
            <tr>
                <td class="bg-light fw-bold">EKS. LOKASI</td>
                <td>: {{ $jwo->unit->siteRelation->name ?? (is_string($jwo->unit->site) ? $jwo->unit->site : ($jwo->site->name ?? '-')) }}</td>
                <td class="bg-light fw-bold">NO. WO</td>
                <td>: @if($jwo->workOrder) <a href="{{ route('work-orders.show', $jwo->workOrder) }}" class="badge bg-primary text-white text-decoration-none fw-bold"><svg class="icon icon-tabler icon-tabler-link d-print-none me-1" width="12" height="12" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 15l6 -6" /><path d="M11 6l.463 -.463a5 5 0 0 1 7.071 7.071l-.534 .534" /><path d="M13 18l-.397 .534a5 5 0 0 1 -7.071 -7.071l.534 -.534" /></svg>{{ $jwo->workOrder->no_wo }}</a> @else - @endif</td>
            </tr>
            <tr>
                <td class="bg-light fw-bold">STATUS JWO</td>
                <td>: <span class="badge {{ $badgeClass }} px-2 py-0">{{ $jwo->status }}</span></td>
                <td class="bg-light fw-bold">NAMA KOMPONEN</td>
                <td>: <strong>{{ $jwo->part ? ($jwo->part->part_description ? $jwo->part->part_description . ' (' . $jwo->part->part_number . ')' : ($jwo->part->part_number ?? '-')) : '-' }}</strong></td>
            </tr>
            <tr>
                <td class="bg-light fw-bold">BIAYA AKTUAL</td>
                <td>: {{ $jwo->cost ? 'Rp ' . number_format($jwo->cost, 0, ',', '.') : '-' }}</td>
                <td class="bg-light fw-bold">MODEL KOMPONEN</td>
                <td>: {{ $jwo->componentGroup->name ?? '-' }}</td>
            </tr>
        </tbody>
    </table>
  </div>

  <div class="mb-3 row g-2">
    <div class="col-6">
        <h4 class="text-uppercase border-bottom pb-1 mb-1 fw-bold text-danger" style="font-size: 0.8rem;">Problem / Kerusakan</h4>
        <p class="mb-0 small" style="white-space: pre-line">{{ $jwo->problem_description }}</p>
    </div>
    <div class="col-6">
        <h4 class="text-uppercase border-bottom pb-1 mb-1 fw-bold text-primary" style="font-size: 0.8rem;">Request Action / Tindakan</h4>
        <p class="mb-0 small" style="white-space: pre-line">{{ $jwo->request_action }}</p>
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
          <div class="fw-bold mb-1" style="font-size: 0.75rem;">{{ $config['title'] }}</div>
          
          <div style="min-height: 45px;" class="signature-container d-flex flex-column align-items-center justify-content-center">
              @if($sig)
              <div class="d-inline-block border border-success border-2 rounded p-1 text-success" style="transform: rotate(-2deg); opacity: 0.9;">
                  <div class="fw-bold" style="font-size: 0.6rem; letter-spacing: 0.4px;">DISETUJUI DIGITAL</div>
                  <div class="fw-bold text-truncate" style="max-width: 150px; margin: 0 auto; font-size: 0.72rem;">{{ $sig->user->nama_lengkap ?? $sig->user->name }}</div>
                  <div style="font-size: 0.6rem;">{{ $sig->created_at->format('d/m/Y H:i') }}</div>
              </div>
              <div class="mt-1" style="font-size: 0.72rem;">( {{ $sig->user->nama_lengkap ?? $sig->user->name }} )</div>
              @elseif($canSign)
              <form action="{{ route('signatures.sign') }}" method="POST" class="d-print-none mb-1">
                  @csrf
                  <input type="hidden" name="document_type" value="{{ get_class($jwo) }}">
                  <input type="hidden" name="document_id" value="{{ $jwo->id }}">
                  <input type="hidden" name="sign_type" value="{{ $type }}">
                  <button type="submit" class="btn btn-sm btn-outline-primary py-0" style="font-size: 0.75rem;" onclick="return confirm('Tanda tangani dokumen ini?')">✍️ Tanda Tangani</button>
              </form>
              <div class="d-none d-print-block mt-2" style="font-size: 0.75rem;">( ......................... )</div>
              @else
              <div class="mt-2" style="font-size: 0.75rem;">( ......................... )</div>
              @endif
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
