@php
  $actions = $actions ?? ['view', 'create', 'edit', 'delete'];
  $selectedPermissions = $selectedPermissions ?? [];

  $categorizedModules = [
      'Utama & Komunikasi' => [
          'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="icon text-cyan" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 9h8" /><path d="M8 13h6" /><path d="M9 18h-3a3 3 0 0 1 -3 -3v-8a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v8a3 3 0 0 1 -3 3h-3l-3 3l-3 -3z" /></svg>',
          'modules' => [
              'chat' => 'Pesan Instan',
              'meetings' => 'Notulen Rapat & Tindak Lanjut',
          ]
      ],
      'Maintenance & Operasional' => [
          'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="icon text-azure" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2" /><path d="M9 3m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z" /><path d="M9 14l2 2l4 -4" /></svg>',
          'modules' => [
              'pra_work_orders' => 'Laporan Kerusakan (Pra-WO)',
              'work_orders' => 'Work Order (WO)',
              'work_orders_kanban' => 'Kanban Board (WO)',
              'wo_comments' => 'Diskusi Work Order',
              'hour_meters' => 'Hour Meter Unit',
              'jwos' => 'Job Work Order (JWO)',
              'productions' => 'Laporan Produksi Harian',
          ]
      ],
      'Planning & Reliability' => [
          'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="icon text-pink" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M12 12l3 2" /><path d="M12 7v5" /></svg>',
          'modules' => [
              'pcr' => 'Plan Component Replacement (PCR)',
              'pm_templates' => 'PM Template (Checklist)',
              'pm_schedules' => 'Jadwal & History PM',
              'plan_budgets' => 'Plan Budget Bulanan (RAB)',
              'fars' => 'Form Analisa Rusak (FAR)',
              'swap_components' => 'Swap Component Report',
          ]
      ],
      'ToolRoom & Workshop' => [
          'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="icon text-warning" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 10h3v-3l-3.5 -3.5a6 6 0 0 1 8 8l6 6a2 2 0 0 1 -3 3l-6 -6a6 6 0 0 1 -8 -8l3.5 3.5" /></svg>',
          'modules' => [
              'tool_transactions' => 'Peminjaman Tool',
              'tool_stock_requests' => 'Approval Stok Tool',
              'incident_reports' => 'Berita Acara (B.A)',
              'stock_opnames' => 'Stock Opname',
          ]
      ],
      'K3 & HSE' => [
          'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="icon text-green" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 3c7.2 0 9 1.8 9 9s-1.8 9 -9 9s-9 -1.8 -9 -9s1.8 -9 9 -9z" /><path d="M9 12l2 2l4 -4" /></svg>',
          'modules' => [
              'hse_jsas' => 'JSA (Job Safety Analysis)',
              'hse_ptws' => 'PTW (Permit to Work)',
              'hse_lotos' => 'LOTO (Lockout Tagout)',
          ]
      ],
      'KPI & Reporting' => [
          'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="icon text-purple" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 19l16 0" /><path d="M4 15l4 -6l4 2l4 -5l4 4" /></svg>',
          'modules' => [
              'breakdown_reports' => 'Report Breakdown & Downtime',
              'kpi_master_data' => 'KPI Master Data',
          ]
      ],
      'Master Data' => [
          'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="icon text-teal" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 17m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M17 17m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M5 17h-2v-6l2 -5h9l4 5h1a2 2 0 0 1 2 2v4h-2m-4 0h-6m-6 -6h15m-6 0v-5" /></svg>',
          'modules' => [
              'master_units' => 'Master Unit (Asset)',
              'unit_types' => 'Master Tipe Unit',
              'unit_models' => 'Master Model Unit',
              'parts' => 'Master Part / Suku Cadang',
              'vendors' => 'Master Vendor / Bengkel',
              'mechanics' => 'Master Mekanik',
              'tools' => 'Master Tool',
              'tool_stocks' => 'Stok Tool',
              'tool_categories' => 'Kategori Tool',
              'breakdown_types' => 'Tipe Breakdown',
              'component_groups' => 'Grup Komponen',
              'wo_categories' => 'Kategori WO',
          ]
      ],
      'Administrator & Sistem' => [
          'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="icon text-indigo" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M10.325 4.317c.426 -1.756 2.924 -1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543 -.94 3.31 .826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756 .426 1.756 2.924 0 3.35a1.724 1.724 0 0 0 -1.066 2.573c.94 1.543 -.826 3.31 -2.37 2.37a1.724 1.724 0 0 0 -2.572 1.065c-.426 1.756 -2.924 1.756 -3.35 0a1.724 1.724 0 0 0 -2.573 -1.066c-1.543 .94 -3.31 -.826 -2.37 -2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756 -.426 -1.756 -2.924 0 -3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94 -1.543 .826 -3.31 2.37 -2.37c1 .608 2.296 .07 2.572 -1.065z" /><path d="M9 12a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" /></svg>',
          'modules' => [
              'users' => 'Manajemen User',
              'roles' => 'Role & Hak Akses',
              'departments' => 'Departemen',
              'jabatans' => 'Jabatan',
              'modules' => 'Master Modul',
              'approval_matrix' => 'Approval Matrix',
              'sites' => 'Master Site (Tambang)',
              'activity_log' => 'Log Aktivitas',
              'backup' => 'Backup Database',
              'settings' => 'Pengaturan Sistem',
          ]
      ],
  ];
@endphp

<div class="card border-0 shadow-sm">
  <div class="card-header bg-light d-flex justify-content-between align-items-center py-3">
    <div>
      <h3 class="card-title fw-bold m-0">Matriks Hak Akses (Permissions)</h3>
      <div class="text-muted small mt-1">Atur perizinan akses secara spesifik per kategori modul aplikasi.</div>
    </div>
    <div class="btn-list">
      <button type="button" class="btn btn-sm btn-outline-primary" id="btn-select-all-permissions">
        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 11l3 3l8 -8" /><path d="M20 12v6a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h9" /></svg>
        Pilih Semua
      </button>
      <button type="button" class="btn btn-sm btn-outline-secondary" id="btn-deselect-all-permissions">
        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M18 6l-12 12" /><path d="M6 6l12 12" /></svg>
        Kosongkan
      </button>
    </div>
  </div>

  <div class="table-responsive">
    <table class="table table-vcenter card-table table-hover table-bordered m-0" id="permissions-matrix-table">
      <thead class="table-light text-center">
        <tr>
          <th style="min-width: 250px; text-align: left;">Modul Aplikasi</th>
          @foreach($actions as $action)
            <th style="width: 120px;" class="text-center">
              <span class="badge bg-blue-lt text-uppercase px-2 py-1">{{ ucfirst($action) }}</span>
            </th>
          @endforeach
        </tr>
      </thead>
      <tbody>
        @foreach($categorizedModules as $catName => $catData)
          @php $catSlug = \Illuminate\Support\Str::slug($catName); @endphp
          {{-- Category Header Row --}}
          <tr class="table-active bg-light text-dark fw-bold border-top border-bottom">
            <td colspan="{{ count($actions) + 1 }}" class="py-2.5 px-3">
              <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                  {!! $catData['icon'] !!}
                  <span class="fs-4">{{ $catName }}</span>
                  <span class="badge bg-secondary-lt pill ms-1">{{ count($catData['modules']) }} Modul</span>
                </div>
                <div>
                  <button type="button" class="btn btn-xs btn-outline-primary toggle-cat-btn" data-target="{{ $catSlug }}">
                    Pilih / Batal Kategori Ini
                  </button>
                </div>
              </div>
            </td>
          </tr>

          {{-- Category Rows --}}
          @foreach($catData['modules'] as $moduleKey => $moduleName)
            <tr class="permission-row cat-row-{{ $catSlug }}">
              <td class="ps-4">
                <div class="d-flex align-items-center justify-content-between">
                  <span class="fw-semibold text-dark">{{ $moduleName }}</span>
                  <span class="text-muted small font-monospace opacity-50">{{ $moduleKey }}</span>
                </div>
              </td>
              @foreach($actions as $action)
                @php $permissionName = $action . '_' . $moduleKey; @endphp
                <td class="text-center align-middle">
                  <label class="form-check form-switch d-inline-flex justify-content-center m-0">
                    <input class="form-check-input perm-checkbox perm-cat-{{ $catSlug }} perm-action-{{ $action }}" 
                           type="checkbox" 
                           name="permissions[]" 
                           value="{{ $permissionName }}"
                           {{ in_array($permissionName, $selectedPermissions) ? 'checked' : '' }}>
                  </label>
                </td>
              @endforeach
            </tr>
          @endforeach
        @endforeach

        {{-- Fitur & Aksi Khusus --}}
        <tr class="table-active bg-light text-dark fw-bold border-top border-bottom">
          <td colspan="{{ count($actions) + 1 }}" class="py-2.5 px-3">
            <div class="d-flex align-items-center gap-2">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon text-azure" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M12 9v4" /><path d="M12 16v.01" /></svg>
              <span class="fs-4">Fitur Khusus & Tambahan</span>
            </div>
          </td>
        </tr>
        <tr>
          <td class="ps-4 fw-semibold">Izin Tambahan</td>
          <td colspan="{{ count($actions) }}">
            <div class="d-flex flex-wrap gap-4 py-1">
              <label class="form-check form-switch m-0 d-flex align-items-center">
                <input class="form-check-input perm-checkbox me-2" type="checkbox" name="permissions[]" value="download_backup"
                  {{ in_array('download_backup', $selectedPermissions) ? 'checked' : '' }}>
                <span class="form-check-label fw-semibold">Download Backup Database</span>
              </label>

              <label class="form-check form-switch m-0 d-flex align-items-center">
                <input class="form-check-input perm-checkbox me-2" type="checkbox" name="permissions[]" value="send_chat"
                  {{ in_array('send_chat', $selectedPermissions) ? 'checked' : '' }}>
                <span class="form-check-label fw-semibold">Kirim Pesan Instan (Live Chat)</span>
              </label>

              <label class="form-check form-switch m-0 d-flex align-items-center">
                <input class="form-check-input perm-checkbox me-2" type="checkbox" name="permissions[]" value="export_kpi"
                  {{ in_array('export_kpi', $selectedPermissions) ? 'checked' : '' }}>
                <span class="form-check-label fw-semibold">Export Laporan KPI</span>
              </label>
            </div>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Select All
    const btnSelectAll = document.getElementById('btn-select-all-permissions');
    if (btnSelectAll) {
        btnSelectAll.addEventListener('click', function() {
            document.querySelectorAll('#permissions-matrix-table .perm-checkbox').forEach(cb => cb.checked = true);
        });
    }

    // Deselect All
    const btnDeselectAll = document.getElementById('btn-deselect-all-permissions');
    if (btnDeselectAll) {
        btnDeselectAll.addEventListener('click', function() {
            document.querySelectorAll('#permissions-matrix-table .perm-checkbox').forEach(cb => cb.checked = false);
        });
    }

    // Toggle Category
    document.querySelectorAll('.toggle-cat-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const targetCat = this.dataset.target;
            const checkboxes = document.querySelectorAll('.perm-cat-' + targetCat);
            if (checkboxes.length > 0) {
                const hasUnchecked = Array.from(checkboxes).some(cb => !cb.checked);
                checkboxes.forEach(cb => cb.checked = hasUnchecked);
            }
        });
    });
});
</script>
