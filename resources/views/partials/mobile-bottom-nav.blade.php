@php
$mobileMenus = [];
if (auth()->check() && auth()->user()->roles->count() > 0) {
    $role = auth()->user()->roles->first();
    if ($role->mobile_menus) {
        $mobileMenus = is_string($role->mobile_menus) ? json_decode($role->mobile_menus, true) : $role->mobile_menus;
    }
}

// Fallback jika Role tidak punya setting, tampilkan default 5 menu.
if (empty($mobileMenus) || !is_array($mobileMenus)) {
    $mobileMenus = ['dashboard', 'work_orders', 'jwos', 'tools', 'chat'];
}

// Batasi maksimal 5
$mobileMenus = array_slice($mobileMenus, 0, 5);

function getMobileMenuHtml($key) {
    $isActive = false;
    $route = '#';
    $label = 'Menu';
    $icon = '';
    
    switch($key) {
        case 'dashboard':
            $route = route('dashboard');
            $isActive = request()->routeIs('dashboard');
            $label = 'Beranda';
            $icon = '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l-2 0l9 -9l9 9l-2 0" /><path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7" /><path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6" /></svg>';
            break;
        case 'work_orders':
        case 'work_orders_kanban':
            $route = route('work-orders.index');
            $isActive = request()->is('work-orders*');
            $label = 'WO';
            $icon = '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2" /><path d="M9 3m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z" /><path d="M9 14l2 2l4 -4" /></svg>';
            break;
        case 'pra_work_orders':
            $route = route('pra-work-orders.index');
            $isActive = request()->is('pra-work-orders*');
            $label = 'Pra-WO';
            $icon = '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 9v4" /><path d="M10.363 3.591l-8.106 13.534a1.914 1.914 0 0 0 1.636 2.871h16.214a1.914 1.914 0 0 0 1.636 -2.87l-8.106 -13.536a1.914 1.914 0 0 0 -3.274 0z" /><path d="M12 16h.01" /></svg>';
            break;
        case 'jwos':
            $route = route('jwos.index');
            $isActive = request()->is('jwos*');
            $label = 'JWO';
            $icon = '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><path d="M9 17h6" /><path d="M9 13h6" /></svg>';
            break;
        case 'fars':
            $route = route('fars.index');
            $isActive = request()->is('fars*');
            $label = 'FAR';
            $icon = '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 9v4" /><path d="M10.363 3.591l-8.106 13.534a1.914 1.914 0 0 0 1.636 2.871h16.214a1.914 1.914 0 0 0 1.636 -2.87l-8.106 -13.536a1.914 1.914 0 0 0 -3.274 0z" /><path d="M12 16h.01" /></svg>';
            break;
        case 'tools':
        case 'tool_transactions':
            $route = route('tools.index');
            $isActive = request()->is('tools*') || request()->is('tool-transactions*');
            $label = 'Tools';
            $icon = '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 10h3v-3l-3.5 -3.5a6 6 0 0 1 8 8l6 6a2 2 0 0 1 -3 3l-6 -6a6 6 0 0 1 -8 -8l3.5 3.5" /></svg>';
            break;
        case 'chat':
            $route = route('chat.index');
            $isActive = request()->is('chat*');
            $label = 'Chat';
            $unreadCount = \App\Models\Message::where('receiver_id', auth()->id())->whereNull('read_at')->count();
            $badge = $unreadCount > 0 ? '<span class="badge bg-red badge-notification badge-pill">'.$unreadCount.'</span>' : '';
            $icon = '<div class="position-relative d-inline-block"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 9h8" /><path d="M8 13h6" /><path d="M9 18h-3a3 3 0 0 1 -3 -3v-8a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v8a3 3 0 0 1 -3 3h-3l-3 3l-3 -3z" /></svg>'.$badge.'</div>';
            break;
        case 'pm_templates':
        case 'pm_schedules':
            $route = route('pm-schedules.index');
            $isActive = request()->is('pm-*');
            $label = 'PM';
            $icon = '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-10z" /><path d="M11 7v10" /><path d="M15 11v6" /><path d="M7 11v6" /></svg>';
            break;
        case 'productions':
            $route = route('productions.index');
            $isActive = request()->is('productions*');
            $label = 'Produksi';
            $icon = '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10.325 4.317c.426 -1.756 2.924 -1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543 -.94 3.31 .826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756 .426 1.756 2.924 0 3.35a1.724 1.724 0 0 0 -1.066 2.573c.94 1.543 -.826 3.31 -2.37 2.37a1.724 1.724 0 0 0 -2.572 1.065c-.426 1.756 -2.924 1.756 -3.35 0a1.724 1.724 0 0 0 -2.573 -1.066c-1.543 .94 -3.31 -.826 -2.37 -2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756 -.426 -1.756 -2.924 0 -3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94 -1.543 .826 -3.31 2.37 -2.37c1 .608 2.296 .07 2.572 -1.065z" /><path d="M9 12a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" /></svg>';
            break;
        case 'pcr':
            $route = route('plan-strategy.pcr.index');
            $isActive = request()->is('plan-strategy*');
            $label = 'PCR';
            $icon = '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M12 12l3 2" /><path d="M12 7v5" /></svg>';
            break;
        case 'master_units':
            $route = route('master-units.index');
            $isActive = request()->is('master-units*');
            $label = 'Units';
            $icon = '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 17m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M17 17m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M5 17h-2v-6l2 -5h9l4 5h1a2 2 0 0 1 2 2v4h-2m-4 0h-6m-6 -6h15m-6 0v-5" /></svg>';
            break;
        case 'hour_meters':
            $route = route('hour-meters.index');
            $isActive = request()->is('hour-meters*');
            $label = 'HM';
            $icon = '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M12 7v5l3 3" /></svg>';
            break;
        case 'fuel':
        case 'fuel_management':
            $route = route('fuel.dashboard');
            $isActive = request()->is('fuel*');
            $label = 'Fuel';
            $icon = '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 21v-14a3 3 0 0 1 3 -3h6a3 3 0 0 1 3 3v14" /><path d="M9 11l6 0" /><path d="M6 21l12 0" /><path d="M16 13l2.5 2.5a2 2 0 0 1 0 2.828l-1.328 1.328a2 2 0 0 1 -2.828 0l-2.344 -2.344" /><path d="M18 10v-4" /></svg>';
            break;
        default:
            $route = route('dashboard');
            $label = 'Lainnya';
            $icon = '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 4m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z" /></svg>';
            break;
    }
    
    $activeClass = $isActive ? 'active text-primary fw-bold' : 'text-muted';
    return '
    <a href="'.$route.'" class="nav-link text-center d-flex flex-column align-items-center justify-content-center '.$activeClass.'" style="flex: 1; padding: 8px 0; gap: 4px; text-decoration: none;">
        <div style="transform: scale(1.15);">'.$icon.'</div>
        <span style="font-size: 0.65rem; line-height: 1;">'.$label.'</span>
    </a>';
}
@endphp

<style>
/* CSS Khusus Bottom Navbar */
.mobile-bottom-nav {
    display: none;
}
@media (max-width: 991.98px) {
    .mobile-bottom-nav {
        display: flex;
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        background: var(--tblr-bg-surface);
        border-top: 1px solid var(--tblr-border-color);
        z-index: 1040;
        padding-bottom: env(safe-area-inset-bottom);
        box-shadow: 0 -2px 10px rgba(0,0,0,0.05);
    }
    .page-wrapper {
        padding-bottom: calc(65px + env(safe-area-inset-bottom)) !important;
    }
}
</style>

<div class="mobile-bottom-nav d-lg-none d-print-none flex-row justify-content-around align-items-center">
    @foreach($mobileMenus as $menuKey)
        {!! getMobileMenuHtml($menuKey) !!}
    @endforeach
</div>
