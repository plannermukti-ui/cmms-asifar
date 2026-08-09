@extends('layouts.tabler')

@section('title', 'Stock Opname - CMMS')

@section('content')
<div class="page-header d-print-none">
  <div class="row align-items-center">
    <div class="col">
      <h2 class="page-title">
        Riwayat Stock Opname
      </h2>
    </div>
    <div class="col-auto ms-auto d-print-none">
      @can('create_stock_opnames')
      <a href="{{ route('stock-opnames.create') }}" class="btn btn-primary">
        Mulai Stock Opname Baru
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
                    <th>ID Audit</th>
                    <th>Tanggal Audit</th>
                    <th>Tipe Lokasi</th>
                    <th>Mekanik</th>
                    <th>Auditor</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($opnames as $opname)
                <tr>
                    <td>#AUD-{{ str_pad($opname->id, 4, '0', STR_PAD_LEFT) }}</td>
                    <td>{{ \Carbon\Carbon::parse($opname->tanggal_audit)->format('d M Y') }}</td>
                    <td>{{ $opname->tipe_audit }}</td>
                    <td>{{ $opname->mechanic->nama_lengkap ?? '-' }}</td>
                    <td>{{ $opname->auditor->name ?? '-' }}</td>
                    <td>
                        <a href="{{ route('stock-opnames.show', $opname) }}" class="btn btn-sm btn-info">Detail</a>
                        @can('delete_stock_opnames')
                        <form action="{{ route('stock-opnames.destroy', $opname) }}" method="post" class="d-inline" onsubmit="return confirm('Yakin menghapus data stock opname ini? Stok TIDAK akan direvert.');">
                          @csrf
                          @method('DELETE')
                          <button type="submit" class="btn btn-sm btn-danger">Hapus Log</button>
                        </form>
                        @endcan
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center">Belum ada data stock opname.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($opnames->hasPages())
      <div class="card-footer">
        {{ $opnames->links('pagination::bootstrap-5') }}
      </div>
    @endif
</div>
@endsection