@extends('layouts.tabler')

@section('title', 'Dashboard Fuel Management System (FMS)')

@section('content')
<div class="page-header d-print-none">
  <div class="container-xl">
    <div class="row g-2 align-items-center">
      <div class="col">
        <div class="page-pretitle text-uppercase font-monospace text-primary">Fuel Management System</div>
        <h2 class="page-title d-flex align-items-center gap-2">
          <svg xmlns="http://www.w3.org/2000/svg" class="icon text-azure" width="28" height="28" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 21v-14a3 3 0 0 1 3 -3h6a3 3 0 0 1 3 3v14" /><path d="M9 11l6 0" /><path d="M6 21l12 0" /><path d="M16 13l2.5 2.5a2 2 0 0 1 0 2.828l-1.328 1.328a2 2 0 0 1 -2.828 0l-2.344 -2.344" /><path d="M18 10v-4" /></svg>
          Dashboard & Realtime Stock FMS
        </h2>
      </div>
      <div class="col-auto ms-auto d-flex gap-2">
        <form method="GET" action="{{ route('fuel.dashboard') }}" class="d-flex align-items-center gap-2">
          <select name="site_id" class="form-select form-select-sm" onchange="this.form.submit()">
            <option value="">-- Semua Site --</option>
            @foreach($sites as $s)
              <option value="{{ $s->id }}" {{ $siteId == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
            @endforeach
          </select>
        </form>
        @can('create_fuel_receivings')
        <a href="{{ route('fuel.receivings.create') }}" class="btn btn-primary btn-sm d-flex align-items-center gap-1">
          <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
          Terima BBM
        </a>
        @endcan
        @can('create_fuel_distributions')
        <a href="{{ route('fuel.distributions.create') }}" class="btn btn-success btn-sm d-flex align-items-center gap-1">
          <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 21v-14a3 3 0 0 1 3 -3h6a3 3 0 0 1 3 3v14" /><path d="M9 11l6 0" /><path d="M6 21l12 0" /></svg>
          Distribusi Shift Baru
        </a>
        @endcan
      </div>
    </div>
  </div>
</div>

<div class="page-body">
  <div class="container-xl">
    
    <!-- Top Summary Metrics Cards -->
    <div class="row row-cards mb-4">
      <div class="col-sm-6 col-lg-3">
        <div class="card card-sm shadow-sm border-0 border-start border-primary border-3">
          <div class="card-body">
            <div class="row align-items-center">
              <div class="col-auto">
                <span class="bg-primary-lt avatar avatar-md">
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon text-primary" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0" /><path d="M12 7v5l3 3" /></svg>
                </span>
              </div>
              <div class="col">
                <div class="text-secondary small font-monospace text-uppercase">Total Stok Gabungan</div>
                <div class="fw-bold fs-2 text-primary">{{ number_format($overallTotalStock, 0, ',', '.') }} <span class="fs-4 text-muted">Liter</span></div>
                <div class="text-muted small">Tangki Timbun + Fuel Truck</div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-sm-6 col-lg-3">
        <div class="card card-sm shadow-sm border-0 border-start border-azure border-3">
          <div class="card-body">
            <div class="row align-items-center">
              <div class="col-auto">
                <span class="bg-azure-lt avatar avatar-md">
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon text-azure" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 21v-14a3 3 0 0 1 3 -3h6a3 3 0 0 1 3 3v14" /><path d="M9 11l6 0" /><path d="M6 21l12 0" /></svg>
                </span>
              </div>
              <div class="col">
                <div class="text-secondary small font-monospace text-uppercase">Stok Tangki Timbun</div>
                <div class="fw-bold fs-2 text-azure">{{ number_format($totalStorageStock, 0, ',', '.') }} <span class="fs-4 text-muted">Liter</span></div>
                <div class="text-muted small">Kapasitas: {{ number_format($totalStorageCapacity, 0, ',', '.') }} L</div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-sm-6 col-lg-3">
        <div class="card card-sm shadow-sm border-0 border-start border-warning border-3">
          <div class="card-body">
            <div class="row align-items-center">
              <div class="col-auto">
                <span class="bg-warning-lt avatar avatar-md">
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon text-warning" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 17m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M17 17m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M5 17h-2v-11a1 1 0 0 1 1 -1h9v12m-4 0h6m4 0h2v-6h-8m0 -5h5l3 5" /></svg>
                </span>
              </div>
              <div class="col">
                <div class="text-secondary small font-monospace text-uppercase">Stok di Fuel Truck</div>
                <div class="fw-bold fs-2 text-warning">{{ number_format($totalTruckStock, 0, ',', '.') }} <span class="fs-4 text-muted">Liter</span></div>
                <div class="text-muted small">{{ $trucks->count() }} Unit Fuel Truck Aktif</div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-sm-6 col-lg-3">
        <div class="card card-sm shadow-sm border-0 border-start border-success border-3">
          <div class="card-body">
            <div class="row align-items-center">
              <div class="col-auto">
                <span class="bg-success-lt avatar avatar-md">
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon text-success" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 12l2 2l4 -4" /><path d="M12 3a9 9 0 1 0 9 9" /></svg>
                </span>
              </div>
              <div class="col">
                <div class="text-secondary small font-monospace text-uppercase">Distribusi Hari Ini</div>
                <div class="fw-bold fs-2 text-success">{{ number_format($todayDistributed, 0, ',', '.') }} <span class="fs-4 text-muted">Liter</span></div>
                <div class="text-muted small">Inbound Hari Ini: {{ number_format($todayReceived, 0, ',', '.') }} L</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Section 1: Real-time Tangki Timbun & SPBU Station Cards -->
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h3 class="card-title fw-bold text-body m-0 d-flex align-items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="icon text-azure" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 21v-14a3 3 0 0 1 3 -3h6a3 3 0 0 1 3 3v14" /></svg>
        Status Tangki Timbun & Fuel Station Real-time
      </h3>
      <a href="{{ route('fuel.storages.index') }}" class="btn btn-sm btn-link text-decoration-none">Kelola Tangki &raquo;</a>
    </div>

    <div class="row row-cards mb-4">
      @forelse($storages as $storage)
      <div class="col-md-6 col-lg-4">
        <div class="card shadow-sm border h-100">
          <div class="card-body p-3">
            <div class="d-flex justify-content-between align-items-start mb-2">
              <div>
                <span class="badge bg-blue-lt font-monospace px-2 py-0.5">{{ $storage->code }}</span>
                <h4 class="card-title fw-bold mt-1 mb-0">{{ $storage->name }}</h4>
                <div class="text-secondary small">{{ $storage->type }} • {{ $storage->site->name ?? 'Semua Site' }}</div>
              </div>
              <span class="badge {{ $storage->status_badge_class }} fw-bold px-2 py-1">
                {{ $storage->fill_percentage }}%
              </span>
            </div>

            <!-- Visual Fuel Gauge Progress Bar -->
            <div class="progress mb-2" style="height: 12px;">
              <div class="progress-bar {{ $storage->fill_percentage <= 20 ? 'bg-danger' : ($storage->fill_percentage <= 40 ? 'bg-warning' : 'bg-primary') }}" 
                   style="width: {{ $storage->fill_percentage }}%" 
                   role="progressbar" 
                   aria-valuenow="{{ $storage->fill_percentage }}" 
                   aria-valuemin="0" 
                   aria-valuemax="100"></div>
            </div>

            <div class="d-flex justify-content-between align-items-center small text-secondary mb-2">
              <span>Stok: <strong class="text-body">{{ number_format($storage->current_stock, 0, ',', '.') }} L</strong></span>
              <span>Kapasitas: {{ number_format($storage->capacity, 0, ',', '.') }} L</span>
            </div>

            <div class="bg-body-tertiary rounded p-2 border small d-flex justify-content-between align-items-center">
              <div>
                <span class="text-muted" style="font-size: 0.7rem;">TOTALIZER FLOWMETER:</span>
                <div class="fw-bold font-monospace text-primary">{{ number_format($storage->current_totalizer, 2, ',', '.') }} L</div>
              </div>
              <a href="{{ route('fuel.storages.show', $storage) }}" class="btn btn-xs btn-outline-primary">Kartu Stok</a>
            </div>
          </div>
        </div>
      </div>
      @empty
      <div class="col-12">
        <div class="card card-body text-center text-muted py-4">
          Belum ada Tangki Timbun / Fuel Station terdaftar. <a href="{{ route('fuel.storages.index') }}" class="mt-2">Tambah Tangki Sekarang</a>
        </div>
      </div>
      @endforelse
    </div>

    <!-- Section 2: Real-time Fuel Truck Units Cards -->
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h3 class="card-title fw-bold text-body m-0 d-flex align-items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="icon text-warning" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 17m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M17 17m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M5 17h-2v-11a1 1 0 0 1 1 -1h9v12m-4 0h6m4 0h2v-6h-8m0 -5h5l3 5" /></svg>
        Status Armada Fuel Truck (Mobile Dispenser)
      </h3>
      <a href="{{ route('fuel.trucks.index') }}" class="btn btn-sm btn-link text-decoration-none">Kelola Fuel Truck &raquo;</a>
    </div>

    <div class="row row-cards mb-4">
      @forelse($trucks as $truck)
      <div class="col-md-6 col-lg-3">
        <div class="card shadow-sm border h-100">
          <div class="card-body p-3">
            <div class="d-flex justify-content-between align-items-start mb-2">
              <div>
                <span class="badge bg-yellow-lt font-monospace px-2 py-0.5">FUEL TRUCK</span>
                <h4 class="card-title fw-bold mt-1 mb-0">{{ $truck->masterUnit->nomor_unit ?? '-' }}</h4>
                <div class="text-secondary small">{{ $truck->site->name ?? 'Semua Site' }}</div>
              </div>
              <span class="badge {{ $truck->status_badge_class }} fw-bold px-2 py-1">
                {{ $truck->fill_percentage }}%
              </span>
            </div>

            <!-- Progress Bar -->
            <div class="progress mb-2" style="height: 10px;">
              <div class="progress-bar {{ $truck->fill_percentage <= 20 ? 'bg-danger' : ($truck->fill_percentage <= 40 ? 'bg-warning' : 'bg-warning') }}" 
                   style="width: {{ $truck->fill_percentage }}%" 
                   role="progressbar"></div>
            </div>

            <div class="d-flex justify-content-between align-items-center small text-secondary mb-2">
              <span>Stok: <strong class="text-body">{{ number_format($truck->current_stock, 0, ',', '.') }} L</strong></span>
              <span>Kapasitas: {{ number_format($truck->capacity, 0, ',', '.') }} L</span>
            </div>

            <div class="bg-body-tertiary rounded p-1.5 border small d-flex justify-content-between align-items-center">
              <div>
                <span class="text-muted" style="font-size: 0.65rem;">TOTALIZER TERKINI:</span>
                <div class="fw-bold font-monospace text-warning">{{ number_format($truck->current_totalizer, 2, ',', '.') }}</div>
              </div>
              <a href="{{ route('fuel.trucks.show', $truck) }}" class="btn btn-xs btn-outline-warning">Detail</a>
            </div>
          </div>
        </div>
      </div>
      @empty
      <div class="col-12">
        <div class="card card-body text-center text-muted py-4">
          Belum ada Fuel Truck ditetapkan. <a href="{{ route('fuel.trucks.index') }}" class="mt-2">Tetapkan Unit Fuel Truck</a>
        </div>
      </div>
      @endforelse
    </div>

    <!-- Section 3: Chart Inbound vs Outbound & Recent Stock Logs -->
    <div class="row row-cards">
      <div class="col-lg-7">
        <div class="card shadow-sm border">
          <div class="card-header border-0 pb-0">
            <h3 class="card-title fw-bold">Grafik Arus BBM 7 Hari Terakhir (Inbound vs Outbound)</h3>
          </div>
          <div class="card-body">
            <div id="chart-fuel-trend" style="min-height: 260px;"></div>
          </div>
        </div>
      </div>

      <div class="col-lg-5">
        <div class="card shadow-sm border h-100">
          <div class="card-header border-0 pb-0 d-flex justify-content-between align-items-center">
            <h3 class="card-title fw-bold">Transaksi & Mutasi Terkini</h3>
            <a href="{{ route('fuel.reports.stock-card') }}" class="btn btn-xs btn-link text-decoration-none">Lihat Semua &raquo;</a>
          </div>
          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table table-vcenter card-table table-striped table-sm">
                <thead>
                  <tr>
                    <th>Waktu / Jenis</th>
                    <th>Lokasi / Unit</th>
                    <th class="text-end">Qty (L)</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($recentLogs as $log)
                  <tr>
                    <td>
                      <div class="small fw-semibold text-body">{{ $log->transaction_type }}</div>
                      <div class="text-muted" style="font-size: 0.68rem;">{{ $log->date_time ? $log->date_time->format('d/m H:i') : '-' }}</div>
                    </td>
                    <td>
                      <span class="small text-truncate d-inline-block" style="max-width: 130px;" title="{{ $log->device_name }}">
                        {{ $log->device_name }}
                      </span>
                    </td>
                    <td class="text-end font-monospace">
                      @if($log->qty_in > 0)
                        <span class="text-success fw-bold">+{{ number_format($log->qty_in, 0, ',', '.') }}</span>
                      @else
                        <span class="text-danger fw-bold">-{{ number_format($log->qty_out, 0, ',', '.') }}</span>
                      @endif
                    </td>
                  </tr>
                  @empty
                  <tr>
                    <td colspan="3" class="text-center text-muted py-3">Belum ada transaksi BBM tercatat.</td>
                  </tr>
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

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const isDark = document.body.getAttribute('data-bs-theme') === 'dark' || document.documentElement.getAttribute('data-bs-theme') === 'dark';
    const textColor = isDark ? '#94a3b8' : '#64748b';
    const gridColor = isDark ? '#334155' : '#e2e8f0';

    const options = {
        series: [{
            name: 'Penerimaan (Inbound)',
            data: @json($chartInbound)
        }, {
            name: 'Distribusi Unit (Outbound)',
            data: @json($chartOutbound)
        }],
        chart: {
            type: 'bar',
            height: 260,
            toolbar: { show: false },
            fontFamily: 'inherit',
            parentHeightOffset: 0
        },
        colors: ['#206bc4', '#2fb344'],
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: '45%',
                borderRadius: 4
            },
        },
        dataLabels: { enabled: false },
        stroke: { show: true, width: 2, colors: ['transparent'] },
        xaxis: {
            categories: @json($chartDates),
            labels: { style: { colors: textColor } }
        },
        yaxis: {
            labels: {
                style: { colors: textColor },
                formatter: function (val) { return Number(val).toLocaleString('id-ID') + ' L'; }
            }
        },
        grid: { borderColor: gridColor, strokeDashArray: 3 },
        tooltip: {
            theme: isDark ? 'dark' : 'light',
            y: {
                formatter: function (val) { return Number(val).toLocaleString('id-ID') + ' Liter'; }
            }
        },
        legend: {
            labels: { colors: textColor }
        }
    };

    const chart = new ApexCharts(document.querySelector("#chart-fuel-trend"), options);
    chart.render();
});
</script>
@endpush
@endsection
