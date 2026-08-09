<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'CMMS Aisfar')</title>
    <!-- CSS files -->
    <link href="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta20/dist/css/tabler.min.css" rel="stylesheet"/>
    <link href="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta20/dist/css/tabler-vendors.min.css" rel="stylesheet"/>
    <style>
      @import url('https://rsms.me/inter/inter.css');
      
      /* =========================================================
         HEAVY EQUIPMENT INDUSTRIAL THEME (APPLIES ONLY IN DARK MODE)
         ========================================================= */
      [data-bs-theme="dark"] {
        --tblr-primary: #f59e0b;
        --tblr-primary-rgb: 245, 158, 11;
        --tblr-primary-fg: #0f172a;
        --tblr-primary-hover: #d97706;
        --tblr-primary-active: #b45309;

        --tblr-bg-surface: #1e293b;
        --tblr-bg-surface-secondary: #0f172a;
        --tblr-body-bg: #0f172a;
        --tblr-border-color: rgba(245, 158, 11, 0.18);
        --tblr-border-color-translucent: rgba(245, 158, 11, 0.12);
      }
      
      body[data-bs-theme="dark"] {
        background-color: #0f172a !important;
        color: #f1f5f9 !important;
        font-feature-settings: "cv03", "cv04", "cv11";
        background-image: 
          radial-gradient(circle at 50% 20%, rgba(245, 158, 11, 0.08), transparent 50%),
          linear-gradient(rgba(255, 255, 255, 0.02) 1px, transparent 1px),
          linear-gradient(90deg, rgba(255, 255, 255, 0.02) 1px, transparent 1px) !important;
        background-size: 100% 100%, 32px 32px, 32px 32px !important;
      }

      body[data-bs-theme="dark"] .navbar-vertical {
        background-color: #0f172a !important;
        border-right: 1px solid rgba(245, 158, 11, 0.2) !important;
      }

      /* Fix sidebar offcanvas text alignment that was lost when removing navbar-collapse */
      .navbar-vertical .navbar-nav {
        flex-direction: column;
        align-items: stretch;
      }
      .navbar-vertical .nav-link {
        justify-content: flex-start !important;
        text-align: left !important;
      }
      .navbar-vertical .nav-item {
        text-align: left !important;
      }
      .navbar-vertical .dropdown-menu {
        position: static !important;
        float: none !important;
        box-shadow: none !important;
        border: none !important;
        background-color: transparent !important;
        padding-left: 1.5rem !important; /* Indent submenus */
        margin-top: 0 !important;
      }
      .navbar-vertical .dropdown-item {
        color: rgba(255,255,255,0.7) !important;
        padding-top: 0.5rem !important;
        padding-bottom: 0.5rem !important;
      }
      .navbar-vertical .dropdown-item.active,
      .navbar-vertical .dropdown-item:active,
      .navbar-vertical .dropdown-item:hover {
        background-color: rgba(255,255,255,0.1) !important;
        color: #fff !important;
        border-radius: 4px;
      }
      /* Ensure page content and header never get overlapped by vertical sidebar on desktop */
      @media (min-width: 992px) {
        .navbar-vertical ~ .page-wrapper {
          margin-left: 15rem !important;
        }
        .navbar-vertical ~ .navbar {
          margin-left: 15rem !important;
        }
      }

      /* Hide empty sidebar on mobile so header is the only top bar */
      @media (max-width: 991.98px) {
        .sidebar-transparent-mobile {
          min-height: 0 !important;
          height: 0 !important;
          padding: 0 !important;
          margin: 0 !important;
          border: none !important;
          overflow: visible !important;
        }
        .sidebar-transparent-mobile .container-fluid {
          padding: 0 !important;
          height: 0 !important;
          overflow: visible !important;
        }
        .sidebar-transparent-mobile .navbar-toggler {
          display: none !important;
        }
      }

      body[data-bs-theme="dark"] header.navbar {
        background-color: #1e293b !important;
        border-bottom: 1px solid rgba(245, 158, 11, 0.2) !important;
      }

      /* Industrial Hazard Stripe Top Bar (Dark Mode Only) */
      [data-bs-theme="dark"] .hazard-stripe-top {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: repeating-linear-gradient(
          -45deg,
          #f59e0b,
          #f59e0b 12px,
          #0f172a 12px,
          #0f172a 24px
        );
        z-index: 9999;
        display: block;
      }
      
      .hazard-stripe-top {
        display: none;
      }

      /* Card Industrial Glassmorphism in Dark Mode */
      [data-bs-theme="dark"] .card {
        background-color: #1e293b !important;
        border: 1px solid rgba(245, 158, 11, 0.2) !important;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.35) !important;
        border-radius: 12px !important;
        color: #f8fafc !important;
      }
      
      [data-bs-theme="dark"] .card-header {
        border-bottom: 1px solid rgba(245, 158, 11, 0.2) !important;
        background: rgba(15, 23, 42, 0.5) !important;
        color: #ffffff !important;
      }

      [data-bs-theme="dark"] .card-title {
        color: #fbbf24 !important;
        font-weight: 700 !important;
      }

      /* Primary Yellow Buttons in Dark Mode */
      [data-bs-theme="dark"] .btn-primary {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%) !important;
        border: none !important;
        color: #0f172a !important;
        font-weight: 700 !important;
        box-shadow: 0 4px 14px rgba(245, 158, 11, 0.3) !important;
      }

      [data-bs-theme="dark"] .btn-primary:hover, 
      [data-bs-theme="dark"] .btn-primary:focus, 
      [data-bs-theme="dark"] .btn-primary:active {
        background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%) !important;
        color: #000000 !important;
        box-shadow: 0 6px 18px rgba(245, 158, 11, 0.5) !important;
      }

      /* Active Nav Links in Dark Mode */
      [data-bs-theme="dark"] .nav-link.active, 
      [data-bs-theme="dark"] .dropdown-item.active {
        background: rgba(245, 158, 11, 0.18) !important;
        color: #fbbf24 !important;
        font-weight: 700 !important;
      }

      /* Tables styling in Dark Mode */
      [data-bs-theme="dark"] .table {
        color: #e2e8f0 !important;
      }
      [data-bs-theme="dark"] .table th {
        background-color: rgba(15, 23, 42, 0.8) !important;
        color: #fbbf24 !important;
        border-bottom: 2px solid rgba(245, 158, 11, 0.3) !important;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.05em;
      }
      [data-bs-theme="dark"] .table td {
        border-color: rgba(245, 158, 11, 0.1) !important;
      }
      
      /* Form inputs in Dark Mode */
      [data-bs-theme="dark"] .form-control, 
      [data-bs-theme="dark"] .form-select {
        background-color: rgba(15, 23, 42, 0.7) !important;
        border: 1px solid rgba(245, 158, 11, 0.25) !important;
        color: #ffffff !important;
      }
      [data-bs-theme="dark"] .form-control:focus, 
      [data-bs-theme="dark"] .form-select:focus {
        border-color: #f59e0b !important;
        box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.25) !important;
        background-color: rgba(15, 23, 42, 0.9) !important;
        color: #ffffff !important;
      }

      /* Modal styling in Dark Mode */
      [data-bs-theme="dark"] .modal-content {
        background-color: #1e293b !important;
        border: 1px solid rgba(245, 158, 11, 0.3) !important;
        color: #f8fafc !important;
      }
      [data-bs-theme="dark"] .modal-header {
        border-bottom: 1px solid rgba(245, 158, 11, 0.2) !important;
      }
      [data-bs-theme="dark"] .modal-footer {
        border-top: 1px solid rgba(245, 158, 11, 0.2) !important;
      }
    </style>
    @stack('styles')
  </head>
  <body>
    <div class="hazard-stripe-top"></div>
    <script>
        var themeStorageKey = "tablerTheme";
        var defaultTheme = "dark"; // Default to dark industrial theme, but toggleable to light
        var theme = localStorage.getItem(themeStorageKey) ? localStorage.getItem(themeStorageKey) : defaultTheme;
        document.body.setAttribute("data-bs-theme", theme);
    </script>
    <div class="page">
      <!-- Sidebar -->
      @if(!isset($hideSidebar) || !$hideSidebar)
        @include('layouts.sidebar')
      @endif
      <!-- Navbar -->
      @include('layouts.header')
      
      <div class="page-wrapper">
        <!-- Page body -->
        <div class="page-body">
          <div class="container-xl">
            @yield('content')
          </div>
        </div>
        <footer class="footer footer-transparent d-print-none">
          <div class="container-xl">
            <div class="row text-center align-items-center flex-row-reverse">
              <div class="col-12 col-lg-auto mt-3 mt-lg-0">
                <ul class="list-inline list-inline-dots mb-0">
                  <li class="list-inline-item">
                    Copyright &copy; {{ date('Y') }}
                    <a href="." class="link-secondary">CMMS Aisfar</a>.
                    All rights reserved.
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </footer>
      </div>
    </div>
    <!-- Tabler Core -->
    <script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta20/dist/js/tabler.min.js" defer></script>
    <!-- TomSelect (kept for other single-select elements if any) -->
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>

    <!-- Virtual Select (Excel-like Checkbox Filters) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/virtual-select-plugin@1.0.39/dist/virtual-select.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/virtual-select-plugin@1.0.39/dist/virtual-select.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Initialize Virtual Select for excel-like filters
            VirtualSelect.init({
                ele: '.excel-filter',
                multiple: true,
                search: true,
                selectAllText: '(Select All)',
                searchPlaceholderText: 'Search...',
                optionsSelectedText: ' terpilih',
                optionSelectedText: ' terpilih',
                allOptionsSelectedText: 'Semua terpilih',
                hideClearButton: false,
                zIndex: 1055,
                showValueAsTags: false,
                dropboxWidth: 'max-content'
            });
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: '{{ session('success') }}',
                    timer: 3000,
                    showConfirmButton: false
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Akses Ditolak',
                    text: '{{ session('error') }}',
                    confirmButtonText: 'Tutup'
                });
            @endif
        });
    </script>
    @stack('scripts')
    @include('chat.widget')
  </body>
</html>
