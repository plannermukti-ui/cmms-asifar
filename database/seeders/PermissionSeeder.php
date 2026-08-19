<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Modul aplikasi yang membutuhkan permission
        $modules = [
            'users' => 'Manajemen User',
            'departments' => 'Departemen',
            'jabatans' => 'Jabatan',
            'roles' => 'Role & Akses',
            'modules' => 'Master Modul',
            'approval_matrix' => 'Approval Matrix',
            'activity_log' => 'Log Aktivitas',
            'backup' => 'Backup Database',
            'settings' => 'Pengaturan Sistem',
            'sites' => 'Lokasi Tambang (Site)',
            'master_units' => 'Master Unit (Asset)',
            'unit_types' => 'Master Tipe Unit',
            'unit_models' => 'Master Model Unit',
            'chat' => 'Pesan Instan',
            'kpi_master_data' => 'KPI Master Data',
            'breakdown_reports' => 'Report Breakdown',
            
            // Administrasi ToolRoom
            'mechanics' => 'Data Mekanik',
            'tool_categories' => 'Kategori Tool',
            'tools' => 'Master Tool',
            'tool_stocks' => 'Stok Tool',
            'tool_transactions' => 'Peminjaman Tool',
            'incident_reports' => 'Berita Acara',
            'stock_opnames' => 'Stock Opname',
            'tool_stock_requests' => 'Approval Stok Tool',
            
            // Produksi
            'productions' => 'Laporan Produksi Harian',

            // K3 & HSE
            'hse_jsas' => 'JSA (Job Safety Analysis)',
            'hse_ptws' => 'PTW (Permit to Work)',
            'hse_lotos' => 'LOTO (Lockout Tagout)',

            // Work Order & Maintenance
            'breakdown_types' => 'Tipe Breakdown',
            'component_groups' => 'Grup Komponen',
            'wo_categories' => 'Kategori WO',
            'vendors' => 'Data Vendor',
            'parts' => 'Master Part',
            'hour_meters' => 'Hour Meter Unit',
            'pm_templates' => 'PM Template',
            'pm_schedules' => 'Jadwal PM (Schedule)',
            'pra_work_orders' => 'Pra-Work Order (PWO)',
            'work_orders' => 'Work Order (WO)',
            'work_orders_kanban' => 'Kanban Board (WO)',
            'plan_budgets' => 'RAB / Budget Plan',
            'jwos' => 'Job Work Order (JWO)',
            'fars' => 'Form Analisa Rusak (FAR)',
            'wo_comments' => 'Diskusi Work Order',
            'swap_components' => 'Swap Component Report',
            'pcr' => 'Plan Component Replacement (PCR)',
            'meetings' => 'Notulen Rapat & Tindak Lanjut',
        ];

        // Aksi standar (CRUD)
        $actions = ['view', 'create', 'edit', 'delete'];

        $permissions = [];

        foreach ($modules as $moduleKey => $moduleName) {
            foreach ($actions as $action) {
                // Untuk activity_log, backup, dan chat mungkin tidak butuh semua aksi, tapi kita biarkan saja standar agar mudah dikelola
                $permissionName = $action . '_' . $moduleKey;
                $permissions[] = $permissionName;
                Permission::firstOrCreate(['name' => $permissionName]);
            }
        }

        // Tambahkan permission tambahan jika ada (misal: download_backup)
        Permission::firstOrCreate(['name' => 'download_backup']);
        Permission::firstOrCreate(['name' => 'send_chat']);
        Permission::firstOrCreate(['name' => 'export_kpi']);
        $permissions[] = 'download_backup';
        $permissions[] = 'send_chat';
        $permissions[] = 'export_kpi';

        // Berikan semua akses ke Role Super Admin (ID 1 atau role dengan nama Super Admin)
        $superAdminRole = Role::firstOrCreate(['name' => 'Super Admin']);
        $superAdminRole->syncPermissions($permissions);

        // Assign Role Super Admin ke user pertama jika ada
        $firstUser = User::first();
        if ($firstUser && !$firstUser->hasRole('Super Admin')) {
            $firstUser->assignRole($superAdminRole);
        }
    }
}
