@extends('layouts.tabler')

@section('title', 'Failure Analysis Report - ' . $far->no_far)

@section('content')
<style>
  /* Screen & Print Styling - Matching Work Order Exactly */
  .wo-report-card {
    background: var(--tblr-card-bg, #fff);
    border: 1px solid var(--tblr-border-color, #cbd5e1);
    box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
    font-size: 0.85rem;
    color: var(--tblr-body-color, #1e293b);
  }

  .table-report-header th {
    background-color: var(--tblr-bg-surface-tertiary, #f1f5f9) !important;
    color: var(--tblr-body-color, #334155) !important;
    font-weight: 700;
    text-transform: uppercase;
    font-size: 0.75rem;
    border-bottom: 2px solid var(--tblr-border-color, #cbd5e1) !important;
  }

  .table-condensed th, .table-condensed td {
    padding: 0.35rem 0.5rem !important;
    vertical-align: middle;
    font-size: 0.8rem;
    color: var(--tblr-body-color);
  }

  .signature-box {
    border: 1px solid var(--tblr-border-color, #cbd5e1);
    border-radius: 4px;
    padding: 8px;
    text-align: center;
    background-color: var(--tblr-bg-surface-tertiary, #f8fafc);
    color: var(--tblr-body-color);
  }

  .signature-space {
    height: 55px;
  }

  /* =========================================================
     DARK MODE — pastikan laporan FAR tetap terbaca
     ========================================================= */
  [data-bs-theme="dark"] .wo-report-card .app-address {
    color: #94a3b8 !important;
  }
  [data-bs-theme="dark"] .wo-report-card .text-dark {
    color: #f1f5f9 !important;
  }
  [data-bs-theme="dark"] .wo-report-card .badge.bg-primary.text-white {
    color: #0f172a !important;
  }
  [data-bs-theme="dark"] .table-report-header th {
    background-color: rgba(15, 23, 42, 0.85) !important;
    color: #fbbf24 !important;
    border-color: rgba(245, 158, 11, 0.3) !important;
  }
  [data-bs-theme="dark"] .table-condensed th,
  [data-bs-theme="dark"] .table-condensed td {
    color: #e2e8f0 !important;
  }
  [data-bs-theme="dark"] .signature-box {
    background-color: rgba(15, 23, 42, 0.5) !important;
    color: #f1f5f9 !important;
  }
  [data-bs-theme="dark"] .wo-report-card .bg-body-tertiary {
    background-color: rgba(15, 23, 42, 0.5) !important;
  }

  @media print {
    body {
      background: #fff !important;
      font-size: 10px !important;
      color: #000 !important;
    }
    .page-header, .navbar, .footer, .d-print-none {
      display: none !important;
    }
    .page-wrapper, .page-body, .container-xl {
      padding: 0 !important;
      margin: 0 !important;
      max-width: 100% !important;
      width: 100% !important;
    }
    .card.wo-report-card {
      --tblr-body-color: #000 !important;
      --tblr-muted-color: #4a5568 !important;
      background-color: #fff !important;
      color: #000 !important;
      border: 1px solid #000 !important;
      box-shadow: none !important;
      padding: 12px !important;
    }
    .card.wo-report-card .app-address {
      color: #333 !important;
    }
    .card.wo-report-card .table {
      color: #000 !important;
    }
    .wo-report-card .table-report-header th {
      background-color: #f1f5f9 !important;
      color: #000 !important;
    }
    .card.wo-report-card .signature-box,
    .card.wo-report-card .bg-body-tertiary {
      background-color: #f8fafc !important;
      color: #000 !important;
    }
    .card.wo-report-card .text-dark {
      color: #000 !important;
    }
    .table-condensed th, .table-condensed td {
      padding: 2px 4px !important;
      font-size: 9.5px !important;
      border-color: #94a3b8 !important;
    }
    .signature-space {
      height: 45px !important;
    }
    .badge {
      border: 0 !important;
      border-color: transparent !important;
      outline: none !important;
      box-shadow: none !important;
      color: #000 !important;
      background: transparent !important;
      padding: 0 !important;
      font-weight: 600 !important;
    }
    .wo-no-badge {
      border: 1.5px solid #000 !important;
      border-radius: 6px !important;
      padding: 4px 12px !important;
      display: inline-block !important;
    }
    @page {
      size: A4 portrait;
      margin: 8mm;
    }
  }
</style>

<div class="container-xl">
    <!-- Top Actions (Screen only) -->
    <div class="page-header d-print-none mb-3">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title text-primary fw-bold">Failure Analysis Report: {{ $far->no_far }}</h2>
            </div>
            <div class="col-auto ms-auto d-print-none gap-2 d-flex">
                <!-- Dropdown Bagikan Link -->
                <div class="dropdown">
                  <button type="button" class="btn btn-outline-primary dropdown-toggle fw-bold shadow-sm" data-bs-toggle="dropdown">
                    <svg class="icon icon-tabler icon-tabler-share me-1" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6 12m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" /><path d="M18 6m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" /><path d="M18 18m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" /><path d="M8.7 10.7l6.6 -3.4" /><path d="M8.7 13.3l6.6 3.4" /></svg>
                    Bagikan Link
                  </button>
                  <div class="dropdown-menu dropdown-menu-end shadow-md">
                    <a class="dropdown-item d-flex align-items-center gap-2" href="#" onclick="copyToClipboard('[FAR: {{ $far->no_far }}]({{ url()->current() }})', 'Link Format Chat berhasil disalin!'); return false;">
                      <svg class="icon icon-tabler icon-tabler-message-share text-primary" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M20 11v-3a3 3 0 0 0 -3 -3h-10a3 3 0 0 0 -3 3v8a3 3 0 0 0 3 3h5" /><path d="M16 22l5 -5" /><path d="M21 21.5v-4.5h-4.5" /></svg>
                      <span>Salin Format Chat (`[FAR](URL)`)</span>
                    </a>
                    <a class="dropdown-item d-flex align-items-center gap-2" href="#" onclick="copyToClipboard('{{ url()->current() }}', 'URL Web berhasil disalin!'); return false;">
                      <svg class="icon icon-tabler icon-tabler-link text-secondary" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 15l6 -6" /><path d="M11 6l.463 -.463a5 5 0 0 1 7.071 7.071l-.534 .534" /><path d="M13 18l-.397 .534a5 5 0 0 1 -7.071 -7.071l.534 -.534" /></svg>
                      <span>Salin URL Web Langsung</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('chat.index') }}" target="_blank" onclick="copyToClipboard('[FAR: {{ $far->no_far }}]({{ url()->current() }})', 'Link disalin! Membuka Live Chat...');">
                      <svg class="icon icon-tabler icon-tabler-brand-hipchat text-success" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 3a9 9 0 1 0 4.5 16.852l3.5 1.148l-1.148 -3.5a9 9 0 0 0 -6.852 -14.5z" /></svg>
                      <span>Buka Live Chat</span>
                    </a>
                  </div>
                </div>

                <button type="button" class="btn btn-success fw-bold shadow-sm" onclick="window.print();">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2" /><path d="M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4" /><path d="M7 13m0 2a2 2 0 0 1 2 -2h6a2 2 0 0 1 2 2v4a2 2 0 0 1 -2 2h-6a2 2 0 0 1 -2 -2z" /></svg>
                    Cetak Report (A4)
                </button>
                @can('edit_fars')
                <a href="{{ route('fars.edit', $far) }}" class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 20h4l10.5 -10.5a1.5 1.5 0 0 0 -4 -4l-10.5 10.5v4" /><path d="M13.5 6.5l4 4" /></svg>
                    Edit FAR
                </a>
                @endcan
                <a href="{{ route('fars.index') }}" class="btn btn-outline-secondary">Kembali</a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success d-print-none">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger d-print-none">{{ session('error') }}</div>
    @endif

    <!-- PRINTABLE FAR REPORT CARD DOCUMENT -->
    <div class="card wo-report-card p-4 rounded-3">
        <!-- Document Header -->
        <div class="row align-items-center border-bottom pb-3 mb-3">
            <div class="col-8">
                <div class="d-flex align-items-center">
                    @php
                        $appLogo = \App\Models\AppSetting::where('key', 'app_logo')->first()?->value;
                        $appName = \App\Models\AppSetting::where('key', 'app_name')->first()?->value ?? 'CMMS AISFAR';
                        $appAddress = \App\Models\AppSetting::where('key', 'app_address')->first()?->value ?? '';
                        $siteCode = $far->siteRelation->code ?? $far->siteRelation->name ?? '';
                        if ($siteCode) {
                            $appName .= ' - ' . $siteCode;
                        }
                    @endphp
                    @if($appLogo)
                        <img src="{{ asset('storage/logos/' . $appLogo) }}" alt="Logo" style="max-height: 45px;" class="me-3">
                    @endif
                    <div>
                        <h2 class="m-0 fw-extrabold text-uppercase text-body tracking-wide" style="font-size: 1.3rem;">{{ $appName }}</h2>
                        @if($appAddress)
                            <div class="app-address" style="font-size: 0.75rem; color: #555; margin-bottom: 2px;">{{ $appAddress }}</div>
                        @endif
                        <div class="text-muted small fw-semibold text-uppercase">FAILURE ANALYSIS REPORT & EVALUATION SHEET</div>
                    </div>
                </div>
            </div>
            <div class="col-4 text-end">
                <div class="badge bg-dark text-white px-3 py-2 fs-4 mb-1 wo-no-badge">NO: {{ $far->no_far }}</div>
                <div class="small text-muted">Tanggal Cetak: {{ date('d M Y H:i') }}</div>
            </div>
        </div>

        <!-- 4-Column Compact Metadata Grid -->
        <div class="row g-2 mb-3">
            <!-- Box 1: Identitas FAR -->
            <div class="col-md-3">
                <div class="border rounded p-2 bg-body-tertiary h-100">
                    <div class="fw-bold text-primary border-bottom pb-1 mb-1 small text-uppercase">1. Identitas FAR</div>
                    <table class="w-100 small">
                        <tr><td class="text-muted">Status:</td><td class="fw-bold text-end"><span class="badge {{ $far->status == 'Approved' ? 'bg-success' : ($far->status == 'Submitted' ? 'bg-warning' : 'bg-secondary') }} text-white px-2 py-0">{{ $far->status }}</span></td></tr>
                        <tr><td class="text-muted">Tgl Kejadian:</td><td class="fw-bold text-end">{{ $far->date_of_failure ? \Carbon\Carbon::parse($far->date_of_failure)->format('d/m/Y') : '-' }}</td></tr>
                        <tr><td class="text-muted">Tgl Lapor:</td><td class="fw-bold text-end">{{ $far->date_reported ? \Carbon\Carbon::parse($far->date_reported)->format('d/m/Y') : '-' }}</td></tr>
                        <tr><td class="text-muted">Pelapor:</td><td class="fw-bold text-end text-truncate" style="max-width: 90px;">{{ $far->reporter->nama_lengkap ?? '-' }}</td></tr>
                        @if($far->work_order_id && $far->workOrder)
                        <tr><td class="text-muted">Ref. WO:</td><td class="fw-bold text-end"><a href="{{ route('work-orders.show', $far->workOrder) }}" class="badge bg-primary text-white text-decoration-none px-2 py-1" title="Buka Detail Work Order"><svg class="icon icon-tabler icon-tabler-link d-print-none me-1" width="12" height="12" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 15l6 -6" /><path d="M11 6l.463 -.463a5 5 0 0 1 7.071 7.071l-.534 .534" /><path d="M13 18l-.397 .534a5 5 0 0 1 -7.071 -7.071l.534 -.534" /></svg>{{ $far->workOrder->no_wo }}</a></td></tr>
                        @endif
                    </table>
                </div>
            </div>

            <!-- Box 2: Identitas Unit -->
            <div class="col-md-3">
                <div class="border rounded p-2 bg-body-tertiary h-100">
                    <div class="fw-bold text-info border-bottom pb-1 mb-1 small text-uppercase">2. Identitas Unit</div>
                    <table class="w-100 small">
                        <tr><td class="text-muted">No Unit:</td><td class="fw-bold text-end text-primary fs-5">{{ $far->masterUnit->nomor_unit ?? '-' }}</td></tr>
                        <tr><td class="text-muted">Site / Project:</td><td class="fw-bold text-end">{{ $far->siteRelation->name ?? '-' }}</td></tr>
                        <tr><td class="text-muted">Model:</td><td class="fw-bold text-end">{{ $far->masterUnit->model->name ?? $far->masterUnit->model->model ?? '-' }}</td></tr>
                        <tr><td class="text-muted">SN Chassis:</td><td class="fw-bold text-end">{{ $far->masterUnit->sn_chassis ?? '-' }}</td></tr>
                        <tr><td class="text-muted">SMU Failure:</td><td class="fw-bold text-end">{{ $far->smu_at_failure ? number_format($far->smu_at_failure) . ' HM' : '-' }}</td></tr>
                    </table>
                </div>
            </div>

            <!-- Box 3: Component Failure -->
            <div class="col-md-3">
                <div class="border rounded p-2 bg-body-tertiary h-100">
                    <div class="fw-bold text-danger border-bottom pb-1 mb-1 small text-uppercase">3. Component Failure</div>
                    <table class="w-100 small">
                        <tr><td class="text-muted">Part No:</td><td class="fw-bold text-end text-danger">{{ $far->component_part_no ?: '-' }}</td></tr>
                        <tr><td class="text-muted">Nama Komp:</td><td class="fw-bold text-end text-break">{{ $far->component_description ?: '-' }}</td></tr>
                        <tr><td class="text-muted">P/N Penyebab:</td><td class="fw-bold text-end">{{ $far->part_no_causing_failure ?: '-' }}</td></tr>
                        <tr><td class="text-muted">Engine Model:</td><td class="fw-bold text-end">{{ $far->masterUnit->engine_model ?? '-' }}</td></tr>
                        <tr><td class="text-muted">Engine SN:</td><td class="fw-bold text-end">{{ $far->masterUnit->sn_engine ?? '-' }}</td></tr>
                    </table>
                </div>
            </div>

            <!-- Box 4: Last History & Oil -->
            <div class="col-md-3">
                <div class="border rounded p-2 bg-body-tertiary h-100">
                    <div class="fw-bold text-warning border-bottom pb-1 mb-1 small text-uppercase">4. Last Comp & Oil</div>
                    <table class="w-100 small">
                        <tr><td class="text-muted">Comp Installed:</td><td class="fw-bold text-end">{{ $far->last_comp_date ? \Carbon\Carbon::parse($far->last_comp_date)->format('d/m/Y') : '-' }}</td></tr>
                        <tr><td class="text-muted">Comp Hours:</td><td class="fw-bold text-end">{{ $far->hours_of_component ? number_format($far->hours_of_component) . ' Hrs' : '-' }}</td></tr>
                        <tr><td class="text-muted">Oil Sampled:</td><td class="fw-bold text-end">{{ $far->last_oil_date_taken ? \Carbon\Carbon::parse($far->last_oil_date_taken)->format('d/m/Y') : '-' }}</td></tr>
                        <tr><td class="text-muted">Oil Eval:</td><td class="fw-bold text-end"><span class="badge bg-danger text-white px-2 py-0">{{ $far->last_oil_eval ?? '-' }}</span></td></tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- 5. ANALISA KERUSAKAN (ANALYSIS DETAILS) -->
        <div class="mb-4 pt-2 border-top">
            <div class="fw-bold text-body mb-2 small text-uppercase">5. URAIAN HASIL ANALISA KERUSAKAN (ANALYSIS DETAILS)</div>
            
            <div class="mb-3">
                <div class="fw-bold text-secondary small mb-1">A. FAILURE OUTLINE (Ringkasan Kerusakan & P/N Terpengaruh)</div>
                <div class="p-3 border rounded bg-body-tertiary text-break" style="white-space: pre-wrap; font-size: 0.85rem;">{{ $far->failure_outline ?: '-' }}</div>
            </div>

            <div class="mb-3">
                <div class="fw-bold text-secondary small mb-1">B. BACKGROUND (Latar Belakang & Kronologi Kerusakan)</div>
                <div class="p-3 border rounded bg-body-tertiary text-break" style="white-space: pre-wrap; font-size: 0.85rem;">{{ $far->background ?: '-' }}</div>
            </div>

            <div class="mb-3">
                <div class="fw-bold text-secondary small mb-1">C. FAILURE ANALYSIS (Analisa Penyebab Teknis Kerusakan)</div>
                <div class="p-3 border rounded bg-body-tertiary text-break" style="white-space: pre-wrap; font-size: 0.85rem;">{{ $far->failure_analysis ?: '-' }}</div>
            </div>

            <div class="mb-3">
                <div class="fw-bold text-danger small mb-1">D. CONCLUSION (Kesimpulan Laporan Analysis)</div>
                <div class="p-3 border border-danger-subtle rounded bg-danger-lt text-break" style="white-space: pre-wrap; font-size: 0.85rem;">{{ $far->conclusion ?: '-' }}</div>
            </div>
        </div>

        <!-- 6. DOKUMENTASI FOTO OBSERVASI (ATTACHMENTS) -->
        @if($far->attachments->count() > 0)
        <div class="mb-4 pt-2 border-top">
            <div class="fw-bold text-body mb-2 small text-uppercase">6. DOKUMENTASI FOTO OBSERVASI (ATTACHMENTS & SKETCHES)</div>
            <div class="row g-3">
                @foreach($far->attachments as $attach)
                <div class="col-md-6">
                    <div class="border rounded p-2 bg-body-tertiary text-center h-100">
                        <img src="{{ asset('storage/' . $attach->photo_path) }}" class="img-fluid rounded mb-2" style="max-height: 220px; object-fit: contain;">
                        <div class="fw-bold text-dark small">{{ $attach->component }}</div>
                        <div class="text-muted small text-break" style="white-space: pre-wrap;">{{ $attach->observation }}</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- 7. LEMBAR PENGESAHAN & PERSETUJUAN (SIGNATURES) -->
        <div class="mt-4 pt-2 border-top">
            <div class="fw-bold text-body mb-2 small text-uppercase">7. Lembar Pengesahan & Persetujuan (Signatures)</div>
            
            @php
            $sigs = $far->signatures->keyBy('sign_type');
            
            $signatureConfig = [
                'dibuat' => [
                    'title' => 'Prepared By',
                    'subtitle' => 'Pembuat Laporan',
                    'allowed_roles' => ['Admin', 'Super Admin', 'User', 'Planner']
                ],
                'ditinjau' => [
                    'title' => 'Reviewed By',
                    'subtitle' => 'Supervisor',
                    'allowed_roles' => ['Supervisor', 'Super Admin']
                ],
                'disetujui' => [
                    'title' => 'Approved By',
                    'subtitle' => 'Plant Superintendent',
                    'allowed_roles' => ['Superintendent', 'Manager', 'Super Admin']
                ],
            ];
            $currentUser = auth()->user();
            @endphp

            <div class="row g-2">
                @foreach($signatureConfig as $type => $config)
                @php
                    $sig = $sigs->get($type);
                    $canSign = false;
                    if (!$sig && $currentUser) {
                        foreach ($config['allowed_roles'] as $role) {
                            if ($currentUser->hasRole($role)) {
                                $canSign = true; break;
                            }
                        }
                    }
                @endphp
                <div class="col-4">
                    <div class="signature-box position-relative">
                        <div class="fw-bold small text-uppercase">{{ $config['title'] }}</div>
                        <div class="text-muted" style="font-size: 0.7rem;">{{ $config['subtitle'] }}</div>
                        
                        <div class="signature-space d-flex align-items-center justify-content-center">
                            @if($sig)
                            <div class="border border-success border-2 rounded p-1 text-success text-center" style="transform: rotate(-3deg); opacity: 0.85; width: 95%;">
                                <div class="fw-bold" style="font-size: 0.55rem; letter-spacing: 0.5px;">DISETUJUI DIGITAL</div>
                                <div class="fw-bold text-truncate" style="font-size: 0.65rem;">{{ $sig->user->nama_lengkap ?? $sig->user->name }}</div>
                                <div style="font-size: 0.5rem;">{{ $sig->created_at->format('d/m/y H:i') }}</div>
                            </div>
                            @elseif($canSign)
                            <form action="{{ route('signatures.sign') }}" method="POST" class="d-print-none">
                                @csrf
                                <input type="hidden" name="document_type" value="{{ get_class($far) }}">
                                <input type="hidden" name="document_id" value="{{ $far->id }}">
                                <input type="hidden" name="sign_type" value="{{ $type }}">
                                <button type="submit" class="btn btn-sm btn-outline-primary" onclick="return confirm('Anda yakin ingin menandatangani dokumen ini?')">✍️ Tanda Tangani</button>
                            </form>
                            <span class="text-muted d-none d-print-block" style="font-size: 0.75rem;">( .................................... )</span>
                            @else
                            <span class="text-muted" style="font-size: 0.75rem;">( .................................... )</span>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<script>
function copyToClipboard(text, successMessage) {
  var textArea = document.createElement("textarea");
  textArea.value = text;
  document.body.appendChild(textArea);
  textArea.focus();
  textArea.select();
  try {
    document.execCommand('copy');
    alert(successMessage);
  } catch (err) {
    console.error('Fallback: Oops, unable to copy', err);
  }
  document.body.removeChild(textArea);
}
</script>
@endsection
