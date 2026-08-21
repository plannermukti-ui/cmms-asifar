<aside class="navbar navbar-vertical navbar-expand-lg sidebar-transparent-mobile" data-bs-theme="dark">
  <div class="container-fluid">
    <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebar-menu" aria-controls="sidebar-menu" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    
    <!-- Menu Container -->
    <div class="offcanvas-lg offcanvas-start" tabindex="-1" id="sidebar-menu" aria-labelledby="sidebar-menu-label" data-bs-theme="dark">
      <div class="offcanvas-header bg-dark border-bottom border-dark">
        <h5 class="offcanvas-title text-muted" id="sidebar-menu-label">Menu Utama</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" data-bs-target="#sidebar-menu" aria-label="Close"></button>
      </div>
      <div class="offcanvas-body pt-0">
        <ul class="navbar-nav pt-lg-2">

        <!-- ======================================================== -->
        <!-- CATEGORY 1: UTAMA                                        -->
        <!-- ======================================================== -->
        <li class="nav-item text-muted text-uppercase fw-bold px-3 pt-3 pb-1" style="font-size: 0.65rem; letter-spacing: 0.08em; opacity: 0.5;">
          Utama
        </li>

        {{-- Dashboard --}}
        <li class="nav-item">
          <a class="nav-link rounded-2 mx-2 mb-1 px-2.5 {{ request()->routeIs('dashboard') ? 'active bg-primary-lt font-weight-bold' : '' }}" href="{{ route('dashboard') }}">
            <span class="nav-link-icon me-2">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon text-blue" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l-2 0l9 -9l9 9l-2 0" /><path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7" /><path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6" /></svg>
            </span>
            <span class="nav-link-title">Dashboard</span>
          </a>
        </li>

        {{-- Pesan Instan --}}
        @can('view_chat')
        <li class="nav-item">
          <a class="nav-link rounded-2 mx-2 mb-1 px-2.5 {{ request()->routeIs('chat.*') ? 'active bg-primary-lt font-weight-bold' : '' }}" href="{{ route('chat.index') }}">
            <span class="nav-link-icon me-2">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon text-cyan" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 9h8" /><path d="M8 13h6" /><path d="M9 18h-3a3 3 0 0 1 -3 -3v-8a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v8a3 3 0 0 1 -3 3h-3l-3 3l-3 -3z" /></svg>
            </span>
            <span class="nav-link-title">Pesan Instan</span>
            @php $unreadChat = \App\Models\Message::where('receiver_id', auth()->id())->whereNull('read_at')->count(); @endphp
            @if($unreadChat > 0)
              <span class="badge bg-red text-white ms-auto pill">{{ $unreadChat }}</span>
            @endif
          </a>
        </li>
        @endcan

        {{-- Notulen Rapat --}}
        @can('view_meetings')
        <li class="nav-item">
          <a class="nav-link rounded-2 mx-2 mb-1 px-2.5 {{ request()->routeIs('meetings.*') ? 'active bg-primary-lt font-weight-bold' : '' }}" href="{{ route('meetings.index') }}">
            <span class="nav-link-icon me-2">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon text-teal" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2" /><path d="M9 3m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z" /><path d="M9 12h6" /><path d="M9 16h6" /></svg>
            </span>
            <span class="nav-link-title">Notulen Rapat</span>
          </a>
        </li>
        @endcan


        <!-- ======================================================== -->
        <!-- CATEGORY 2: MAINTENANCE & WORK ORDER                     -->
        <!-- ======================================================== -->
        @if(auth()->user()->can('view_pra_work_orders') || auth()->user()->can('view_work_orders') || auth()->user()->can('view_work_orders_kanban') || auth()->user()->can('view_hour_meters') || auth()->user()->can('view_jwos') || auth()->user()->can('view_productions'))
        <li class="nav-item text-muted text-uppercase fw-bold px-3 pt-3 pb-1" style="font-size: 0.65rem; letter-spacing: 0.08em; opacity: 0.5;">
          Maintenance & Operasional
        </li>

        {{-- Laporan Kerusakan (Pra-WO) --}}
        @can('view_pra_work_orders')
        <li class="nav-item">
          <a class="nav-link rounded-2 mx-2 mb-1 px-2.5 {{ request()->routeIs('pra-work-orders.*') ? 'active bg-primary-lt font-weight-bold' : '' }}" href="{{ route('pra-work-orders.index') }}">
            <span class="nav-link-icon me-2">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon text-danger" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 9v4" /><path d="M10.363 3.591l-8.106 13.534a1.914 1.914 0 0 0 1.636 2.871h16.214a1.914 1.914 0 0 0 1.636 -2.87l-8.106 -13.536a1.914 1.914 0 0 0 -3.274 0z" /><path d="M12 16h.01" /></svg>
            </span>
            <span class="nav-link-title">Laporan Kerusakan</span>
          </a>
        </li>
        @endcan

        {{-- Work Order --}}
        @if(auth()->user()->can('view_work_orders') || auth()->user()->can('view_work_orders_kanban'))
        @php
            $isWoActive = request()->is('work-orders*') && !request()->is('work-orders-kanban*');
            $isKanbanActive = request()->is('work-orders-kanban*');
            $isWoDropdownActive = $isWoActive || $isKanbanActive;
        @endphp
        <li class="nav-item dropdown {{ $isWoDropdownActive ? 'active' : '' }}">
          <a class="nav-link dropdown-toggle rounded-2 mx-2 mb-1 px-2.5 {{ $isWoDropdownActive ? 'active bg-primary-lt font-weight-bold' : '' }}" href="#navbar-wo" data-bs-toggle="dropdown" data-bs-auto-close="false" role="button" aria-expanded="{{ $isWoDropdownActive ? 'true' : 'false' }}">
            <span class="nav-link-icon me-2">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon text-azure" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2" /><path d="M9 3m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z" /><path d="M9 14l2 2l4 -4" /></svg>
            </span>
            <span class="nav-link-title">Work Order (WO)</span>
          </a>
          <div class="dropdown-menu {{ $isWoDropdownActive ? 'show' : '' }}">
            @can('view_work_orders')
            <a class="dropdown-item py-1.5 {{ request()->routeIs('work-orders.index') ? 'active font-weight-bold' : '' }}" href="{{ route('work-orders.index') }}">
              Daftar Work Order
            </a>
            @endcan
            @can('view_work_orders_kanban')
            <a class="dropdown-item py-1.5 {{ request()->routeIs('work-orders.kanban') ? 'active font-weight-bold' : '' }}" href="{{ route('work-orders.kanban') }}">
              Kanban Board
            </a>
            @endcan
          </div>
        </li>
        @endif

        {{-- Hour Meter (HM) --}}
        @can('view_hour_meters')
        <li class="nav-item">
          <a class="nav-link rounded-2 mx-2 mb-1 px-2.5 {{ request()->is('hour-meters*') ? 'active bg-primary-lt font-weight-bold' : '' }}" href="{{ route('hour-meters.index') }}">
            <span class="nav-link-icon me-2">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon text-teal" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M12 7v5l3 3" /></svg>
            </span>
            <span class="nav-link-title">Hour Meter (HM)</span>
          </a>
        </li>
        @endcan

        {{-- Job Work Order (JWO) --}}
        @can('view_jwos')
        <li class="nav-item">
          <a class="nav-link rounded-2 mx-2 mb-1 px-2.5 {{ request()->is('jwos*') ? 'active bg-primary-lt font-weight-bold' : '' }}" href="{{ route('jwos.index') }}">
            <span class="nav-link-icon me-2">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon text-primary" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><path d="M9 17h6" /><path d="M9 13h6" /></svg>
            </span>
            <span class="nav-link-title">Job Work Order (JWO)</span>
          </a>
        </li>
        @endcan

        {{-- Laporan Produksi Harian --}}
        @can('view_productions')
        <li class="nav-item">
          <a class="nav-link rounded-2 mx-2 mb-1 px-2.5 {{ request()->routeIs('productions.*') ? 'active bg-primary-lt font-weight-bold' : '' }}" href="{{ route('productions.index') }}">
            <span class="nav-link-icon me-2">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon text-orange" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10.325 4.317c.426 -1.756 2.924 -1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543 -.94 3.31 .826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756 .426 1.756 2.924 0 3.35a1.724 1.724 0 0 0 -1.066 2.573c.94 1.543 -.826 3.31 -2.37 2.37a1.724 1.724 0 0 0 -2.572 1.065c-.426 1.756 -2.924 1.756 -3.35 0a1.724 1.724 0 0 0 -2.573 -1.066c-1.543 .94 -3.31 -.826 -2.37 -2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756 -.426 -1.756 -2.924 0 -3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94 -1.543 .826 -3.31 2.37 -2.37c1 .608 2.296 .07 2.572 -1.065z" /><path d="M9 12a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" /></svg>
            </span>
            <span class="nav-link-title">Laporan Produksi</span>
          </a>
        </li>
        @endcan
        @endif


        <!-- ======================================================== -->
        <!-- CATEGORY 3: PLANNING & RELIABILITY                       -->
        <!-- ======================================================== -->
        @if(auth()->user()->can('view_pcr') || auth()->user()->can('view_pm_templates') || auth()->user()->can('view_pm_schedules') || auth()->user()->can('view_plan_budgets') || auth()->user()->can('view_fars') || auth()->user()->can('view_swap_components'))
        <li class="nav-item text-muted text-uppercase fw-bold px-3 pt-3 pb-1" style="font-size: 0.65rem; letter-spacing: 0.08em; opacity: 0.5;">
          Planning & Reliability
        </li>

        {{-- Plan Component Replacement (PCR) --}}
        @can('view_pcr')
        <li class="nav-item">
          <a class="nav-link rounded-2 mx-2 mb-1 px-2.5 {{ request()->is('plan-strategy*') ? 'active bg-primary-lt font-weight-bold' : '' }}" href="{{ route('plan-strategy.pcr.index') }}">
            <span class="nav-link-icon me-2">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon text-pink" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M12 12l3 2" /><path d="M12 7v5" /></svg>
            </span>
            <span class="nav-link-title">Plan Component (PCR)</span>
          </a>
        </li>
        @endcan

        {{-- Preventive Maintenance --}}
        @if(auth()->user()->can('view_pm_templates') || auth()->user()->can('view_pm_schedules'))
        @php
            $isPmActive = request()->is('pm-templates*', 'pm-schedules*');
        @endphp
        <li class="nav-item dropdown {{ $isPmActive ? 'active' : '' }}">
          <a class="nav-link dropdown-toggle rounded-2 mx-2 mb-1 px-2.5 {{ $isPmActive ? 'active bg-primary-lt font-weight-bold' : '' }}" href="#navbar-pm" data-bs-toggle="dropdown" data-bs-auto-close="false" role="button" aria-expanded="{{ $isPmActive ? 'true' : 'false' }}">
            <span class="nav-link-icon me-2">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon text-green" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-10z" /><path d="M11 7v10" /><path d="M15 11v6" /><path d="M7 11v6" /></svg>
            </span>
            <span class="nav-link-title">Preventive Maint.</span>
          </a>
          <div class="dropdown-menu {{ $isPmActive ? 'show' : '' }}">
            @can('view_pm_templates')
            <a class="dropdown-item py-1.5 {{ request()->routeIs('pm-templates.index') ? 'active font-weight-bold' : '' }}" href="{{ route('pm-templates.index') }}">
              Templates (Checklist)
            </a>
            @endcan
            @can('view_pm_schedules')
            <a class="dropdown-item py-1.5 {{ request()->routeIs('pm-schedules.index') ? 'active font-weight-bold' : '' }}" href="{{ route('pm-schedules.index') }}">
              Jadwal PM
            </a>
            <a class="dropdown-item py-1.5 {{ request()->routeIs('pm-schedules.all-history') ? 'active font-weight-bold' : '' }}" href="{{ route('pm-schedules.all-history') }}">
              History Service
            </a>
            @endcan
          </div>
        </li>
        @endif

        {{-- Budget --}}
        @can('view_plan_budgets')
        <li class="nav-item">
          <a class="nav-link rounded-2 mx-2 mb-1 px-2.5 {{ request()->is('plan-budgets*') ? 'active bg-primary-lt font-weight-bold' : '' }}" href="{{ route('plan-budgets.index') }}">
            <span class="nav-link-icon me-2">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon text-yellow" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 8v-3a1 1 0 0 0 -1 -1h-10a1 1 0 0 0 -1 1v12a1 1 0 0 0 1 1h10a1 1 0 0 0 1 -1v-3" /><path d="M20 12h-4v4h4z" /></svg>
            </span>
            <span class="nav-link-title">Plan Budget Bulanan</span>
          </a>
        </li>
        @endcan

        {{-- Failure Analysis Report (FAR) --}}
        @can('view_fars')
        <li class="nav-item">
          <a class="nav-link rounded-2 mx-2 mb-1 px-2.5 {{ request()->is('fars*') ? 'active bg-primary-lt font-weight-bold' : '' }}" href="{{ route('fars.index') }}">
            <span class="nav-link-icon me-2">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon text-red" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 9v4" /><path d="M10.363 3.591l-8.106 13.534a1.914 1.914 0 0 0 1.636 2.871h16.214a1.914 1.914 0 0 0 1.636 -2.87l-8.106 -13.536a1.914 1.914 0 0 0 -3.274 0z" /><path d="M12 16h.01" /></svg>
            </span>
            <span class="nav-link-title">Failure Analysis (FAR)</span>
          </a>
        </li>
        @endcan

        {{-- Swap Component Report --}}
        @can('view_swap_components')
        <li class="nav-item">
          <a class="nav-link rounded-2 mx-2 mb-1 px-2.5 {{ request()->is('swap-components*') ? 'active bg-primary-lt font-weight-bold' : '' }}" href="{{ route('swap-components.index') }}">
            <span class="nav-link-icon me-2">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon text-purple" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 10h14l-4 -4" /><path d="M17 14h-14l4 4" /></svg>
            </span>
            <span class="nav-link-title">Swap Component</span>
          </a>
        </li>
        @endcan
        @endif


        <!-- ======================================================== -->
        <!-- CATEGORY 4: TOOLROOM & WORKSHOP                          -->
        <!-- ======================================================== -->
        @if(auth()->user()->can('view_tool_transactions') || auth()->user()->can('view_incident_reports') || auth()->user()->can('view_stock_opnames') || auth()->user()->can('view_tool_stock_requests'))
        <li class="nav-item text-muted text-uppercase fw-bold px-3 pt-3 pb-1" style="font-size: 0.65rem; letter-spacing: 0.08em; opacity: 0.5;">
          ToolRoom & Workshop
        </li>

        @php
            $isToolRoomActive = request()->is('tool-transactions*', 'incident-reports*', 'stock-opnames*', 'tool-stock-requests*');
        @endphp
        <li class="nav-item dropdown {{ $isToolRoomActive ? 'active' : '' }}">
          <a class="nav-link dropdown-toggle rounded-2 mx-2 mb-1 px-2.5 {{ $isToolRoomActive ? 'active bg-primary-lt font-weight-bold' : '' }}" href="#navbar-toolroom" data-bs-toggle="dropdown" data-bs-auto-close="false" role="button" aria-expanded="{{ $isToolRoomActive ? 'true' : 'false' }}">
            <span class="nav-link-icon me-2">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon text-warning" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 10h3v-3l-3.5 -3.5a6 6 0 0 1 8 8l6 6a2 2 0 0 1 -3 3l-6 -6a6 6 0 0 1 -8 -8l3.5 3.5" /></svg>
            </span>
            <span class="nav-link-title">Operasional ToolRoom</span>
          </a>
          <div class="dropdown-menu {{ $isToolRoomActive ? 'show' : '' }}">
            @can('view_tool_transactions')
            <a class="dropdown-item py-1.5 {{ request()->is('tool-transactions*') ? 'active font-weight-bold' : '' }}" href="{{ route('tool-transactions.index') }}">
              Peminjaman Tool
            </a>
            @endcan
            @can('view_tool_stock_requests')
            <a class="dropdown-item py-1.5 {{ request()->is('tool-stock-requests*') ? 'active font-weight-bold' : '' }}" href="{{ route('tool-stock-requests.index') }}">
              Approval Stok Tool
            </a>
            @endcan
            @can('view_incident_reports')
            <a class="dropdown-item py-1.5 {{ request()->is('incident-reports*') ? 'active font-weight-bold' : '' }}" href="{{ route('incident-reports.index') }}">
              Berita Acara (B.A)
            </a>
            @endcan
            @can('view_stock_opnames')
            <a class="dropdown-item py-1.5 {{ request()->is('stock-opnames*') ? 'active font-weight-bold' : '' }}" href="{{ route('stock-opnames.index') }}">
              Stock Opname
            </a>
            @endcan
          </div>
        </li>
        @endif


        <!-- ======================================================== -->
        <!-- CATEGORY 5: KPI & REPORTING                              -->
        <!-- ======================================================== -->
        @if(auth()->user()->can('view_kpi_master_data') || auth()->user()->can('view_breakdown_reports'))
        <li class="nav-item text-muted text-uppercase fw-bold px-3 pt-3 pb-1" style="font-size: 0.65rem; letter-spacing: 0.08em; opacity: 0.5;">
          KPI & Reporting
        </li>

        @php
            $isKpiActive = request()->is('kpi*') || request()->is('reports*');
        @endphp
        <li class="nav-item dropdown {{ $isKpiActive ? 'active' : '' }}">
          <a class="nav-link dropdown-toggle rounded-2 mx-2 mb-1 px-2.5 {{ $isKpiActive ? 'active bg-primary-lt font-weight-bold' : '' }}" href="#navbar-kpi" data-bs-toggle="dropdown" data-bs-auto-close="false" role="button" aria-expanded="{{ $isKpiActive ? 'true' : 'false' }}">
            <span class="nav-link-icon me-2">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon text-purple" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 19l16 0" /><path d="M4 15l4 -6l4 2l4 -5l4 4" /></svg>
            </span>
            <span class="nav-link-title">Laporan & KPI</span>
          </a>
          <div class="dropdown-menu {{ $isKpiActive ? 'show' : '' }}">
            @can('view_breakdown_reports')
            <a class="dropdown-item py-1.5 {{ request()->routeIs('reports.breakdown') ? 'active font-weight-bold' : '' }}" href="{{ route('reports.breakdown') }}">
              Report Breakdown & Downtime
            </a>
            @endcan
            @can('view_kpi_master_data')
            <a class="dropdown-item py-1.5 {{ request()->routeIs('kpi.master-data') ? 'active font-weight-bold' : '' }}" href="{{ route('kpi.master-data') }}">
              KPI Master Data
            </a>
            @endcan
          </div>
        </li>
        @endif


        <!-- ======================================================== -->
        <!-- CATEGORY 6: MASTER DATA                                  -->
        <!-- ======================================================== -->
        <li class="nav-item text-muted text-uppercase fw-bold px-3 pt-3 pb-1" style="font-size: 0.65rem; letter-spacing: 0.08em; opacity: 0.5;">
          Master Data
        </li>

        {{-- Master Unit --}}
        @if(auth()->user()->can('view_master_units') || auth()->user()->can('view_unit_types') || auth()->user()->can('view_unit_models'))
        <li class="nav-item">
          <a class="nav-link rounded-2 mx-2 mb-1 px-2.5 {{ request()->is('master-units*', 'unit-types*', 'unit-models*') ? 'active bg-primary-lt font-weight-bold' : '' }}" href="{{ route('master-units.index') }}">
            <span class="nav-link-icon me-2">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon text-teal" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 17m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M17 17m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M5 17h-2v-6l2 -5h9l4 5h1a2 2 0 0 1 2 2v4h-2m-4 0h-6m-6 -6h15m-6 0v-5" /></svg>
            </span>
            <span class="nav-link-title">Master Unit</span>
          </a>
        </li>
        @endif

        {{-- Master Part --}}
        @can('view_parts')
        <li class="nav-item">
          <a class="nav-link rounded-2 mx-2 mb-1 px-2.5 {{ request()->is('parts*') ? 'active bg-primary-lt font-weight-bold' : '' }}" href="{{ route('parts.index') }}">
            <span class="nav-link-icon me-2">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon text-azure" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" /><path d="M12 3l1.5 3l3.5 .5l-2.5 2.5l.5 3.5l-3 -1.5l-3 1.5l.5 -3.5l-2.5 -2.5l3.5 -.5z" /></svg>
            </span>
            <span class="nav-link-title">Master Part / Suku Cadang</span>
          </a>
        </li>
        @endcan

        {{-- Master Vendor / Bengkel --}}
        @can('view_vendors')
        <li class="nav-item">
          <a class="nav-link rounded-2 mx-2 mb-1 px-2.5 {{ request()->is('vendors*') ? 'active bg-primary-lt font-weight-bold' : '' }}" href="{{ route('vendors.index') }}">
            <span class="nav-link-icon me-2">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon text-primary" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 21l18 0" /><path d="M5 21v-14l8 -4v18" /><path d="M19 21v-10l-6 -4" /><path d="M9 9l0 .01" /><path d="M9 12l0 .01" /><path d="M9 15l0 .01" /><path d="M9 18l0 .01" /></svg>
            </span>
            <span class="nav-link-title">Master Vendor / Bengkel</span>
          </a>
        </li>
        @endcan

        {{-- Master Mekanik --}}
        @can('view_mechanics')
        <li class="nav-item">
          <a class="nav-link rounded-2 mx-2 mb-1 px-2.5 {{ request()->is('mechanics*') ? 'active bg-primary-lt font-weight-bold' : '' }}" href="{{ route('mechanics.index') }}">
            <span class="nav-link-icon me-2">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon text-yellow" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" /><path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /></svg>
            </span>
            <span class="nav-link-title">Master Mekanik</span>
          </a>
        </li>
        @endcan

        {{-- Master Tool & Stok --}}
        @if(auth()->user()->can('view_tools') || auth()->user()->can('view_tool_categories') || auth()->user()->can('view_tool_stocks'))
        @php
            $isToolMasterActive = request()->is('tools*', 'tool-categories*', 'tool-stocks*');
        @endphp
        <li class="nav-item dropdown {{ $isToolMasterActive ? 'active' : '' }}">
          <a class="nav-link dropdown-toggle rounded-2 mx-2 mb-1 px-2.5 {{ $isToolMasterActive ? 'active bg-primary-lt font-weight-bold' : '' }}" href="#navbar-master-tool" data-bs-toggle="dropdown" data-bs-auto-close="false" role="button" aria-expanded="{{ $isToolMasterActive ? 'true' : 'false' }}">
            <span class="nav-link-icon me-2">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon text-warning" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 10h3v-3l-3.5 -3.5a6 6 0 0 1 8 8l6 6a2 2 0 0 1 -3 3l-6 -6a6 6 0 0 1 -8 -8l3.5 3.5" /></svg>
            </span>
            <span class="nav-link-title">Master Tool</span>
          </a>
          <div class="dropdown-menu {{ $isToolMasterActive ? 'show' : '' }}">
            @can('view_tools')
            <a class="dropdown-item py-1.5 {{ request()->is('tools*') ? 'active font-weight-bold' : '' }}" href="{{ route('tools.index') }}">
              Daftar Tool
            </a>
            @endcan
            @can('view_tool_stocks')
            <a class="dropdown-item py-1.5 {{ request()->is('tool-stocks*') ? 'active font-weight-bold' : '' }}" href="{{ route('tool-stocks.index') }}">
              Stok Tool
            </a>
            @endcan
            @can('view_tool_categories')
            <a class="dropdown-item py-1.5 {{ request()->is('tool-categories*') ? 'active font-weight-bold' : '' }}" href="{{ route('tool-categories.index') }}">
              Kategori Tool
            </a>
            @endcan
          </div>
        </li>
        @endif

        {{-- Relasi Database 3D --}}
        <li class="nav-item">
          <a class="nav-link rounded-2 mx-2 mb-1 px-2.5 {{ request()->routeIs('database-relations.*') ? 'active bg-primary-lt font-weight-bold' : '' }}" href="{{ route('database-relations.index') }}">
            <span class="nav-link-icon me-2">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon text-cyan" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 3l8 4.5l0 9l-8 4.5l-8 -4.5l0 -9l8 -4.5" /><path d="M12 12l8 -4.5" /><path d="M12 12l0 9" /><path d="M12 12l-8 -4.5" /><path d="M16 5.25l-8 4.5" /></svg>
            </span>
            <span class="nav-link-title">Relasi Database 3D</span>
            <span class="badge bg-cyan-lt text-cyan ms-auto pill" style="font-size: 0.65rem;">3D</span>
          </a>
        </li>


        <!-- ======================================================== -->
        <!-- CATEGORY 7: ADMINISTRATOR                                -->
        <!-- ======================================================== -->
        @if(auth()->user()->can('view_users') || auth()->user()->can('view_departments') || auth()->user()->can('view_jabatans') || auth()->user()->can('view_roles') || auth()->user()->can('view_modules') || auth()->user()->can('view_approval_matrix') || auth()->user()->can('view_activity_log') || auth()->user()->can('view_backup') || auth()->user()->can('view_settings') || auth()->user()->can('view_sites'))
        <li class="nav-item text-muted text-uppercase fw-bold px-3 pt-3 pb-1" style="font-size: 0.65rem; letter-spacing: 0.08em; opacity: 0.5;">
          Administrator
        </li>

        @php
            $isAdminActive = request()->is('users*', 'master-data*', 'departments*', 'jabatans*', 'roles*', 'modules*', 'approval-matrix*', 'activity-log*', 'backup*', 'settings*', 'sites*');
        @endphp
        <li class="nav-item dropdown {{ $isAdminActive ? 'active' : '' }}">
          <a class="nav-link dropdown-toggle rounded-2 mx-2 mb-1 px-2.5 {{ $isAdminActive ? 'active bg-primary-lt font-weight-bold' : '' }}" href="#navbar-admin" data-bs-toggle="dropdown" data-bs-auto-close="false" role="button" aria-expanded="{{ $isAdminActive ? 'true' : 'false' }}">
            <span class="nav-link-icon me-2">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon text-indigo" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10.325 4.317c.426 -1.756 2.924 -1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543 -.94 3.31 .826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756 .426 1.756 2.924 0 3.35a1.724 1.724 0 0 0 -1.066 2.573c.94 1.543 -.826 3.31 -2.37 2.37a1.724 1.724 0 0 0 -2.572 1.065c-.426 1.756 -2.924 1.756 -3.35 0a1.724 1.724 0 0 0 -2.573 -1.066c-1.543 .94 -3.31 -.826 -2.37 -2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756 -.426 -1.756 -2.924 0 -3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94 -1.543 .826 -3.31 2.37 -2.37c1 .608 2.296 .07 2.572 -1.065z" /><path d="M9 12a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" /></svg>
            </span>
            <span class="nav-link-title">Sistem & Akses</span>
          </a>
          <div class="dropdown-menu {{ $isAdminActive ? 'show' : '' }}">
            @can('view_users')
            <a class="dropdown-item py-1.5 {{ request()->is('users*') ? 'active font-weight-bold' : '' }}" href="{{ route('users.index') }}">
              Manajemen User
              @php $pendingUsers = \App\Models\User::where('status', 'pending')->count(); @endphp
              @if($pendingUsers > 0)
                <span class="badge bg-yellow text-dark ms-auto pill">{{ $pendingUsers }}</span>
              @endif
            </a>
            @endcan
            @if(auth()->user()->can('view_departments') || auth()->user()->can('view_jabatans') || auth()->user()->can('view_roles') || auth()->user()->can('view_modules'))
            <a class="dropdown-item py-1.5 {{ request()->is('master-data*', 'departments*', 'jabatans*', 'roles*', 'modules*') ? 'active font-weight-bold' : '' }}" href="{{ route('master-data.index') }}">
              Role & Master Data General
            </a>
            @endif
            @can('view_approval_matrix')
            <a class="dropdown-item py-1.5 {{ request()->is('approval-matrix*') ? 'active font-weight-bold' : '' }}" href="{{ route('approval-matrix.index') }}">
              Approval Matrix
            </a>
            @endcan
            @if(auth()->user()->can('view_sites') || auth()->user()->can('view_settings'))
            <a class="dropdown-item py-1.5 {{ request()->is('sites*') ? 'active font-weight-bold' : '' }}" href="{{ route('sites.index') }}">
              Master Site (Tambang)
            </a>
            @endif
            @can('view_activity_log')
            <a class="dropdown-item py-1.5 {{ request()->is('activity-log*') ? 'active font-weight-bold' : '' }}" href="{{ route('activity-log.index') }}">
              Log Aktivitas
            </a>
            @endcan
            @can('view_backup')
            <a class="dropdown-item py-1.5 {{ request()->is('backup*') ? 'active font-weight-bold' : '' }}" href="{{ route('backup.index') }}">
              Backup Database
            </a>
            @endcan
            @can('view_settings')
            <a class="dropdown-item py-1.5 {{ request()->is('settings*') ? 'active font-weight-bold' : '' }}" href="{{ route('settings.index') }}">
              Pengaturan Sistem
            </a>
            @endcan
          </div>
        </li>
        @endif

        </ul>
      </div>
    </div>
  </div>
</aside>
