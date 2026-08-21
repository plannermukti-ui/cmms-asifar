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

  /* =========================================================
     DARK MODE — pastikan laporan WO tetap terbaca
     ========================================================= */
  [data-bs-theme="dark"] .wo-report-card .app-address {
    color: #94a3b8 !important;
  }
  [data-bs-theme="dark"] .table-report-header th {
    background-color: rgba(15, 23, 42, 0.85) !important;
    color: #fbbf24 !important;
    border-color: rgba(245, 158, 11, 0.3) !important;
  }
  [data-bs-theme="dark"] .table-condensed td.bg-light {
    background-color: rgba(15, 23, 42, 0.85) !important;
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
    .wo-report-card .table-condensed td.bg-light {
      background-color: #f1f5f9 !important;
      color: #000 !important;
    }
    .card.wo-report-card .signature-box,
    .card.wo-report-card .bg-body-tertiary {
      background-color: #f8fafc !important;
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
            <div class="app-address" style="font-size: 0.75rem; color: #555; margin-bottom: 2px;">{{ $appAddress }}</div>
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
        </table>
      </div>
    </div>

    <!-- Box 4: Klasifikasi -->
    <div class="col-3">
      <div class="border rounded p-2 bg-body-tertiary h-100">
        <div class="fw-bold text-teal border-bottom pb-1 mb-1 small text-uppercase">4. Klasifikasi</div>
        <table class="w-100 small">
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
        @php
          $rowNo = 1;
          $statusBadge = function ($status) {
              return match($status) {
                  'Open' => 'bg-blue-lt text-blue', 'Inprogress' => 'bg-yellow-lt text-dark', 'Completed' => 'bg-green-lt text-green',
                  'Cancel' => 'bg-red-lt text-red', 'Backlog' => 'bg-purple-lt text-purple', default => 'bg-secondary-lt text-secondary'
              };
          };
        @endphp
        @forelse($workOrder->tasks as $tIdx => $task)
          @if($task->subtasks->count() > 0)
            @foreach($task->subtasks as $sIdx => $st)
            <tr>
              @if($sIdx === 0)
                <td rowspan="{{ $task->subtasks->count() }}" class="text-center align-top fw-bold bg-light">{{ $tIdx+1 }}</td>
                <td rowspan="{{ $task->subtasks->count() }}" class="align-top">
                  <div class="fw-bold text-primary">{{ $task->problem }}</div>
                  <div class="mb-1">
                    <span class="badge {{ $statusBadge($task->status) }} py-0">{{ $task->status }}</span>
                  </div>
                  <div class="text-muted" style="font-size: 0.75rem;">
                    Comp: {{ $task->componentGroup->name ?? '-' }} | Date: {{ $task->date_problem ? $task->date_problem->format('d/m/Y H:i') : '-' }}
                  </div>
                </td>
              @endif
              <td class="align-top">
                <div class="fw-semibold">{{ $st->action }}</div>
                <div class="text-muted" style="font-size: 0.75rem;">
                  Action: {{ $st->date_action ? $st->date_action->format('d/m/Y H:i') : '-' }} | Finish: {{ $st->date_finish ? $st->date_finish->format('d/m/Y H:i') : '-' }} | Durasi: {{ $st->duration_hours ?? '-' }} hrs | Breakdown: {{ $st->breakdownType->name ?? '-' }} | Status: <span class="badge {{ $statusBadge($st->status) }} py-0">{{ $st->status }}</span>
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
                  <div class="mb-2">
                    <div class="small fw-semibold">• {{ $sp->part->part_number ?? '-' }}{{ $sp->part->part_description ? ' - ' . $sp->part->part_description : '' }} (x{{ $sp->qty }})</div>
                    <div style="font-size: 0.7rem; padding-left: 10px;">
                      @if($sp->part_status == 'Replace')
                        <span class="badge bg-success-lt text-success py-0">Replace</span>
                      @elseif($sp->part_status == 'Repair')
                        <span class="badge bg-warning-lt text-warning py-0">Repair</span>
                      @elseif($sp->part_status == 'Order Part')
                        <span class="badge bg-blue-lt text-blue py-0">Order Part</span>
                        @if($sp->mol_pr) <span class="text-muted ms-1">MOL/PR: {{ $sp->mol_pr }}</span> @endif
                        @if($sp->order_status) <span class="badge bg-secondary-lt py-0 ms-1">{{ $sp->order_status }}</span> @endif
                      @elseif($sp->part_status == 'Swap / Canibal')
                        <span class="badge bg-purple-lt text-purple py-0">Swap / Canibal</span>
                        <div class="text-muted mt-1">
                          @if($sp->swap_type && $sp->swapUnit) 
                            {{ $sp->swap_type }}: {{ $sp->swapUnit->nomor_unit }} 
                          @elseif($sp->swap_type) 
                            {{ $sp->swap_type }} 
                          @elseif($sp->swapUnit) 
                            Swap Unit: {{ $sp->swapUnit->nomor_unit }} 
                          @endif
                          @if($sp->mol_pr) | PR/MOL: {{ $sp->mol_pr }} @endif
                          @if($sp->swap_status) | Status: <span class="badge bg-secondary-lt py-0">{{ $sp->swap_status }}</span> @endif
                        </div>
                        @if($sp->swap_remarks) <div class="text-muted mt-1">Remarks: {{ $sp->swap_remarks }}</div> @endif
                      @endif
                    </div>
                  </div>
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
                <div class="mt-1">
                  <span class="badge {{ $statusBadge($task->status) }} py-0">{{ $task->status }}</span>
                </div>
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
                                <button type="button" class="btn btn-xs btn-outline-primary" onclick="loadJsaForEdit({{ $jsa->id }})">Edit</button>
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
                                <button type="button" class="btn btn-xs btn-outline-info" onclick="loadPtwForEdit({{ $ptw->id }})">Edit</button>
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
                            <div class="d-print-none text-end mt-2">
                                <button type="button" class="btn btn-xs btn-outline-danger" onclick="loadLotoForEdit({{ $loto->id }})">Edit</button>
                                @if($loto->status == 'Active')
                                <form action="{{ route('hse.loto.remove', $loto) }}" method="POST" class="d-inline" onsubmit="return confirm('Lepas gembok LOTO ini?');">
                                    @csrf
                                    <button class="btn btn-xs btn-warning">Lepas (Remove)</button>
                                </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-muted small fst-italic">Belum ada LOTO.</div>
                @endforelse
            </div>
        </div>
    </div>
  </div>

  <!-- Ringkasan Durasi & Waktu Tanggap -->
  <div class="mt-4 pt-2 border-top">
    <div class="fw-bold text-body mb-2 small text-uppercase">7. Ringkasan Durasi & Waktu Tanggap (Duration Summary)</div>
    @php
      $fmtHrs = fn($v) => $v !== null ? number_format((float) $v, 2) . ' Hrs' : '-';
    @endphp
    <div class="row g-2">
      <!-- Kiri: Rekonsiliasi Durasi -->
      <div class="col-md-6">
        <div class="border rounded p-2 h-100 bg-body-tertiary">
          <div class="fw-bold small text-muted mb-2">Rekonsiliasi Durasi</div>
          <table class="w-100 small">
            <tr><td class="text-muted">Waktu BD:</td><td class="fw-bold text-end">{{ $workOrder->waktu_bd ? $workOrder->waktu_bd->format('d/m/y H:i') : '-' }}</td></tr>
            <tr><td class="text-muted">Respontime (Date Problem Task 1 − Waktu BD):</td><td class="fw-bold text-end">{{ $fmtHrs($summary['respontime']) }}</td></tr>
            <tr><td class="text-muted">Total Durasi Subtask:</td><td class="fw-bold text-end">{{ $fmtHrs($summary['adjusted_total_subtask']) }}</td></tr>
            <tr><td class="text-muted">No Action:</td><td class="fw-bold text-end">{{ $fmtHrs($summary['no_action']) }}</td></tr>
            <tr class="border-top"><td class="fw-bold">Durasi (Hrs):</td><td class="fw-bold text-end text-danger">{{ $fmtHrs($summary['durasi_hrs']) }}</td></tr>
          </table>
          <div class="mt-1 pt-1 border-top small text-muted">
            Rumus: <b>Durasi (Hrs) = Respontime + Total Durasi Subtask + No Action</b>
            @if($summary['durasi_hrs'] !== null && !$summary['overrun'])
              <span class="text-success fw-bold">✓ Seimbang</span>
            @elseif($summary['durasi_hrs'] !== null && $summary['overrun'])
              <span class="text-warning fw-bold">⚠ Disetarakan (lihat rincian di bawah)</span>
            @endif
          </div>
        </div>
      </div>

      <!-- Kanan: Durasi per Tipe Breakdown -->
      <div class="col-md-6">
        <div class="border rounded p-2 h-100 bg-body-tertiary">
          <div class="fw-bold small text-muted mb-2">Durasi Task per Tipe Breakdown (per Subtask)</div>
          <table class="w-100 small">
            @forelse($summary['by_breakdown_type'] as $group)
            <tr>
              <td class="text-muted">{{ $group['label'] }}</td>
              <td class="fw-bold text-end">{{ $fmtHrs($group['adjusted_total']) }}</td>
              <td class="text-muted text-end" style="width:44px;">{{ $group['count'] }}x</td>
            </tr>
            @empty
            <tr><td class="text-muted small fst-italic">Belum ada data subtask.</td></tr>
            @endforelse
            <tr class="border-top">
              <td class="fw-bold">Total</td>
              <td class="fw-bold text-end">{{ $fmtHrs($summary['adjusted_total_subtask']) }}</td>
              <td></td>
            </tr>
          </table>
        </div>
      </div>
    </div>

    <!-- Rincian per Subtask -->
    @if(count($summary['subtasks']) > 0)
    <div class="mt-2">
      @foreach($summary['warnings'] as $warning)
      <div class="alert alert-warning py-2 small mb-2"><strong>⚠ Perhatian:</strong> {{ $warning }}</div>
      @endforeach
      <div class="table-responsive border rounded bg-body-tertiary">
        <table class="table table-condensed mb-0">
          <thead class="table-report-header">
            <tr>
              <th class="text-center">No</th>
              <th>Task</th>
              <th>SubTask / Action</th>
              <th>Tipe Breakdown</th>
              <th class="text-center">Date Action</th>
              <th class="text-center">Date Finish*</th>
              <th class="text-end">Durasi (Hrs)</th>
              @if($summary['overrun'])
              <th class="text-end">Durasi Disetarakan</th>
              <th class="text-center">Date Action Disetarakan</th>
              @endif
            </tr>
          </thead>
          <tbody>
            @foreach($summary['subtasks'] as $i => $row)
            <tr>
              <td class="text-center">{{ $i + 1 }}</td>
              <td>{{ $row['task_no'] }}</td>
              <td>{{ $row['action'] ?? '-' }}</td>
              <td>{{ $row['breakdown_type'] ? (($row['breakdown_code'] ? $row['breakdown_code'] . ' - ' : '') . $row['breakdown_type']) : 'Tanpa Tipe' }}</td>
              <td class="text-center">{{ $row['date_action'] ? $row['date_action']->format('d/m/Y H:i') : '-' }}</td>
              <td class="text-center">{{ $row['effective_finish'] ? $row['effective_finish']->format('d/m/Y H:i') : '-' }}</td>
              <td class="text-end">{{ $row['duration'] > 0 ? number_format($row['duration'], 2) : '-' }}</td>
              @if($summary['overrun'])
              <td class="text-end fw-bold text-warning">{{ number_format($row['adjusted_duration'], 2) }}</td>
              <td class="text-center text-warning">{{ $row['adjusted_date_action'] ? $row['adjusted_date_action']->format('d/m/Y H:i') : '-' }}</td>
              @endif
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      <div class="small text-muted mt-1">
        @if($summary['overrun'])
        * Tanggal Finish efektif: subtask yang belum memiliki Date Finish memakai Date Finish paling akhir dari subtask lain yang sudah terisi. <b class="text-warning">Date Action setiap subtask dikurangi proporsional</b> agar Durasi (Hrs) = Respontime + Total Durasi Subtask + No Action tetap seimbang.
        @else
        * Tanggal Finish efektif: subtask yang belum memiliki Date Finish memakai Date Finish paling akhir dari subtask lain yang sudah terisi.
        @endif
      </div>
    </div>
    @endif
  </div>

  <!-- Time Frame (Timeline) Section -->
  <div class="mt-4 pt-2 border-top d-print-none">
    <div class="fw-bold text-body mb-2 small text-uppercase">Time Frame (Timeline Progress)</div>
    @php
        $timelineItems = [];
        $nowTs = now()->timestamp * 1000;
        $minTimestamp = null;
        
        $unitNo = $workOrder->unit->nomor_unit ?? 'WO';
        
        // Line 1: Work Order
        if ($workOrder->waktu_bd) {
            $startTs = \Carbon\Carbon::parse($workOrder->waktu_bd)->timestamp * 1000;
            $endTs = $workOrder->waktu_rfu ? \Carbon\Carbon::parse($workOrder->waktu_rfu)->timestamp * 1000 : $nowTs;
            if ($endTs <= $startTs) $endTs = $startTs + (15 * 60 * 1000);
            
            $minTimestamp = $startTs;
            
            $timelineItems[] = [
                'x' => 'Unit: ' . $unitNo,
                'y' => [$startTs, $endTs],
                'fillColor' => '#0284c7',
                'label_type' => 'Work Order',
                'remarks' => 'Waktu BD s/d ' . ($workOrder->waktu_rfu ? 'Waktu RFU' : 'Sekarang (Berjalan)')
            ];
        }
        
        // Line 2 & 3: Tasks and Subtasks
        foreach ($workOrder->tasks as $idx => $task) {
            $taskNum = $idx + 1;
            $taskLabel = $task->problem ?: "Task {$taskNum}";
            
            // Subtasks processing first to determine task max finish
            $subtaskItemsForTask = [];
            $highestSubtaskFinishTs = null;
            
            foreach ($task->subtasks as $sidx => $st) {
                $subNum = $sidx + 1;
                $subLabel = $st->action ?: "Subtask {$taskNum}.{$subNum}";
                
                // Determine Subtask Start
                if ($st->date_action) {
                    $stStartTs = \Carbon\Carbon::parse($st->date_action)->timestamp * 1000;
                } elseif ($st->date_finish && $st->duration_hours) {
                    $stStartTs = \Carbon\Carbon::parse($st->date_finish)->subHours((float)$st->duration_hours)->timestamp * 1000;
                } elseif ($task->date_problem) {
                    $stStartTs = \Carbon\Carbon::parse($task->date_problem)->timestamp * 1000;
                } elseif ($workOrder->waktu_bd) {
                    $stStartTs = \Carbon\Carbon::parse($workOrder->waktu_bd)->timestamp * 1000;
                } else {
                    $stStartTs = $nowTs;
                }
                
                // Determine Subtask End
                if ($st->date_finish) {
                    $stEndTs = \Carbon\Carbon::parse($st->date_finish)->timestamp * 1000;
                } elseif ($st->date_action && $st->duration_hours) {
                    $stEndTs = \Carbon\Carbon::parse($st->date_action)->addHours((float)$st->duration_hours)->timestamp * 1000;
                } elseif ($st->date_action) {
                    $stEndTs = $stStartTs;
                } elseif ($workOrder->waktu_rfu) {
                    $stEndTs = \Carbon\Carbon::parse($workOrder->waktu_rfu)->timestamp * 1000;
                } else {
                    $stEndTs = $nowTs;
                }
                
                // Guarantee minimum 15-minute visual bar width if start >= end
                if ($stEndTs <= $stStartTs) {
                    $stEndTs = $stStartTs + (15 * 60 * 1000);
                }
                
                if (!$highestSubtaskFinishTs || $stEndTs > $highestSubtaskFinishTs) {
                    $highestSubtaskFinishTs = $stEndTs;
                }
                
                $subtaskItemsForTask[] = [
                    'x' => $subLabel,
                    'y' => [$stStartTs, $stEndTs],
                    'fillColor' => '#10b981',
                    'label_type' => "Subtask {$taskNum}.{$subNum} (Action)",
                    'remarks' => $subLabel
                ];
            }
            
            // Task Line
            $taskStartTs = $task->date_problem 
                ? \Carbon\Carbon::parse($task->date_problem)->timestamp * 1000 
                : ($workOrder->waktu_bd ? \Carbon\Carbon::parse($workOrder->waktu_bd)->timestamp * 1000 : $nowTs);
                
            $taskEndTs = $highestSubtaskFinishTs ?: ($workOrder->waktu_rfu ? \Carbon\Carbon::parse($workOrder->waktu_rfu)->timestamp * 1000 : $nowTs);
            if ($taskEndTs <= $taskStartTs) {
                $taskEndTs = $taskStartTs + (15 * 60 * 1000);
            }
            
            $timelineItems[] = [
                'x' => $taskLabel,
                'y' => [$taskStartTs, $taskEndTs],
                'fillColor' => '#f59e0b',
                'label_type' => "Task {$taskNum} (Line BD)",
                'remarks' => $taskLabel
            ];
            
            // Add Subtasks after their Task Line
            foreach ($subtaskItemsForTask as $stItem) {
                $timelineItems[] = $stItem;
            }
        }
        
        // ---- No Action (Idle Gaps) Calculation ----
        $woStartTs = $workOrder->waktu_bd ? \Carbon\Carbon::parse($workOrder->waktu_bd)->timestamp * 1000 : null;
        $woEndTs = $workOrder->waktu_rfu ? \Carbon\Carbon::parse($workOrder->waktu_rfu)->timestamp * 1000 : $nowTs;
        
        if ($woStartTs && $woEndTs > $woStartTs) {
            $workIntervals = [];
            foreach ($timelineItems as $item) {
                if ($item['fillColor'] === '#10b981') { // Green subtasks only
                    $workIntervals[] = [$item['y'][0], $item['y'][1]];
                }
            }
            
            usort($workIntervals, fn($a, $b) => $a[0] <=> $b[0]);
            
            $mergedWork = [];
            foreach ($workIntervals as $interval) {
                if (empty($mergedWork)) {
                    $mergedWork[] = $interval;
                } else {
                    $lastIdx = count($mergedWork) - 1;
                    if ($interval[0] <= $mergedWork[$lastIdx][1]) {
                        $mergedWork[$lastIdx][1] = max($mergedWork[$lastIdx][1], $interval[1]);
                    } else {
                        $mergedWork[] = $interval;
                    }
                }
            }
            
            $noActionGaps = [];
            $currentCursor = $woStartTs;
            
            foreach ($mergedWork as $block) {
                if ($block[0] > $currentCursor) {
                    $gapDurMinutes = ($block[0] - $currentCursor) / (1000 * 60);
                    if ($gapDurMinutes >= 1) {
                        $noActionGaps[] = [$currentCursor, $block[0]];
                    }
                }
                $currentCursor = max($currentCursor, $block[1]);
            }
            
            if ($woEndTs > $currentCursor) {
                $gapDurMinutes = ($woEndTs - $currentCursor) / (1000 * 60);
                if ($gapDurMinutes >= 1) {
                    $noActionGaps[] = [$currentCursor, $woEndTs];
                }
            }
            
            foreach ($noActionGaps as $gIdx => $gap) {
                $gapHrs = round(($gap[1] - $gap[0]) / 3600000, 2);
                $timelineItems[] = [
                    'x' => 'No Action',
                    'y' => [$gap[0], $gap[1]],
                    'fillColor' => '#ef4444',
                    'label_type' => 'No Action (Idle)',
                    'remarks' => "Tidak Ada Aksi / Idle ({$gapHrs} Jam)"
                ];
            }
        }
    @endphp

    @if(count($timelineItems) > 0)
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <div class="border rounded bg-body-tertiary p-3">
        <div id="chart-timeline"></div>
    </div>
    @else
    <div class="alert alert-info py-2 small mb-0">Belum ada data waktu yang cukup untuk menampilkan Time Frame.</div>
    @endif
  </div>

  <!-- Signatures Section (Tanda Tangan 4 Kolom) -->
  <div class="mt-4 pt-2 border-top">
    <div class="fw-bold text-body mb-2 small text-uppercase">8. Lembar Pengesahan & Persetujuan (Signatures)</div>
    
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

<!-- Ruang Diskusi & Komentar Bergaya Postingan -->
<div class="card mt-4 shadow-sm" id="wo-discussion-card" style="background-color: #f0f4f8; border-top: 3px solid #206bc4;">
  <div class="card-header border-0 pb-1">
    <h3 class="card-title text-primary d-flex align-items-center gap-2 m-0">
      <svg class="icon" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M21 14l-3 -3h-7a1 1 0 0 1 -1 -1v-6a1 1 0 0 1 1 -1h9a1 1 0 0 1 1 1v10" /><path d="M14 15v2a1 1 0 0 1 -1 1h-7l-3 3v-10a1 1 0 0 1 1 -1h2" /></svg>
      Ruang Diskusi Tim
    </h3>
  </div>
  <div class="card-body pt-2">
    <!-- Buat Postingan Baru -->
    <div class="card mb-4 shadow-sm border-0 rounded-3">
      <div class="card-body p-3">
        <div class="d-flex align-items-center mb-2">
          @if(auth()->user()->avatar_url)
            <span class="avatar avatar-sm rounded-circle me-2" style="background-image: url('{{ auth()->user()->avatar_url }}')"></span>
          @else
            <span class="avatar avatar-sm rounded-circle bg-azure-lt me-2">{{ substr(auth()->user()->nama_lengkap, 0, 1) }}</span>
          @endif
          <h4 class="card-title m-0">Buat Topik / Pernyataan Baru</h4>
        </div>
        <form id="woDiscussionForm" data-no-loader>
          <textarea id="woDiscussionInput" class="form-control mb-2 bg-light border-0" placeholder="Bagikan pembaruan atau pernyataan terkait Work Order ini..." rows="2" style="resize:none;" required></textarea>
          
          <div id="woDiscussionAttachmentPreview" class="mb-2 d-none p-2 border rounded bg-white small d-flex justify-content-between align-items-center">
            <span id="woDiscussionAttachmentName" class="text-truncate"></span>
            <button type="button" class="btn-close" id="woDiscussionAttachmentRemove"></button>
          </div>
          <input type="file" id="woDiscussionAttachment" class="d-none" accept="image/*,.pdf,.doc,.docx,.xls,.xlsx">

          <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-1">
              <button type="button" class="btn btn-sm btn-outline-secondary" onclick="document.getElementById('woDiscussionAttachment').click()" title="Lampirkan File (Foto max 2MB, Dokumen max 10MB)">
                <svg class="icon m-0" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M15 7l-6.5 6.5a1.5 1.5 0 0 0 3 3l6.5 -6.5a3 3 0 0 0 -6 -6l-6.5 6.5a4.5 4.5 0 0 0 9 9l6.5 -6.5" /></svg>
                <span class="d-none d-sm-inline ms-1">Lampirkan</span>
              </button>
              @foreach(['👍', '✅', '⚠️', '🛠️'] as $emo)
                <button type="button" class="btn btn-xs btn-ghost-secondary border-0 p-1 fs-5 btn-wo-emo text-decoration-none" data-target="woDiscussionInput" data-emoji="{{ $emo }}">{{ $emo }}</button>
              @endforeach
            </div>
            <button type="submit" class="btn btn-primary btn-sm px-4 rounded-pill fw-bold shadow-sm" id="woDiscussionSubmit">Posting</button>
          </div>
        </form>
      </div>
    </div>

  <!-- Daftar Postingan (Feed) -->
  <div id="woDiscussionLoading" class="text-center text-muted small my-3" style="display: none;">
    <div class="spinner-border spinner-border-sm" role="status"></div> Memuat diskusi...
  </div>
  <div id="woDiscussionMessages" class="d-flex flex-column gap-3">
    <!-- Postingan akan di-render di sini via JS -->
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

<!-- MODAL EDIT JSA -->
<div class="modal modal-blur fade" id="modal-edit-jsa" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
      <form id="edit-jsa-form" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="modal-header">
          <h5 class="modal-title">Edit JSA (Job Safety Analysis)</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Upload Dokumen Scan JSA (Opsional)</label>
            <input type="file" class="form-control" name="document_scan" accept=".pdf,image/*">
            <div id="edit-jsa-current-doc" class="mt-1"></div>
          </div>
          <div class="mb-3">
            <label class="form-label">Catatan Tambahan (Opsional)</label>
            <textarea class="form-control" name="notes" rows="2" id="edit-jsa-notes"></textarea>
          </div>
          <label class="form-label">Langkah Kerja & Identifikasi Bahaya</label>
          <div id="edit-jsa-steps-container">
            <!-- Steps will be loaded dynamically -->
          </div>
          <button type="button" class="btn btn-sm btn-outline-primary mt-2" id="btn-add-edit-jsa-step">+ Tambah Langkah Kerja</button>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn me-auto" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- MODAL EDIT PTW -->
<div class="modal modal-blur fade" id="modal-edit-ptw" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <form id="edit-ptw-form" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="modal-header">
          <h5 class="modal-title">Edit Permit to Work (PTW)</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label required">Tipe Permit</label>
            <select name="permit_type" id="edit-ptw-permit-type" class="form-select" required>
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
            <div id="edit-ptw-current-doc" class="mt-1"></div>
          </div>
          <div class="row g-2 mb-3">
            <div class="col-6">
              <label class="form-label required">Berlaku Dari</label>
              <input type="datetime-local" class="form-control" name="valid_from" id="edit-ptw-valid-from" required>
            </div>
            <div class="col-6">
              <label class="form-label required">Berlaku Sampai</label>
              <input type="datetime-local" class="form-control" name="valid_to" id="edit-ptw-valid-to" required>
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label">Catatan / Deskripsi Tambahan</label>
            <textarea class="form-control" name="notes" rows="2" id="edit-ptw-notes"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn me-auto" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-info">Simpan Perubahan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- MODAL EDIT LOTO -->
<div class="modal modal-blur fade" id="modal-edit-loto" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <form id="edit-loto-form" method="POST">
        @csrf
        @method('PUT')
        <div class="modal-header">
          <h5 class="modal-title">Edit LOTO (Lockout/Tagout)</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label required">Titik Isolasi (Isolation Point)</label>
            <input type="text" class="form-control" name="isolation_point" id="edit-loto-isolation-point" placeholder="Misal: Battery Disconnect Switch" required>
          </div>
          <div class="row g-2">
            <div class="col-6">
              <label class="form-label">Nomor Gembok (Lock No)</label>
              <input type="text" class="form-control" name="lock_number" id="edit-loto-lock-number" placeholder="Opsional">
            </div>
            <div class="col-6">
              <label class="form-label">Nomor Tag (Tag No)</label>
              <input type="text" class="form-control" name="tag_number" id="edit-loto-tag-number" placeholder="Opsional">
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn me-auto" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-danger">Simpan Perubahan</button>
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

    // Edit JSA step add button
    let editJsaStepCount = 0;
    const btnAddEditJsaStep = document.getElementById('btn-add-edit-jsa-step');
    const editJsaStepsContainer = document.getElementById('edit-jsa-steps-container');
    if (btnAddEditJsaStep && editJsaStepsContainer) {
      btnAddEditJsaStep.addEventListener('click', function() {
        addEditJsaStep({ job_step: '', potential_hazard: '', control_measure: '' });
      });
    }

    // Edit JSA form submit
    const editJsaForm = document.getElementById('edit-jsa-form');
    if (editJsaForm) {
      editJsaForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        fetch(this.action, {
          method: 'POST',
          body: formData
        }).then(r => r.json()).then(data => {
          if (data.success) {
            location.reload();
          } else {
            alert('Gagal: ' + (data.message || 'Terjadi kesalahan'));
          }
        }).catch(() => location.reload());
      });
    }

    // Edit PTW form submit
    const editPtwForm = document.getElementById('edit-ptw-form');
    if (editPtwForm) {
      editPtwForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        fetch(this.action, {
          method: 'POST',
          body: formData
        }).then(r => r.json()).then(data => {
          if (data.success) {
            location.reload();
          } else {
            alert('Gagal: ' + (data.message || 'Terjadi kesalahan'));
          }
        }).catch(() => location.reload());
      });
    }

    // Edit LOTO form submit
    const editLotoForm = document.getElementById('edit-loto-form');
    if (editLotoForm) {
      editLotoForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        fetch(this.action, {
          method: 'POST',
          body: formData
        }).then(r => r.json()).then(data => {
          if (data.success) {
            location.reload();
          } else {
            alert('Gagal: ' + (data.message || 'Terjadi kesalahan'));
          }
        }).catch(() => location.reload());
      });
    }
  });

  function addEditJsaStep(data) {
    const container = document.getElementById('edit-jsa-steps-container');
    const idx = container.children.length;
    const row = document.createElement('div');
    row.className = 'row g-2 mb-2 edit-jsa-step-row';
    row.innerHTML = `
      <div class="col-md-4">
        <input type="text" class="form-control" name="steps[${idx}][job_step]" placeholder="Uraian Langkah Kerja" value="${data.job_step || ''}" required>
      </div>
      <div class="col-md-4">
        <input type="text" class="form-control" name="steps[${idx}][potential_hazard]" placeholder="Potensi Bahaya" value="${data.potential_hazard || ''}" required>
      </div>
      <div class="col-md-4 d-flex gap-1">
        <input type="text" class="form-control" name="steps[${idx}][control_measure]" placeholder="Tindakan Pengendalian" value="${data.control_measure || ''}" required>
        <button type="button" class="btn btn-icon btn-outline-danger btn-remove-edit-jsa-step" tabindex="-1">
          <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-trash" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
        </button>
      </div>
    `;
    container.appendChild(row);
    row.querySelector('.btn-remove-edit-jsa-step').addEventListener('click', function() {
      const rows = container.querySelectorAll('.edit-jsa-step-row');
      if (rows.length > 1) {
        row.remove();
      } else {
        alert('Minimal harus ada 1 langkah kerja.');
      }
    });
  }

  function loadJsaForEdit(jsaId) {
    fetch('/hse/jsa/' + jsaId + '/edit')
      .then(r => r.json())
      .then(data => {
        const form = document.getElementById('edit-jsa-form');
        form.action = '/hse/jsa/' + jsaId;
        document.getElementById('edit-jsa-notes').value = data.notes || '';
        
        const docContainer = document.getElementById('edit-jsa-current-doc');
        if (data.document_scan) {
          docContainer.innerHTML = '<small class="text-muted">Dokumen saat ini: <a href="' + data.document_scan + '" target="_blank">Lihat</a></small>';
        } else {
          docContainer.innerHTML = '';
        }
        
        const stepsContainer = document.getElementById('edit-jsa-steps-container');
        stepsContainer.innerHTML = '';
        if (data.steps && data.steps.length > 0) {
          data.steps.forEach(step => addEditJsaStep(step));
        } else {
          addEditJsaStep({ job_step: '', potential_hazard: '', control_measure: '' });
        }
        
        const modal = new bootstrap.Modal(document.getElementById('modal-edit-jsa'));
        modal.show();
      })
      .catch(err => alert('Gagal memuat data JSA: ' + err));
  }

  function loadPtwForEdit(ptwId) {
    fetch('/hse/ptw/' + ptwId + '/edit')
      .then(r => r.json())
      .then(data => {
        const form = document.getElementById('edit-ptw-form');
        form.action = '/hse/ptw/' + ptwId;
        document.getElementById('edit-ptw-permit-type').value = data.permit_type || '';
        document.getElementById('edit-ptw-valid-from').value = data.valid_from || '';
        document.getElementById('edit-ptw-valid-to').value = data.valid_to || '';
        document.getElementById('edit-ptw-notes').value = data.notes || '';
        
        const docContainer = document.getElementById('edit-ptw-current-doc');
        if (data.document_scan) {
          docContainer.innerHTML = '<small class="text-muted">Dokumen saat ini: <a href="' + data.document_scan + '" target="_blank">Lihat</a></small>';
        } else {
          docContainer.innerHTML = '';
        }
        
        const modal = new bootstrap.Modal(document.getElementById('modal-edit-ptw'));
        modal.show();
      })
      .catch(err => alert('Gagal memuat data PTW: ' + err));
  }

  function loadLotoForEdit(lotoId) {
    fetch('/hse/loto/' + lotoId + '/edit')
      .then(r => r.json())
      .then(data => {
        const form = document.getElementById('edit-loto-form');
        form.action = '/hse/loto/' + lotoId;
        document.getElementById('edit-loto-isolation-point').value = data.isolation_point || '';
        document.getElementById('edit-loto-lock-number').value = data.lock_number || '';
        document.getElementById('edit-loto-tag-number').value = data.tag_number || '';
        
        const modal = new bootstrap.Modal(document.getElementById('modal-edit-loto'));
        modal.show();
      })
      .catch(err => alert('Gagal memuat data LOTO: ' + err));
  }

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

  // Render ApexCharts Timeline
  document.addEventListener("DOMContentLoaded", function () {
    @if(!empty($timelineItems))
    const timelineItems = @json($timelineItems);
    const container = document.getElementById('chart-timeline');
    
    if (container && timelineItems.length > 0) {
      function renderChart() {
        if (typeof ApexCharts === 'undefined') {
          setTimeout(renderChart, 100);
          return;
        }
        
        const isDark = document.body.getAttribute("data-bs-theme") === "dark";
        const lblColor = isDark ? "#94a3b8" : "#475569";
        const gridColor = isDark ? "#334155" : "#e2e8f0";
        const uniqueCategories = [...new Set(timelineItems.map(i => i.x))];
        const chartHeight = Math.max(200, uniqueCategories.length * 40 + 60);

        const options = {
          series: [
            {
              data: timelineItems
            }
          ],
          chart: {
            height: chartHeight,
            type: 'rangeBar',
            toolbar: { show: true },
            background: 'transparent'
          },
          plotOptions: {
            bar: {
              horizontal: true,
              distributed: false,
              barHeight: '60%'
            }
          },
          dataLabels: {
            enabled: true,
            formatter: function(val, opts) {
              const item = timelineItems[opts.dataPointIndex];
              return item ? item.remarks : '';
            },
            style: {
              colors: ['#fff'],
              fontSize: '11px',
              fontWeight: '600'
            }
          },
          xaxis: {
            type: 'datetime',
            @if($minTimestamp)
            min: @json($minTimestamp),
            @endif
            labels: {
              style: { colors: lblColor },
              datetimeUTC: false,
              datetimeFormatter: {
                year: 'yyyy',
                month: 'MMM \'yy',
                day: 'dd MMM',
                hour: 'HH:mm'
              }
            },
            grid: { borderColor: gridColor, strokeDashArray: 3 }
          },
          yaxis: {
            labels: {
              align: 'left',
              minWidth: 100,
              maxWidth: 200,
              style: { colors: lblColor, fontSize: '11px', fontWeight: 600 }
            }
          },
          legend: { show: false },
          tooltip: {
            theme: isDark ? 'dark' : 'light',
            custom: function(opts) {
              const item = timelineItems[opts.dataPointIndex];
              if (!item) return '';
              const from = new Date(item.y[0]).toLocaleString('id-ID', {day:'2-digit', month:'short', year:'numeric', hour:'2-digit', minute:'2-digit'});
              const to = new Date(item.y[1]).toLocaleString('id-ID', {day:'2-digit', month:'short', year:'numeric', hour:'2-digit', minute:'2-digit'});
              const durHrs = ((item.y[1] - item.y[0]) / 3600000).toFixed(1);
              return '<div class="p-2 small">' +
                '<div><strong>' + item.x + '</strong> (' + item.label_type + ')</div>' +
                '<div class="text-muted mb-1">Remarks: ' + item.remarks + '</div>' +
                '<div>Start: ' + from + '</div>' +
                '<div>End: ' + to + '</div>' +
                '<div class="fw-bold mt-1">Durasi: ' + durHrs + ' Jam</div>' +
              '</div>';
            }
          },
          grid: {
            borderColor: gridColor,
            xaxis: { lines: { show: true } },
            yaxis: { lines: { show: false } },
            padding: {
              left: 10,
              right: 20
            }
          }
        };

        const chart = new ApexCharts(container, options);
        chart.render();
      }
      
      renderChart();
    }
    @endif
  });

  // WO Discussion Thread Logic
  document.addEventListener("DOMContentLoaded", function() {
    const woId = "{{ $workOrder->getRouteKey() }}";
    const authId = {{ auth()->id() ?? 'null' }};
    const isSuperAdmin = {{ auth()->user()->hasRole('Super Admin') ? 'true' : 'false' }};
    const container = document.getElementById('woDiscussionMessages');
    const loading = document.getElementById('woDiscussionLoading');
    let isFetching = false;
    let selectedFile = null;

    if (!container) return;

    // File Input Logic
    const fileInput = document.getElementById('woDiscussionAttachment');
    const filePreview = document.getElementById('woDiscussionAttachmentPreview');
    const fileNameDisplay = document.getElementById('woDiscussionAttachmentName');
    
    fileInput.addEventListener('change', async function(e) {
      if (this.files && this.files[0]) {
        let file = this.files[0];
        const isImage = file.type.startsWith('image/');
        
        // Document max 10MB
        if (!isImage && file.size > 10 * 1024 * 1024) {
           alert('Ukuran dokumen maksimal 10 MB.');
           this.value = '';
           return;
        }

        // Image compression if > 2MB
        if (isImage && file.size > 2 * 1024 * 1024) {
           try {
             file = await compressImage(file, 2);
           } catch(err) {
             console.error("Compression failed", err);
           }
        }

        selectedFile = file;
        fileNameDisplay.textContent = file.name;
        filePreview.classList.remove('d-none');
      }
    });

    document.getElementById('woDiscussionAttachmentRemove').addEventListener('click', function() {
      selectedFile = null;
      fileInput.value = '';
      filePreview.classList.add('d-none');
    });

    function compressImage(file, maxMB) {
      return new Promise((resolve, reject) => {
        const reader = new FileReader();
        reader.readAsDataURL(file);
        reader.onload = event => {
          const img = new Image();
          img.src = event.target.result;
          img.onload = () => {
            const canvas = document.createElement('canvas');
            let width = img.width;
            let height = img.height;
            // Resize proportionally if too large (e.g. max 1920px)
            const MAX_DIMENSION = 1920;
            if (width > MAX_DIMENSION || height > MAX_DIMENSION) {
               if (width > height) {
                 height = (height / width) * MAX_DIMENSION;
                 width = MAX_DIMENSION;
               } else {
                 width = (width / height) * MAX_DIMENSION;
                 height = MAX_DIMENSION;
               }
            }
            canvas.width = width;
            canvas.height = height;
            const ctx = canvas.getContext('2d');
            ctx.drawImage(img, 0, 0, width, height);
            
            // Output as JPEG
            canvas.toBlob((blob) => {
               if(blob) {
                 const newFile = new File([blob], file.name, { type: 'image/jpeg', lastModified: Date.now() });
                 resolve(newFile);
               } else {
                 resolve(file); // fallback
               }
            }, 'image/jpeg', 0.8);
          };
          img.onerror = error => reject(error);
        };
        reader.onerror = error => reject(error);
      });
    }

    function formatText(text) {
      return text.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/\n/g, '<br>');
    }

    function renderAvatar(user) {
      if (user.avatar_url) {
        return `<span class="avatar avatar-sm rounded-circle me-2 flex-shrink-0" style="background-image: url('${user.avatar_url}')"></span>`;
      }
      return `<span class="avatar avatar-sm rounded-circle bg-azure-lt me-2 flex-shrink-0">${user.nama_lengkap.charAt(0)}</span>`;
    }

    function renderAttachment(item) {
      if (!item.attachment_url) return '';
      if (item.attachment_type === 'image') {
        return `<div class="mt-2 mb-2"><a href="${item.attachment_url}" target="_blank"><img src="${item.attachment_url}" class="img-fluid rounded border shadow-sm" style="max-height: 250px; object-fit: contain;"></a></div>`;
      } else {
        return `<div class="mt-2 mb-2"><a href="${item.attachment_url}" target="_blank" class="btn btn-sm btn-outline-primary d-inline-flex align-items-center gap-1"><svg class="icon m-0" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /></svg> ${item.attachment_name || 'Download Dokumen'}</a></div>`;
      }
    }

    function renderDeleteBtn(item) {
      if (item.user_id === authId || isSuperAdmin) {
        return `
          <div class="dropdown">
            <button class="btn btn-sm btn-ghost-secondary btn-icon border-0" data-bs-toggle="dropdown"><svg class="icon m-0" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" /><path d="M12 19m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" /><path d="M12 5m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" /></svg></button>
            <div class="dropdown-menu dropdown-menu-end">
              <button type="button" class="dropdown-item text-danger btn-delete-comment" data-id="${item.id}">Hapus</button>
            </div>
          </div>
        `;
      }
      return '';
    }

    function renderPost(post) {
      const date = new Date(post.created_at).toLocaleString('id-ID', {day:'2-digit', month:'short', hour:'2-digit', minute:'2-digit'});
      let html = `
        <div class="card shadow-sm border-0 rounded-3 mb-1" id="post-${post.id}">
          <div class="card-body p-3">
            <!-- Header Post -->
            <div class="d-flex align-items-center mb-2 justify-content-between">
              <div class="d-flex align-items-center">
                ${renderAvatar(post.user)}
                <div>
                  <div class="fw-bold text-dark" style="font-size: 0.9rem;">${post.user.nama_lengkap}</div>
                  <div class="text-muted small" style="font-size: 0.75rem;">${date}</div>
                </div>
              </div>
              ${renderDeleteBtn(post)}
            </div>
            <!-- Body Post -->
            <div class="mb-2 text-dark" style="font-size: 0.95rem; line-height: 1.5;">
              ${formatText(post.body)}
            </div>
            ${renderAttachment(post)}
            
            <!-- Balasan / Replies Section -->
            <div class="border-top pt-3 bg-light rounded-3 px-3 pb-3 mt-3">
              <div class="fw-bold text-secondary mb-2 small d-flex align-items-center gap-1">
                <svg class="icon" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M21 14l-3 -3h-7a1 1 0 0 1 -1 -1v-6a1 1 0 0 1 1 -1h9a1 1 0 0 1 1 1v10" /><path d="M14 15v2a1 1 0 0 1 -1 1h-7l-3 3v-10a1 1 0 0 1 1 -1h2" /></svg>
                ${post.replies.length > 0 ? post.replies.length + ' Komentar' : 'Belum ada komentar'}
              </div>
              
              <div class="d-flex flex-column gap-2 mb-3">
      `;
      
      // Render Replies
      post.replies.forEach(reply => {
        const rDate = new Date(reply.created_at).toLocaleString('id-ID', {day:'2-digit', month:'short', hour:'2-digit', minute:'2-digit'});
        html += `
          <div class="d-flex" id="post-${reply.id}">
            ${renderAvatar(reply.user)}
            <div class="bg-white border rounded-3 p-2 px-3 shadow-xs flex-fill position-relative">
              <div class="d-flex justify-content-between align-items-center mb-1">
                <div class="fw-bold text-azure" style="font-size: 0.8rem;">${reply.user.nama_lengkap}</div>
                <div class="d-flex align-items-center gap-2">
                  <div class="text-muted" style="font-size: 0.7rem;">${rDate}</div>
                  ${renderDeleteBtn(reply)}
                </div>
              </div>
              <div class="text-dark" style="font-size: 0.85rem;">${formatText(reply.body)}</div>
              ${renderAttachment(reply)}
            </div>
          </div>
        `;
      });

      html += `
              </div>
              
              <!-- Input Form Reply -->
              <div class="d-flex mt-2">
                @if(auth()->user()->avatar_url)
                  <span class="avatar avatar-sm rounded-circle me-2 flex-shrink-0 mt-1" style="background-image: url('{{ auth()->user()->avatar_url }}')"></span>
                @else
                  <span class="avatar avatar-sm rounded-circle bg-azure-lt me-2 flex-shrink-0 mt-1">{{ substr(auth()->user()->nama_lengkap, 0, 1) }}</span>
                @endif
                <form class="flex-fill form-reply" data-parent="${post.id}">
                  <div class="d-flex align-items-center gap-2">
                    <input type="text" class="form-control form-control-sm rounded-pill px-3 reply-input" placeholder="Tulis balasan..." required autocomplete="off">
                    
                    <button type="button" class="btn btn-sm btn-outline-secondary btn-icon rounded-circle reply-file-btn" title="Lampirkan File" onclick="this.nextElementSibling.click()">
                      <svg class="icon m-0" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M15 7l-6.5 6.5a1.5 1.5 0 0 0 3 3l6.5 -6.5a3 3 0 0 0 -6 -6l-6.5 6.5a4.5 4.5 0 0 0 9 9l6.5 -6.5" /></svg>
                    </button>
                    <input type="file" class="d-none reply-file-input" accept="image/*,.pdf,.doc,.docx,.xls,.xlsx">

                    <button type="submit" class="btn btn-sm btn-icon btn-primary rounded-circle shadow-sm flex-shrink-0" title="Kirim Balasan">
                      <svg class="icon m-0" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 14l11 -11" /><path d="M21 3l-6.5 18a.55 .55 0 0 1 -1 0l-3.5 -7l-7 -3.5a.55 .55 0 0 1 0 -1l18 -6.5" /></svg>
                    </button>
                  </div>
                  <!-- Preview balasan file -->
                  <div class="reply-file-preview d-none mt-1 p-1 px-2 border rounded bg-white small d-flex justify-content-between align-items-center">
                    <span class="reply-file-name text-truncate"></span>
                    <button type="button" class="btn-close reply-file-remove"></button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      `;
      return html;
    }

    function loadComments() {
      if (isFetching) return;
      isFetching = true;
      if (container.children.length === 0 && loading) loading.style.display = 'block';

      fetch(`/work-orders/${woId}/comments`)
        .then(r => r.json())
        .then(posts => {
          if (loading) loading.style.display = 'none';
          if (posts.error) return; // Unauthorized
          
          if (posts.length === 0) {
            container.innerHTML = '<div class="text-center text-muted small py-4">Belum ada diskusi. Mulai percakapan sekarang!</div>';
            return;
          }

          let html = '';
          const reversedPosts = [...posts].reverse();
          reversedPosts.forEach(post => {
            html += renderPost(post);
          });
          
          const activeInput = document.activeElement;
          if (activeInput && activeInput.classList.contains('reply-input') && activeInput.value.length > 0) {
             // Skip re-render if user is typing
          } else {
             container.innerHTML = html;
          }
        })
        .catch(e => console.error(e))
        .finally(() => { isFetching = false; });
    }

    // Initial load & Polling
    loadComments();
    setInterval(loadComments, 10000);

    // Post Statement (Main Form)
    document.getElementById('woDiscussionForm').addEventListener('submit', function(e) {
      e.preventDefault();
      const input = document.getElementById('woDiscussionInput');
      const text = input.value.trim();
      if (!text) return;

      const btn = document.getElementById('woDiscussionSubmit');
      btn.disabled = true;

      const formData = new FormData();
      formData.append('body', text);
      if (selectedFile) formData.append('attachment', selectedFile);

      fetch(`/work-orders/${woId}/comments`, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
          'Accept': 'application/json'
        },
        body: formData
      })
      .then(r => r.json())
      .then(res => {
        if (res.success) {
          input.value = '';
          document.getElementById('woDiscussionAttachmentRemove').click(); // Reset file
          loadComments();
        } else alert(res.message || 'Gagal mengirim postingan');
      })
      .finally(() => { btn.disabled = false; input.focus(); });
    });

    // Delegasi Event untuk Reply & File Reply & Delete
    container.addEventListener('change', async function(e) {
      if (e.target.classList.contains('reply-file-input')) {
        const fileInput = e.target;
        const form = fileInput.closest('.form-reply');
        const preview = form.querySelector('.reply-file-preview');
        const nameDisplay = form.querySelector('.reply-file-name');
        
        if (fileInput.files && fileInput.files[0]) {
          let file = fileInput.files[0];
          const isImage = file.type.startsWith('image/');
          
          if (!isImage && file.size > 10 * 1024 * 1024) {
             alert('Ukuran dokumen maksimal 10 MB.');
             fileInput.value = '';
             return;
          }
          if (isImage && file.size > 2 * 1024 * 1024) {
             try {
               file = await compressImage(file, 2);
             } catch(err) { console.error(err); }
          }
          
          form.selectedFile = file;
          nameDisplay.textContent = file.name;
          preview.classList.remove('d-none');
        }
      }
    });

    container.addEventListener('click', function(e) {
      if (e.target.classList.contains('reply-file-remove')) {
        const form = e.target.closest('.form-reply');
        form.selectedFile = null;
        form.querySelector('.reply-file-input').value = '';
        form.querySelector('.reply-file-preview').classList.add('d-none');
      }

      if (e.target.classList.contains('btn-delete-comment')) {
        const id = e.target.dataset.id;
        if (confirm('Apakah Anda yakin ingin menghapus komentar ini?')) {
          fetch(`/work-orders/${woId}/comments/${id}`, {
            method: 'DELETE',
            headers: {
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
              'Accept': 'application/json'
            }
          })
          .then(r => r.json())
          .then(res => {
            if (res.success) loadComments();
            else alert('Gagal menghapus');
          });
        }
      }
    });

    container.addEventListener('submit', function(e) {
      if (e.target.classList.contains('form-reply')) {
        e.preventDefault();
        const form = e.target;
        const parentId = form.dataset.parent;
        const input = form.querySelector('.reply-input');
        const btn = form.querySelector('button[type="submit"]');
        const text = input.value.trim();
        if (!text) return;

        btn.disabled = true;
        
        const formData = new FormData();
        formData.append('body', text);
        formData.append('parent_id', parentId);
        if (form.selectedFile) formData.append('attachment', form.selectedFile);

        fetch(`/work-orders/${woId}/comments`, {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
          },
          body: formData
        })
        .then(r => r.json())
        .then(res => {
          if (res.success) {
            input.value = '';
            const removeBtn = form.querySelector('.reply-file-remove');
            if(removeBtn) removeBtn.click();
            loadComments();
          } else alert('Gagal membalas komentar');
        })
        .finally(() => { btn.disabled = false; });
      }
    });

    // Emojis for Main Input
    document.addEventListener('click', function(e) {
      const btn = e.target.closest('.btn-wo-emo');
      if (btn) {
        const targetId = btn.dataset.target;
        if (targetId) {
          const input = document.getElementById(targetId);
          input.value += btn.dataset.emoji;
          input.focus();
        }
      }
    });
  });
</script>
@endsection
