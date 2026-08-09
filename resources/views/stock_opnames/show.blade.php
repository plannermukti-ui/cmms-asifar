@extends('layouts.tabler')

@section('title', 'Detail Stock Opname - CMMS')

@section('content')
<div class="page-header d-print-none">
  <div class="row align-items-center">
    <div class="col">
      <h2 class="page-title">
        Detail Stock Opname #AUD-{{ str_pad($stockOpname->id, 4, '0', STR_PAD_LEFT) }}
      </h2>
    </div>
    <div class="col-auto ms-auto d-print-none">
      <a href="{{ route('stock-opnames.index') }}" class="btn btn-secondary">
        Kembali
      </a>
    </div>
  </div>
</div>

<div class="card mt-3">
    <div class="card-body">
        <div class="row mb-4">
            <div class="col-md-6">
                <p><strong>Tanggal Audit:</strong> {{ \Carbon\Carbon::parse($stockOpname->tanggal_audit)->format('d M Y') }}</p>
                <p><strong>Auditor:</strong> {{ $stockOpname->auditor->name ?? '-' }}</p>
            </div>
            <div class="col-md-6 text-md-end">
                <p><strong>Lokasi:</strong> {{ $stockOpname->tipe_audit }}</p>
                @if($stockOpname->tipe_audit === 'Mechanic')
                    <p><strong>Mekanik:</strong> {{ $stockOpname->mechanic->nama_lengkap ?? '-' }}</p>
                @endif
            </div>
        </div>
        
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Tool</th>
                    <th class="text-center">Stok Sistem (Sblm)</th>
                    <th class="text-center">Stok Fisik Aktual</th>
                    <th class="text-center">Selisih</th>
                </tr>
            </thead>
            <tbody>
                @foreach($stockOpname->details as $detail)
                <tr>
                    <td>{{ $detail->tool->name ?? '-' }}</td>
                    <td class="text-center">{{ $detail->stok_sistem }}</td>
                    <td class="text-center"><strong>{{ $detail->stok_fisik }}</strong></td>
                    <td class="text-center">
                        @if($detail->selisih > 0)
                            <span class="text-success">+{{ $detail->selisih }}</span>
                        @elseif($detail->selisih < 0)
                            <span class="text-danger">{{ $detail->selisih }}</span>
                        @else
                            <span class="text-muted">0</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection