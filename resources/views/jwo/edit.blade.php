@extends('layouts.tabler')
@section('title', 'Edit JWO')

@section('content')
<div class="page-header d-print-none">
  <div class="row align-items-center">
    <div class="col">
      <h2 class="page-title text-primary fw-bold">Edit Job Work Order: {{ $jwo->no_jwo }}</h2>
    </div>
    <div class="col-auto ms-auto d-print-none">
      <a href="{{ route('jwos.index') }}" class="btn btn-outline-secondary">Kembali</a>
    </div>
  </div>
</div>

<div class="card">
  <form action="{{ route('jwos.update', $jwo) }}" method="POST" enctype="multipart/form-data">
    @csrf @method('PUT')
    <div class="card-body">
      <div class="row g-3">
        <!-- Vendor -->
        <div class="col-md-6">
          <label class="form-label required">Vendor / Bengkel Luar</label>
          <select class="form-select" name="vendor_id" required>
            @foreach($vendors as $vendor)
              <option value="{{ $vendor->id }}" {{ $vendor->id == $jwo->vendor_id ? 'selected' : '' }}>{{ $vendor->name }}</option>
            @endforeach
          </select>
        </div>
        
        <!-- Unit (Optional) -->
        <div class="col-md-6">
          <label class="form-label">Unit Kendaraan (Opsional)</label>
          <select class="form-select" name="unit_id">
            <option value="">-- Tidak Terkait Unit --</option>
            @foreach($units as $unit)
              <option value="{{ $unit->id }}" {{ $unit->id == $jwo->unit_id ? 'selected' : '' }}>{{ $unit->nomor_unit }}</option>
            @endforeach
          </select>
        </div>
        
        <!-- Component Group -->
        <div class="col-md-6">
          <label class="form-label">Grup Komponen (Opsional)</label>
          <select class="form-select" name="component_group_id">
            <option value="">-- Pilih Group --</option>
            @foreach($componentGroups as $cg)
              <option value="{{ $cg->id }}" {{ $cg->id == $jwo->component_group_id ? 'selected' : '' }}>{{ $cg->name }}</option>
            @endforeach
          </select>
        </div>

        <!-- Part -->
        <div class="col-md-6">
          <label class="form-label">Part / Komponen yang Dikirim</label>
          <select class="form-select" name="part_id">
            @foreach($parts as $part)
              <option value="{{ $part->id }}" {{ $part->id == $jwo->part_id ? 'selected' : '' }}>{{ $part->part_number }} - {{ $part->part_description }}</option>
            @endforeach
          </select>
        </div>

        <!-- Problem -->
        <div class="col-md-12">
          <label class="form-label required">Deskripsi Kerusakan (Problem)</label>
          <textarea class="form-control" name="problem_description" rows="3" required>{{ $jwo->problem_description }}</textarea>
        </div>

        <!-- Action -->
        <div class="col-md-12">
          <label class="form-label required">Permintaan Tindakan (Request Action)</label>
          <textarea class="form-control" name="request_action" rows="3" required>{{ $jwo->request_action }}</textarea>
        </div>
        
        <!-- Dates -->
        <div class="col-md-12">
          <label class="form-label">Estimasi Selesai (Opsional)</label>
          <input type="date" class="form-control" name="date_expected" value="{{ $jwo->date_expected ? $jwo->date_expected->format('Y-m-d') : '' }}">
        </div>

        <!-- Photos -->
        <div class="col-md-6">
          <label class="form-label">Foto Kerusakan 1 (Opsional)</label>
          @if($jwo->photo_1)
            <div class="mb-2">
              <img src="{{ asset('storage/jwo_photos/' . $jwo->photo_1) }}" alt="Foto 1" class="img-thumbnail" style="max-height: 120px;">
              <div class="small text-muted">Foto saat ini</div>
            </div>
          @endif
          <input type="file" class="form-control" name="photo_1" accept="image/jpeg,image/png,image/jpg">
        </div>

        <div class="col-md-6">
          <label class="form-label">Foto Kerusakan 2 (Opsional)</label>
          @if($jwo->photo_2)
            <div class="mb-2">
              <img src="{{ asset('storage/jwo_photos/' . $jwo->photo_2) }}" alt="Foto 2" class="img-thumbnail" style="max-height: 120px;">
              <div class="small text-muted">Foto saat ini</div>
            </div>
          @endif
          <input type="file" class="form-control" name="photo_2" accept="image/jpeg,image/png,image/jpg">
        </div>
      </div>
    </div>
    <div class="card-footer text-end">
      <button type="submit" class="btn btn-primary" id="btn-update-jwo">Update JWO</button>
    </div>
  </form>
</div>

@push('scripts')
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
<!-- Browser Image Compression -->
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/browser-image-compression@2.0.1/dist/browser-image-compression.js"></script>
<script>
  document.addEventListener("DOMContentLoaded", function () {
    new TomSelect("select[name='part_id']", { create: false, placeholder: '-- Cari & Pilih Part --' });
    new TomSelect("select[name='vendor_id']", { create: false });
    new TomSelect("select[name='unit_id']", { create: false });

    // Logika Kompresi Gambar Otomatis (Frontend Edit)
    async function handleImageCompression(event) {
        const file = event.target.files[0];
        if (!file) return;

        const btnSubmit = document.getElementById('btn-update-jwo');
        const originalText = btnSubmit.innerHTML;
        btnSubmit.disabled = true;
        btnSubmit.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span> Mengompresi Foto...';

        try {
            const options = {
                maxSizeMB: 1,
                maxWidthOrHeight: 1280,
                useWebWorker: true
            };
            const compressedFile = await imageCompression(file, options);
            
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
            btnSubmit.disabled = false;
            btnSubmit.innerHTML = originalText;
        }
    }

    const inputP1 = document.querySelector('input[name="photo_1"]');
    const inputP2 = document.querySelector('input[name="photo_2"]');
    if (inputP1) inputP1.addEventListener('change', handleImageCompression);
    if (inputP2) inputP2.addEventListener('change', handleImageCompression);
  });
</script>
@endpush
@endsection
