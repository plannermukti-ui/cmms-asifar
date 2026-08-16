@extends('layouts.tabler')

@section('title', 'Detail Mekanik - CMMS')

@section('content')
<div class="page-header d-print-none">
  <div class="row align-items-center">
    <div class="col">
      <h2 class="page-title">
        Detail Mekanik: {{ $mechanic->nama_lengkap }}
      </h2>
    </div>
    <div class="col-auto ms-auto d-print-none">
      <a href="{{ route('mechanics.index') }}" class="btn btn-secondary">
        Kembali
      </a>
    </div>
  </div>
</div>

<div class="row row-cards mt-3">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="datagrid-item">
                            <div class="datagrid-title">Site</div>
                            <div class="datagrid-content">{{ $mechanic->site->name ?? '-' }}</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="datagrid-item">
                            <div class="datagrid-title">Jabatan</div>
                            <div class="datagrid-content">{{ $mechanic->jabatan->nama_jabatan ?? '-' }}</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="datagrid-item">
                            <div class="datagrid-title">Status</div>
                            <div class="datagrid-content">
                                @if($mechanic->is_active)
                                    <span class="badge bg-success">Aktif</span>
                                @else
                                    <span class="badge bg-danger">Non-Aktif</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Stats Cards -->
    <div class="col-sm-6 col-lg-4">
        <div class="card card-sm">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <span class="bg-primary text-white avatar">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M15 21h-9a3 3 0 0 1 -3 -3v-1h10v2a2 2 0 0 0 4 0v-14a2 2 0 1 1 2 2h-2m2 -4h-2.28m-1 0h-7.42m-2.6 0h-3.7" /><path d="M12 21v-14" /></svg>
                        </span>
                    </div>
                    <div class="col">
                        <div class="font-weight-medium">Total Work Order Dikerjakan</div>
                        <div class="text-secondary">{{ $totalWO }} Work Order</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-sm-6 col-lg-4">
        <div class="card card-sm">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <span class="bg-green text-white avatar">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="9" /><polyline points="12 7 12 12 15 15" /></svg>
                        </span>
                    </div>
                    <div class="col">
                        <div class="font-weight-medium">Total Waktu Pengerjaan</div>
                        <div class="text-secondary">{{ number_format($totalDuration, 1) }} Jam</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-sm-6 col-lg-4">
        <div class="card card-sm">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <span class="bg-warning text-white avatar">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 10h3v-3l-3.5 -3.5a6 6 0 0 1 8 8l6 6a2 2 0 0 1 -3 3l-6 -6a6 6 0 0 1 -8 -8l3.5 3.5" /></svg>
                        </span>
                    </div>
                    <div class="col">
                        <div class="font-weight-medium">Tool Dialokasikan</div>
                        <div class="text-secondary">{{ $totalTools }} Pcs</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Table of Allocated Tools -->
    <div class="col-12 mt-3">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Daftar Tool yang Dialokasikan ke Mekanik</h3>
            </div>
            <div class="table-responsive">
                <table class="table card-table table-vcenter">
                    <thead>
                        <tr>
                            <th>Nama Tool</th>
                            <th>Kategori</th>
                            <th>Kuantitas</th>
                            <th>Cost (Rp)</th>
                            <th>Total Cost (Rp)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($toolsAllocated as $stock)
                        <tr>
                            <td>{{ $stock->tool->name ?? '-' }}</td>
                            <td>{{ $stock->tool->category->name ?? '-' }}</td>
                            <td><strong>{{ $stock->quantity }}</strong></td>
                            <td>{{ number_format($stock->tool->price ?? 0, 0, ',', '.') }}</td>
                            <td><strong>{{ number_format(($stock->tool->price ?? 0) * $stock->quantity, 0, ',', '.') }}</strong></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">Belum ada tool yang dialokasikan ke mekanik ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
