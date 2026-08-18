@extends('layouts.tabler')

@section('title', 'Data Mekanik - CMMS')

@section('content')
<div class="page-header d-print-none">
  <div class="row align-items-center">
    <div class="col">
      <h2 class="page-title">
        Data Mekanik
      </h2>
    </div>
    <div class="col-auto ms-auto d-print-none">
      <div class="btn-list">
        @can('create_mechanics')
        <a href="#" class="btn btn-primary d-none d-sm-inline-block" data-bs-toggle="modal" data-bs-target="#modal-tambah-mekanik">
          <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="12" y1="5" x2="12" y2="19" /><line x1="5" y1="12" x2="19" y2="12" /></svg>
          Tambah Mekanik
        </a>
        @endcan
      </div>
    </div>
  </div>
</div>

<div class="row row-cards mt-3">
  <div class="col-12">
    <div class="card">
      <div class="table-responsive">
        <table class="table card-table table-vcenter text-nowrap datatable">
          <thead>
            <tr>
              <th>ID</th>
              <th>Nama Lengkap</th>
              <th>Jabatan</th>
              <th>Status</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($mechanics as $mech)
            <tr>
              <td>{{ $mech->id }}</td>
              <td>{{ $mech->nama_lengkap }}</td>
              <td>{{ $mech->jabatan ? $mech->jabatan->nama_jabatan : '-' }}</td>
              <td>
                @if($mech->is_active)
                  <span class="badge bg-success me-1"></span> Aktif
                @else
                  <span class="badge bg-danger me-1"></span> Non-Aktif
                @endif
              </td>
              <td>
                @can('view_mechanics')
                <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#modal-detail-mekanik-{{ $mech->id }}">Detail</button>
                @endcan
                @can('edit_mechanics')
                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modal-edit-mekanik-{{ $mech->id }}">Edit</button>
                @endcan
                @can('delete_mechanics')
                <form action="{{ route('mechanics.destroy', $mech) }}" method="post" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus mekanik ini?');">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                </form>
                @endcan
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="5" class="text-center">Belum ada data mekanik.</td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
      @if($mechanics->hasPages())
      <div class="card-footer d-flex align-items-center">
        {{ $mechanics->links('pagination::bootstrap-5') }}
      </div>
      @endif
    </div>
  </div>
</div>

{{-- Modal Tambah Mekanik --}}
<div class="modal modal-blur fade" id="modal-tambah-mekanik" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Tambah Mekanik</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('mechanics.store') }}" method="post">
        @csrf
        <div class="modal-body">
          @if(is_null(auth()->user()->site_id))
          <div class="mb-3">
            <label class="form-label required">Site</label>
            <select name="site_id" class="form-select" required>
              <option value="">-- Pilih Site --</option>
              @foreach($sites as $site)
                <option value="{{ $site->id }}">{{ $site->name }}</option>
              @endforeach
            </select>
          </div>
          @endif
          <div class="mb-3">
            <label class="form-label required">Nama Lengkap</label>
            <input type="text" class="form-control" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Jabatan</label>
            <select name="jabatan_id" class="form-select">
              <option value="">-- Pilih Jabatan --</option>
              @foreach($jabatans as $jabatan)
                <option value="{{ $jabatan->id }}" {{ old('jabatan_id') == $jabatan->id ? 'selected' : '' }}>{{ $jabatan->nama_jabatan }}</option>
              @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label required">Status</label>
            <select name="is_active" class="form-select" required>
              <option value="1" {{ old('is_active', '1') == '1' ? 'selected' : '' }}>Aktif</option>
              <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Non-Aktif</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn me-auto" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>

@foreach($mechanics as $mech)
{{-- Modal Detail & Riwayat Lengkap Mekanik --}}
<div class="modal modal-blur fade" id="modal-detail-mekanik-{{ $mech->id }}" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <div>
          <h5 class="modal-title fw-bold">Detail & Riwayat Mekanik: {{ $mech->nama_lengkap }}</h5>
          <div class="text-muted small">Informasi kepegawaian, performa kerja, dan inventaris tool mekanik.</div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        
        {{-- Info Profil --}}
        <div class="card bg-light border-0 mb-3">
          <div class="card-body py-3">
            <div class="row g-3">
              <div class="col-6 col-md-3">
                <div class="text-muted small text-uppercase fw-semibold">ID Mekanik</div>
                <div class="fw-bold fs-3 text-dark">#{{ $mech->id }}</div>
              </div>
              <div class="col-6 col-md-3">
                <div class="text-muted small text-uppercase fw-semibold">Jabatan</div>
                <div class="fw-bold text-dark">{{ $mech->jabatan->nama_jabatan ?? '-' }}</div>
              </div>
              <div class="col-6 col-md-3">
                <div class="text-muted small text-uppercase fw-semibold">Lokasi Site</div>
                <div class="fw-bold text-dark">{{ $mech->site->name ?? 'Semua Site' }}</div>
              </div>
              <div class="col-6 col-md-3">
                <div class="text-muted small text-uppercase fw-semibold">Status</div>
                <div>
                  @if($mech->is_active)
                    <span class="badge bg-success-lt fw-bold px-2 py-1">Aktif</span>
                  @else
                    <span class="badge bg-danger-lt fw-bold px-2 py-1">Non-Aktif</span>
                  @endif
                </div>
              </div>
            </div>
          </div>
        </div>

        {{-- Stats Cards --}}
        <div class="row g-3 mb-3">
          <div class="col-md-4">
            <div class="card card-sm bg-primary-lt border-0 shadow-none">
              <div class="card-body">
                <div class="row align-items-center">
                  <div class="col-auto">
                    <span class="bg-primary text-white avatar">
                      <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M15 21h-9a3 3 0 0 1 -3 -3v-1h10v2a2 2 0 0 0 4 0v-14a2 2 0 1 1 2 2h-2m2 -4h-2.28m-1 0h-7.42m-2.6 0h-3.7" /><path d="M12 21v-14" /></svg>
                    </span>
                  </div>
                  <div class="col">
                    <div class="text-muted small">Total WO Dikerjakan</div>
                    <div class="fw-bold fs-2 text-primary">{{ $mech->total_wo ?? 0 }} <span class="fs-4 text-muted fw-normal">WO</span></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          <div class="col-md-4">
            <div class="card card-sm bg-green-lt border-0 shadow-none">
              <div class="card-body">
                <div class="row align-items-center">
                  <div class="col-auto">
                    <span class="bg-green text-white avatar">
                      <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="9" /><polyline points="12 7 12 12 15 15" /></svg>
                    </span>
                  </div>
                  <div class="col">
                    <div class="text-muted small">Total Durasi Pengerjaan</div>
                    <div class="fw-bold fs-2 text-green">{{ number_format($mech->total_duration ?? 0, 1, ',', '.') }} <span class="fs-4 text-muted fw-normal">Jam</span></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          <div class="col-md-4">
            <div class="card card-sm bg-warning-lt border-0 shadow-none">
              <div class="card-body">
                <div class="row align-items-center">
                  <div class="col-auto">
                    <span class="bg-warning text-white avatar">
                      <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 10h3v-3l-3.5 -3.5a6 6 0 0 1 8 8l6 6a2 2 0 0 1 -3 3l-6 -6a6 6 0 0 1 -8 -8l3.5 3.5" /></svg>
                    </span>
                  </div>
                  <div class="col">
                    <div class="text-muted small">Tool Dialokasikan</div>
                    <div class="fw-bold fs-2 text-warning">{{ $mech->total_tools ?? 0 }} <span class="fs-4 text-muted fw-normal">Pcs</span></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        {{-- Tabel Tool yang Dialokasikan --}}
        <div class="card border shadow-none">
          <div class="card-header py-2 bg-light d-flex justify-content-between align-items-center">
            <h4 class="card-title m-0 fw-bold">Daftar Tool yang Dialokasikan ke Mekanik</h4>
            <span class="badge bg-primary-lt">{{ count($mech->tools_allocated ?? []) }} Item</span>
          </div>
          <div class="table-responsive" style="max-height: 280px; overflow-y: auto;">
            <table class="table card-table table-vcenter table-hover table-striped">
              <thead class="table-light sticky-top">
                <tr>
                  <th>No</th>
                  <th>Nama Tool</th>
                  <th>Kategori</th>
                  <th class="text-end">Kuantitas</th>
                  <th class="text-end">Harga Satuan</th>
                  <th class="text-end">Total Biaya</th>
                </tr>
              </thead>
              <tbody>
                @forelse($mech->tools_allocated ?? [] as $toolIndex => $stock)
                <tr>
                  <td>{{ $loop->iteration }}</td>
                  <td class="fw-bold">{{ $stock->tool->name ?? '-' }}</td>
                  <td><span class="badge bg-secondary-lt">{{ $stock->tool->category->name ?? '-' }}</span></td>
                  <td class="text-end fw-bold">{{ $stock->quantity }} Pcs</td>
                  <td class="text-end">Rp {{ number_format($stock->tool->price ?? 0, 0, ',', '.') }}</td>
                  <td class="text-end fw-bold text-primary">Rp {{ number_format(($stock->tool->price ?? 0) * $stock->quantity, 0, ',', '.') }}</td>
                </tr>
                @empty
                <tr>
                  <td colspan="6" class="text-center text-muted py-4">Belum ada perkakas/tool yang dialokasikan ke mekanik ini.</td>
                </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary me-auto" data-bs-dismiss="modal">Tutup</button>
        <button type="button" class="btn btn-primary" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#modal-edit-mekanik-{{ $mech->id }}">
          <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 20h4l10.5 -10.5a2.828 2.828 0 1 0 -4 -4l-10.5 10.5v4" /><path d="M13.5 6.5l4 4" /></svg>
          Edit Mekanik Ini
        </button>
      </div>
    </div>
  </div>
</div>

{{-- Modal Edit Mekanik --}}
<div class="modal modal-blur fade" id="modal-edit-mekanik-{{ $mech->id }}" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Mekanik: {{ $mech->nama_lengkap }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('mechanics.update', $mech) }}" method="post">
        @csrf
        @method('PUT')
        <div class="modal-body">
          @if(is_null(auth()->user()->site_id))
          <div class="mb-3">
            <label class="form-label required">Site</label>
            <select name="site_id" class="form-select" required>
              <option value="">-- Pilih Site --</option>
              @foreach($sites as $site)
                <option value="{{ $site->id }}" {{ old('site_id', $mech->site_id) == $site->id ? 'selected' : '' }}>{{ $site->name }}</option>
              @endforeach
            </select>
          </div>
          @endif
          <div class="mb-3">
            <label class="form-label required">Nama Lengkap</label>
            <input type="text" class="form-control" name="nama_lengkap" value="{{ old('nama_lengkap', $mech->nama_lengkap) }}" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Jabatan</label>
            <select name="jabatan_id" class="form-select">
              <option value="">-- Pilih Jabatan --</option>
              @foreach($jabatans as $jabatan)
                <option value="{{ $jabatan->id }}" {{ old('jabatan_id', $mech->jabatan_id) == $jabatan->id ? 'selected' : '' }}>{{ $jabatan->nama_jabatan }}</option>
              @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label required">Status</label>
            <select name="is_active" class="form-select" required>
              <option value="1" {{ old('is_active', $mech->is_active) == 1 ? 'selected' : '' }}>Aktif</option>
              <option value="0" {{ old('is_active', $mech->is_active) == 0 ? 'selected' : '' }}>Non-Aktif</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn me-auto" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endforeach

@endsection