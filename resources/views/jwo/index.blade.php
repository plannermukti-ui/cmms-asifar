@extends('layouts.tabler')
@section('title', 'Job Work Order (JWO)')

@section('content')
<div class="page-header d-print-none">
  <div class="row align-items-center">
    <div class="col">
      <h2 class="page-title text-primary fw-bold">Job Work Order (JWO)</h2>
      <div class="text-muted mt-1">Daftar perbaikan ke vendor luar</div>
    </div>
    <div class="col-auto ms-auto d-print-none">
      <div class="btn-list">
        <a href="#" class="btn btn-primary d-none d-sm-inline-block" data-bs-toggle="modal" data-bs-target="#modal-jwo-create">
          <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
          Buat JWO Baru
        </a>
      </div>
    </div>
  </div>
</div>

<div class="card">
  <div class="card-header">
    <h3 class="card-title">Daftar JWO</h3>
    <div class="card-actions">
        <form action="{{ route('jwos.index') }}" method="GET" class="d-flex">
            <select name="status[]" class="form-select form-select-sm me-2 excel-filter" multiple data-placeholder="Semua Status" style="min-width: 150px;">
                <option value="Progress Site" {{ in_array('Progress Site', (array)request('status', [])) ? 'selected' : '' }}>Progress Site</option>
                <option value="Sent" {{ in_array('Sent', (array)request('status', [])) ? 'selected' : '' }}>Sent</option>
                <option value="Progress Vendor" {{ in_array('Progress Vendor', (array)request('status', [])) ? 'selected' : '' }}>Progress Vendor</option>
                <option value="Completed" {{ in_array('Completed', (array)request('status', [])) ? 'selected' : '' }}>Completed</option>
                <option value="Cancelled" {{ in_array('Cancelled', (array)request('status', [])) ? 'selected' : '' }}>Cancelled</option>
            </select>
            <input type="text" name="search" class="form-control form-control-sm me-2" placeholder="Cari No JWO / Vendor..." value="{{ request('search') }}">
            <button type="submit" class="btn btn-sm btn-primary">Cari</button>
        </form>
    </div>
  </div>
  <div class="table-responsive">
    <table class="table card-table table-vcenter text-nowrap">
      <thead>
        <tr>
          <th>No JWO</th>
          <th>Kode Unit</th>
          <th>Vendor</th>
          <th>Part / Barang</th>
          <th>Masalah</th>
          <th>Status</th>
          <th>Tanggal Sent</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($jwos as $jwo)
        <tr>
          <td>
            <a href="{{ route('jwos.show', $jwo) }}" class="fw-bold text-primary text-decoration-none">{{ $jwo->no_jwo }}</a>
            @if($jwo->workOrder)
              <div class="text-muted small">WO: {{ $jwo->workOrder->no_wo }}</div>
            @endif
          </td>
          <td>
            <span class="badge bg-blue-lt text-uppercase">{{ $jwo->unit->nomor_unit ?? '-' }}</span>
          </td>
          <td class="fw-semibold">{{ $jwo->vendor->name ?? '-' }}</td>
          <td>
            <div class="fw-bold">{{ $jwo->part->part_description ?? $jwo->part->part_number ?? '-' }}</div>
            @if($jwo->part && $jwo->part->part_description && $jwo->part->part_number)
              <div class="text-muted small">PN: {{ $jwo->part->part_number }}</div>
            @endif
          </td>
          <td>{{ Str::limit($jwo->problem_description, 30) }}</td>
          <td>
            @php
              $badgeColor = match($jwo->status) {
                'Progress Site' => 'bg-cyan-lt text-cyan',
                'Sent' => 'bg-warning-lt text-warning',
                'Progress Vendor' => 'bg-azure-lt text-azure',
                'Completed' => 'bg-success-lt text-success',
                'Cancelled' => 'bg-danger-lt text-danger',
                default => 'bg-secondary-lt'
              };
            @endphp
            <span class="badge {{ $badgeColor }}">{{ $jwo->status }}</span>
          </td>
          <td>{{ $jwo->date_sent ? \Carbon\Carbon::parse($jwo->date_sent)->format('d M Y') : '-' }}</td>
          <td>
            <a href="{{ route('jwos.show', $jwo) }}" class="btn btn-sm btn-info">Detail</a>
            <a href="{{ route('jwos.edit', $jwo) }}" class="btn btn-sm btn-primary">Edit</a>
            <form action="{{ route('jwos.destroy', $jwo) }}" method="post" class="d-inline" onsubmit="return confirm('Hapus JWO ini?');">
              @csrf @method('DELETE')
              <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
            </form>
          </td>
        </tr>
        @empty
        <tr><td colspan="8" class="text-center">Belum ada data JWO.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  
  @if($errors->any())
  <div class="alert alert-danger alert-dismissible" role="alert">
    <div class="d-flex">
      <div>
        <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="9" /><line x1="12" y1="8" x2="12" y2="12" /><line x1="12" y1="16" x2="12.01" y2="16" /></svg>
      </div>
      <div>
        <h4 class="alert-title">Gagal menyimpan data!</h4>
        <div class="text-muted">
          <ul class="mb-0">
            @foreach($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      </div>
    </div>
    <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
  </div>
  @endif

  <div class="card-footer d-flex align-items-center">
    {{ $jwos->links('pagination::bootstrap-5') }}
  </div>
</div>

<!-- Modal Create JWO -->
<div class="modal modal-blur fade" id="modal-jwo-create" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
      <form action="{{ route('jwos.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title">Buat Job Work Order (JWO) Baru</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row g-3">
            <!-- Vendor -->
            <div class="col-md-6">
              <label class="form-label required">Vendor / Bengkel Luar</label>
              <select class="form-select" name="vendor_id" required>
                <option value="">-- Pilih Vendor --</option>
                @foreach($vendors as $vendor)
                  <option value="{{ $vendor->id }}">{{ $vendor->name }} - {{ $vendor->address }}</option>
                @endforeach
              </select>
            </div>
            
            <!-- Work Order (Optional) -->
            <div class="col-md-6">
              <label class="form-label">Terkait WO Internal (Opsional)</label>
              <select class="form-select" name="work_order_id">
                <option value="">-- Kosongkan jika standalone --</option>
                @foreach($workOrders as $wo)
                  <option value="{{ $wo->id }}">{{ $wo->no_wo }} {{ $wo->unit ? ' - ' . $wo->unit->nomor_unit : '' }}</option>
                @endforeach
              </select>
            </div>
            
            <!-- Unit (Optional) -->
            <div class="col-md-6">
              <label class="form-label">Unit Kendaraan / Alat Berat (Opsional)</label>
              <select class="form-select" name="unit_id">
                <option value="">-- Tidak Terkait Unit --</option>
                @foreach($units as $unit)
                  <option value="{{ $unit->id }}">{{ $unit->nomor_unit }}</option>
                @endforeach
              </select>
            </div>
            
            <!-- Component Group -->
            <div class="col-md-6">
              <label class="form-label">Grup Komponen (Opsional)</label>
              <select class="form-select" name="component_group_id">
                <option value="">-- Pilih Group --</option>
                @foreach($componentGroups as $cg)
                  <option value="{{ $cg->id }}">{{ $cg->name }}</option>
                @endforeach
              </select>
            </div>

            <!-- Part -->
            <div class="col-md-12">
              <label class="form-label required">Part / Komponen yang Dikirim</label>
              <select class="form-select" name="part_id" required>
                <option value="">-- Pilih Part / Sparepart --</option>
                @foreach($parts as $part)
                  <option value="{{ $part->id }}">{{ $part->part_number }} - {{ $part->part_name }}</option>
                @endforeach
              </select>
            </div>

            <!-- Problem -->
            <div class="col-md-12">
              <label class="form-label required">Deskripsi Kerusakan (Problem)</label>
              <textarea class="form-control" name="problem_description" rows="3" required placeholder="Jelaskan kerusakan komponen"></textarea>
            </div>

            <!-- Action -->
            <div class="col-md-12">
              <label class="form-label required">Permintaan Tindakan (Request Action)</label>
              <textarea class="form-control" name="request_action" rows="3" required placeholder="Instruksi perbaikan ke vendor"></textarea>
            </div>
            
            <!-- Dates -->
            <div class="col-md-12">
              <label class="form-label">Estimasi Selesai (Opsional)</label>
              <input type="date" class="form-control" name="date_expected">
            </div>

            <!-- Photos -->
            <div class="col-md-6">
              <label class="form-label">Foto Kerusakan 1 (Opsional)</label>
              <input type="file" class="form-control" name="photo_1" accept="image/jpeg,image/png,image/jpg">
            </div>
            <div class="col-md-6">
              <label class="form-label">Foto Kerusakan 2 (Opsional)</label>
              <input type="file" class="form-control" name="photo_2" accept="image/jpeg,image/png,image/jpg">
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn me-auto" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary" id="btn-submit-jwo">Simpan & Buat JWO</button>
        </div>
      </form>
    </div>
  </div>
</div>

@push('scripts')
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
<!-- Browser Image Compression -->
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/browser-image-compression@2.0.1/dist/browser-image-compression.js"></script>
<script>
  document.addEventListener("DOMContentLoaded", function () {
    // Inisialisasi Tom Select untuk dropdown Part
    new TomSelect("select[name='part_id']", {
        create: false,
        sortField: {
            field: "text",
            direction: "asc"
        },
        placeholder: '-- Cari & Pilih Part / Sparepart --'
    });
    // Mapping WO ID ke Unit ID
    const woToUnitMap = {
        @foreach($workOrders as $wo)
            @if($wo->master_unit_id)
            "{{ $wo->id }}": "{{ $wo->master_unit_id }}",
            @endif
        @endforeach
    };

    // Inisialisasi Tom Select
    new TomSelect("select[name='vendor_id']", { create: false });
    
    let tsUnit = new TomSelect("select[name='unit_id']", { create: false });
    
    new TomSelect("select[name='work_order_id']", { 
        create: false, 
        placeholder: "-- Cari Nomor WO --",
        onChange: function(value) {
            if (value && woToUnitMap[value]) {
                // Jika WO dipilih dan memiliki unit, otomatis pilih unit tersebut dan kunci
                tsUnit.setValue(woToUnitMap[value]);
                tsUnit.lock(); // Kunci agar tidak bisa diubah manual jika terkait WO
            } else {
                // Jika WO dikosongkan, buka kunci
                tsUnit.unlock();
                tsUnit.clear();
            }
        }
    });
    
    @if($errors->any())
    var modalJwo = new bootstrap.Modal(document.getElementById('modal-create-jwo'));
    modalJwo.show();
    @endif

    // Logika Kompresi Gambar Otomatis (Frontend)
    async function handleImageCompression(event) {
        const file = event.target.files[0];
        if (!file) return;

        // Tampilkan loading di tombol submit
        const btnSubmit = document.getElementById('btn-submit-jwo');
        const originalText = btnSubmit.innerHTML;
        btnSubmit.disabled = true;
        btnSubmit.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span> Mengompresi Foto...';

        try {
            // Opsi kompresi: max ukuran file 1MB, max lebar/tinggi 1280px
            const options = {
                maxSizeMB: 1,
                maxWidthOrHeight: 1280,
                useWebWorker: true
            };
            const compressedFile = await imageCompression(file, options);
            
            // Masukkan file terkompresi kembali ke input
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(new File([compressedFile], compressedFile.name, {
                type: compressedFile.type,
                lastModified: new Date().getTime()
            }));
            event.target.files = dataTransfer.files;
            
        } catch (error) {
            console.error("Gagal mengompresi gambar:", error);
            alert("Terjadi kesalahan saat mengompresi gambar. Coba gambar lain.");
        } finally {
            // Kembalikan tombol submit
            btnSubmit.disabled = false;
            btnSubmit.innerHTML = originalText;
        }
    }

    document.querySelector('input[name="photo_1"]').addEventListener('change', handleImageCompression);
    document.querySelector('input[name="photo_2"]').addEventListener('change', handleImageCompression);
  });
</script>
@endpush
@endsection
