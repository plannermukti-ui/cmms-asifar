@extends('layouts.tabler')

@section('title', 'Manajemen Stok Tool - CMMS')

@section('content')
<div class="page-header d-print-none">
  <div class="row align-items-center">
    <div class="col">
      <h2 class="page-title">
        Detail Stok Tool
      </h2>
    </div>
    <div class="col-auto ms-auto d-print-none">
      <a href="{{ route('tools.index') }}" class="btn btn-secondary">
        Kembali ke Master
      </a>
      @can('create_tool_stocks')
      <a href="{{ route('tool-stocks.create') }}" class="btn btn-primary">
        Tambah Stok Manual
      </a>
      @endcan
    </div>
  </div>
</div>

<div class="card mt-3">
    <div class="table-responsive">
        <table class="table card-table table-vcenter">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tool</th>
                    <th>Lokasi (Tipe)</th>
                    <th>Mekanik</th>
                    <th>Kuantitas</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($stocks as $stock)
                <tr>
                    <td>{{ $stock->id }}</td>
                    <td>{{ $stock->tool->name ?? '-' }}</td>
                    <td>
                        @if($stock->location_type == 'ToolRoom')
                            <span class="badge bg-primary">Tool Room</span>
                        @else
                            <span class="badge bg-indigo">Mekanik</span>
                        @endif
                    </td>
                    <td>{{ $stock->mechanic->nama_lengkap ?? '-' }}</td>
                    <td><strong>{{ $stock->quantity }}</strong></td>
                    <td>
                        @can('edit_tool_stocks')
                        <a href="{{ route('tool-stocks.edit', $stock) }}" class="btn btn-sm btn-primary">Edit Qty</a>
                        @endcan
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center">Belum ada rincian stok.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($stocks->hasPages())
      <div class="card-footer">
        {{ $stocks->links('pagination::bootstrap-5') }}
      </div>
    @endif
</div>
@endsection