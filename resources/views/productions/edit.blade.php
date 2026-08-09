@extends('layouts.tabler')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title text-uppercase font-weight-bold">
                    Edit Laporan Produksi Harian (Shift)
                </h2>
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
        <form action="{{ route('productions.update', $production->id) }}" method="POST" id="productionForm">
            @csrf
            @method('PUT')
            
            <!-- Section 1: Informasi Dasar -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-primary-lt">
                    <h3 class="card-title font-weight-bold">Informasi Dasar Shift</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label required">Tanggal</label>
                            <input type="date" name="date" class="form-control" required value="{{ \Carbon\Carbon::parse($production->date)->format('Y-m-d') }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label required">Shift</label>
                            <select name="shift" class="form-select" required>
                                <option value="DS" {{ $production->shift == 'DS' ? 'selected' : '' }}>Day Shift (DS)</option>
                                <option value="NS" {{ $production->shift == 'NS' ? 'selected' : '' }}>Night Shift (NS)</option>
                            </select>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Catatan Tambahan (Opsional)</label>
                            <input type="text" name="notes" class="form-control" value="{{ $production->notes }}" placeholder="Contoh: Kondisi cuaca hujan di pagi hari...">
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
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="12" y1="5" x2="12" y2="19" /><line x1="5" y1="12" x2="19" y2="12" /></svg>
                    Tambah Fleet (Digger) Baru
                </button>
            </div>

            <!-- Section 3: Support Units -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-green-lt d-flex justify-content-between align-items-center">
                    <h3 class="card-title font-weight-bold">Unit Support (Dozer, Grader, dll)</h3>
                    <button type="button" class="btn btn-sm btn-success" onclick="addSupport()">+ Tambah Support</button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-vcenter card-table">
                            <thead>
                                <tr>
                                    <th style="width: 40%">Unit Support</th>
                                    <th>HM Awal</th>
                                    <th>HM Akhir</th>
                                    <th class="w-1">Hapus</th>
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
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-red-lt d-flex justify-content-between align-items-center">
                    <h3 class="card-title font-weight-bold">Delay / Standby Time (Kendala)</h3>
                    <button type="button" class="btn btn-sm btn-danger" onclick="addDelay()">+ Tambah Delay</button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-vcenter card-table">
                            <thead>
                                <tr>
                                    <th>Jam Mulai</th>
                                    <th>Jam Selesai</th>
                                    <th>Kode Delay</th>
                                    <th>Terdampak Ke Fleet</th>
                                    <th>Keterangan</th>
                                    <th class="w-1">Hapus</th>
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
                <button type="submit" class="btn btn-primary btn-lg shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" /><path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M14 4l0 4l-6 0l0 -4" /></svg>
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

    const existingProduction = @json($production);

    document.addEventListener('DOMContentLoaded', function() {
        if (existingProduction.fleets && existingProduction.fleets.length > 0) {
            existingProduction.fleets.forEach((fleet) => {
                addFleet(fleet);
            });
        } else {
            addFleet(); // Add 1 default if empty
        }

        if (existingProduction.supports && existingProduction.supports.length > 0) {
            existingProduction.supports.forEach((support) => {
                addSupport(support);
            });
        }

        if (existingProduction.delays && existingProduction.delays.length > 0) {
            existingProduction.delays.forEach((delay) => {
                addDelay(delay);
            });
        }
    });

    function addFleet(initialData = null) {
        const fleetId = fleetIndex;
        
        let fleetHtml = `
        <div class="card shadow-sm border border-secondary mb-4 fleet-card" id="fleet-card-${fleetId}">
            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                <h3 class="card-title font-weight-bold m-0"><span class="badge bg-primary rounded-circle me-2">${fleetId + 1}</span> Fleet Digger</h3>
                <button type="button" class="btn btn-sm btn-danger" onclick="removeFleet(${fleetId})">Hapus Fleet</button>
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
                            <option value="OB (Overburden)" ${initialData && initialData.material_type == 'OB (Overburden)' ? 'selected' : ''}>OB (Overburden)</option>
                            <option value="Coal (Batubara)" ${initialData && initialData.material_type == 'Coal (Batubara)' ? 'selected' : ''}>Coal (Batubara)</option>
                            <option value="Top Soil" ${initialData && initialData.material_type == 'Top Soil' ? 'selected' : ''}>Top Soil</option>
                            <option value="Mud (Lumpur)" ${initialData && initialData.material_type == 'Mud (Lumpur)' ? 'selected' : ''}>Mud (Lumpur)</option>
                            <option value="Sub Soil" ${initialData && initialData.material_type == 'Sub Soil' ? 'selected' : ''}>Sub Soil</option>
                            <option value="Waste" ${initialData && initialData.material_type == 'Waste' ? 'selected' : ''}>Waste</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Target BCM / Jam</label>
                        <input type="number" step="0.01" name="fleets[${fleetId}][target_bcm_per_hour]" class="form-control" placeholder="Contoh: 350" value="${initialData && initialData.target_bcm_per_hour ? initialData.target_bcm_per_hour : ''}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Jarak Angkut (KM)</label>
                        <input type="number" step="0.01" name="fleets[${fleetId}][distance]" class="form-control" placeholder="Contoh: 0.7" value="${initialData && initialData.distance ? initialData.distance : ''}">
                    </div>
                </div>

                <hr>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h4 class="m-0 text-azure font-weight-bold">Unit Angkut (Haulers)</h4>
                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="addHauler(${fleetId})">+ Tambah Hauler ke Fleet Ini</button>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-vcenter table-bordered table-sm">
                        <thead class="bg-azure-lt">
                            <tr>
                                <th style="min-width: 250px;">Unit Hauler</th>
                                <th>Payload</th>
                                <th class="text-center" colspan="12">Ritasi per Jam (Jam 1 - 12)</th>
                                <th class="w-1">Hapus</th>
                            </tr>
                            <tr>
                                <th></th>
                                <th>(BCM)</th>
                                ${[...Array(12).keys()].map(i => `<th class="text-center p-1" style="min-width: 40px; font-size:10px;">${i+1}</th>`).join('')}
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
        let diggerVal = initialData ? initialData.digger_id : '';
        VirtualSelect.init({
            ele: `#digger-select-${fleetId}`,
            options: formattedUnits,
            search: true,
            placeholder: 'Pilih Digger...',
            dropboxWidth: '300px',
            selectedValue: diggerVal
        });
        if(diggerVal) document.getElementById(`digger_id_${fleetId}`).value = diggerVal;

        document.querySelector(`#digger-select-${fleetId}`).addEventListener('change', function() {
            document.getElementById(`digger_id_${fleetId}`).value = this.value;
            updateDelayFleetOptions();
        });

        if (initialData && initialData.haulers && initialData.haulers.length > 0) {
            initialData.haulers.forEach(h => addHauler(fleetId, h));
        } else {
            addHauler(fleetId);
        }

        fleetIndex++;
        updateDelayFleetOptions();
    }

    function removeFleet(fleetId) {
        document.getElementById(`fleet-card-${fleetId}`).remove();
        updateDelayFleetOptions();
    }

    // A unique counter for all haulers to avoid ID collision in JS
    let globalHaulerCounter = 0; 
    
    function addHauler(fleetId, initialData = null) {
        const hId = globalHaulerCounter++;
        const tbody = document.getElementById(`hauler-list-${fleetId}`);
        
        const tr = document.createElement('tr');
        
        let selectHtml = `<div id="hauler-select-${hId}"></div><input type="hidden" name="fleets[${fleetId}][haulers][${hId}][hauler_id]" id="hauler_id_${hId}" required>`;
        
        let hourlyInputs = '';
        for(let i=1; i<=12; i++) {
            let hourlyVal = initialData && initialData.hourly_ritasi && initialData.hourly_ritasi[i] !== undefined ? initialData.hourly_ritasi[i] : '';
            hourlyInputs += `<td><input type="number" min="0" name="fleets[${fleetId}][haulers][${hId}][hourly_ritasi][${i}]" class="form-control px-1 py-1 text-center" style="font-size:12px;" value="${hourlyVal}"></td>`;
        }

        let payloadVal = initialData ? initialData.payload : '';

        tr.innerHTML = `
            <td>${selectHtml}</td>
            <td><input type="number" step="0.01" name="fleets[${fleetId}][haulers][${hId}][payload]" class="form-control px-1" required value="${payloadVal}"></td>
            ${hourlyInputs}
            <td><button type="button" class="btn btn-icon btn-sm btn-danger" onclick="this.closest('tr').remove()"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="18" y1="6" x2="6" y2="18" /><line x1="6" y1="6" x2="18" y2="18" /></svg></button></td>
        `;
        tbody.appendChild(tr);

        // Init Hauler VirtualSelect
        let haulerVal = initialData ? initialData.hauler_id : '';
        VirtualSelect.init({
            ele: `#hauler-select-${hId}`,
            options: formattedUnits,
            search: true,
            placeholder: 'Pilih Hauler...',
            dropboxWidth: '250px',
            selectedValue: haulerVal
        });
        if(haulerVal) document.getElementById(`hauler_id_${hId}`).value = haulerVal;
        
        document.querySelector(`#hauler-select-${hId}`).addEventListener('change', function() {
            document.getElementById(`hauler_id_${hId}`).value = this.value;
        });
    }

    function addSupport(initialData = null) {
        const tr = document.createElement('tr');
        
        let selectHtml = `<div id="support-select-${supportIndex}"></div><input type="hidden" name="supports[${supportIndex}][support_id]" id="support_id_${supportIndex}" required>`;
        
        let hmAwal = initialData && initialData.hm_awal ? initialData.hm_awal : '';
        let hmAkhir = initialData && initialData.hm_akhir ? initialData.hm_akhir : '';

        tr.innerHTML = `
            <td>${selectHtml}</td>
            <td><input type="number" step="0.01" name="supports[${supportIndex}][hm_awal]" class="form-control" placeholder="0.00" required value="${hmAwal}"></td>
            <td><input type="number" step="0.01" name="supports[${supportIndex}][hm_akhir]" class="form-control" placeholder="0.00" required value="${hmAkhir}"></td>
            <td><button type="button" class="btn btn-icon btn-sm btn-danger" onclick="this.closest('tr').remove()">X</button></td>
        `;
        document.getElementById('support-list').appendChild(tr);

        let currentIdx = supportIndex;
        let supportVal = initialData ? initialData.support_id : '';
        VirtualSelect.init({
            ele: `#support-select-${currentIdx}`,
            options: formattedUnits,
            search: true,
            placeholder: 'Pilih Support...',
            dropboxWidth: '250px',
            selectedValue: supportVal
        });
        if(supportVal) document.getElementById(`support_id_${currentIdx}`).value = supportVal;
        
        document.querySelector(`#support-select-${currentIdx}`).addEventListener('change', function() {
            document.getElementById(`support_id_${currentIdx}`).value = this.value;
        });

        supportIndex++;
    }

    function addDelay(initialData = null) {
        const tr = document.createElement('tr');
        const currDelayIdx = delayIndex++;
        
        let startVal = initialData ? initialData.start_time : '';
        if(startVal.length > 5) startVal = startVal.substring(0, 5); // remove seconds
        
        let endVal = initialData ? initialData.end_time : '';
        if(endVal.length > 5) endVal = endVal.substring(0, 5);
        
        let rmks = initialData ? initialData.remarks : '';
        
        let code = initialData ? initialData.delay_code : '';
        
        let mappedFleetIdx = '';
        if (initialData && initialData.production_fleet_id) {
            const idx = existingProduction.fleets.findIndex(f => f.id === initialData.production_fleet_id);
            if(idx !== -1) mappedFleetIdx = idx;
        }

        tr.innerHTML = `
            <td><input type="time" name="delays[${currDelayIdx}][start_time]" class="form-control" required value="${startVal}"></td>
            <td><input type="time" name="delays[${currDelayIdx}][end_time]" class="form-control" required value="${endVal}"></td>
            <td>
                <select name="delays[${currDelayIdx}][delay_code]" class="form-select" required>
                    <option value="Rain" ${code == 'Rain' ? 'selected' : ''}>Rain (Hujan)</option>
                    <option value="Slippery" ${code == 'Slippery' ? 'selected' : ''}>Slippery (Licin)</option>
                    <option value="Breakdown" ${code == 'Breakdown' ? 'selected' : ''}>Breakdown Unit</option>
                    <option value="No Operator" ${code == 'No Operator' ? 'selected' : ''}>No Operator</option>
                    <option value="Refueling" ${code == 'Refueling' ? 'selected' : ''}>Refueling</option>
                    <option value="Rest/Meal" ${code == 'Rest/Meal' ? 'selected' : ''}>Rest/Meal Time</option>
                    <option value="Other" ${code == 'Other' ? 'selected' : ''}>Lain-lain</option>
                </select>
            </td>
            <td>
                <select name="delays[${currDelayIdx}][fleet_id]" class="form-select delay-fleet-select" data-selected="${mappedFleetIdx}">
                    <option value="">-- Semua Fleet (Global) --</option>
                </select>
            </td>
            <td><input type="text" name="delays[${currDelayIdx}][remarks]" class="form-control" placeholder="Keterangan" value="${rmks}"></td>
            <td><button type="button" class="btn btn-icon btn-sm btn-danger" onclick="this.closest('tr').remove()">X</button></td>
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
                const label = diggerEl.querySelector('.vscomp-value').textContent;
                if(label) diggerName = label;
            }
            fleets.push({ id: fId, name: diggerName });
        });

        // Update all selects while preserving their current value if possible
        selects.forEach(select => {
            let currentVal = select.value;
            if(!currentVal && select.getAttribute('data-selected')) {
                currentVal = select.getAttribute('data-selected');
                select.removeAttribute('data-selected');
            }
            
            let html = '<option value="">-- Semua Fleet (Global) --</option>';
            fleets.forEach(f => {
                html += `<option value="${f.id}" ${currentVal == f.id ? 'selected' : ''}>${f.name}</option>`;
            });
            select.innerHTML = html;
        });
    }
</script>
@endsection
