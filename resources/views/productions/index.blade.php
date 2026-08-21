@extends('layouts.tabler')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title text-uppercase font-weight-bold">
                    Laporan Produksi Harian (Shift)
                </h2>
                <div class="text-muted mt-1">Data produksi alat berat pertambangan (Loading & Hauling) per Shift</div>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <a href="{{ route('productions.create') }}" class="btn btn-primary d-none d-sm-inline-block shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="12" y1="5" x2="12" y2="19" /><line x1="5" y1="12" x2="19" y2="12" /></svg>
                        Buat Laporan Shift Baru
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        
        @if(session('success'))
            <div class="alert alert-success alert-dismissible bg-success-lt shadow-sm" role="alert">
                <div class="d-flex">
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10" /></svg>
                    </div>
                    <div>{{ session('success') }}</div>
                </div>
                <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
            </div>
        @endif

        <div class="card shadow-sm border-0">
            <div class="table-responsive">
                <table class="table table-vcenter card-table table-striped table-hover">
                    <thead>
                        <tr>
                            <th class="w-1">No.</th>
                            <th>Tanggal & Shift</th>
                            <th>Total Fleets</th>
                            <th>Total Ritasi</th>
                            <th>Total Produksi (BCM)</th>
                            <th class="w-1">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($productions as $index => $prod)
                            @php
                                $totalRit = 0;
                                $totalTon = 0;
                                foreach($prod->fleets as $fleet) {
                                    foreach($fleet->haulers as $h) {
                                        $totalRit += $h->total_ritasi;
                                        $totalTon += ($h->payload * $h->total_ritasi);
                                    }
                                }
                            @endphp
                            <tr>
                                <td>{{ $productions->firstItem() + $index }}</td>
                                <td>
                                    <div class="font-weight-bold">{{ \Carbon\Carbon::parse($prod->date)->format('d M Y') }}</div>
                                    <div class="text-muted">Shift: <span class="badge {{ $prod->shift == 'DS' ? 'bg-orange-lt' : 'bg-dark-lt' }}">{{ $prod->shift }}</span></div>
                                </td>
                                <td>
                                    <span class="badge bg-blue-lt">{{ $prod->fleets->count() }} Fleet(s)</span>
                                </td>
                                <td class="font-weight-bold">
                                    {{ number_format($totalRit) }} Rit
                                </td>
                                <td class="font-weight-bold text-success">
                                    {{ number_format($totalTon, 2) }}
                                </td>
                                <td>
                                    <div class="btn-list flex-nowrap">
                                        <a href="{{ route('productions.show', $prod) }}" class="btn btn-sm btn-outline-info">Detail</a>
                                        <a href="{{ route('productions.edit', $prod) }}" class="btn btn-sm btn-outline-warning">Edit</a>
                                        <form action="{{ route('productions.destroy', $prod) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus laporan shift ini beserta seluruh data fleet-nya?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-lg mb-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="9" /><line x1="9" y1="10" x2="9.01" y2="10" /><line x1="15" y1="10" x2="15.01" y2="10" /><path d="M9.5 15.25a3.5 3.5 0 0 1 5 0" /></svg>
                                    <div>Belum ada laporan produksi harian yang dicatat.</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($productions->hasPages())
                <div class="card-footer d-flex align-items-center">
                    {{ $productions->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
