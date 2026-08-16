@extends('layouts.tabler')

@section('title', 'Detail Asset - ' . $masterUnit->nomor_unit)

@section('content')
<div class="page-header d-print-none mb-3">
  <div class="container-xl">
    <div class="row g-2 align-items-center">
      <div class="col">
        <div class="page-pretitle">Populasi Asset Unit</div>
        <h2 class="page-title text-uppercase font-weight-bold">
          {{ $masterUnit->nomor_unit }}
        </h2>
        <div class="text-muted small mt-1">
          Type: <span class="badge bg-blue-lt me-1">{{ $masterUnit->type->name ?? '-' }}</span>
          Model: <span class="badge bg-secondary-lt me-1">{{ $masterUnit->model->name ?? '-' }}</span>
          Site: <span class="badge bg-green-lt">{{ $masterUnit->siteRelation->name ?? $masterUnit->site ?? '-' }}</span>
        </div>
      </div>
      <div class="col-auto ms-auto d-print-none">
        <div class="btn-list">
          <a href="{{ route('master-units.edit', $masterUnit->id) }}" class="btn btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 20h4l10.5 -10.5a1.5 1.5 0 0 0 -4 -4l-10.5 10.5v4" /><path d="M13.5 6.5l4 4" /></svg>
            Edit Unit
          </a>
          <a href="{{ route('master-units.index') }}" class="btn btn-outline-secondary">Kembali</a>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="page-body">
  <div class="container-xl">

    <!-- KPI Summary Row -->
    <div class="row row-cards mb-4">
      <div class="col-sm-6 col-lg-3">
        <div class="card card-sm shadow-sm border-0">
          <div class="card-body">
            <div class="row align-items-center">
              <div class="col-auto">
                <span class="bg-primary text-white avatar">
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><path d="M9 17h6" /><path d="M9 13h6" /></svg>
                </span>
              </div>
              <div class="col">
                <div class="font-weight-medium">Total Work Order</div>
                <div class="text-muted">{{ $workOrders->count() }} Laporan WO</div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-sm-6 col-lg-3">
        <div class="card card-sm shadow-sm border-0">
          <div class="card-body">
            <div class="row align-items-center">
              <div class="col-auto">
                <span class="bg-purple text-white avatar">
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 8v-3a1 1 0 0 0 -1 -1h-10a1 1 0 0 0 -1 1v14a1 1 0 0 0 1 1h10a1 1 0 0 0 1 -1v-3" /><path d="M20 12l-15 0" /><path d="M17 9l3 3l-3 3" /></svg>
                </span>
              </div>
              <div class="col">
                <div class="font-weight-medium">Total Cost Maintenance</div>
                <div class="text-muted font-weight-bold text-danger">Rp {{ number_format($totalWoCost, 0, ',', '.') }}</div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-sm-6 col-lg-3">
        <div class="card card-sm shadow-sm border-0">
          <div class="card-body">
            <div class="row align-items-center">
              <div class="col-auto">
                <span class="bg-azure text-white avatar">
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="9" /><polyline points="12 7 12 12 15 15" /></svg>
                </span>
              </div>
              <div class="col">
                <div class="font-weight-medium">Aktivitas Produksi</div>
                <div class="text-muted">
                  {{ $diggerFleets->count() + $haulerRecords->count() + $supportRecords->count() }} Shift Operasional
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-sm-6 col-lg-3">
        <div class="card card-sm shadow-sm border-0">
          <div class="card-body">
            <div class="row align-items-center">
              <div class="col-auto">
                <span class="{{ $masterUnit->active ? 'bg-green' : 'bg-red' }} text-white avatar">
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10" /></svg>
                </span>
              </div>
              <div class="col">
                <div class="font-weight-medium">Status Unit</div>
                <div class="text-muted">
                  <span class="badge {{ $masterUnit->active ? 'bg-green' : 'bg-red' }}">{{ $masterUnit->active ? 'Active' : 'Inactive' }}</span>
                  @if($masterUnit->service)
                    <span class="badge bg-orange ms-1">In Service</span>
                  @endif
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Main Detail & Specs Card -->
    <div class="card mb-4 shadow-sm">
      <div class="card-header bg-dark text-white">
        <h3 class="card-title m-0">Spesifikasi & Informasi Detail Asset</h3>
      </div>
      <div class="card-body">
        <div class="row g-3">
          <div class="col-md-3">
            <div class="text-muted small">Nomor Unit</div>
            <div class="fw-bold fs-3 text-primary">{{ $masterUnit->nomor_unit }}</div>
          </div>
          <div class="col-md-3">
            <div class="text-muted small">Tipe Unit</div>
            <div class="fw-bold">{{ $masterUnit->type->name ?? '-' }}</div>
          </div>
          <div class="col-md-3">
            <div class="text-muted small">Model Unit</div>
            <div class="fw-bold">{{ $masterUnit->model->name ?? '-' }}</div>
          </div>
          <div class="col-md-3">
            <div class="text-muted small">Site / Lokasi Kerja</div>
            <div class="fw-bold">{{ $masterUnit->siteRelation->name ?? $masterUnit->site ?? '-' }}</div>
          </div>

          <div class="col-md-3">
            <div class="text-muted small">Serial Number Chassis</div>
            <div class="fw-bold">{{ $masterUnit->sn_chassis ?: '-' }}</div>
          </div>
          <div class="col-md-3">
            <div class="text-muted small">Engine Model</div>
            <div class="fw-bold">{{ $masterUnit->engine_model ?: '-' }}</div>
          </div>
          <div class="col-md-3">
            <div class="text-muted small">Serial Number Engine</div>
            <div class="fw-bold">{{ $masterUnit->sn_engine ?: '-' }}</div>
          </div>
          <div class="col-md-3">
            <div class="text-muted small">Engine Make</div>
            <div class="fw-bold">{{ $masterUnit->engine_make ?: '-' }}</div>
          </div>

          <div class="col-md-3">
            <div class="text-muted small">Capacity</div>
            <div class="fw-bold">{{ $masterUnit->capacity ?: '-' }}</div>
          </div>
          <div class="col-md-3">
            <div class="text-muted small">Nomor Polisi</div>
            <div class="fw-bold">{{ $masterUnit->no_polisi ?: '-' }}</div>
          </div>
          <div class="col-md-3">
            <div class="text-muted small">Power (HP / KW)</div>
            <div class="fw-bold">{{ $masterUnit->hp ? $masterUnit->hp . ' HP' : '-' }} {{ $masterUnit->kw ? '/ ' . $masterUnit->kw . ' KW' : '' }}</div>
          </div>
          <div class="col-md-3">
            <div class="text-muted small">Tahun Perakitan</div>
            <div class="fw-bold">{{ $masterUnit->perakitan ?: '-' }}</div>
          </div>

          <div class="col-md-3">
            <div class="text-muted small">Tanggal Diterima</div>
            <div class="fw-bold">{{ $masterUnit->date_receive ? \Carbon\Carbon::parse($masterUnit->date_receive)->format('d M Y') : '-' }}</div>
          </div>
          <div class="col-md-3">
            <div class="text-muted small">Asal (Dari)</div>
            <div class="fw-bold">{{ $masterUnit->dari ?: '-' }}</div>
          </div>
          <div class="col-md-3">
            <div class="text-muted small">Lokasi Spesifik</div>
            <div class="fw-bold">{{ $masterUnit->location ?: '-' }}</div>
          </div>
          <div class="col-md-3">
            <div class="text-muted small">Attachments</div>
            <div class="fw-bold">{{ $masterUnit->attachments ?: '-' }}</div>
          </div>

          @if($masterUnit->remarks)
            <div class="col-md-12">
              <div class="text-muted small">Keterangan / Remarks</div>
              <div class="p-2 bg-light rounded text-dark">{{ $masterUnit->remarks }}</div>
            </div>
          @endif
        </div>
      </div>
    </div>

    <!-- History Tabs Section -->
    <div class="card shadow-sm">
      <div class="card-header">
        <ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs">
          <li class="nav-item">
            <a href="#tab-wo" class="nav-item nav-link active" data-bs-toggle="tab">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /></svg>
              Riwayat Work Order ({{ $workOrders->count() }})
            </a>
          </li>
          <li class="nav-item">
            <a href="#tab-prod" class="nav-item nav-link" data-bs-toggle="tab">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 4l-8 4l8 4l8 -4l-8 -4" /><path d="M4 12l8 4l8 -4" /><path d="M4 16l8 4l8 -4" /></svg>
              Riwayat Produksi Tambang
            </a>
          </li>
          <li class="nav-item">
            <a href="#tab-far-jwo" class="nav-item nav-link" data-bs-toggle="tab">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 9v4" /><path d="M10.363 3.591l-8.106 13.534a1.914 1.914 0 0 0 1.636 2.871h16.214a1.914 1.914 0 0 0 1.636 -2.87l-8.106 -13.536a1.914 1.914 0 0 0 -3.274 0z" /></svg>
              Riwayat FAR & JWO ({{ $fars->count() + $jwos->count() }})
            </a>
          </li>
          <li class="nav-item">
            <a href="#tab-pm" class="nav-item nav-link" data-bs-toggle="tab">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><rect x="4" y="5" width="16" height="16" rx="2" /><line x1="16" y1="3" x2="16" y2="7" /><line x1="8" y1="3" x2="8" y2="7" /></svg>
              PM & Hour Meter ({{ $pmSchedules->count() + $hourMeters->count() }})
            </a>
          </li>
          <li class="nav-item">
            <a href="#tab-activity" class="nav-item nav-link" data-bs-toggle="tab">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 8l0 4l2 2" /><path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0" /></svg>
              Log Aktivitas ({{ $activityLogs->count() }})
            </a>
          </li>
        </ul>
      </div>

      <div class="card-body">
        <div class="tab-content">
          
          <!-- TAB 1: WORK ORDERS -->
          <div class="tab-pane active show" id="tab-wo">
            <div class="table-responsive">
              <table class="table table-vcenter table-striped table-hover">
                <thead>
                  <tr>
                    <th>No. WO</th>
                    <th>Status WO</th>
                    <th>Tipe WO</th>
                    <th>Downtime</th>
                    <th>Waktu BD</th>
                    <th>Waktu RFU</th>
                    <th>Biaya WO (Cost)</th>
                    <th>Problem / Masalah</th>
                    <th>Pelapor</th>
                    <th class="w-1">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($workOrders as $wo)
                    <tr>
                      <td class="font-weight-bold text-primary">{{ $wo->no_wo }}</td>
                      <td><span class="badge bg-blue text-white">{{ $wo->status_wo }}</span></td>
                      <td><span class="badge bg-danger text-white">{{ $wo->tipe_wo }}</span></td>
                      <td><span class="badge bg-secondary">{{ $wo->downtime_code }}</span></td>
                      <td class="small">{{ $wo->waktu_bd ? \Carbon\Carbon::parse($wo->waktu_bd)->format('d/m/Y H:i') : '-' }}</td>
                      <td class="small">{{ $wo->waktu_rfu ? \Carbon\Carbon::parse($wo->waktu_rfu)->format('d/m/Y H:i') : '-' }}</td>
                      <td class="fw-bold text-danger small">
                        @if($wo->maintenance_cost > 0)
                          Rp {{ number_format($wo->maintenance_cost, 0, ',', '.') }}
                        @else
                          <span class="text-muted font-weight-normal">-</span>
                        @endif
                      </td>
                      <td class="small">{{ Str::limit($wo->tasks->first()?->problem ?? '-', 35) }}</td>
                      <td class="small">{{ $wo->creator->name ?? '-' }}</td>
                      <td>
                        <a href="{{ route('work-orders.show', $wo) }}" class="btn btn-sm btn-outline-info">Detail WO</a>
                      </td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="10" class="text-center text-muted py-4">Belum ada riwayat Work Order untuk unit ini.</td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>

          <!-- TAB 2: PRODUKSI TAMBANG -->
          <div class="tab-pane" id="tab-prod">
            
            <h4 class="mb-3 text-azure font-weight-bold">Aktivitas Sebagai Digger (Alat Muat)</h4>
            <div class="table-responsive mb-4">
              <table class="table table-vcenter table-striped">
                <thead>
                  <tr>
                    <th>Tanggal & Shift</th>
                    <th>Material</th>
                    <th>Jarak Angkut</th>
                    <th>Jumlah Hauler (DT)</th>
                    <th>Total Ritasi Fleet</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($diggerFleets as $df)
                    @php
                      $fleetRit = $df->haulers->sum('total_ritasi');
                    @endphp
                    <tr>
                      <td>
                        <div class="fw-bold">{{ \Carbon\Carbon::parse($df->production->date ?? now())->format('d M Y') }}</div>
                        <small class="text-muted">Shift: {{ $df->production->shift ?? '-' }}</small>
                      </td>
                      <td>{{ $df->material_type }}</td>
                      <td>{{ $df->distance ? $df->distance . ' KM' : '-' }}</td>
                      <td>{{ $df->haulers->count() }} Unit DT</td>
                      <td class="fw-bold text-success">{{ $fleetRit }} Rit</td>
                      <td>
                        @if($df->production)
                          <a href="{{ route('productions.show', $df->production->id) }}" class="btn btn-sm btn-outline-info">Lihat Shift</a>
                        @endif
                      </td>
                    </tr>
                  @empty
                    <tr><td colspan="6" class="text-center text-muted py-3">Tidak ada riwayat sebagai Digger.</td></tr>
                  @endforelse
                </tbody>
              </table>
            </div>

            <h4 class="mb-3 text-green font-weight-bold">Aktivitas Sebagai Hauler (Alat Angkut)</h4>
            <div class="table-responsive mb-4">
              <table class="table table-vcenter table-striped">
                <thead>
                  <tr>
                    <th>Tanggal & Shift</th>
                    <th>Digger Pembawa</th>
                    <th>Payload (Ton)</th>
                    <th>Total Ritasi</th>
                    <th>Total Tonase</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($haulerRecords as $hr)
                    <tr>
                      <td>
                        <div class="fw-bold">{{ \Carbon\Carbon::parse($hr->fleet->production->date ?? now())->format('d M Y') }}</div>
                        <small class="text-muted">Shift: {{ $hr->fleet->production->shift ?? '-' }}</small>
                      </td>
                      <td class="fw-bold">{{ $hr->fleet->digger->nomor_unit ?? '-' }}</td>
                      <td>{{ $hr->payload }}</td>
                      <td class="fw-bold">{{ $hr->total_ritasi }} Rit</td>
                      <td class="fw-bold text-success">{{ number_format($hr->payload * $hr->total_ritasi, 2) }} Ton</td>
                      <td>
                        @if($hr->fleet && $hr->fleet->production)
                          <a href="{{ route('productions.show', $hr->fleet->production->id) }}" class="btn btn-sm btn-outline-info">Lihat Shift</a>
                        @endif
                      </td>
                    </tr>
                  @empty
                    <tr><td colspan="6" class="text-center text-muted py-3">Tidak ada riwayat sebagai Hauler.</td></tr>
                  @endforelse
                </tbody>
              </table>
            </div>

            <h4 class="mb-3 text-warning font-weight-bold">Aktivitas Sebagai Support Equipment</h4>
            <div class="table-responsive">
              <table class="table table-vcenter table-striped">
                <thead>
                  <tr>
                    <th>Tanggal & Shift</th>
                    <th>HM Awal</th>
                    <th>HM Akhir</th>
                    <th>Total Jam Kerja</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($supportRecords as $sr)
                    <tr>
                      <td>
                        <div class="fw-bold">{{ \Carbon\Carbon::parse($sr->production->date ?? now())->format('d M Y') }}</div>
                        <small class="text-muted">Shift: {{ $sr->production->shift ?? '-' }}</small>
                      </td>
                      <td>{{ $sr->hm_awal }}</td>
                      <td>{{ $sr->hm_akhir }}</td>
                      <td class="fw-bold">{{ ($sr->hm_akhir && $sr->hm_awal) ? ($sr->hm_akhir - $sr->hm_awal) : '-' }} Jam</td>
                      <td>
                        @if($sr->production)
                          <a href="{{ route('productions.show', $sr->production->id) }}" class="btn btn-sm btn-outline-info">Lihat Shift</a>
                        @endif
                      </td>
                    </tr>
                  @empty
                    <tr><td colspan="5" class="text-center text-muted py-3">Tidak ada riwayat sebagai Support.</td></tr>
                  @endforelse
                </tbody>
              </table>
            </div>

          </div>

          <!-- TAB 3: FAR & JWO -->
          <div class="tab-pane" id="tab-far-jwo">
            <h4 class="mb-3 text-danger font-weight-bold">Failure Analysis Reports (FAR)</h4>
            <div class="table-responsive mb-4">
              <table class="table table-vcenter table-striped">
                <thead>
                  <tr>
                    <th>No. FAR</th>
                    <th>Judul / Kejadian</th>
                    <th>Tanggal Kejadian</th>
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($fars as $far)
                    <tr>
                      <td class="fw-bold text-danger">{{ $far->no_far }}</td>
                      <td>{{ $far->title ?? $far->kejadian ?? '-' }}</td>
                      <td>{{ $far->created_at ? $far->created_at->format('d/m/Y') : '-' }}</td>
                      <td><span class="badge bg-secondary">{{ $far->status ?? 'Draft' }}</span></td>
                      <td><a href="{{ route('fars.show', $far) }}" class="btn btn-sm btn-outline-info">Lihat FAR</a></td>
                    </tr>
                  @empty
                    <tr><td colspan="5" class="text-center text-muted py-3">Belum ada FAR untuk unit ini.</td></tr>
                  @endforelse
                </tbody>
              </table>
            </div>

            <h4 class="mb-3 text-azure font-weight-bold">Job Work Orders (JWO)</h4>
            <div class="table-responsive">
              <table class="table table-vcenter table-striped">
                <thead>
                  <tr>
                    <th>No. JWO</th>
                    <th>Pekerjaan</th>
                    <th>Vendor / Contractor</th>
                    <th>Biaya (Cost)</th>
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($jwos as $jwo)
                    <tr>
                      <td class="fw-bold text-azure">{{ $jwo->no_jwo }}</td>
                      <td>{{ $jwo->problem_description ?? $jwo->job_description ?? '-' }}</td>
                      <td>{{ $jwo->vendor->name ?? '-' }}</td>
                      <td class="fw-bold text-danger">
                        @if($jwo->cost > 0)
                          Rp {{ number_format($jwo->cost, 0, ',', '.') }}
                        @else
                          <span class="text-muted font-weight-normal">-</span>
                        @endif
                      </td>
                      <td><span class="badge bg-blue">{{ $jwo->status ?? 'Open' }}</span></td>
                      <td><a href="{{ route('jwos.show', $jwo) }}" class="btn btn-sm btn-outline-info">Lihat JWO</a></td>
                    </tr>
                  @empty
                    <tr><td colspan="6" class="text-center text-muted py-3">Belum ada JWO untuk unit ini.</td></tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>

          <!-- TAB 4: PM SCHEDULE & HOUR METER -->
          <div class="tab-pane" id="tab-pm">
            <h4 class="mb-3 text-primary font-weight-bold">Jadwal Preventative Maintenance (PM)</h4>
            <div class="table-responsive mb-4">
              <table class="table table-vcenter table-striped">
                <thead>
                  <tr>
                    <th>Paket PM</th>
                    <th>Target Hour Meter</th>
                    <th>Tanggal Terakhir Servis</th>
                    <th>Status PM</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($pmSchedules as $pm)
                    <tr>
                      <td class="fw-bold">{{ $pm->pm_package ?? 'PM General' }}</td>
                      <td>{{ $pm->target_hm }} HM</td>
                      <td>{{ $pm->last_service_date ? \Carbon\Carbon::parse($pm->last_service_date)->format('d/m/Y') : '-' }}</td>
                      <td><span class="badge bg-info">{{ $pm->status ?? 'Scheduled' }}</span></td>
                    </tr>
                  @empty
                    <tr><td colspan="4" class="text-center text-muted py-3">Belum ada jadwal PM.</td></tr>
                  @endforelse
                </tbody>
              </table>
            </div>

            <h4 class="mb-3 text-success font-weight-bold">Pencatatan Hour Meter (HM)</h4>
            <div class="table-responsive">
              <table class="table table-vcenter table-striped">
                <thead>
                  <tr>
                    <th>Tanggal</th>
                    <th>Hour Meter (HM)</th>
                    <th>Pencatat</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($hourMeters as $hm)
                    <tr>
                      <td>{{ $hm->date ? \Carbon\Carbon::parse($hm->date)->format('d/m/Y') : $hm->created_at->format('d/m/Y') }}</td>
                      <td class="fw-bold text-success">{{ number_format($hm->reading, 2) }} HM</td>
                      <td class="small">{{ $hm->recorder->name ?? '-' }}</td>
                    </tr>
                  @empty
                    <tr><td colspan="3" class="text-center text-muted py-3">Belum ada catatan Hour Meter.</td></tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>

          <!-- TAB 5: LOG AKTIVITAS -->
          <div class="tab-pane" id="tab-activity">
            <div class="table-responsive">
              <table class="table table-vcenter table-striped">
                <thead>
                  <tr>
                    <th>Waktu</th>
                    <th>User</th>
                    <th>Aktivitas Perubahan</th>
                    <th>Detail Perubahan</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($activityLogs as $log)
                    <tr>
                      <td class="small" style="white-space: nowrap;">{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                      <td class="fw-bold small">{{ $log->causer->name ?? 'System' }}</td>
                      <td><span class="badge bg-secondary">{{ $log->description }}</span></td>
                      <td class="small text-muted">
                        @if($log->changes())
                          <pre class="m-0 bg-light p-1 rounded" style="font-size: 10px;">{{ json_encode($log->changes(), JSON_PRETTY_PRINT) }}</pre>
                        @else
                          -
                        @endif
                      </td>
                    </tr>
                  @empty
                    <tr><td colspan="4" class="text-center text-muted py-3">Belum ada audit log untuk unit ini.</td></tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>

        </div>
      </div>
    </div>

  </div>
</div>
@endsection
