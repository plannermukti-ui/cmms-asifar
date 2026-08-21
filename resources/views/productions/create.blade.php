@extends('layouts.tabler')

@section('title', 'Buat Laporan Produksi Harian - CMMS Aisfar')

@section('content')
<style>
  .fleet-card {
    border: 1px solid rgba(0, 0, 0, 0.08);
    border-radius: 8px;
    transition: all 0.2s ease;
  }
  .fleet-header {
    background: #f8fafc;
  }
  .fleet-table-head th {
    background: #f1f5f9;
    color: #475569;
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.02em;
    border: 1px solid #e2e8f0;
    vertical-align: middle;
  }
  .ritasi-input {
    font-size: 0.8rem;
    padding: 0.25rem 0.2rem;
    border-radius: 4px;
  }
  .ritasi-input:focus {
    border-color: var(--app-accent, #f59e0b);
    box-shadow: 0 0 0 2px var(--app-accent-glow, rgba(245, 158, 11, 0.25));
  }

  /* ── Dark Mode Harmonization ── */
  [data-bs-theme="dark"] .fleet-card {
    background: #182234;
    border-color: rgba(255, 255, 255, 0.08);
  }
  [data-bs-theme="dark"] .fleet-header {
    background: #131c2c;
    border-color: rgba(255, 255, 255, 0.08);
  }
  [data-bs-theme="dark"] .fleet-table-head th {
    background: #131c2c !important;
    color: #cbd5e1 !important;
    border-color: rgba(255, 255, 255, 0.08) !important;
  }
  [data-bs-theme="dark"] .table-bordered th,
  [data-bs-theme="dark"] .table-bordered td {
    border-color: rgba(255, 255, 255, 0.08) !important;
  }
  [data-bs-theme="dark"] .ritasi-input {
    background-color: #0f172a !important;
    color: #f8fafc !important;
    border-color: rgba(255, 255, 255, 0.12) !important;
  }
</style>

<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title text-uppercase font-weight-bold d-flex align-items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon text-primary" width="28" height="28" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 17l4 0" /><path d="M7 17l0 -4" /><path d="M7 13l5 0" /><path d="M12 13l3 4" /><path d="M17 17l3 0" /><path d="M14 9l5 0" /><path d="M17 9l0 8" /></svg>
                    Buat Laporan Produksi Harian (Shift)
                </h2>
                <div class="text-secondary mt-1">Input data ritasi alat muat (Digger), alat angkut (Hauler), unit support, dan catatan kendala operasional.</div>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <a href="{{ route('productions.index') }}" class="btn btn-outline-secondary">
                        Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <form action="{{ route('productions.store') }}" method="POST" id="productionForm">
            @csrf
            
            <!-- Section 1: Informasi Dasar -->
            <div class="card shadow-sm mb-4">
                <div class="card-header border-bottom py-2.5 d-flex align-items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon text-primary" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12z" /><path d="M16 3v4" /><path d="M8 3v4" /><path d="M4 11h16" /></svg>
                    <h3 class="card-title font-weight-bold m-0">Informasi Dasar Shift</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label required">Tanggal</label>
                            <input type="date" name="date" class="form-control" required value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label required">Shift</label>
                            <select name="shift" class="form-select" required>
                                <option value="DS">Day Shift (DS - Siang)</option>
                                <option value="NS">Night Shift (NS - Malam)</option>
                            </select>
                        </div>
                        <div class="col-md-12 mb-2">
                            <label class="form-label">Catatan Tambahan (Opsional)</label>
                            <input type="text" name="notes" class="form-control" placeholder="Contoh: Kondisi cuaca hujan gerimis di pit pada pagi hari...">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section Fleets Container -->
            <div id="fleets-container">
                <!-- Fleets will be appended here -->
            </div>

            <div class="text-center mb-4">
                <button type="button" class="btn btn-primary btn-pill shadow-sm" onclick="addFleet()">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="12" y1="5" x2="12" y2="19" /><line x1="5" y1="12" x2="19" y2="12" /></svg>
                    Tambah Fleet (Digger) Baru
                </button>
            </div>

            <!-- Section 3: Support Units -->
            <div class="card shadow-sm mb-4">
                <div class="card-header border-bottom py-2.5 d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon text-success" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 17m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M17 17m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M5 17h-2v-11a1 1 0 0 1 1 -1h9v12m-4 0h6m4 0h2v-6h-8m0 -5h5l3 5" /></svg>
                        <h3 class="card-title font-weight-bold m-0">Unit Support (Dozer, Grader, dll)</h3>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-success shadow-none" onclick="addSupport()">+ Tambah Support</button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-vcenter card-table table-bordered mb-0">
                            <thead>
                                <tr class="fleet-table-head">
                                    <th style="width: 40%">Unit Support</th>
                                    <th>HM Awal</th>
                                    <th>HM Akhir</th>
                                    <th class="w-1 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="support-list">
                                <!-- Dynamic rows here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Section 4: Delay Events -->
            <div class="card shadow-sm mb-4">
                <div class="card-header border-bottom py-2.5 d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon text-warning" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="9" /><polyline points="12 7 12 12 15 15" /></svg>
                        <h3 class="card-title font-weight-bold m-0">Delay / Standby Time (Kendala)</h3>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-warning shadow-none" onclick="addDelay()">+ Tambah Delay</button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-vcenter card-table table-bordered mb-0">
                            <thead>
                                <tr class="fleet-table-head">
                                    <th style="width: 140px;">Jam Mulai</th>
                                    <th style="width: 140px;">Jam Selesai</th>
                                    <th>Kode Delay</th>
                                    <th>Terdampak Ke Fleet</th>
                                    <th>Keterangan</th>
                                    <th class="w-1 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="delay-list">
                                <!-- Dynamic rows here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="mb-5 d-flex justify-content-end">
                <button type="submit" class="btn btn-primary btn-lg shadow-sm d-flex align-items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" /><path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M14 4l0 4l-6 0l0 -4" /></svg>
                    Simpan Laporan Shift
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    const formattedUnits = @json($formattedUnits);
    let fleetIndex = 0;
    let supportIndex = 0;
    let delayIndex = 0;
    
    // Store fleets data for the delay dropdown
    let currentFleets = [];

    document.addEventListener('DOMContentLoaded', function() {
        // Add 1 default fleet on load
        addFleet();
    });

    function addFleet() {
        const fleetId = fleetIndex;
        
        let fleetHtml = `
        <div class="card shadow-sm mb-4 fleet-card" id="fleet-card-${fleetId}">
            <div class="card-header fleet-header d-flex justify-content-between align-items-center py-2.5 border-bottom">
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-primary text-primary-fg fw-bold px-2 py-1">${fleetId + 1}</span>
                    <h3 class="card-title font-weight-bold m-0 d-flex align-items-center gap-1.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon text-primary" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 17l4 0" /><path d="M7 17l0 -4" /><path d="M7 13l5 0" /><path d="M12 13l3 4" /><path d="M17 17l3 0" /><path d="M14 9l5 0" /><path d="M17 9l0 8" /></svg>
                        Fleet Digger #${fleetId + 1}
                    </h3>
                </div>
                <button type="button" class="btn btn-sm btn-outline-danger shadow-none d-flex align-items-center gap-1" onclick="removeFleet(${fleetId})">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-trash" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
                    Hapus Fleet
                </button>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label class="form-label required">Digger (Loader)</label>
                        <div id="digger-select-${fleetId}"></div>
                        <input type="hidden" name="fleets[${fleetId}][digger_id]" id="digger_id_${fleetId}" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label required">Material</label>
                        <select name="fleets[${fleetId}][material_type]" class="form-select" required>
                            <option value="OB (Overburden)">OB (Overburden)</option>
                            <option value="Coal (Batubara)">Coal (Batubara)</option>
                            <option value="Top Soil">Top Soil</option>
                            <option value="Mud (Lumpur)">Mud (Lumpur)</option>
                            <option value="Sub Soil">Sub Soil</option>
                            <option value="Waste">Waste</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Target BCM / Jam</label>
                        <input type="number" step="0.01" name="fleets[${fleetId}][target_bcm_per_hour]" class="form-control" placeholder="Contoh: 350">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Jarak Angkut (KM)</label>
                        <input type="number" step="0.01" name="fleets[${fleetId}][distance]" class="form-control" placeholder="Contoh: 0.7">
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-2 pt-2 border-top">
                    <h4 class="m-0 text-primary font-weight-bold d-flex align-items-center gap-1.5" style="font-size: 0.9rem;">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon text-primary" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 17m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M17 17m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M5 17h-2v-11a1 1 0 0 1 1 -1h9v12m-4 0h6m4 0h2v-6h-8m0 -5h5l3 5" /></svg>
                        Unit Angkut (Haulers)
                    </h4>
                    <button type="button" class="btn btn-sm btn-outline-primary shadow-none" onclick="addHauler(${fleetId})">+ Tambah Hauler</button>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-vcenter table-bordered table-sm mb-0">
                        <thead class="fleet-table-head">
                            <tr>
                                <th style="min-width: 220px;">Unit Hauler</th>
                                <th style="width: 90px;" class="text-center">Payload (BCM)</th>
                                <th class="text-center" colspan="12">Ritasi per Jam (Jam 1 - 12)</th>
                                <th class="w-1 text-center">Aksi</th>
                            </tr>
                            <tr>
                                <th></th>
                                <th></th>
                                ${[...Array(12).keys()].map(i => `<th class="text-center p-1" style="min-width: 38px; font-size:11px;">J${i+1}</th>`).join('')}
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="hauler-list-${fleetId}">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        `;

        document.getElementById('fleets-container').insertAdjacentHTML('beforeend', fleetHtml);

        // Init Digger VirtualSelect
        VirtualSelect.init({
            ele: `#digger-select-${fleetId}`,
            options: formattedUnits,
            search: true,
            placeholder: 'Pilih Digger...',
            dropboxWidth: '300px'
        });

        document.querySelector(`#digger-select-${fleetId}`).addEventListener('change', function() {
            document.getElementById(`digger_id_${fleetId}`).value = this.value;
            updateDelayFleetOptions();
        });

        // Add 1 default hauler to this fleet
        addHauler(fleetId);

        fleetIndex++;
        updateDelayFleetOptions();
    }

    function removeFleet(fleetId) {
        document.getElementById(`fleet-card-${fleetId}`).remove();
        updateDelayFleetOptions();
    }

    // A unique counter for all haulers to avoid ID collision in JS
    let globalHaulerCounter = 0; 
    
    function addHauler(fleetId) {
        const hId = globalHaulerCounter++;
        const tbody = document.getElementById(`hauler-list-${fleetId}`);
        
        const tr = document.createElement('tr');
        
        let selectHtml = `<div id="hauler-select-${hId}"></div><input type="hidden" name="fleets[${fleetId}][haulers][${hId}][hauler_id]" id="hauler_id_${hId}" required>`;
        
        let hourlyInputs = '';
        for(let i=1; i<=12; i++) {
            hourlyInputs += `<td class="p-1"><input type="number" min="0" name="fleets[${fleetId}][haulers][${hId}][hourly_ritasi][${i}]" class="form-control ritasi-input text-center" placeholder="0"></td>`;
        }

        tr.innerHTML = `
            <td>${selectHtml}</td>
            <td><input type="number" step="0.01" name="fleets[${fleetId}][haulers][${hId}][payload]" class="form-control form-control-sm text-center" placeholder="20" required></td>
            ${hourlyInputs}
            <td class="text-center"><button type="button" class="btn btn-icon btn-sm btn-ghost-danger shadow-none" onclick="this.closest('tr').remove()" title="Hapus Baris Hauler"><svg xmlns="http://www.w3.org/2000/svg" class="icon m-0" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="18" y1="6" x2="6" y2="18" /><line x1="6" y1="6" x2="18" y2="18" /></svg></button></td>
        `;
        tbody.appendChild(tr);

        // Init Hauler VirtualSelect
        VirtualSelect.init({
            ele: `#hauler-select-${hId}`,
            options: formattedUnits,
            search: true,
            placeholder: 'Pilih Hauler...',
            dropboxWidth: '250px'
        });
        document.querySelector(`#hauler-select-${hId}`).addEventListener('change', function() {
            document.getElementById(`hauler_id_${hId}`).value = this.value;
        });
    }

    function addSupport() {
        const tr = document.createElement('tr');
        
        let selectHtml = `<div id="support-select-${supportIndex}"></div><input type="hidden" name="supports[${supportIndex}][support_id]" id="support_id_${supportIndex}" required>`;
        
        tr.innerHTML = `
            <td>${selectHtml}</td>
            <td><input type="number" step="0.01" name="supports[${supportIndex}][hm_awal]" class="form-control form-control-sm" placeholder="0.00" required></td>
            <td><input type="number" step="0.01" name="supports[${supportIndex}][hm_akhir]" class="form-control form-control-sm" placeholder="0.00" required></td>
            <td class="text-center"><button type="button" class="btn btn-icon btn-sm btn-ghost-danger shadow-none" onclick="this.closest('tr').remove()" title="Hapus"><svg xmlns="http://www.w3.org/2000/svg" class="icon m-0" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="18" y1="6" x2="6" y2="18" /><line x1="6" y1="6" x2="18" y2="18" /></svg></button></td>
        `;
        document.getElementById('support-list').appendChild(tr);

        let currentIdx = supportIndex;
        VirtualSelect.init({
            ele: `#support-select-${currentIdx}`,
            options: formattedUnits,
            search: true,
            placeholder: 'Pilih Support...',
            dropboxWidth: '250px'
        });
        document.querySelector(`#support-select-${currentIdx}`).addEventListener('change', function() {
            document.getElementById(`support_id_${currentIdx}`).value = this.value;
        });

        supportIndex++;
    }

    function addDelay() {
        const tr = document.createElement('tr');
        const currDelayIdx = delayIndex++;
        
        tr.innerHTML = `
            <td><input type="time" name="delays[${currDelayIdx}][start_time]" class="form-control form-control-sm" required></td>
            <td><input type="time" name="delays[${currDelayIdx}][end_time]" class="form-control form-control-sm" required></td>
            <td>
                <select name="delays[${currDelayIdx}][delay_code]" class="form-select form-select-sm" required>
                    <option value="Rain">Rain (Hujan)</option>
                    <option value="Slippery">Slippery (Licin)</option>
                    <option value="Breakdown">Breakdown Unit</option>
                    <option value="No Operator">No Operator</option>
                    <option value="Refueling">Refueling</option>
                    <option value="Rest/Meal">Rest/Meal Time</option>
                    <option value="Other">Lain-lain</option>
                </select>
            </td>
            <td>
                <select name="delays[${currDelayIdx}][fleet_id]" class="form-select form-select-sm delay-fleet-select">
                    <option value="">-- Semua Fleet (Global) --</option>
                </select>
            </td>
            <td><input type="text" name="delays[${currDelayIdx}][remarks]" class="form-control form-control-sm" placeholder="Keterangan..."></td>
            <td class="text-center"><button type="button" class="btn btn-icon btn-sm btn-ghost-danger shadow-none" onclick="this.closest('tr').remove()" title="Hapus"><svg xmlns="http://www.w3.org/2000/svg" class="icon m-0" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="18" y1="6" x2="6" y2="18" /><line x1="6" y1="6" x2="18" y2="18" /></svg></button></td>
        `;
        document.getElementById('delay-list').appendChild(tr);
        updateDelayFleetOptions();
    }

    function updateDelayFleetOptions() {
        const selects = document.querySelectorAll('.delay-fleet-select');
        if(selects.length === 0) return;

        // Gather all current fleets
        let fleets = [];
        document.querySelectorAll('.fleet-card').forEach((card) => {
            const idParts = card.id.split('-');
            const fId = idParts[2];
            // get the text of the selected digger
            const diggerEl = document.querySelector(`#digger-select-${fId}`);
            let diggerName = 'Fleet ' + (parseInt(fId)+1);
            if(diggerEl && diggerEl.value) {
                const label = diggerEl.querySelector('.vscomp-value')?.textContent;
                if(label && label.trim() !== '') diggerName = label;
            }
            fleets.push({ id: fId, name: diggerName });
        });

        // Update all selects while preserving their current value if possible
        selects.forEach(select => {
            const currentVal = select.value;
            let html = '<option value="">-- Semua Fleet (Global) --</option>';
            fleets.forEach(f => {
                html += `<option value="${f.id}" ${currentVal == f.id ? 'selected' : ''}>${f.name}</option>`;
            });
            select.innerHTML = html;
        });
    }
</script>
@endsection
