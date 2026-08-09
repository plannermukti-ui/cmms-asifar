@extends('layouts.tabler')

@section('title', 'Mulai Stock Opname - CMMS')

@section('content')
<div class="page-header d-print-none">
  <div class="row align-items-center">
    <div class="col">
      <h2 class="page-title">
        Mulai Stock Opname
      </h2>
    </div>
    <div class="col-auto ms-auto d-print-none">
      <a href="{{ route('stock-opnames.index') }}" class="btn btn-secondary">
        Batal
      </a>
    </div>
  </div>
</div>

<div class="row mt-3">
  <div class="col-12">
    <!-- Filter form -->
    <div class="card mb-3">
        <div class="card-body">
            <form action="{{ route('stock-opnames.create') }}" method="GET" class="row align-items-end">
                <div class="col-md-3">
                    <label class="form-label">Tipe Lokasi Audit</label>
                    <select name="tipe_audit" class="form-select" id="tipeAuditSelect" onchange="toggleMekanikFilter()">
                        <option value="ToolRoom" {{ request('tipe_audit') == 'ToolRoom' ? 'selected' : '' }}>Tool Room (Gudang)</option>
                        <option value="Mechanic" {{ request('tipe_audit') == 'Mechanic' ? 'selected' : '' }}>Mekanik</option>
                    </select>
                </div>
                <div class="col-md-4" id="mechanicFilterWrap" style="{{ request('tipe_audit') == 'Mechanic' ? '' : 'display:none;' }}">
                    <label class="form-label">Pilih Mekanik</label>
                    <select name="mechanic_id" class="form-select">
                        <option value="">-- Pilih Mekanik --</option>
                        @foreach($mechanics as $mechanic)
                            <option value="{{ $mechanic->id }}" {{ request('mechanic_id') == $mechanic->id ? 'selected' : '' }}>{{ $mechanic->nama_lengkap }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-secondary w-100">Load Data Sistem</button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Opname form -->
    <div class="card">
        <form action="{{ route('stock-opnames.store') }}" method="post">
        @csrf
        <input type="hidden" name="tipe_audit" value="{{ request('tipe_audit', 'ToolRoom') }}">
        <input type="hidden" name="mechanic_id" value="{{ request('mechanic_id') }}">
        
        <div class="card-header border-0">
            <div class="row align-items-center w-100">
                <div class="col-md-3">
                    <label class="form-label">Tanggal Audit Fisik</label>
                    <input type="date" class="form-control" name="tanggal_audit" value="{{ date('Y-m-d') }}" required>
                </div>
                <div class="col text-end">
                    <span class="text-muted">Menampilkan {{ $stocks->count() }} item pada {{ request('tipe_audit', 'ToolRoom') }}</span>
                </div>
            </div>
        </div>
        
        <div class="table-responsive">
            <table class="table card-table table-vcenter">
                <thead>
                    <tr>
                        <th>Tool Name</th>
                        <th class="text-center" width="150">Stok Sistem Saat Ini</th>
                        <th class="text-center" width="150">Stok Fisik Aktual</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stocks as $index => $stock)
                    <tr>
                        <td>
                            {{ $stock->tool->name ?? '-' }}
                            <input type="hidden" name="tools[{{ $index }}][tool_id]" value="{{ $stock->tool_id }}">
                        </td>
                        <td class="text-center">
                            <strong>{{ $stock->quantity }}</strong>
                            <input type="hidden" name="tools[{{ $index }}][stok_sistem]" value="{{ $stock->quantity }}">
                        </td>
                        <td>
                            <input type="number" class="form-control text-center" name="tools[{{ $index }}][stok_fisik]" value="{{ $stock->quantity }}" min="0" required>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center">Tidak ada stok yang tercatat untuk lokasi ini. Silakan tambahkan stok baru terlebih dahulu jika kosong.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($stocks->count() > 0)
        <div class="card-footer text-end">
            <button type="submit" class="btn btn-primary" onclick="return confirm('Data akan disimpan dan Stok Sistem akan di-update sesuai Stok Fisik Aktual yang anda input. Lanjutkan?')">Simpan Hasil Stock Opname</button>
        </div>
        @endif
        </form>
    </div>
  </div>
</div>

<script>
    function toggleMekanikFilter() {
        var val = document.getElementById('tipeAuditSelect').value;
        var wrap = document.getElementById('mechanicFilterWrap');
        if(val === 'Mechanic') { wrap.style.display = 'block'; } else { wrap.style.display = 'none'; }
    }
</script>
@endsection