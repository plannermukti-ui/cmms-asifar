@extends('layouts.tabler')

@section('title', 'Backup Database - CMMS Aisfar')

@section('content')
<div class="page-header d-print-none">
  <div class="row align-items-center">
    <div class="col">
      <h2 class="page-title">Backup Database</h2>
      <div class="text-secondary mt-1">Buat dan unduh backup database MySQL secara manual.</div>
    </div>
    <div class="col-auto ms-auto d-print-none">
      <form method="POST" action="{{ route('backup.create') }}" onsubmit="return confirm('Mulai proses backup sekarang?');">
        @csrf
        <button type="submit" class="btn btn-primary">
          <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 18a4.6 4.4 0 0 1 0 -9a5 4.5 0 0 1 11 2h1a3.5 3.5 0 0 1 0 7h-1" /><path d="M9 15l3 3l3 -3" /><path d="M12 12l0 6" /></svg>
          Backup Sekarang
        </button>
      </form>
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
  <div class="card-header">
    <h3 class="card-title">Riwayat Backup</h3>
  </div>
  <div class="table-responsive">
    <table class="table table-vcenter card-table">
      <thead>
        <tr>
          <th>Nama File</th>
          <th>Ukuran</th>
          <th>Tanggal</th>
          <th class="w-1">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($backups as $backup)
        <tr>
          <td>{{ $backup['name'] }}</td>
          <td>{{ number_format($backup['size'] / 1024, 2) }} KB</td>
          <td>{{ $backup['date'] }}</td>
          <td class="d-flex gap-2">
            <a href="{{ route('backup.download', $backup['name']) }}" class="btn btn-sm btn-outline-primary">Unduh</a>
            <form action="{{ route('backup.destroy', $backup['name']) }}" method="POST" onsubmit="return confirm('Hapus file backup ini?');">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
            </form>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="4" class="text-center text-muted py-4">Belum ada file backup. Klik "Backup Sekarang" untuk membuat.</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

<div class="card mt-3">
  <div class="card-body">
    <h4>ℹ️ Catatan Penting</h4>
    <ul class="text-secondary">
      <li>Backup disimpan di folder <code>storage/app/backups/</code> pada server.</li>
      <li>Pastikan <strong>mysqldump</strong> tersedia di PATH sistem Laragon Anda (<code>C:\laragon\bin\mysql\mysql-8.0...\bin</code>).</li>
      <li>Disarankan untuk membuat backup secara rutin sebelum melakukan perubahan data besar.</li>
    </ul>
  </div>
</div>
@endsection
