@extends('layouts.tabler')
@section('title', 'Work Order Report - ' . $workOrder->no_wo)

@section('content')
<style>
  /* Screen & Print Styling */
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
    .wo-report-card {
      border: 1px solid #000 !important;
      box-shadow: none !important;
      padding: 12px !important;
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
      white-space: nowrap !important;
      display: inline !important;
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

<!-- Top Actions (Screen only) -->
<div class="page-header d-print-none mb-3">
  <div class="row align-items-center">
    <div class="col">
      <h2 class="page-title text-primary fw-bold">Laporan Work Order: {{ $workOrder->no_wo }}</h2>
    </div>
    <div class="col-auto ms-auto d-print-none gap-2 d-flex">
      <!-- Dropdown Bagikan Link -->
      <div class="dropdown">
        <button type="button" class="btn btn-outline-primary dropdown-toggle fw-bold shadow-sm" data-bs-toggle="dropdown">
          <svg class="icon icon-tabler icon-tabler-share me-1" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6 12m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" /><path d="M18 6m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" /><path d="M18 18m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" /><path d="M8.7 10.7l6.6 -3.4" /><path d="M8.7 13.3l6.6 3.4" /></svg>
          Bagikan Link
        </button>
        <div class="dropdown-menu dropdown-menu-end shadow-md">
          <a class="dropdown-item d-flex align-items-center gap-2" href="#" onclick="copyToClipboard('[Work Order: {{ $workOrder->no_wo }}]({{ url()->current() }})', 'Link Format Chat berhasil disalin!'); return false;">
            <svg class="icon icon-tabler icon-tabler-message-share text-primary" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M20 11v-3a3 3 0 0 0 -3 -3h-10a3 3 0 0 0 -3 3v8a3 3 0 0 0 3 3h5" /><path d="M16 22l5 -5" /><path d="M21 21.5v-4.5h-4.5" /></svg>
            <span>Salin Format Chat (`[WO](URL)`)</span>
          </a>
          <a class="dropdown-item d-flex align-items-center gap-2" href="#" onclick="copyToClipboard('{{ url()->current() }}', 'URL Web berhasil disalin!'); return false;">
            <svg class="icon icon-tabler icon-tabler-link text-secondary" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 15l6 -6" /><path d="M11 6l.463 -.463a5 5 0 0 1 7.071 7.071l-.534 .534" /><path d="M13 18l-.397 .534a5 5 0 0 1 -7.071 -7.071l.534 -.534" /></svg>
            <span>Salin URL Web Langsung</span>
          </a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('chat.index') }}" target="_blank" onclick="copyToClipboard('[Work Order: {{ $workOrder->no_wo }}]({{ url()->current() }})', 'Link disalin! Membuka Live Chat...');">
            <svg class="icon icon-tabler icon-tabler-brand-hipchat text-success" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 3a9 9 0 1 0 4.5 16.852l3.5 1.148l-1.148 -3.5a9 9 0 0 0 -6.852 -14.5z" /></svg>
            <span>Buka Live Chat</span>
          </a>
        </div>
      </div>

      <button type="button" class="btn btn-success fw-bold shadow-sm" onclick="window.print();">
        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2" /><path d="M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4" /><path d="M7 13m0 2a2 2 0 0 1 2 -2h6a2 2 0 0 1 2 2v4a2 2 0 0 1 -2 2h-6a2 2 0 0 1 -2 -2z" /></svg>
        Cetak Report (A4)
      </button>
      @can('edit_work_orders')
      <a href="{{ route('work-orders.edit', $workOrder) }}" class="btn btn-primary">
        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 20h4l10.5 -10.5a1.5 1.5 0 0 0 -4 -4l-10.5 10.5v4" /><path d="M13.5 6.5l4 4" /></svg>
        Edit WO
      </a>
      @endcan
      <a href="{{ route('work-orders.index') }}" class="btn btn-outline-secondary">Kembali</a>
    </div>
  </div>
</div>

<!-- PRINTABLE WORK ORDER REPORT DOCUMENT -->
<div class="card wo-report-card p-4 rounded-3">
  
  <!-- Document Header -->
  <div class="row align-items-center border-bottom pb-3 mb-3">
    <div class="col-8">
      <div class="d-flex align-items-center">
        @php
            $appLogo = \App\Models\AppSetting::where('key', 'app_logo')->first()?->value;
            $appName = \App\Models\AppSetting::where('key', 'app_name')->first()?->value ?? 'CMMS AISFAR';
            $appAddress = \App\Models\AppSetting::where('key', 'app_address')->first()?->value ?? '';
            $siteCode = $workOrder->unit->siteRelation->code ?? (is_string($workOrder->unit->site) ? $workOrder->unit->site : ($workOrder->site->code ?? auth()->user()->site?->code ?? ''));
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
            <div style="font-size: 0.75rem; color: #555; margin-bottom: 2px;">{{ $appAddress }}</div>
          @endif
          <div class="text-muted small fw-semibold">WORK ORDER MAINTENANCE REPORT & EXECUTION SHEET</div>
        </div>
      </div>
    </div>
    <div class="col-4 text-end">
      <div class="badge bg-dark text-white px-3 py-2 fs-4 mb-1 wo-no-badge">NO: {{ $workOrder->no_wo }}</div>
      <div class="small text-muted">Tanggal Cetak: {{ date('d M Y H:i') }}</div>
    </div>
  </div>

  <!-- 4-Column Compact Metadata Grid -->
  <div class="row g-2 mb-3">
    
    <!-- Box 1: Identitas WO -->
    <div class="col-3">
      <div class="border rounded p-2 bg-body-tertiary h-100">
        <div class="fw-bold text-primary border-bottom pb-1 mb-1 small text-uppercase">1. Identitas WO</div>
        <table class="w-100 small">
          <tr><td class="text-muted">Status:</td><td class="fw-bold text-end"><span class="badge bg-blue text-white px-2 py-0">{{ $workOrder->status_wo }}</span></td></tr>
          <tr><td class="text-muted">Tipe WO:</td><td class="fw-bold text-end"><span class="badge bg-danger text-white px-2 py-0">{{ $workOrder->tipe_wo }}</span></td></tr>
          <tr><td class="text-muted">Downtime:</td><td class="fw-bold text-end">{{ $workOrder->downtime_code }}</td></tr>
          <tr><td class="text-muted">Opportunity:</td><td class="fw-bold text-end"><span class="badge {{ $workOrder->opportunity ? 'bg-success' : 'bg-secondary' }} text-white px-2 py-0">{{ $workOrder->opportunity ? 'Yes' : 'No' }}</span></td></tr>
          <tr><td class="text-muted">Dibuat:</td><td class="fw-bold text-end text-truncate" style="max-width: 90px;">{{ $workOrder->creator->name ?? '-' }}</td></tr>
          @if($workOrder->fars && $workOrder->fars->count() > 0)
          <tr><td class="text-muted">Ref. FAR:</td><td class="fw-bold text-end">
            @foreach($workOrder->fars as $f)
              <a href="{{ route('fars.show', $f) }}" class="badge bg-danger text-white text-decoration-none px-1.5 py-0 mb-1" title="Buka Detail FAR"><svg class="icon icon-tabler icon-tabler-link d-print-none me-1" width="10" height="10" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 15l6 -6" /><path d="M11 6l.463 -.463a5 5 0 0 1 7.071 7.071l-.534 .534" /><path d="M13 18l-.397 .534a5 5 0 0 1 -7.071 -7.071l.534 -.534" /></svg>{{ $f->no_far }}</a>
            @endforeach
          </td></tr>
          @endif
          @if($workOrder->jwos && $workOrder->jwos->count() > 0)
          <tr><td class="text-muted">Ref. JWO:</td><td class="fw-bold text-end">
            @foreach($workOrder->jwos as $j)
              <a href="{{ route('jwos.show', $j) }}" class="badge bg-azure text-white text-decoration-none px-1.5 py-0 mb-1" title="Buka Detail JWO"><svg class="icon icon-tabler icon-tabler-link d-print-none me-1" width="10" height="10" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 15l6 -6" /><path d="M11 6l.463 -.463a5 5 0 0 1 7.071 7.071l-.534 .534" /><path d="M13 18l-.397 .534a5 5 0 0 1 -7.071 -7.071l.534 -.534" /></svg>{{ $j->no_jwo }}</a>
            @endforeach
          </td></tr>
          @endif
        </table>
      </div>
    </div>

    <!-- Box 2: Identitas Unit -->
    <div class="col-3">
      <div class="border rounded p-2 bg-body-tertiary h-100">
        <div class="fw-bold text-info border-bottom pb-1 mb-1 small text-uppercase">2. Identitas Unit</div>
        <table class="w-100 small">
          <tr><td class="text-muted">No Unit:</td><td class="fw-bold text-end text-primary fs-5">{{ $workOrder->unit->nomor_unit ?? '-' }}</td></tr>
          <tr><td class="text-muted">Site:</td><td class="fw-bold text-end">{{ $workOrder->unit?->siteRelation?->code ?? $workOrder->unit?->siteRelation?->name ?? (is_string($workOrder->unit?->site) && $workOrder->unit?->site ? $workOrder->unit?->site : ($workOrder->site?->code ?? '-')) }}</td></tr>
          <tr><td class="text-muted">Model:</td><td class="fw-bold text-end">{{ $workOrder->unit->model->name ?? '-' }}</td></tr>
          <tr><td class="text-muted">Hours Meter:</td><td class="fw-bold text-end">{{ $workOrder->hours_meter ? $workOrder->hours_meter . ' HM' : '-' }}</td></tr>
          <tr><td class="text-muted">Lokasi:</td><td class="fw-bold text-end">{{ $workOrder->lokasi_kerusakan ?? '-' }}</td></tr>
        </table>
      </div>
    </div>

    <!-- Box 3: Waktu Breakdown -->
    <div class="col-3">
      <div class="border rounded p-2 bg-body-tertiary h-100">
        <div class="fw-bold text-warning border-bottom pb-1 mb-1 small text-uppercase">3. Waktu & Durasi</div>
        <table class="w-100 small">
          <tr><td class="text-muted">Waktu BD:</td><td class="fw-bold text-end">{{ $workOrder->waktu_bd ? $workOrder->waktu_bd->format('d/m/y H:i') : '-' }}</td></tr>
          <tr><td class="text-muted">Waktu RFU:</td><td class="fw-bold text-end">{{ $workOrder->waktu_rfu ? $workOrder->waktu_rfu->format('d/m/y H:i') : '(Running)' }}</td></tr>
          <tr><td class="text-muted">Durasi BD:</td><td class="fw-bold text-end text-danger fs-5">{{ $workOrder->durasi_hrs }} Hrs</td></tr>
          <tr><td class="text-muted">Tipe BD:</td><td class="fw-bold text-end">{{ $workOrder->breakdownType ? ($workOrder->breakdownType->code ? $workOrder->breakdownType->code . ' - ' . $workOrder->breakdownType->name : $workOrder->breakdownType->name) : '-' }}</td></tr>
        </table>
      </div>
    </div>

    <!-- Box 4: Klasifikasi -->
    <div class="col-3">
      <div class="border rounded p-2 bg-body-tertiary h-100">
        <div class="fw-bold text-teal border-bottom pb-1 mb-1 small text-uppercase">4. Klasifikasi</div>
        <table class="w-100 small">
          <tr><td class="text-muted">Comp Group:</td><td class="fw-bold text-end">{{ $workOrder->componentGroup->name ?? '-' }}</td></tr>
          <tr><td class="text-muted">Kat 1 / 2:</td><td class="fw-bold text-end">{{ $workOrder->category1->name ?? '-' }} / {{ $workOrder->category2->name ?? '-' }}</td></tr>
          <tr><td class="text-muted">Kat 3 / 4:</td><td class="fw-bold text-end">{{ $workOrder->category3->name ?? '-' }} / {{ $workOrder->category4->name ?? '-' }}</td></tr>
          <tr><td class="text-muted">Kat 5:</td><td class="fw-bold text-end">{{ $workOrder->category5->name ?? '-' }}</td></tr>
        </table>
      </div>
    </div>

  </div>

  <!-- Execution Details Table (Tasks, Actions, Manpower, Parts, Tools) -->
  <div class="mb-3">
    <div class="fw-bold text-body mb-1 small text-uppercase border-bottom pb-1">5. Uraian Pekerjaan & Pelaksanaan (Execution Details)</div>
    
    <table class="table table-bordered table-condensed mb-0">
      <thead class="table-report-header">
        <tr>
          <th width="4%" class="text-center">No</th>
          <th width="26%">Problem / Task</th>
          <th width="28%">Action / SubTask</th>
          <th width="14%">Manpower (Mekanik)</th>
          <th width="14%">Parts / Suku Cadang</th>
          <th width="14%">Tools Used</th>
        </tr>
      </thead>
      <tbody>
        @php $rowNo = 1; @endphp
        @forelse($workOrder->tasks as $tIdx => $task)
          @if($task->subtasks->count() > 0)
            @foreach($task->subtasks as $sIdx => $st)
            <tr>
              @if($sIdx === 0)
                <td rowspan="{{ $task->subtasks->count() }}" class="text-center align-top fw-bold bg-light">{{ $tIdx+1 }}</td>
                <td rowspan="{{ $task->subtasks->count() }}" class="align-top">
                  <div class="fw-bold text-primary">{{ $task->problem }}</div>
                  <div class="text-muted" style="font-size: 0.75rem;">
                    Comp: {{ $task->componentGroup->name ?? '-' }} | Date: {{ $task->date_problem ? $task->date_problem->format('d/m/Y H:i') : '-' }}
                  </div>
                </td>
              @endif
              <td class="align-top">
                <div class="fw-semibold">{{ $st->action }}</div>
                <div class="text-muted" style="font-size: 0.75rem;">
                  Date: {{ $st->date_action ? $st->date_action->format('d/m/Y H:i') : '-' }} | Status: <span class="badge bg-secondary py-0">{{ $st->status }}</span>
                </div>
              </td>
              <td class="align-top">
                @forelse($st->manpower as $mp)
                  <div class="badge bg-azure-lt text-body mb-1 d-block text-start">• {{ $mp->mechanic->nama_lengkap ?? '-' }}</div>
                @empty
                  <span class="text-muted small">-</span>
                @endforelse
              </td>
              <td class="align-top">
                @forelse($st->parts as $sp)
                  <div class="small fw-semibold">• {{ $sp->part->part_number ?? '-' }} (x{{ $sp->qty }})</div>
                @empty
                  <span class="text-muted small">-</span>
                @endforelse
              </td>
              <td class="align-top">
                @forelse($st->tools as $toolTx)
                  <div class="small text-muted">• {{ $toolTx->toolTransaction->tool->name ?? 'Tool #' . $toolTx->tool_transaction_id }}</div>
                @empty
                  <span class="text-muted small">-</span>
                @endforelse
              </td>
            </tr>
            @endforeach
          @else
            <tr>
              <td class="text-center align-top fw-bold bg-light">{{ $tIdx+1 }}</td>
              <td class="align-top">
                <div class="fw-bold text-primary">{{ $task->problem }}</div>
              </td>
              <td colspan="4" class="text-muted text-center">Belum ada rincian SubTask (Action)</td>
            </tr>
          @endif
        @empty
          <tr>
            <td colspan="6" class="text-center text-muted py-3">Belum ada data Task / Problem yang dicatat pada Work Order ini.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <!-- HSE / K3 Documents Section -->
  <div class="mb-3 mt-4 pt-2 border-top">
    <div class="d-flex align-items-center justify-content-between mb-2 pb-1 border-bottom">
        <div class="fw-bold text-body small text-uppercase">6. Dokumen Keselamatan (HSE / K3)</div>
        <div class="d-print-none gap-2 d-flex">
            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modal-jsa">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-clipboard-check" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2" /><path d="M9 3m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z" /><path d="M9 14l2 2l4 -4" /></svg>
                Buat JSA
            </button>
            <button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#modal-ptw">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-file-certificate" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M5 8v-3a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2h-5" /><path d="M6 14m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" /><path d="M4.5 17l-1.5 5l3 -1.5l3 1.5l-1.5 -5" /></svg>
                Buat Permit (PTW)
            </button>
            <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#modal-loto">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-lock" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 13a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v6a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2v-6z" /><path d="M11 16a1 1 0 1 0 2 0a1 1 0 0 0 -2 0" /><path d="M8 11v-4a4 4 0 1 1 8 0v4" /></svg>
                Tambah LOTO
            </button>
        </div>
    </div>
    
    <div class="row g-2">
        <!-- JSA List -->
        <div class="col-4">
            <div class="border rounded p-2 h-100 bg-body-tertiary">
                <div class="fw-bold small text-muted mb-2">JSA (Job Safety Analysis)</div>
                @forelse($workOrder->jsas as $jsa)
                    <div class="card mb-2 shadow-sm border-0">
                        <div class="card-body p-2 small">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="fw-bold">JSA #{{ $jsa->id }}</span>
                                <span class="badge {{ $jsa->status == 'Approved' ? 'bg-success' : 'bg-secondary' }}">{{ $jsa->status }}</span>
                            </div>
                            <div class="text-muted mb-1">{{ $jsa->steps->count() }} Langkah Kerja</div>
                            @if($jsa->document_scan)
                            <div class="mb-1">
                                <a href="{{ Storage::url($jsa->document_scan) }}" target="_blank" class="badge bg-blue text-white text-decoration-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-file-download" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><path d="M12 17v-6" /><path d="M9.5 14.5l2.5 2.5l2.5 -2.5" /></svg>
                                    Lihat Dokumen
                                </a>
                            </div>
                            @endif
                            <div class="d-print-none text-end mt-2">
                                @if($jsa->status != 'Approved' && auth()->user()->can('approve_hse'))
                                <form action="{{ route('hse.jsa.approve', $jsa) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button class="btn btn-xs btn-success">Approve</button>
                                </form>
                                @endif
                                <form action="{{ route('hse.jsa.destroy', $jsa) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus JSA?');">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-xs btn-outline-danger">Hapus</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-muted small fst-italic">Belum ada JSA.</div>
                @endforelse
            </div>
        </div>

        <!-- PTW List -->
        <div class="col-4">
            <div class="border rounded p-2 h-100 bg-body-tertiary">
                <div class="fw-bold small text-muted mb-2">Permit to Work (PTW)</div>
                @forelse($workOrder->ptws as $ptw)
                    <div class="card mb-2 shadow-sm border-0">
                        <div class="card-body p-2 small">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="fw-bold text-info">{{ $ptw->permit_type }}</span>
                                <span class="badge {{ $ptw->status == 'Approved' ? 'bg-success' : 'bg-secondary' }}">{{ $ptw->status }}</span>
                            </div>
                            <div class="text-muted mb-1">Valid: {{ $ptw->valid_from->format('d/m H:i') }} - {{ $ptw->valid_to->format('d/m H:i') }}</div>
                            @if($ptw->document_scan)
                            <div class="mb-1">
                                <a href="{{ Storage::url($ptw->document_scan) }}" target="_blank" class="badge bg-blue text-white text-decoration-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-file-download" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><path d="M12 17v-6" /><path d="M9.5 14.5l2.5 2.5l2.5 -2.5" /></svg>
                                    Lihat Dokumen
                                </a>
                            </div>
                            @endif
                            <div class="d-print-none text-end mt-2">
                                @if($ptw->status != 'Approved' && auth()->user()->can('approve_hse'))
                                <form action="{{ route('hse.ptw.approve', $ptw) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button class="btn btn-xs btn-success">Approve</button>
                                </form>
                                @endif
                                <form action="{{ route('hse.ptw.destroy', $ptw) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus PTW?');">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-xs btn-outline-danger">Hapus</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-muted small fst-italic">Belum ada Permit.</div>
                @endforelse
            </div>
        </div>

        <!-- LOTO List -->
        <div class="col-4">
            <div class="border rounded p-2 h-100 bg-body-tertiary">
                <div class="fw-bold small text-muted mb-2">LOTO (Lockout/Tagout)</div>
                @forelse($workOrder->lotos as $loto)
                    <div class="card mb-2 shadow-sm border-0">
                        <div class="card-body p-2 small">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="fw-bold text-danger">{{ $loto->isolation_point }}</span>
                                <span class="badge {{ $loto->status == 'Active' ? 'bg-danger' : 'bg-secondary' }}">{{ $loto->status }}</span>
                            </div>
                            <div class="text-muted mb-1">
                                Lock: {{ $loto->lock_number ?? '-' }} | Tag: {{ $loto->tag_number ?? '-' }}
                            </div>
                            <div class="text-muted mb-1" style="font-size: 0.7rem">
                                By: {{ $loto->applier->name ?? '-' }} ({{ $loto->applied_at->format('d/m H:i') }})
                            </div>
                            @if($loto->status == 'Active')
                            <div class="d-print-none text-end mt-2">
                                <form action="{{ route('hse.loto.remove', $loto) }}" method="POST" class="d-inline" onsubmit="return confirm('Lepas gembok LOTO ini?');">
                                    @csrf
                                    <button class="btn btn-xs btn-warning">Lepas (Remove)</button>
                                </form>
                            </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-muted small fst-italic">Belum ada LOTO.</div>
                @endforelse
            </div>
        </div>
    </div>
  </div>

  <!-- Signatures Section (Tanda Tangan 4 Kolom) -->
  <div class="mt-4 pt-2 border-top">
    <div class="fw-bold text-body mb-2 small text-uppercase">7. Lembar Pengesahan & Persetujuan (Signatures)</div>
    
    @php
      $sigs = $workOrder->signatures->keyBy('sign_type');
      
      $signatureConfig = [
        'dikerjakan' => [
            'title' => 'Dikerjakan Oleh',
            'subtitle' => 'Mekanik / Tech',
            'allowed_roles' => ['Admin', 'Super Admin', 'User']
        ],
        'diperiksa' => [
            'title' => 'Diperiksa Oleh',
            'subtitle' => 'Foreman Maintenance',
            'allowed_roles' => ['Foreman']
        ],
        'ditinjau' => [
            'title' => 'Ditinjau Oleh',
            'subtitle' => 'Supervisor',
            'allowed_roles' => ['Supervisor']
        ],
        'disetujui' => [
            'title' => 'Disetujui Oleh',
            'subtitle' => 'Superintendent',
            'allowed_roles' => ['Superintendent', 'Manager']
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
      <div class="col-3">
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
                <input type="hidden" name="document_type" value="{{ get_class($workOrder) }}">
                <input type="hidden" name="document_id" value="{{ $workOrder->id }}">
                <input type="hidden" name="sign_type" value="{{ $type }}">
                <button type="submit" class="btn btn-sm btn-outline-primary" onclick="return confirm('Anda yakin ingin menandatangani dokumen ini?')">✍️ Tanda Tangani</button>
            </form>
            <span class="text-muted d-none d-print-block" style="font-size: 0.75rem;">( .................................... )</span>
            @else
            <span class="text-muted" style="font-size: 0.75rem;">( .................................... )</span>
            @endif
          </div>

          <div class="border-top pt-1 mt-1 text-start" style="font-size: 0.7rem;">
            @if($sig)
            <div>Nama: {{ Str::limit($sig->user->nama_lengkap ?? $sig->user->name, 15) }}</div>
            <div>Tgl: {{ $sig->created_at->format('d/m/Y') }}</div>
            @else
            <div>Nama: ________________</div>
            <div>Tgl: ____ / ____ / 2026</div>
            @endif
          </div>
        </div>
      </div>
      @endforeach
    </div>

  </div>

</div>

<!-- MODAL JSA -->
<div class="modal modal-blur fade" id="modal-jsa" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
      <form action="{{ route('hse.jsa.store', $workOrder) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title">Buat JSA (Job Safety Analysis)</h5>
          <div class="ms-auto">
             <a href="{{ route('hse.jsa.template', $workOrder) }}" target="_blank" class="btn btn-sm btn-outline-info">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-printer" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2" /><path d="M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4" /><path d="M7 13m0 2a2 2 0 0 1 2 -2h6a2 2 0 0 1 2 2v4a2 2 0 0 1 -2 2h-6a2 2 0 0 1 -2 -2z" /></svg>
                Download / Cetak Template
             </a>
          </div>
          <button type="button" class="btn-close ms-2" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="alert alert-info">
             <strong>Info:</strong> Anda dapat mencetak template kosong, mengisi manual, lalu mengunggah hasil scan JSA di sini. Langkah kerja digital menjadi opsional jika dokumen scan diunggah.
          </div>
          <div class="mb-3">
            <label class="form-label">Upload Dokumen Scan JSA (Opsional)</label>
            <input type="file" class="form-control" name="document_scan" accept=".pdf,image/*">
          </div>
          <div class="mb-3">
            <label class="form-label">Catatan Tambahan (Opsional)</label>
            <textarea class="form-control" name="notes" rows="2"></textarea>
          </div>
          <label class="form-label">Langkah Kerja & Identifikasi Bahaya (Opsional jika upload scan)</label>
          <div id="jsa-steps-container">
            <div class="row g-2 mb-2 jsa-step-row">
              <div class="col-md-4">
                <input type="text" class="form-control" name="steps[0][job_step]" placeholder="Uraian Langkah Kerja">
              </div>
              <div class="col-md-4">
                <input type="text" class="form-control" name="steps[0][potential_hazard]" placeholder="Potensi Bahaya">
              </div>
              <div class="col-md-4 d-flex gap-1">
                <input type="text" class="form-control" name="steps[0][control_measure]" placeholder="Tindakan Pengendalian">
                <button type="button" class="btn btn-icon btn-outline-danger btn-remove-jsa-step" tabindex="-1">
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-trash" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
                </button>
              </div>
            </div>
          </div>
          <button type="button" class="btn btn-sm btn-outline-primary mt-2" id="btn-add-jsa-step">+ Tambah Langkah Kerja</button>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn me-auto" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan JSA</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- MODAL PTW -->
<div class="modal modal-blur fade" id="modal-ptw" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <form action="{{ route('hse.ptw.store', $workOrder) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title">Buat Permit to Work (PTW)</h5>
          <div class="ms-auto">
             <a href="{{ route('hse.ptw.template', $workOrder) }}" target="_blank" class="btn btn-sm btn-outline-info" id="ptw-print-btn">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-printer" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2" /><path d="M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4" /><path d="M7 13m0 2a2 2 0 0 1 2 -2h6a2 2 0 0 1 2 2v4a2 2 0 0 1 -2 2h-6a2 2 0 0 1 -2 -2z" /></svg>
                Cetak Template
             </a>
          </div>
          <button type="button" class="btn-close ms-2" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="alert alert-info">
             <strong>Info:</strong> Pilih Tipe Permit lalu klik "Cetak Template" untuk print form fisik. Anda dapat mengunggah file scan di sini.
          </div>
          <div class="mb-3">
            <label class="form-label required">Tipe Permit</label>
            <select name="permit_type" id="ptw_permit_type" class="form-select" required>
              <option value="">-- Pilih Tipe --</option>
              <option value="Hot Work">Hot Work (Pekerjaan Panas/Las)</option>
              <option value="Confined Space">Confined Space (Ruang Terbatas)</option>
              <option value="Working at Height">Working at Height (Ketinggian)</option>
              <option value="Electrical Work">Electrical Work (Kelistrikan)</option>
              <option value="Cold Work">Cold Work</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Upload Dokumen Scan PTW (Opsional)</label>
            <input type="file" class="form-control" name="document_scan" accept=".pdf,image/*">
          </div>
          <div class="row g-2 mb-3">
            <div class="col-6">
              <label class="form-label required">Berlaku Dari</label>
              <input type="datetime-local" class="form-control" name="valid_from" required>
            </div>
            <div class="col-6">
              <label class="form-label required">Berlaku Sampai</label>
              <input type="datetime-local" class="form-control" name="valid_to" required>
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label">Catatan / Deskripsi Tambahan</label>
            <textarea class="form-control" name="notes" rows="2"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn me-auto" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-info">Simpan Permit</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- MODAL LOTO -->
<div class="modal modal-blur fade" id="modal-loto" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <form action="{{ route('hse.loto.store', $workOrder) }}" method="POST">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title">Pasang LOTO (Lockout/Tagout)</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label required">Titik Isolasi (Isolation Point)</label>
            <input type="text" class="form-control" name="isolation_point" placeholder="Misal: Battery Disconnect Switch" required>
          </div>
          <div class="row g-2">
            <div class="col-6">
              <label class="form-label">Nomor Gembok (Lock No)</label>
              <input type="text" class="form-control" name="lock_number" placeholder="Opsional">
            </div>
            <div class="col-6">
              <label class="form-label">Nomor Tag (Tag No)</label>
              <input type="text" class="form-control" name="tag_number" placeholder="Opsional">
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn me-auto" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-danger">Simpan LOTO</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  document.addEventListener("DOMContentLoaded", function() {
    let jsaStepCount = 1;
    const btnAddJsaStep = document.getElementById('btn-add-jsa-step');
    const jsaStepsContainer = document.getElementById('jsa-steps-container');

    if (btnAddJsaStep && jsaStepsContainer) {
      btnAddJsaStep.addEventListener('click', function() {
        const row = document.createElement('div');
        row.className = 'row g-2 mb-2 jsa-step-row';
        row.innerHTML = `
          <div class="col-md-4">
            <input type="text" class="form-control" name="steps[${jsaStepCount}][job_step]" placeholder="Uraian Langkah Kerja" required>
          </div>
          <div class="col-md-4">
            <input type="text" class="form-control" name="steps[${jsaStepCount}][potential_hazard]" placeholder="Potensi Bahaya" required>
          </div>
          <div class="col-md-4 d-flex gap-1">
            <input type="text" class="form-control" name="steps[${jsaStepCount}][control_measure]" placeholder="Tindakan Pengendalian" required>
            <button type="button" class="btn btn-icon btn-outline-danger btn-remove-jsa-step" tabindex="-1">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-trash" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
            </button>
          </div>
        `;
        jsaStepsContainer.appendChild(row);
        jsaStepCount++;
      });

      jsaStepsContainer.addEventListener('click', function(e) {
        if (e.target.closest('.btn-remove-jsa-step')) {
          const rows = jsaStepsContainer.querySelectorAll('.jsa-step-row');
          if (rows.length > 1) {
            e.target.closest('.jsa-step-row').remove();
          } else {
            alert('Minimal harus ada 1 langkah kerja.');
          }
        }
      });
    }

    const ptwPermitTypeSelect = document.getElementById('ptw_permit_type');
    const ptwPrintBtn = document.getElementById('ptw-print-btn');
    if (ptwPermitTypeSelect && ptwPrintBtn) {
        const baseUrl = ptwPrintBtn.href.split('?')[0];
        ptwPermitTypeSelect.addEventListener('change', function() {
            if (this.value) {
                ptwPrintBtn.href = baseUrl + '?type=' + encodeURIComponent(this.value);
            } else {
                ptwPrintBtn.href = baseUrl;
            }
        });
    }
  });

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
