@extends('layouts.tabler')

@section('title', 'Master Tool - CMMS')

@section('content')
<div class="page-header d-print-none">
  <div class="row align-items-center">
    <div class="col">
      <h2 class="page-title">
        Master Tool & Stok
      </h2>
    </div>
    <div class="col-auto ms-auto d-print-none">
      <div class="btn-list">
        @can('view_tool_categories')
        <a href="{{ route('tool-categories.index') }}" class="btn btn-secondary d-none d-sm-inline-block">
          Kelola Kategori
        </a>
        @endcan
        @can('create_tools')
        <a href="#" class="btn btn-primary d-none d-sm-inline-block" data-bs-toggle="modal" data-bs-target="#modal-tambah-tool">
          <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="12" y1="5" x2="12" y2="19" /><line x1="5" y1="12" x2="19" y2="12" /></svg>
          Tambah Tool
        </a>
        @endcan
      </div>
    </div>
  </div>
</div>

<div class="row row-cards mt-3">
  <!-- Summary Cards -->
  <div class="col-sm-6 col-lg-3">
    <div class="card card-sm">
      <div class="card-body">
        <div class="row align-items-center">
          <div class="col-auto">
            <span class="bg-primary text-white avatar">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="12" y1="5" x2="12" y2="19" /><line x1="5" y1="12" x2="19" y2="12" /></svg>
            </span>
          </div>
          <div class="col">
            <div class="font-weight-medium">
              Total Tool (Rp)
            </div>
            <div class="text-secondary">
              Rp {{ number_format($totalToolCost, 0, ',', '.') }}
            </div>
            <div class="text-secondary small mt-1">
              Qty: {{ $totalToolQty }} Total Asset
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <div class="col-sm-6 col-lg-3">
    <div class="card card-sm">
      <div class="card-body">
        <div class="row align-items-center">
          <div class="col-auto">
            <span class="bg-indigo text-white avatar">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="9" /><polyline points="12 7 12 12 15 15" /></svg>
            </span>
          </div>
          <div class="col">
            <div class="font-weight-medium">
              Tool Mekanik (Rp)
            </div>
            <div class="text-secondary">
              Rp {{ number_format($mechanicCost, 0, ',', '.') }}
            </div>
            <div class="text-secondary small mt-1">
              Qty: {{ $mechanicQty }} Total Asset
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-sm-6 col-lg-3">
    <div class="card card-sm">
      <div class="card-body">
        <div class="row align-items-center">
          <div class="col-auto">
            <span class="bg-blue text-white avatar">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 10h3v-3l-3.5 -3.5a6 6 0 0 1 8 8l6 6a2 2 0 0 1 -3 3l-6 -6a6 6 0 0 1 -8 -8l3.5 3.5" /></svg>
            </span>
          </div>
          <div class="col">
            <div class="font-weight-medium">
              Tool Tool Room (Rp)
            </div>
            <div class="text-secondary">
              Rp {{ number_format($roomCost, 0, ',', '.') }}
            </div>
            <div class="text-secondary small mt-1">
              Qty: {{ $roomQty }} Total Asset
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-sm-6 col-lg-3">
    <div class="card card-sm">
      <div class="card-body">
        <div class="row align-items-center">
          <div class="col-auto">
            <span class="bg-danger text-white avatar">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="4" y1="7" x2="20" y2="7" /><line x1="10" y1="11" x2="10" y2="17" /><line x1="14" y1="11" x2="14" y2="17" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
            </span>
          </div>
          <div class="col">
            <div class="font-weight-medium">
              Damaged/Missing Tools
            </div>
            <div class="text-secondary">
              Rp {{ number_format($damagedCost, 0, ',', '.') }}
            </div>
            <div class="text-secondary small mt-1">
              Qty: {{ $damagedQty }} Total Asset
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-12 mt-3">
    <div class="card">
      <div class="table-responsive">
        <table class="table card-table table-vcenter text-nowrap datatable">
          <thead>
            <tr>
              <th>ID</th>
              <th>Foto</th>
              <th>Nama Tool</th>
              <th>Kategori</th>
              {{-- <th>Spesifikasi</th> --}}
              <th>Stok Total</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($tools as $tool)
            <tr>
              <td>{{ $tool->id }}</td>
              <td>
                @if($tool->foto)
                  <img src="{{ asset('storage/' . $tool->foto) }}" alt="Foto" class="avatar">
                @else
                  <span class="avatar bg-blue-lt">TL</span>
                @endif
              </td>
              <td class="fw-bold">{{ $tool->name }}</td>
              <td>{{ $tool->category ? $tool->category->name : '-' }}</td>
              {{-- <td>{{ $tool->spesifikasi ?? '-' }}</td> --}}
              <td>
                @php
                    $stokToolroom = $tool->stocks()->where('location_type', 'ToolRoom')->sum('quantity');
                    $stokMekanik = $tool->stocks()->where('location_type', 'Mechanic')->sum('quantity');
                @endphp
                <span class="badge bg-primary text-white fw-bold">TR: {{ $stokToolroom }}</span>
                <span class="badge bg-indigo text-white fw-bold">MEK: {{ $stokMekanik }}</span>
              </td>
              <td>
                @can('view_tool_stocks')
                <a href="{{ route('tool-stocks.index', ['tool_id' => $tool->id]) }}" class="btn btn-sm btn-info">Cek Stok</a>
                @endcan
                @can('edit_tools')
                <a href="{{ route('tools.edit', $tool) }}" class="btn btn-sm btn-primary">Edit</a>
                @endcan
                @can('delete_tools')
                <form action="{{ route('tools.destroy', $tool) }}" method="post" class="d-inline" onsubmit="return confirm('Yakin menghapus tool ini?');">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                </form>
                @endcan
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="7" class="text-center">Belum ada data tool.</td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
      @if($tools->hasPages())
      <div class="card-footer d-flex align-items-center">
        {{ $tools->links('pagination::bootstrap-5') }}
      </div>
      @endif
    </div>
  </div>
</div>
<div class="modal modal-blur fade" id="modal-tambah-tool" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Tambah Tool</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('tools.store') }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="modal-body">
          <div class="row">
            @if(is_null(auth()->user()->site_id))
            <div class="col-md-12 mb-3">
              <label class="form-label required">Site</label>
              <select name="site_id" class="form-select" required>
                <option value="">-- Pilih Site --</option>
                @foreach($sites as $site)
                  <option value="{{ $site->id }}">{{ $site->name }}</option>
                @endforeach
              </select>
            </div>
            @endif
            <div class="col-md-6 mb-3">
              <label class="form-label required">Nama Tool</label>
              <input type="text" class="form-control" name="name" value="{{ old('name') }}" required>
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label required">Kategori</label>
              <select name="tool_category_id" class="form-select" required>
                <option value="">-- Pilih Kategori --</option>
                @foreach($categories as $category)
                  <option value="{{ $category->id }}" {{ old('tool_category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-12 mb-3">
              <label class="form-label">Spesifikasi / Deskripsi</label>
              <textarea class="form-control" name="spesifikasi" rows="3">{{ old('spesifikasi') }}</textarea>
            </div>
            <div class="col-md-12 mb-3">
              <label class="form-label">Cost / Harga Satuan (Rp)</label>
              <input type="number" class="form-control" name="price" value="{{ old('price', 0) }}" min="0" step="0.01">
            </div>
            <div class="col-md-12 mb-3">
              <label class="form-label">Foto Tool</label>
              <input type="file" class="form-control" name="foto" accept="image/*">
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn me-auto" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan Tool</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection