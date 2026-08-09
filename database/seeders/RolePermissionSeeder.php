<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Buat role Super Admin
        $superAdminRole = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'Super Admin']);

        // 2. Daftar modul
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
            
            // Administrasi ToolRoom
            'mechanics' => 'Data Mekanik',
            'tool_categories' => 'Kategori Tool',
            'tools' => 'Master Tool',
            'tool_stocks' => 'Stok Tool',
            'tool_transactions' => 'Peminjaman Tool',
            'incident_reports' => 'Berita Acara',
            'stock_opnames' => 'Stock Opname',
            
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
            'plan_budgets' => 'RAB / Budget Plan',
            'jwos' => 'Job Work Order (JWO)',
            'fars' => 'Form Analisa Rusak (FAR)',
        ];

        $actions = ['view', 'create', 'edit', 'delete'];

        $permissionsToGive = [];

        foreach ($modules as $module => $label) {
            foreach ($actions as $action) {
                $permissionName = $action . '_' . $module;
                $permission = \Spatie\Permission\Models\Permission::firstOrCreate(['name' => $permissionName]);
                $permissionsToGive[] = $permissionName;
            }
        }

        // Tambahan permission spesifik lainnya (export, print, approve, dll)
        $extraPermissions = [
            'export_kpi',
            'approve_fars',
            'approve_wo',
        ];

        foreach ($extraPermissions as $perm) {
            \Spatie\Permission\Models\Permission::firstOrCreate(['name' => $perm]);
            $permissionsToGive[] = $perm;
        }

        // Sync all permissions to Super Admin
        $superAdminRole->syncPermissions($permissionsToGive);

        // 3. Buat user Super Admin (jika belum ada)
        $user = \App\Models\User::firstOrCreate(
            ['email' => 'admin@cmms-aisfar.com'],
            [
                'nik' => 'ADMIN001',
                'nama_lengkap' => 'Super Administrator',
                'no_whatsapp' => '081234567890',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'status' => 'active',
            ]
        );

        $user->assignRole($superAdminRole);
    }
}
