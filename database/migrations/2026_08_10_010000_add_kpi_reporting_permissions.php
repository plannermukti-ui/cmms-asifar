<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

return new class extends Migration
{
    public function up(): void
    {
        $permissions = [Permission::firstOrCreate(['name' => 'export_kpi'])];

        foreach (['kpi_master_data', 'breakdown_reports'] as $module) {
            foreach (['view', 'create', 'edit', 'delete'] as $action) {
                $permissions[] = Permission::firstOrCreate(['name' => $action . '_' . $module]);
            }
        }

        $superAdmin = Role::where('name', 'Super Admin')->first();
        if ($superAdmin) {
            $superAdmin->givePermissionTo($permissions);
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    public function down(): void
    {
        Permission::whereIn('name', [
            'view_kpi_master_data', 'create_kpi_master_data', 'edit_kpi_master_data', 'delete_kpi_master_data',
            'view_breakdown_reports', 'create_breakdown_reports', 'edit_breakdown_reports', 'delete_breakdown_reports',
        ])->delete();

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
};
