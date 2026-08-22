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
            'mobile_menus' => 'nullable|array|max:5',
        ]);

        $role = Role::create(['name' => $request->name]);
        
        if ($request->has('mobile_menus')) {
            $role->mobile_menus = json_encode($request->mobile_menus);
            $role->save();
        }
        
        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        }

        activity('role_access')
            ->causedBy(auth()->user())
            ->performedOn($role)
            ->withProperties(['permissions' => $request->permissions ?? [], 'mobile_menus' => $request->mobile_menus ?? []])
            ->log('Role dan matriks hak akses dibuat');

        return redirect()->route('roles.index')->with('success', 'Role berhasil ditambahkan.');
    }

    public function edit(string $id)
    {
        $role = Role::findOrFail($id);
        $modules = $this->modules;
        $actions = $this->actions;
        $rolePermissions = $role->permissions->pluck('name')->toArray();
        $mobileMenus = $role->mobile_menus ? json_decode($role->mobile_menus, true) : [];

        return view('roles.edit', compact('role', 'modules', 'actions', 'rolePermissions', 'mobileMenus'));
    }

    public function update(Request $request, string $id)
    {
        $role = Role::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,'.$role->id,
            'permissions' => 'nullable|array',
            'mobile_menus' => 'nullable|array|max:5',
        ]);

        $role->update(['name' => $request->name]);
        
        $role->mobile_menus = $request->has('mobile_menus') ? json_encode($request->mobile_menus) : null;
        $role->save();

        // Sync permissions
        $permissions = $request->permissions ?? [];
        $role->syncPermissions($permissions);

        activity('role_access')
            ->causedBy(auth()->user())
            ->performedOn($role)
            ->withProperties(['permissions' => $permissions, 'mobile_menus' => $request->mobile_menus ?? []])
            ->log('Matriks hak akses role diperbarui');

        return redirect()->route('roles.index')->with('success', 'Role berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $role = Role::findOrFail($id);
        $role->delete();
        return redirect()->route('roles.index')->with('success', 'Role berhasil dihapus.');
    }
}
