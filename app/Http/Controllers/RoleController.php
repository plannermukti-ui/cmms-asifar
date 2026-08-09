<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_roles')->only(['index', 'show']);
        $this->middleware('permission:create_roles')->only(['create', 'store']);
        $this->middleware('permission:edit_roles')->only(['edit', 'update']);
        $this->middleware('permission:delete_roles')->only(['destroy']);
    }

    private $modules = [
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

    private $actions = ['view', 'create', 'edit', 'delete'];

    public function index()
    {
        $roles = Role::paginate(10);
        $modules = $this->modules;
        $actions = $this->actions;
        return view('roles.index', compact('roles', 'modules', 'actions'));
    }

    public function create()
    {
        $modules = $this->modules;
        $actions = $this->actions;
        return view('roles.create', compact('modules', 'actions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'permissions' => 'nullable|array',
        ]);

        $role = Role::create(['name' => $request->name]);
        
        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        }

        return redirect()->route('roles.index')->with('success', 'Role berhasil ditambahkan.');
    }

    public function edit(string $id)
    {
        $role = Role::findOrFail($id);
        $modules = $this->modules;
        $actions = $this->actions;
        $rolePermissions = $role->permissions->pluck('name')->toArray();

        return view('roles.edit', compact('role', 'modules', 'actions', 'rolePermissions'));
    }

    public function update(Request $request, string $id)
    {
        $role = Role::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,'.$role->id,
            'permissions' => 'nullable|array',
        ]);

        $role->update(['name' => $request->name]);

        // Sync permissions
        $permissions = $request->permissions ?? [];
        $role->syncPermissions($permissions);

        return redirect()->route('roles.index')->with('success', 'Role berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $role = Role::findOrFail($id);
        $role->delete();
        return redirect()->route('roles.index')->with('success', 'Role berhasil dihapus.');
    }
}
