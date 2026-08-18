<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_users')->only(['index', 'show']);
        $this->middleware('permission:create_users')->only(['create', 'store']);
        $this->middleware('permission:edit_users')->only(['edit', 'update']);
        $this->middleware('permission:delete_users')->only(['destroy']);
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
    ];

    private $actions = ['view', 'create', 'edit', 'delete'];

    public function index()
    {
        $users = \App\Models\User::with('site')->orderBy('created_at', 'desc')->paginate(10);
        $departments = \App\Models\Department::all();
        $jabatans = \App\Models\Jabatan::all();
        $roles = \Spatie\Permission\Models\Role::all();
        $sites = \App\Models\Site::all();
        return view('users.index', compact('users', 'departments', 'jabatans', 'roles', 'sites'));
    }

    public function create()
    {
        $departments = \App\Models\Department::all();
        $jabatans = \App\Models\Jabatan::all();
        $roles = Role::all();
        $sites = \App\Models\Site::all();
        return view('users.create', compact('departments', 'jabatans', 'roles', 'sites'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nik' => 'required|string|max:50|unique:users,nik',
            'nama_lengkap' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'no_whatsapp' => 'required|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'department_id' => 'nullable|exists:departments,id',
            'jabatan_id' => 'nullable|exists:jabatans,id',
            'site_id' => 'nullable|exists:sites,id',
            'role' => 'required|exists:roles,name',
            'status' => 'required|in:active,pending,rejected',
        ]);

        $user = \App\Models\User::create([
            'nik' => $request->nik,
            'nama_lengkap' => $request->nama_lengkap,
            'email' => $request->email,
            'no_whatsapp' => $request->no_whatsapp,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'department_id' => $request->department_id,
            'jabatan_id' => $request->jabatan_id,
            'site_id' => $request->site_id,
            'status' => $request->status,
        ]);

        $user->assignRole($request->role);

        activity('user_access')
            ->causedBy(auth()->user())
            ->performedOn($user)
            ->withProperties(['role' => $request->role, 'direct_permissions' => []])
            ->log('Pengguna dibuat dengan hak akses');

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function edit(string $id)
    {
        $user = \App\Models\User::findOrFail($id);
        $departments = \App\Models\Department::all();
        $jabatans = \App\Models\Jabatan::all();
        $roles = Role::all();
        $sites = \App\Models\Site::all();
        
        $modules = $this->modules;
        $actions = $this->actions;
        
        // Get permissions that are specifically assigned to user (not via role)
        $userDirectPermissions = $user->permissions->pluck('name')->toArray();

        $permissionSources = \App\Models\User::with('permissions')
            ->where('id', '!=', $user->id)
            ->orderBy('nama_lengkap')
            ->get(['id', 'nama_lengkap', 'email'])
            ->map(fn ($source) => [
                'id' => $source->id,
                'label' => $source->nama_lengkap . ' (' . $source->email . ')',
                'permissions' => $source->getDirectPermissions()->pluck('name')->values(),
            ]);

        return view('users.edit', compact('user', 'departments', 'jabatans', 'roles', 'sites', 'modules', 'actions', 'userDirectPermissions', 'permissionSources'));
    }

    public function update(Request $request, string $id)
    {
        $user = \App\Models\User::findOrFail($id);

        $request->validate([
            'nik' => 'required|string|max:50|unique:users,nik,'.$user->id,
            'nama_lengkap' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'no_whatsapp' => 'required|string|max:20',
            'password' => 'nullable|string|min:8|confirmed',
            'department_id' => 'nullable|exists:departments,id',
            'jabatan_id' => 'nullable|exists:jabatans,id',
            'site_id' => 'nullable|exists:sites,id',
            'role' => 'required|exists:roles,name',
            'status' => 'required|in:active,pending,rejected',
            'permissions' => 'nullable|array',
        ]);

        $data = [
            'nik' => $request->nik,
            'nama_lengkap' => $request->nama_lengkap,
            'email' => $request->email,
            'no_whatsapp' => $request->no_whatsapp,
            'department_id' => $request->department_id,
            'jabatan_id' => $request->jabatan_id,
            'site_id' => $request->site_id,
            'status' => $request->status,
        ];

        if ($request->filled('password')) {
            $data['password'] = \Illuminate\Support\Facades\Hash::make($request->password);
        }

        $user->update($data);
        $user->syncRoles([$request->role]);

        // Sync direct permissions
        $permissions = $request->permissions ?? [];
        $user->syncPermissions($permissions);

        activity('user_access')
            ->causedBy(auth()->user())
            ->performedOn($user)
            ->withProperties([
                'role' => $request->role,
                'direct_permissions' => $permissions,
            ])
            ->log('Hak akses pengguna diperbarui');

        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $user = \App\Models\User::findOrFail($id);
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User berhasil dihapus.');
    }
}
