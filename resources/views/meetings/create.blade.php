@extends('layouts.tabler')

@section('title', 'Buat Notulen Rapat Baru - CMMS')

@section('content')
<div class="page-header d-print-none">
  <div class="container-xl">
    <div class="row g-2 align-items-center">
      <div class="col">
        <div class="page-pretitle">Notulen Rapat</div>
        <h2 class="page-title">Buat Notulen Rapat Baru</h2>
      </div>
      <div class="col-auto ms-auto d-print-none">
        <div class="btn-list">
          <a href="{{ route('meetings.index') }}" class="btn btn-secondary">
            Kembali
          </a>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="page-body">
  <div class="container-xl">

    @if ($errors->any())
      <div class="alert alert-danger mb-3">
        <ul class="mb-0">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form action="{{ route('meetings.store') }}" method="POST" id="meeting-form">
      @csrf

      {{-- Card 1: Informasi Header Rapat --}}
      <div class="card mb-3 border-0 shadow-sm">
        <div class="card-header bg-light">
          <h3 class="card-title fw-bold">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon text-primary me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 19a9 9 0 0 1 9 0a9 9 0 0 1 9 0" /><path d="M3 6a9 9 0 0 1 9 0a9 9 0 0 1 9 0" /><line x1="3" y1="6" x2="3" y2="19" /><line x1="12" y1="6" x2="12" y2="19" /><line x1="21" y1="6" x2="21" y2="19" /></svg>
            Informasi Sesi Rapat
          </h3>
        </div>
        <div class="card-body">
          <div class="row g-3">
            <div class="col-md-4">
              <label class="form-label required">Nomor Dokumen Notulen</label>
              <input type="text" class="form-control font-monospace fw-bold" name="meeting_number" value="{{ old('meeting_number', $meetingNumber) }}" required>
            </div>
            <div class="col-md-4">
              <label class="form-label required">Jenis Rapat</label>
              <select name="meeting_type" class="form-select" required>
                @foreach(['Daily Standup', 'Weekly Coordination', 'Monthly Review', 'Safety Talk', 'Ad-hoc'] as $type)
                  <option value="{{ $type }}" {{ old('meeting_type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                @endforeach
              </select>
            </div>
            @if(is_null(auth()->user()->site_id))
            <div class="col-md-4">
              <label class="form-label">Lokasi Site</label>
              <select name="site_id" class="form-select">
                <option value="">-- Pilih Site --</option>
                @foreach($sites as $site)
                  <option value="{{ $site->id }}" {{ old('site_id') == $site->id ? 'selected' : '' }}>{{ $site->name }}</option>
                @endforeach
              </select>
            </div>
            @endif
            <div class="col-md-8">
              <label class="form-label required">Topik / Judul Rapat</label>
              <input type="text" class="form-control" name="title" value="{{ old('title') }}" placeholder="Contoh: Daily Coordination Meeting - Plant & Mine Ops" required>
            </div>
            <div class="col-md-4">
              <label class="form-label required">Tanggal Rapat</label>
              <input type="date" class="form-control" name="meeting_date" value="{{ old('meeting_date', date('Y-m-d')) }}" required>
            </div>
            <div class="col-md-3">
              <label class="form-label">Jam Mulai</label>
              <input type="time" class="form-control" name="start_time" value="{{ old('start_time', '08:00') }}">
            </div>
            <div class="col-md-3">
              <label class="form-label">Jam Selesai</label>
              <input type="time" class="form-control" name="end_time" value="{{ old('end_time', '09:00') }}">
            </div>
            <div class="col-md-6">
              <label class="form-label">Lokasi / Ruang Rapat</label>
              <input type="text" class="form-control" name="location" value="{{ old('location') }}" placeholder="Contoh: Ruang Rapat Workshop / Online Teams">
            </div>
            <div class="col-md-6">
              <label class="form-label">Pimpinan Rapat / Moderator</label>
              <input type="text" class="form-control" name="leader_name" value="{{ old('leader_name', auth()->user()->nama_lengkap) }}" placeholder="Nama pimpinan rapat">
            </div>
            <div class="col-md-6">
              <label class="form-label">Notulis Rapat</label>
              <input type="text" class="form-control" name="notetaker_name" value="{{ old('notetaker_name', auth()->user()->nama_lengkap) }}" placeholder="Nama pencatat notulen">
            </div>
            <div class="col-md-12">
              <label class="form-label">Daftar Hadir / Peserta Rapat</label>
              <textarea class="form-control" name="attendees" rows="2" placeholder="Tuliskan nama peserta atau departemen yang hadir (pisahkan dengan koma atau baris baru)">{{ old('attendees') }}</textarea>
            </div>
            <div class="col-md-12">
              <label class="form-label">Agenda & Catatan Pembahasan Umum</label>
              <textarea class="form-control" name="agenda" rows="3" placeholder="Ringkasan poin-poin agenda yang dibahas...">{{ old('agenda') }}</textarea>
            </div>
          </div>
        </div>
      </div>

      {{-- Card 2: Action Items Table (Continuous Issues) --}}
      <div class="card mb-3 border-0 shadow-sm">
        <div class="card-header bg-light d-flex justify-content-between align-items-center flex-wrap gap-2">
          <div>
            <h3 class="card-title fw-bold m-0">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon text-teal me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2" /><path d="M9 3m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z" /><path d="M9 14l2 2l4 -4" /></svg>
              Butir Pembahasan & Tindak Lanjut (Action Items)
            </h3>
            <div class="text-muted small">Catat keputusan rapat, PIC, target due date, dan pantau isu berkelanjutan.</div>
          </div>
          <div class="btn-list">
            <button type="button" class="btn btn-sm btn-outline-warning" id="btn-import-open-items">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M13 3l0 7l6 0l-8 11l0 -7l-6 0z" /></svg>
              ⚡ Tarik Isu Belum Selesai (Import Open Items)
            </button>
            <button type="button" class="btn btn-sm btn-outline-primary" id="btn-add-item-row">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="12" y1="5" x2="12" y2="19" /><line x1="5" y1="12" x2="19" y2="12" /></svg>
              Tambah Baris Isu
            </button>
          </div>
        </div>
        <div class="table-responsive">
          <table class="table card-table table-vcenter table-bordered" id="action-items-table">
            <thead class="table-light text-center">
              <tr>
                <th style="width: 40px;">No</th>
                <th style="min-width: 200px;">Isu / Permasalahan <span class="text-danger">*</span></th>
                <th style="min-width: 220px;">Pembahasan & Tindakan yang Disepakati</th>
                <th style="width: 140px;">Kategori</th>
                <th style="width: 160px;">PIC (Penanggung Jawab)</th>
                <th style="width: 110px;">Prioritas</th>
                <th style="width: 130px;">Due Date</th>
                <th style="width: 130px;">Status</th>
                <th style="width: 100px;">Progres (%)</th>
                <th style="width: 50px;">Aksi</th>
              </tr>
            </thead>
            <tbody id="action-items-tbody">
              {{-- Initial Row 1 --}}
              <tr class="action-item-row" data-row="0">
                <td class="text-center row-number fw-bold">1</td>
                <td>
                  <input type="hidden" name="items[0][parent_action_item_id]" class="input-parent-id" value="">
                  <textarea name="items[0][issue]" class="form-control form-control-sm" rows="2" placeholder="Tuliskan isu/permasalahan..." required></textarea>
                </td>
                <td>
                  <textarea name="items[0][discussion]" class="form-control form-control-sm" rows="2" placeholder="Rencana tindakan / solusi yang disepakati..."></textarea>
                </td>
                <td>
                  <select name="items[0][category]" class="form-select form-select-sm">
                    @foreach(['Breakdown & WO', 'Sparepart & Logistic', 'Manpower', 'HSE & Safety', 'Operations & Plant', 'Budget & Admin', 'General'] as $cat)
                      <option value="{{ $cat }}" {{ $cat == 'Breakdown & WO' ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                  </select>
                </td>
                <td>
                  <select name="items[0][pic_id]" class="form-select form-select-sm mb-1">
                    <option value="">-- Pilih User PIC --</option>
                    @foreach($users as $u)
                      <option value="{{ $u->id }}">{{ $u->nama_lengkap }}</option>
                    @endforeach
                  </select>
                  <input type="text" name="items[0][pic_name]" class="form-control form-control-sm" placeholder="Atau nama manual...">
                </td>
                <td>
                  <select name="items[0][priority]" class="form-select form-select-sm">
                    <option value="Critical">🔴 Critical</option>
                    <option value="High">🟠 High</option>
                    <option value="Medium" selected>🔵 Medium</option>
                    <option value="Low">⚪ Low</option>
                  </select>
                </td>
                <td>
                  <input type="date" name="items[0][due_date]" class="form-control form-control-sm">
                </td>
                <td>
                  <select name="items[0][status]" class="form-select form-select-sm select-status">
                    <option value="Open" selected>Open</option>
                    <option value="In Progress">In Progress</option>
                    <option value="Waiting Part">Waiting Part</option>
                    <option value="Completed">Completed</option>
                    <option value="Cancelled">Cancelled</option>
                  </select>
                </td>
                <td>
                  <div class="input-group input-group-sm">
                    <input type="number" name="items[0][progress_percent]" class="form-control input-progress" value="0" min="0" max="100">
                    <span class="input-group-text">%</span>
                  </div>
                </td>
                <td class="text-center">
                  <button type="button" class="btn btn-sm btn-outline-danger btn-remove-row" title="Hapus Baris">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon m-0" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="4" y1="7" x2="20" y2="7" /><line x1="10" y1="11" x2="10" y2="17" /><line x1="14" y1="11" x2="14" y2="17" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <div class="card border-0 shadow-sm">
        <div class="card-body d-flex justify-content-between align-items-center">
          <a href="{{ route('meetings.index') }}" class="btn btn-secondary">Batal</a>
          <button type="submit" class="btn btn-primary px-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10" /></svg>
            Simpan Notulen Rapat
          </button>
        </div>
      </div>

    </form>
  </div>
</div>

{{-- Modal Import Open Action Items --}}
<div class="modal modal-blur fade" id="modal-import-open-items" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header bg-light">
        <div>
          <h5 class="modal-title fw-bold">Tarik Isu Belum Selesai (Outstanding Action Items)</h5>
          <div class="text-muted small">Pilih isu-isu dari meeting sebelumnya yang masih berjalan untuk dilanjutkan pada meeting ini.</div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-0">
        <div id="import-loading" class="text-center py-5">
          <div class="spinner-border text-primary" role="status"></div>
          <div class="text-muted mt-2">Memuat daftar isu yang masih berjalan...</div>
        </div>
        <div id="import-empty" class="text-center py-5 d-none">
          <div class="text-muted">Tidak ada isu yang berstatus Open / In Progress dari meeting sebelumnya.</div>
        </div>
        <div class="table-responsive" id="import-table-container">
          <table class="table card-table table-vcenter table-hover table-striped mb-0">
            <thead class="table-light">
              <tr>
                <th style="width: 40px;" class="text-center">
                  <input type="checkbox" class="form-check-input" id="check-all-import">
                </th>
                <th>Isu / Permasalahan</th>
                <th>Asal Meeting</th>
                <th>Kategori</th>
                <th>PIC</th>
                <th>Target Selesai</th>
                <th>Status Terakhir</th>
              </tr>
            </thead>
            <tbody id="import-tbody">
              {{-- Populated via AJAX --}}
            </tbody>
          </table>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn me-auto" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-warning" id="btn-confirm-import">
          <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M13 3l0 7l6 0l-8 11l0 -7l-6 0z" /></svg>
          Tambahkan Isu Terpilih ke Notulen Ini
        </button>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let rowIndex = 1;
    const tbody = document.getElementById('action-items-tbody');
    const usersList = @json($users);

    function generateUserOptions(selectedId) {
        let options = '<option value="">-- Pilih User PIC --</option>';
        usersList.forEach(u => {
            options += `<option value="${u.id}" ${u.id == selectedId ? 'selected' : ''}>${u.nama_lengkap}</option>`;
        });
        return options;
    }

    function reindexRows() {
        const rows = tbody.querySelectorAll('.action-item-row');
        rows.forEach((row, idx) => {
            row.querySelector('.row-number').textContent = idx + 1;
            row.querySelectorAll('input, select, textarea').forEach(input => {
                const name = input.getAttribute('name');
                if (name) {
                    input.setAttribute('name', name.replace(/items\[\d+\]/, `items[${idx}]`));
                }
            });
        });
        rowIndex = rows.length;
    }

    function attachRowEvents(row) {
        row.querySelector('.btn-remove-row').addEventListener('click', function() {
            if (tbody.querySelectorAll('.action-item-row').length > 1) {
                row.remove();
                reindexRows();
            } else {
                window.showTablerErrorModal('Perhatian', 'Minimal harus ada 1 baris tindak lanjut.');
            }
        });

        const statusSelect = row.querySelector('.select-status');
        const progressInput = row.querySelector('.input-progress');
        if (statusSelect && progressInput) {
            statusSelect.addEventListener('change', function() {
                if (this.value === 'Completed') {
                    progressInput.value = 100;
                } else if (this.value === 'Open' && progressInput.value == 100) {
                    progressInput.value = 0;
                }
            });
        }
    }

    // Attach to existing initial row
    document.querySelectorAll('.action-item-row').forEach(attachRowEvents);

    // Add Row Button
    document.getElementById('btn-add-item-row').addEventListener('click', function() {
        const newRow = document.createElement('tr');
        newRow.className = 'action-item-row';
        newRow.dataset.row = rowIndex;
        newRow.innerHTML = `
            <td class="text-center row-number fw-bold">${rowIndex + 1}</td>
            <td>
              <input type="hidden" name="items[${rowIndex}][parent_action_item_id]" class="input-parent-id" value="">
              <textarea name="items[${rowIndex}][issue]" class="form-control form-control-sm" rows="2" placeholder="Tuliskan isu/permasalahan..." required></textarea>
            </td>
            <td>
              <textarea name="items[${rowIndex}][discussion]" class="form-control form-control-sm" rows="2" placeholder="Rencana tindakan / solusi yang disepakati..."></textarea>
            </td>
            <td>
              <select name="items[${rowIndex}][category]" class="form-select form-select-sm">
                <option value="Breakdown & WO">Breakdown & WO</option>
                <option value="Sparepart & Logistic">Sparepart & Logistic</option>
                <option value="Manpower">Manpower</option>
                <option value="HSE & Safety">HSE & Safety</option>
                <option value="Operations & Plant">Operations & Plant</option>
                <option value="Budget & Admin">Budget & Admin</option>
                <option value="General" selected>General</option>
              </select>
            </td>
            <td>
              <select name="items[${rowIndex}][pic_id]" class="form-select form-select-sm mb-1">
                ${generateUserOptions('')}
              </select>
              <input type="text" name="items[${rowIndex}][pic_name]" class="form-control form-control-sm" placeholder="Atau nama manual...">
            </td>
            <td>
              <select name="items[${rowIndex}][priority]" class="form-select form-select-sm">
                <option value="Critical">🔴 Critical</option>
                <option value="High">🟠 High</option>
                <option value="Medium" selected>🔵 Medium</option>
                <option value="Low">⚪ Low</option>
              </select>
            </td>
            <td>
              <input type="date" name="items[${rowIndex}][due_date]" class="form-control form-control-sm">
            </td>
            <td>
              <select name="items[${rowIndex}][status]" class="form-select form-select-sm select-status">
                <option value="Open" selected>Open</option>
                <option value="In Progress">In Progress</option>
                <option value="Waiting Part">Waiting Part</option>
                <option value="Completed">Completed</option>
                <option value="Cancelled">Cancelled</option>
              </select>
            </td>
            <td>
              <div class="input-group input-group-sm">
                <input type="number" name="items[${rowIndex}][progress_percent]" class="form-control input-progress" value="0" min="0" max="100">
                <span class="input-group-text">%</span>
              </div>
            </td>
            <td class="text-center">
              <button type="button" class="btn btn-sm btn-outline-danger btn-remove-row" title="Hapus Baris">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon m-0" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="4" y1="7" x2="20" y2="7" /><line x1="10" y1="11" x2="10" y2="17" /><line x1="14" y1="11" x2="14" y2="17" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
              </button>
            </td>
        `;
        tbody.appendChild(newRow);
        attachRowEvents(newRow);
        rowIndex++;
    });

    // Import Open Items Modal Logic
    const importModalEl = document.getElementById('modal-import-open-items');
    const importModal = new bootstrap.Modal(importModalEl);
    let loadedOpenItems = [];

    document.getElementById('btn-import-open-items').addEventListener('click', function() {
        document.getElementById('import-loading').classList.remove('d-none');
        document.getElementById('import-empty').classList.add('d-none');
        document.getElementById('import-table-container').classList.add('d-none');
        document.getElementById('import-tbody').innerHTML = '';

        importModal.show();

        fetch('{{ route("meetings.get-open-action-items") }}')
            .then(res => res.json())
            .then(data => {
                document.getElementById('import-loading').classList.add('d-none');
                if (data.success && data.items.length > 0) {
                    loadedOpenItems = data.items;
                    document.getElementById('import-table-container').classList.remove('d-none');
                    let html = '';
                    data.items.forEach((item, i) => {
                        html += `
                            <tr>
                              <td class="text-center">
                                <input type="checkbox" class="form-check-input check-import-item" value="${item.id}" data-index="${i}">
                              </td>
                              <td>
                                <div class="fw-bold">${item.issue}</div>
                                ${item.discussion ? `<div class="text-muted small">${item.discussion}</div>` : ''}
                                ${item.latest_update ? `<div class="text-info small">Update: ${item.latest_update}</div>` : ''}
                              </td>
                              <td>
                                <span class="font-monospace small fw-bold text-primary">${item.meeting_number}</span>
                                <div class="text-muted small">${item.meeting_date}</div>
                              </td>
                              <td><span class="badge bg-secondary-lt">${item.category}</span></td>
                              <td>${item.pic_name || '-'}</td>
                              <td>${item.due_date || '-'}</td>
                              <td>
                                <span class="badge bg-warning-lt">${item.status} (${item.progress_percent}%)</span>
                              </td>
                            </tr>
                        `;
                    });
                    document.getElementById('import-tbody').innerHTML = html;
                } else {
                    document.getElementById('import-empty').classList.remove('d-none');
                }
            })
            .catch(err => {
                document.getElementById('import-loading').classList.add('d-none');
                window.showTablerErrorModal('Gagal Mengambil Data', 'Gagal memuat data isu terbuka: ' + err);
            });
    });

    // Check all import items
    document.getElementById('check-all-import').addEventListener('change', function() {
        const checked = this.checked;
        document.querySelectorAll('.check-import-item').forEach(cb => cb.checked = checked);
    });

    // Confirm Import Selected Items
    document.getElementById('btn-confirm-import').addEventListener('click', function() {
        const checkedBoxes = document.querySelectorAll('.check-import-item:checked');
        if (checkedBoxes.length === 0) {
            window.showTablerErrorModal('Peringatan', 'Silakan pilih minimal satu isu untuk diimpor.');
            return;
        }

        // If the first row is completely empty, clear it first
        const firstRow = tbody.querySelector('.action-item-row');
        if (tbody.querySelectorAll('.action-item-row').length === 1 && firstRow) {
            const firstIssue = firstRow.querySelector('textarea[name$="[issue]"]').value.trim();
            if (firstIssue === '') {
                firstRow.remove();
            }
        }

        checkedBoxes.forEach(cb => {
            const item = loadedOpenItems[cb.dataset.index];
            const newRow = document.createElement('tr');
            newRow.className = 'action-item-row table-warning-lt';
            newRow.dataset.row = rowIndex;
            newRow.innerHTML = `
                <td class="text-center row-number fw-bold">${rowIndex + 1}</td>
                <td>
                  <input type="hidden" name="items[${rowIndex}][parent_action_item_id]" class="input-parent-id" value="${item.id}">
                  <textarea name="items[${rowIndex}][issue]" class="form-control form-control-sm" rows="2" required>${item.issue}</textarea>
                  <small class="text-warning fw-semibold font-monospace">Lanjutan dari ${item.meeting_number}</small>
                </td>
                <td>
                  <textarea name="items[${rowIndex}][discussion]" class="form-control form-control-sm" rows="2">${item.discussion || ''}</textarea>
                </td>
                <td>
                  <select name="items[${rowIndex}][category]" class="form-select form-select-sm">
                    ${['Breakdown & WO', 'Sparepart & Logistic', 'Manpower', 'HSE & Safety', 'Operations & Plant', 'Budget & Admin', 'General'].map(cat => `
                      <option value="${cat}" ${cat == item.category ? 'selected' : ''}>${cat}</option>
                    `).join('')}
                  </select>
                </td>
                <td>
                  <select name="items[${rowIndex}][pic_id]" class="form-select form-select-sm mb-1">
                    ${generateUserOptions(item.pic_id)}
                  </select>
                  <input type="text" name="items[${rowIndex}][pic_name]" class="form-control form-control-sm" value="${item.pic_name || ''}">
                </td>
                <td>
                  <select name="items[${rowIndex}][priority]" class="form-select form-select-sm">
                    <option value="Critical" ${item.priority == 'Critical' ? 'selected' : ''}>🔴 Critical</option>
                    <option value="High" ${item.priority == 'High' ? 'selected' : ''}>🟠 High</option>
                    <option value="Medium" ${item.priority == 'Medium' ? 'selected' : ''}>🔵 Medium</option>
                    <option value="Low" ${item.priority == 'Low' ? 'selected' : ''}>⚪ Low</option>
                  </select>
                </td>
                <td>
                  <input type="date" name="items[${rowIndex}][due_date]" class="form-control form-control-sm" value="${item.due_date || ''}">
                </td>
                <td>
                  <select name="items[${rowIndex}][status]" class="form-select form-select-sm select-status">
                    <option value="Open" ${item.status == 'Open' ? 'selected' : ''}>Open</option>
                    <option value="In Progress" ${item.status == 'In Progress' ? 'selected' : ''}>In Progress</option>
                    <option value="Waiting Part" ${item.status == 'Waiting Part' ? 'selected' : ''}>Waiting Part</option>
                    <option value="Completed" ${item.status == 'Completed' ? 'selected' : ''}>Completed</option>
                    <option value="Cancelled" ${item.status == 'Cancelled' ? 'selected' : ''}>Cancelled</option>
                  </select>
                </td>
                <td>
                  <div class="input-group input-group-sm">
                    <input type="number" name="items[${rowIndex}][progress_percent]" class="form-control input-progress" value="${item.progress_percent || 0}" min="0" max="100">
                    <span class="input-group-text">%</span>
                  </div>
                </td>
                <td class="text-center">
                  <button type="button" class="btn btn-sm btn-outline-danger btn-remove-row" title="Hapus Baris">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon m-0" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="4" y1="7" x2="20" y2="7" /><line x1="10" y1="11" x2="10" y2="17" /><line x1="14" y1="11" x2="14" y2="17" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
                  </button>
                </td>
            `;
            tbody.appendChild(newRow);
            attachRowEvents(newRow);
            rowIndex++;
        });

        reindexRows();
        importModal.hide();
    });
});
</script>
@endpush

@endsection
