@extends('layouts.tabler')

@section('title', 'Approval Matrix - CMMS Aisfar')

@section('content')
<div class="page-header d-print-none">
  <div class="row align-items-center">
    <div class="col">
      <h2 class="page-title">Approval Matrix</h2>
      <div class="text-secondary mt-1">Atur hierarki persetujuan untuk setiap modul/dokumen.</div>
    </div>
  </div>
</div>

@if (session('success'))
    <div class="alert alert-success mt-3">{{ session('success') }}</div>
@endif

<div class="row mt-3">
  <div class="col-12">
    <div class="card card-md mb-3 bg-primary-lt">
      <div class="card-body">
        <div class="row align-items-center">
          <div class="col-auto">
            <span class="bg-primary text-white avatar">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0" /><path d="M12 9h.01" /><path d="M11 12h1v4h1" /></svg>
            </span>
          </div>
          <div class="col">
            <h3 class="mb-1">Bagaimana Cara Kerja Approval Matrix?</h3>
            <div class="text-secondary">
              Fitur ini digunakan untuk mengatur alur persetujuan (approval) secara berjenjang pada tiap modul.
              <ul class="mb-0 mt-1">
                <li><strong>Level 1</strong>: Menandakan orang pertama yang harus memberikan persetujuan (biasanya atasan langsung).</li>
                <li><strong>Level 2, 3, dst</strong>: Persetujuan lanjutan yang dibutuhkan setelah level sebelumnya menyetujui (contoh: Manager, lalu Direktur).</li>
                <li>Pilih <strong>Modul</strong>, tentukan <strong>Urutan/Level</strong>, dan pilih <strong>Role (Jabatan)</strong> yang berhak melakukan approval pada level tersebut.</li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <!-- Form Tambah -->
  <div class="col-md-4">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Tambah Level Approval</h3>
      </div>
      <div class="card-body">
        <form action="{{ route('approval-matrix.store') }}" method="POST">
          @csrf
          <div class="mb-3">
            <label class="form-label required">Modul</label>
            <select name="module_name" class="form-select" required>
              <option value="">-- Pilih Modul --</option>
              @foreach ($modules as $module)
                <option value="{{ $module->name }}">{{ $module->name }}</option>
              @endforeach
            </select>
            <small class="text-secondary mt-1 d-block">
              Tidak menemukan modul? <a href="{{ route('modules.index') }}" target="_blank">Tambahkan di Master Data Modul</a>
            </small>
          </div>
          <div class="mb-3">
            <label class="form-label required">Urutan (Level)</label>
            <input type="number" name="sequence" class="form-control" min="1" max="10" placeholder="1" required>
            <small class="text-secondary">1 = Approver pertama, 2 = Approver kedua, dst.</small>
          </div>
          <div class="mb-3">
            <label class="form-label required">Role Approver</label>
            <select name="role_id" class="form-select" required>
              <option value="">-- Pilih Role --</option>
              @foreach ($roles as $role)
                <option value="{{ $role->id }}">{{ $role->name }}</option>
              @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Keterangan</label>
            <input type="text" name="description" class="form-control" placeholder="Opsional">
          </div>
          <button type="submit" class="btn btn-primary w-100">Simpan</button>
        </form>
      </div>
    </div>
  </div>

  <!-- Tabel Approval Matrix -->
  <div class="col-md-8">
    @php $grouped = $matrices->groupBy('module_name'); @endphp
    @forelse ($grouped as $moduleName => $items)
    <div class="card mb-3">
      <div class="card-header">
        <h4 class="card-title">{{ $moduleName }}</h4>
      </div>
      <div class="table-responsive">
        <table class="table table-vcenter card-table">
          <thead>
            <tr>
              <th>Level</th>
              <th>Role Approver</th>
              <th>Keterangan</th>
              <th class="w-1">Hapus</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($items->sortBy('sequence') as $item)
            <tr>
              <td>
                <span class="badge bg-blue-lt">Level {{ $item->sequence }}</span>
              </td>
              <td>{{ $item->role->name ?? '-' }}</td>
              <td>{{ $item->description ?? '-' }}</td>
              <td>
                <form action="{{ route('approval-matrix.destroy', $item) }}" method="POST" onsubmit="return confirm('Hapus level ini?');">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                </form>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
    @empty
    <div class="card">
      <div class="card-body text-center text-muted">
        Belum ada konfigurasi approval matrix. Tambahkan dari form di sebelah kiri.
      </div>
    </div>
    @endforelse
  </div>
</div>
@endsection
