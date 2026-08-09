@extends('layouts.tabler')

@section('title', 'Buat Failure Analysis Report')

@section('content')
<div class="container-xl">
    <div class="page-header d-print-none mb-3">
        <div class="row align-items-center">
            <div class="col">
                <div class="page-pretitle">Modul FAR</div>
                <h2 class="page-title text-primary fw-bold">
                    Buat Failure Analysis Report Baru
                </h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <a href="{{ route('fars.index') }}" class="btn btn-outline-secondary">
                    Kembali
                </a>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <h4 class="alert-title">Terdapat Kesalahan:</h4>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('fars.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row row-cards">
            <!-- HEADER INFO -->
            <div class="col-12">
                <div class="card shadow-sm rounded-4 border-0">
                    <div class="card-header bg-primary text-white rounded-top-4">
                        <h3 class="card-title text-white"><svg class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M4 11l16 0" /><path d="M4 15l16 0" /><path d="M4 19l16 0" /></svg>Informasi Dasar</h3>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label required">No. FAR</label>
                                <input type="text" name="no_far" class="form-control fw-bold text-primary" value="{{ $no_far }}" readonly required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label required">Site / Project</label>
                                <select name="site_id" class="form-select" required>
                                    @foreach($sites as $site)
                                        <option value="{{ $site->id }}" {{ (auth()->user()->site_id ?? null) == $site->id ? 'selected' : '' }}>{{ $site->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Referensi Work Order</label>
                                <select name="work_order_id" id="work_order_id" class="form-select">
                                    <option value="">-- Tidak ada referensi --</option>
                                    @foreach($workOrders as $wo)
                                        <option value="{{ $wo->id }}" data-unit="{{ $wo->master_unit_id }}" data-site="{{ $wo->site_id }}">{{ $wo->no_wo }} - {{ $wo->title }}</option>
                                    @endforeach
                                </select>
                                <small class="text-muted">Opsional. Jika dipilih, Unit otomatis disesuaikan.</small>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label required">Pilih Unit (Equipment)</label>
                                <select name="master_unit_id" id="master_unit_id" class="form-select" required>
                                    <option value="">-- Pilih Unit --</option>
                                    @foreach($units as $unit)
                                        <option value="{{ $unit->id }}" data-model="{{ $unit->model->name ?? '' }}" data-sn="{{ $unit->sn_chassis }}" data-engmodel="{{ $unit->engine_model }}" data-engsn="{{ $unit->sn_engine }}">{{ $unit->nomor_unit }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label required">Tanggal Dilaporkan</label>
                                <input type="date" name="date_reported" class="form-control" value="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label required">Tanggal Kejadian (Failure)</label>
                                <input type="date" name="date_of_failure" class="form-control" value="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">SMU Saat Kejadian</label>
                                <input type="number" name="smu_at_failure" class="form-control" placeholder="14329">
                            </div>
                        </div>

                        <!-- Readonly Unit Info populated by JS -->
                        <div class="row g-3 mt-2 bg-light p-3 rounded" id="unitInfoBox" style="display:none;">
                            <div class="col-md-3">
                                <strong>Model:</strong> <span id="lblModel"></span>
                            </div>
                            <div class="col-md-3">
                                <strong>Serial No:</strong> <span id="lblSn"></span>
                            </div>
                            <div class="col-md-3">
                                <strong>Engine Model:</strong> <span id="lblEngModel"></span>
                            </div>
                            <div class="col-md-3">
                                <strong>Engine SN:</strong> <span id="lblEngSn"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- COMPONENT FAILURE -->
            <div class="col-md-6">
                <div class="card shadow-sm rounded-4 border-0 h-100">
                    <div class="card-header bg-danger text-white rounded-top-4">
                        <h3 class="card-title text-white">Component Failure</h3>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Part No. Komponen</label>
                            <select name="component_part_no" id="component_part_no" class="form-select tom-select" data-placeholder="Pilih Part Number...">
                                <option value=""></option>
                                @foreach($parts as $part)
                                    <option value="{{ $part->part_number }}" data-desc="{{ $part->part_description }}">{{ $part->part_number }} - {{ $part->part_description }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nama Komponen (Description)</label>
                            <input type="text" name="component_description" id="component_description" class="form-control" placeholder="Contoh: DRIVE GP-CIRCLE">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Part No. Yang Menyebabkan Kerusakan</label>
                            <select name="part_no_causing_failure" id="part_no_causing_failure" class="form-select tom-select" data-placeholder="Pilih Part Number...">
                                <option value=""></option>
                                @foreach($parts as $part)
                                    <option value="{{ $part->part_number }}">{{ $part->part_number }} - {{ $part->part_description }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- COMPONENT & OIL HISTORY -->
            <div class="col-md-6">
                <div class="card shadow-sm rounded-4 border-0 mb-3">
                    <div class="card-header bg-warning text-white rounded-top-4">
                        <h3 class="card-title text-white">Last Component Installed</h3>
                    </div>
                    <div class="card-body row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Tgl Pemasangan</label>
                            <input type="date" name="last_comp_date" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">SMU Pasang</label>
                            <input type="number" name="last_comp_smu" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Umur Komp.</label>
                            <input type="number" name="hours_of_component" class="form-control" placeholder="Hours">
                        </div>
                    </div>
                </div>
                <div class="card shadow-sm rounded-4 border-0">
                    <div class="card-header bg-info text-white rounded-top-4">
                        <h3 class="card-title text-white">Last Oil Sampled</h3>
                    </div>
                    <div class="card-body row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Tgl Diambil</label>
                            <input type="date" name="last_oil_date_taken" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Tgl Dikirim</label>
                            <input type="date" name="last_oil_date_sent" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Tgl Diterima</label>
                            <input type="date" name="last_oil_date_received" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Evaluasi</label>
                            <select name="last_oil_eval" class="form-select">
                                <option value="">-Pilih-</option>
                                <option value="A">A</option>
                                <option value="B">B</option>
                                <option value="C">C</option>
                                <option value="X">X</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ANALYSIS TEXTAREAS -->
            <div class="col-12">
                <div class="card shadow-sm rounded-4 border-0">
                    <div class="card-header bg-dark text-white rounded-top-4">
                        <h3 class="card-title text-white">Analysis Details</h3>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <label class="form-label fw-bold">1. Failure Outline (Ringkasan Kerusakan)</label>
                            <div class="text-muted small mb-1">Sebutkan Part No. Yang Mengalami Kerusakan</div>
                            <textarea name="failure_outline" class="form-control" rows="4" placeholder="1. 8X-5303 Gear-Worm: Mengalami kegagalan katastropik..."></textarea>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-bold">2. Background (Alasan Kerusakan)</label>
                            <div class="text-muted small mb-1">Jelaskan Latar Belakang / Kronologi Kerusakan</div>
                            <textarea name="background" class="form-control" rows="4" placeholder="Kerusakan ini ditemukan saat pelaksanaan inspeksi..."></textarea>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-bold">3. Failure Analysis (Analisa Kerusakan)</label>
                            <div class="text-muted small mb-1">Analisa mendalam penyebab teknis kegagalan</div>
                            <textarea name="failure_analysis" class="form-control" rows="5" placeholder="1. Turunnya volume oli secara drastis disebabkan oleh kebocoran..."></textarea>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-bold text-danger">4. Conclusion (Kesimpulan)</label>
                            <div class="text-muted small mb-1">Kesimpulan akhir dari analisa kerusakan.</div>
                            <textarea name="conclusion" class="form-control" rows="4" placeholder="Kerusakan pada 423-4971 DRIVE GP-CIRCLE merupakan akibat langsung dari volume oli yang berada jauh di bawah level..."></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ATTACHMENTS / OBSERVATION -->
            <div class="col-12">
                <div class="card shadow-sm rounded-4 border-0">
                    <div class="card-header bg-azure text-white rounded-top-4 d-flex justify-content-between align-items-center">
                        <h3 class="card-title text-white mb-0">Attachment Photo / Sketches (Observasi)</h3>
                        <button type="button" class="btn btn-sm btn-light text-azure fw-bold" id="btnAddAttachment">
                            + Tambah Foto Observasi
                        </button>
                    </div>
                    <div class="card-body" id="attachmentContainer">
                        <div class="alert alert-info small mb-3">
                            Upload foto/sketsa bukti kerusakan. File gambar maksimal 2MB per foto. Anda dapat menambah lebih dari 1 foto.
                        </div>
                        
                        <!-- Template for attachment row -->
                        <div class="row g-3 attachment-row mb-4 pb-3 border-bottom">
                            <div class="col-md-3">
                                <label class="form-label">Komponen / Bagian</label>
                                <input type="text" name="attachments[0][component]" class="form-control" placeholder="Letak dan posisi...">
                            </div>
                            <div class="col-md-5">
                                <label class="form-label">Observasi (Penjelasan)</label>
                                <textarea name="attachments[0][observation]" class="form-control" rows="3" placeholder="Jelaskan proses kerusakan, temuan visual..."></textarea>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label required">Upload Foto (Max 2MB)</label>
                                <input type="file" name="attachments[0][photo]" class="form-control photo-input" accept="image/*">
                                <div class="mt-2 preview-container"></div>
                                <button type="button" class="btn btn-sm btn-outline-danger mt-2 btn-remove-row d-none">Hapus Baris</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 text-end">
                <button type="submit" class="btn btn-primary btn-lg px-5">Simpan Laporan FAR</button>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Unit auto-select & info population logic
    const woSelect = document.getElementById('work_order_id');
    const unitSelect = document.getElementById('master_unit_id');
    const unitInfoBox = document.getElementById('unitInfoBox');
    
    function populateUnitInfo() {
        const option = unitSelect.options[unitSelect.selectedIndex];
        if (option && option.value) {
            document.getElementById('lblModel').textContent = option.dataset.model || '-';
            document.getElementById('lblSn').textContent = option.dataset.sn || '-';
            document.getElementById('lblEngModel').textContent = option.dataset.engmodel || '-';
            document.getElementById('lblEngSn').textContent = option.dataset.engsn || '-';
            unitInfoBox.style.display = 'flex';
        } else {
            unitInfoBox.style.display = 'none';
        }
    }

    woSelect.addEventListener('change', function() {
        const selectedWo = woSelect.options[woSelect.selectedIndex];
        if (selectedWo && selectedWo.dataset.unit) {
            unitSelect.value = selectedWo.dataset.unit;
            populateUnitInfo();
        }
        if (selectedWo && selectedWo.dataset.site) {
            document.querySelector('select[name="site_id"]').value = selectedWo.dataset.site;
        }
    });

    unitSelect.addEventListener('change', populateUnitInfo);

    // Auto-fill component description when part is selected
    const componentPartSelect = document.getElementById('component_part_no');
    const componentDescInput = document.getElementById('component_description');
    
    if (componentPartSelect) {
        componentPartSelect.addEventListener('change', function() {
            const option = componentPartSelect.options[componentPartSelect.selectedIndex];
            if (option && option.dataset.desc) {
                componentDescInput.value = option.dataset.desc;
            } else {
                componentDescInput.value = '';
            }
        });
    }

    // Dynamic Attachments logic
    const attachmentContainer = document.getElementById('attachmentContainer');
    const btnAddAttachment = document.getElementById('btnAddAttachment');
    let attachmentIndex = 1;

    btnAddAttachment.addEventListener('click', function() {
        const html = `
        <div class="row g-3 attachment-row mb-4 pb-3 border-bottom">
            <div class="col-md-3">
                <label class="form-label">Komponen / Bagian</label>
                <input type="text" name="attachments[${attachmentIndex}][component]" class="form-control" placeholder="Letak dan posisi...">
            </div>
            <div class="col-md-5">
                <label class="form-label">Observasi (Penjelasan)</label>
                <textarea name="attachments[${attachmentIndex}][observation]" class="form-control" rows="3" placeholder="Jelaskan proses kerusakan, temuan visual..."></textarea>
            </div>
            <div class="col-md-4">
                <label class="form-label required">Upload Foto (Max 2MB)</label>
                <input type="file" name="attachments[${attachmentIndex}][photo]" class="form-control photo-input" accept="image/*">
                <div class="mt-2 preview-container"></div>
                <button type="button" class="btn btn-sm btn-outline-danger mt-2 btn-remove-row">Hapus Baris</button>
            </div>
        </div>`;
        attachmentContainer.insertAdjacentHTML('beforeend', html);
        attachmentIndex++;
    });

    attachmentContainer.addEventListener('click', function(e) {
        if (e.target.classList.contains('btn-remove-row')) {
            e.target.closest('.attachment-row').remove();
        }
    });

    // Automatic canvas image compression function (Target <= 2MB)
    function compressImageIfNeeded(file, maxMb, callback) {
        const maxBytes = maxMb * 1024 * 1024;
        if (file.size <= maxBytes) {
            callback(file, false);
            return;
        }

        const reader = new FileReader();
        reader.onload = function(event) {
            const img = new Image();
            img.onload = function() {
                const canvas = document.createElement('canvas');
                let width = img.width;
                let height = img.height;
                const maxDim = 1920;

                if (width > maxDim || height > maxDim) {
                    if (width > height) {
                        height = Math.round((height * maxDim) / width);
                        width = maxDim;
                    } else {
                        width = Math.round((width * maxDim) / height);
                        height = maxDim;
                    }
                }

                canvas.width = width;
                canvas.height = height;

                const ctx = canvas.getContext('2d');
                ctx.drawImage(img, 0, 0, width, height);

                canvas.toBlob(function(blob) {
                    if (blob) {
                        const compressedFile = new File([blob], file.name, {
                            type: 'image/jpeg',
                            lastModified: Date.now()
                        });
                        callback(compressedFile, true);
                    } else {
                        callback(file, false);
                    }
                }, 'image/jpeg', 0.82);
            };
            img.src = event.target.result;
        };
        reader.readAsDataURL(file);
    }

    // Automatic compression on photo input change
    attachmentContainer.addEventListener('change', function(e) {
        if (e.target.classList.contains('photo-input')) {
            const input = e.target;
            const file = input.files[0];
            const previewContainer = input.nextElementSibling;
            
            if (file) {
                previewContainer.innerHTML = '<div class="text-muted small mt-2"><div class="spinner-border spinner-border-sm me-1"></div> Mengompres gambar secara otomatis...</div>';

                compressImageIfNeeded(file, 2, function(processedFile, wasCompressed) {
                    if (wasCompressed) {
                        const dataTransfer = new DataTransfer();
                        dataTransfer.items.add(processedFile);
                        input.files = dataTransfer.files;
                    }

                    const reader = new FileReader();
                    reader.onload = function(re) {
                        const sizeKb = Math.round(processedFile.size / 1024);
                        const badge = wasCompressed ? `<span class="badge bg-success text-white mt-1 d-inline-block">Otomatis Dikompres (${sizeKb} KB)</span>` : `<span class="badge bg-secondary text-white mt-1 d-inline-block">Ukuran: ${sizeKb} KB</span>`;
                        previewContainer.innerHTML = `<img src="${re.target.result}" class="img-thumbnail mt-2 d-block" style="max-height: 150px;"> ${badge}`;
                    };
                    reader.readAsDataURL(processedFile);
                });
            } else {
                previewContainer.innerHTML = '';
            }
        }
    });
});
</script>
@endsection
