@extends('layouts.tabler')

@section('title', 'Failure Analysis Report')

@section('content')
<div class="container-xl">
    <div class="page-header d-print-none mb-3">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title text-primary fw-bold">
                    <svg class="icon icon-tabler icon-tabler-file-analytics me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><path d="M9 17l0 -5" /><path d="M12 17l0 -1" /><path d="M15 17l0 -3" /></svg>
                    Failure Analysis Report (FAR)
                </h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                @can('create_fars')
                <a href="{{ route('fars.create') }}" class="btn btn-primary d-none d-sm-inline-block">
                    <svg class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
                    Buat Laporan Baru
                </a>
                @endcan
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible" role="alert">
            <div class="d-flex">
                <div>
                    <svg class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10" /></svg>
                </div>
                <div>{{ session('success') }}</div>
            </div>
            <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
        </div>
    @endif

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-header bg-white border-bottom-0 pt-3 pb-0">
            <form action="{{ route('fars.index') }}" method="GET" class="d-flex gap-2 w-100">
                <div class="input-icon w-100">
                    <span class="input-icon-addon">
                        <svg class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" /><path d="M21 21l-6 -6" /></svg>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Cari berdasarkan No FAR atau Unit...">
                </div>
                <button type="submit" class="btn btn-secondary">Cari</button>
            </form>
        </div>
        <div class="table-responsive">
            <table class="table table-vcenter table-mobile-md card-table table-hover">
                <thead>
                    <tr>
                        <th>No FAR</th>
                        <th>Unit</th>
                        <th>Tanggal Kejadian</th>
                        <th>Status</th>
                        <th class="w-1"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($fars as $far)
                        <tr>
                            <td data-label="No FAR">
                                <div class="font-weight-bold text-primary">{{ $far->no_far }}</div>
                                <div class="text-muted small">Pelapor: {{ $far->reporter->nama_lengkap ?? '-' }}</div>
                            </td>
                            <td data-label="Unit">
                                <div class="font-weight-bold">{{ $far->masterUnit->nomor_unit ?? '-' }}</div>
                                <div class="text-muted small">{{ $far->masterUnit->model->name ?? '-' }}</div>
                            </td>
                            <td data-label="Tanggal Kejadian">
                                {{ $far->date_of_failure ? \Carbon\Carbon::parse($far->date_of_failure)->format('d M Y') : '-' }}
                            </td>
                            <td data-label="Status">
                                @if($far->status == 'Draft')
                                    <span class="badge bg-secondary-lt">Draft</span>
                                @elseif($far->status == 'Submitted')
                                    <span class="badge bg-warning-lt">Submitted</span>
                                @else
                                    <span class="badge bg-success-lt">Approved</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-list flex-nowrap justify-content-end">
                                    <a href="{{ route('fars.show', $far) }}" class="btn btn-sm btn-outline-info">Detail</a>
                                    @can('edit_fars')
                                    <a href="{{ route('fars.edit', $far) }}" class="btn btn-sm btn-outline-warning">Edit</a>
                                    @endcan
                                    @can('delete_fars')
                                    <form action="{{ route('fars.destroy', $far) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus FAR ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                                    </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">Belum ada Failure Analysis Report.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($fars->hasPages())
        <div class="card-footer d-flex align-items-center justify-content-between border-top-0">
            {{ $fars->links('pagination::bootstrap-5') }}
        </div>
        @endif
    </div>
</div>
@endsection
