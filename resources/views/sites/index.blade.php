@extends('layouts.tabler')

@section('title', 'Master Data Site')

@section('content')
<div class="page-header d-print-none">
    <div class="row align-items-center">
        <div class="col">
            <h2 class="page-title">Master Data Site</h2>
            <div class="text-secondary mt-1">Daftar lokasi operasional (Site) aplikasi CMMS.</div>
        </div>
        <div class="col-auto ms-auto d-print-none">
            <a href="{{ route('sites.create') }}" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
                Tambah Site Baru
            </a>
        </div>
    </div>
</div>

@if (session('success'))
<div class="alert alert-success mt-3">{{ session('success') }}</div>
@endif
@if (session('error'))
<div class="alert alert-danger mt-3">{{ session('error') }}</div>
@endif

<div class="card mt-3">
    <div class="table-responsive">
        <table class="table card-table table-vcenter text-nowrap datatable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Kode</th>
                    <th>Nama Site</th>
                    <th>Deskripsi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sites as $site)
                <tr>
                    <td>{{ $site->id }}</td>
                    <td><span class="badge bg-blue-lt">{{ $site->code }}</span></td>
                    <td><strong>{{ $site->name }}</strong></td>
                    <td class="text-secondary">{{ Str::limit($site->description, 50) }}</td>
                    <td>
                        <div class="btn-list flex-nowrap">
                            <a href="{{ route('sites.edit', $site) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                            <form action="{{ route('sites.destroy', $site) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus site ini?');" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center text-secondary">Belum ada data site.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
