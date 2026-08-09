@extends('layouts.tabler')

@php
  $mode = request('mode', 'plan'); // 'plan' = Dokumen Pengajuan Rencana, 'eval' = Evaluasi Realisasi vs Actual
@endphp

@section('title', ($mode == 'plan' ? 'Dokumen Pengajuan Plan Budget' : 'Evaluasi Realisasi Plan vs Actual Budget') . ' - CMMS Aisfar')

@section('content')
<style>
  @media print {
    body {
      background: #fff !important;
      color: #000 !important;
      font-size: 11px !important;
    }
    .page-header, .navbar, .footer, .d-print-none, .nav-tabs {
      display: none !important;
    }
    .page-wrapper, .page-body, .container-xl {
      padding: 0 !important;
      margin: 0 !important;
      max-width: 100% !important;
      width: 100% !important;
    }
    .card {
      border: none !important;
      box-shadow: none !important;
      background: transparent !important;
    }
    .table {
      border-color: #000 !important;
      color: #000 !important;
    }
    .table th, .table td {
      border-color: #000 !important;
      padding: 4px 6px !important;
    }
    .badge {
      border: none !important;
      outline: none !important;
      box-shadow: none !important;
      color: #000 !important;
      background: transparent !important;
      padding: 0 !important;
      font-weight: 600 !important;
    }
    .collapse {
      display: block !important;
    }
    @page {
      size: A4 {{ $mode == 'eval' ? 'landscape' : 'portrait' }};
      margin: 10mm;
    }
  }
</style>

<!-- Top Actions (Screen only) -->
<div class="page-header d-print-none mb-3">
  <div class="row align-items-center">
    <div class="col">
      <h2 class="page-title text-primary fw-bold">
        {{ $mode == 'plan' ? 'Dokumen Pengajuan Plan Budget Bulanan' : 'Evaluasi Realisasi Plan vs Actual Budget' }}
      </h2>
    </div>
    <div class="col-auto ms-auto d-print-none gap-2 d-flex">
      <!-- Dropdown Bagikan Link -->
      <div class="dropdown">
        <button type="button" class="btn btn-outline-primary dropdown-toggle fw-bold shadow-sm" data-bs-toggle="dropdown">
          <svg class="icon icon-tabler icon-tabler-share me-1" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6 12m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" /><path d="M18 6m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" /><path d="M18 18m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" /><path d="M8.7 10.7l6.6 -3.4" /><path d="M8.7 13.3l6.6 3.4" /></svg>
          Bagikan Link
        </button>
        <div class="dropdown-menu dropdown-menu-end shadow-md">
          <a class="dropdown-item d-flex align-items-center gap-2" href="#" onclick="copyToClipboard('[Plan Budget: {{ $planBudget->bulan }}/{{ $planBudget->tahun }}]({{ url()->current() }})', 'Link Format Chat berhasil disalin!'); return false;">
            <svg class="icon icon-tabler icon-tabler-message-share text-primary" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M20 11v-3a3 3 0 0 0 -3 -3h-10a3 3 0 0 0 -3 3v8a3 3 0 0 0 3 3h5" /><path d="M16 22l5 -5" /><path d="M21 21.5v-4.5h-4.5" /></svg>
            <span>Salin Format Chat (`[Plan Budget](URL)`)</span>
          </a>
          <a class="dropdown-item d-flex align-items-center gap-2" href="#" onclick="copyToClipboard('{{ url()->current() }}', 'URL Web berhasil disalin!'); return false;">
            <svg class="icon icon-tabler icon-tabler-link text-secondary" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 15l6 -6" /><path d="M11 6l.463 -.463a5 5 0 0 1 7.071 7.071l-.534 .534" /><path d="M13 18l-.397 .534a5 5 0 0 1 -7.071 -7.071l.534 -.534" /></svg>
            <span>Salin URL Web Langsung</span>
          </a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('chat.index') }}" target="_blank" onclick="copyToClipboard('[Plan Budget: {{ $planBudget->bulan }}/{{ $planBudget->tahun }}]({{ url()->current() }})', 'Link disalin! Membuka Live Chat...');">
            <svg class="icon icon-tabler icon-tabler-brand-hipchat text-success" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 3a9 9 0 1 0 4.5 16.852l3.5 1.148l-1.148 -3.5a9 9 0 0 0 -6.852 -14.5z" /></svg>
            <span>Buka Live Chat</span>
          </a>
        </div>
      </div>

      <button type="button" class="btn btn-success fw-bold shadow-sm" onclick="window.print();">
        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2" /><path d="M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4" /><path d="M7 13m0 2a2 2 0 0 1 2 -2h6a2 2 0 0 1 2 2v4a2 2 0 0 1 -2 2h-6a2 2 0 0 1 -2 -2z" /></svg>
        Cetak Dokumen (Print)
      </button>
      <a href="{{ route('plan-budgets.index') }}" class="btn btn-secondary">
        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l14 0" /><path d="M5 12l6 6" /><path d="M5 12l6 -6" /></svg>
        Kembali
      </a>
    </div>
  </div>
</div>

<!-- Tab Navigation Mode Switcher -->
<div class="card-header d-print-none mb-3 border-0">
  <ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs">
    <li class="nav-item">
      <a href="{{ route('plan-budgets.show', $planBudget) }}?mode=plan" class="nav-link {{ $mode == 'plan' ? 'active font-weight-bold' : '' }}">
        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1 text-primary" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><path d="M9 15l2 2l4 -4" /></svg>
        📝 Dokumen Pengajuan Rencana (Plan Only)
      </a>
    </li>
    <li class="nav-item">
      <a href="{{ route('plan-budgets.show', $planBudget) }}?mode=eval" class="nav-link {{ $mode == 'eval' ? 'active font-weight-bold' : '' }}">
        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1 text-success" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 12m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v6a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" /><path d="M9 8m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v10a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" /><path d="M15 4m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v14a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" /></svg>
        📊 Evaluasi Realisasi Goal (Plan vs Actual)
      </a>
    </li>
  </ul>
</div>

<!-- Header Laporan Cetak (Print Header) -->
<div class="d-none d-print-block mb-4">
  <div class="row align-items-center border-bottom pb-3">
    <div class="col-8">
      @php
        $appLogo = \App\Models\AppSetting::where('key', 'app_logo')->first()?->value;
        $appName = \App\Models\AppSetting::where('key', 'app_name')->first()?->value ?? 'CMMS AISFAR';
        $appAddress = \App\Models\AppSetting::where('key', 'app_address')->first()?->value ?? '';
        $siteCode = $planBudget->site->code ?? (auth()->user()->site?->code ?? '');
        if ($siteCode) {
            $appName .= ' - ' . $siteCode;
        }
      @endphp
      <div class="d-flex align-items-center mb-1">
        @if($appLogo)
          <img src="{{ asset('storage/logos/' . $appLogo) }}" alt="Logo" style="height: 50px; margin-right: 15px;">
        @endif
        <div>
          <h2 class="fw-bold m-0 text-uppercase" style="letter-spacing: 1px;">{{ $appName }}</h2>
          <div class="small text-muted">{{ $appAddress }}</div>
        </div>
      </div>
      <h3 class="fw-bold text-primary m-0 mt-2">
        {{ $mode == 'plan' ? 'DOKUMEN PENGAJUAN PLAN BUDGET BULANAN' : 'LAPORAN EVALUASI REALISASI PLAN VS ACTUAL BUDGET' }}
      </h3>
    </div>
    <div class="col-4 text-end">
      <div class="small">Tanggal Cetak: <strong>{{ \Carbon\Carbon::now()->format('d F Y H:i') }}</strong></div>
      <div class="small">Status Document: <strong>{{ $planBudget->status }}</strong></div>
    </div>
  </div>
</div>

<div class="card mt-2">
  <div class="card-body">
    <div class="datagrid">
      <div class="datagrid-item">
        <div class="datagrid-title">Site (Cabang)</div>
        <div class="datagrid-content fw-bold">{{ $planBudget->site->name ?? '-' }}</div>
      </div>
      <div class="datagrid-item">
        <div class="datagrid-title">Periode</div>
        <div class="datagrid-content"><strong>{{ \Carbon\Carbon::createFromFormat('Y-m', $planBudget->period)->format('F Y') }}</strong></div>
      </div>
      <div class="datagrid-item">
        <div class="datagrid-title">Status Document</div>
        <div class="datagrid-content">
          <span class="status status-{{ $planBudget->status == 'Approved' ? 'green' : 'yellow' }}">
            {{ $planBudget->status }}
          </span>
        </div>
      </div>
      <div class="datagrid-item">
        <div class="datagrid-title">Dibuat Oleh</div>
        <div class="datagrid-content">{{ $planBudget->creator->name ?? '-' }}</div>
      </div>
    </div>
  </div>

  @if ($mode == 'plan')
    <!-- MODE 1: DOKUMEN PENGAJUAN RENCANA (PLAN ONLY) -->
    <div class="table-responsive">
      <table class="table table-vcenter card-table table-bordered table-striped text-nowrap">
        <thead class="bg-light">
          <tr>
            <th class="w-1 text-center font-weight-bold">No</th>
            <th class="font-weight-bold">Unit Alat Berat</th>
            <th class="text-center font-weight-bold">Target PA (%)</th>
            <th class="font-weight-bold">Rincian Rencana Sparepart (Part & Qty)</th>
            <th class="text-end font-weight-bold">Rencana Biaya Sparepart (Rp)</th>
          </tr>
        </thead>
        <tbody>
          @php $totalPlanCost = 0; $no = 1; @endphp
          @foreach ($planBudget->units as $u)
            @php $totalPlanCost += $u->planned_cost; @endphp
            <tr>
              <td class="text-center">{{ $no++ }}</td>
              <td>
                <div class="font-weight-bold text-dark">{{ $u->unit->nomor_unit }}</div>
                <div class="text-muted small">{{ $u->unit->model->model_no ?? '' }}</div>
              </td>
              <td class="text-center font-weight-bold text-primary">
                {{ number_format($u->target_pa, 2) }}%
              </td>
              <td>
                @if($u->parts->count() > 0)
                  <ul class="list-unstyled mb-0">
                    @foreach($u->parts as $partPlan)
                      <li class="border-bottom py-1">
                        <strong class="text-dark">{{ $partPlan->part->part_number ?? '?' }}</strong> - {{ $partPlan->part->part_description ?? '' }}
                        <span class="badge bg-blue-lt text-blue ms-1">{{ $partPlan->qty }} Unit</span>
                        <span class="text-muted ms-1">(Rp{{ number_format($partPlan->price, 0, ',', '.') }}/unit = <strong>Rp{{ number_format($partPlan->total_price, 0, ',', '.') }}</strong>)</span>
                      </li>
                    @endforeach
                  </ul>
                @else
                  <span class="text-muted italic">Tidak ada rincian sparepart</span>
                @endif
              </td>
              <td class="text-end font-weight-bold text-primary">
                Rp {{ number_format($u->planned_cost, 0, ',', '.') }}
              </td>
            </tr>
          @endforeach
        </tbody>
        <tfoot class="bg-light font-weight-bold">
          <tr>
            <td colspan="4" class="text-end text-uppercase">Total Rencana Biaya Budget</td>
            <td class="text-end text-primary">
              Rp {{ number_format($totalPlanCost, 0, ',', '.') }}
            </td>
          </tr>
        </tfoot>
      </table>
    </div>

  @else

    <!-- MODE 2: EVALUASI REALISASI GOAL (PLAN VS ACTUAL) -->
    <div class="table-responsive">
      <table class="table table-vcenter card-table table-bordered table-striped table-hover text-nowrap">
        <thead class="bg-light">
          <tr>
            <th rowspan="2" class="align-middle text-center font-weight-bold">Unit</th>
            <th colspan="3" class="text-center font-weight-bold border-start border-end">Physical Availability (PA)</th>
            <th colspan="3" class="text-center font-weight-bold">Biaya Sparepart (Cost)</th>
          </tr>
          <tr>
            <th class="text-center text-muted border-start">Target Plan</th>
            <th class="text-center text-muted">Realisasi Actual</th>
            <th class="text-center text-muted border-end">Status Goal</th>
            
            <th class="text-center text-muted">Rencana Plan (Rp)</th>
            <th class="text-center text-muted">Realisasi Actual (Rp)</th>
            <th class="text-center text-muted">Variance</th>
          </tr>
        </thead>
        <tbody>
          @php
            $totalPlanCost = 0;
            $totalActualCost = 0;
          @endphp
          @foreach ($planBudget->units as $u)
            @php
              $paAchieved = $u->actual_pa >= $u->target_pa;
              
              $costVariance = $u->planned_cost - $u->actual_cost;
              $costStatus = $costVariance >= 0 ? 'Underbudget' : 'Overbudget';
              $costColor = $costVariance >= 0 ? 'text-success' : 'text-danger';
              
              $totalPlanCost += $u->planned_cost;
              $totalActualCost += $u->actual_cost;
            @endphp
            <tr>
              <td>
                <div class="font-weight-bold">{{ $u->unit->nomor_unit }}</div>
                <div class="text-muted small">{{ $u->unit->model->model_no ?? '' }}</div>
              </td>
              
              <!-- PA Section -->
              <td class="text-center border-start font-weight-bold text-primary">
                {{ number_format($u->target_pa, 2) }}%
              </td>
              <td class="text-center font-weight-bold {{ $paAchieved ? 'text-success' : 'text-danger' }}">
                {{ number_format($u->actual_pa, 2) }}%
              </td>
              <td class="text-center border-end">
                @if($paAchieved)
                  <span class="badge bg-green text-green-fg">Tercapai</span>
                @else
                  <span class="badge bg-red text-red-fg">Tidak Tercapai</span>
                @endif
              </td>
              
              <!-- Cost Section -->
              <td class="text-end font-weight-bold text-primary">
                <div>{{ number_format($u->planned_cost, 0, ',', '.') }}</div>
                @if($u->parts->count() > 0)
                  <div class="mt-2 text-start">
                    <a class="text-muted small text-decoration-none d-print-none" data-bs-toggle="collapse" href="#parts-{{ $u->id }}" role="button" aria-expanded="false" aria-controls="parts-{{ $u->id }}">
                      Lihat {{ $u->parts->count() }} Part
                    </a>
                    <div class="collapse mt-1" id="parts-{{ $u->id }}">
                      <ul class="list-unstyled small mb-0 p-2 bg-light border rounded">
                        @foreach($u->parts as $partPlan)
                          <li class="border-bottom pb-1 mb-1">
                            {{ $partPlan->part->part_number ?? '?' }} - {{ $partPlan->qty }}x (Rp{{ number_format($partPlan->price, 0, ',', '.') }})
                            <br><strong class="text-dark">= Rp{{ number_format($partPlan->total_price, 0, ',', '.') }}</strong>
                          </li>
                        @endforeach
                      </ul>
                    </div>
                  </div>
                @endif
              </td>
              <td class="text-end font-weight-bold {{ $costColor }}">
                {{ number_format($u->actual_cost, 0, ',', '.') }}
              </td>
              <td class="text-end">
                <div class="font-weight-bold {{ $costColor }}">{{ number_format(abs($costVariance), 0, ',', '.') }}</div>
                <div class="small {{ $costColor }}">{{ $costStatus }}</div>
              </td>
            </tr>
          @endforeach
        </tbody>
        <tfoot class="bg-light font-weight-bold">
          <tr>
            <td colspan="4" class="text-end text-uppercase">Grand Total Cost</td>
            <td class="text-end text-primary border-start">
              {{ number_format($totalPlanCost, 0, ',', '.') }}
            </td>
            <td class="text-end {{ ($totalPlanCost - $totalActualCost) >= 0 ? 'text-success' : 'text-danger' }}">
              {{ number_format($totalActualCost, 0, ',', '.') }}
            </td>
            <td class="text-end {{ ($totalPlanCost - $totalActualCost) >= 0 ? 'text-success' : 'text-danger' }}">
              {{ number_format(abs($totalPlanCost - $totalActualCost), 0, ',', '.') }}
              <div class="small fw-normal">{{ ($totalPlanCost - $totalActualCost) >= 0 ? 'Underbudget' : 'Overbudget' }}</div>
            </td>
          </tr>
        </tfoot>
      </table>
    </div>
  @endif

  <!-- KOLOM TANDA TANGAN (SIGNATURE SECTION FOR SITE & HO) -->
  <div class="card-footer bg-white border-top-0 mt-4">
    <div class="row pt-3 text-center">
      <!-- TIM SITE -->
      <div class="col-6 border-end">
        <div class="fw-bold text-uppercase text-dark mb-1" style="letter-spacing: 0.5px;">TIM SITE (PROJECT)</div>
        <div class="text-muted small mb-4">Disiapkan & Diperiksa oleh:</div>
        
        <div class="row px-3">
          <div class="col-6 text-center">
            <div style="height: 60px;"></div>
            <div class="border-top pt-1 fw-bold text-dark">Maintenance Planner</div>
            <div class="text-muted small">Tanggal: ____________</div>
          </div>
          <div class="col-6 text-center">
            <div style="height: 60px;"></div>
            <div class="border-top pt-1 fw-bold text-dark">Site Manager / PM</div>
            <div class="text-muted small">Tanggal: ____________</div>
          </div>
        </div>
      </div>

      <!-- TIM HEAD OFFICE -->
      <div class="col-6">
        <div class="fw-bold text-uppercase text-dark mb-1" style="letter-spacing: 0.5px;">TIM HEAD OFFICE (HO)</div>
        <div class="text-muted small mb-4">Direview & Disetujui oleh:</div>
        
        <div class="row px-3">
          <div class="col-6 text-center">
            <div style="height: 60px;"></div>
            <div class="border-top pt-1 fw-bold text-dark">Maintenance Head / Manager</div>
            <div class="text-muted small">Tanggal: ____________</div>
          </div>
          <div class="col-6 text-center">
            <div style="height: 60px;"></div>
            <div class="border-top pt-1 fw-bold text-dark">General Manager / Direktur</div>
            <div class="text-muted small">Tanggal: ____________</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
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
@if(request('print'))
<script>
  window.addEventListener('load', function() {
    setTimeout(function() {
      window.print();
    }, 500);
  });
</script>
@endif
@endpush
