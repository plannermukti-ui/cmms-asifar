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

      /* Sidebar collapse via tombol hamburger di header (desktop only) */
      @media (min-width: 992px) {
        .navbar-vertical {
          transition: transform 0.3s ease;
        }
        body.sidebar-collapsed .navbar-vertical {
          transform: translateX(-100%);
        }
        .navbar-vertical ~ .navbar,
        .navbar-vertical ~ .page-wrapper {
          transition: margin-left 0.3s ease;
        }
        body.sidebar-collapsed .navbar-vertical ~ .navbar,
        body.sidebar-collapsed .navbar-vertical ~ .page-wrapper {
          margin-left: 0 !important;
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
        if (localStorage.getItem("tablerSidebarCollapsed") === "1") {
            document.body.classList.add("sidebar-collapsed");
        }
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
        });
    </script>
    <script>
        // Toggle sidebar (desktop) — simpan preferensi biar inget saat pindah halaman
        (function () {
            var toggleBtn = document.getElementById('sidebar-toggle');
            if (toggleBtn) {
                toggleBtn.addEventListener('click', function () {
                    var collapsed = document.body.classList.toggle('sidebar-collapsed');
                    try { localStorage.setItem('tablerSidebarCollapsed', collapsed ? '1' : '0'); } catch (e) {}
                });
            }
        })();
    </script>
    @stack('scripts')
    @include('chat.widget')

    <!-- GLOBAL TABLER UI ERROR NOTIFICATION POPUP MODAL -->
    <div class="modal modal-blur fade" id="globalTablerErrorModal" tabindex="-1" role="dialog" aria-hidden="true" style="z-index: 9999;">
      <div class="modal-dialog modal-md modal-dialog-centered" role="document">
        <div class="modal-content shadow-lg border-0">
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          <div class="modal-status bg-danger"></div>
          <div class="modal-body text-center py-4">
            <div class="avatar avatar-lg bg-danger-lt text-danger mb-3 rounded-circle mx-auto">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-alert-triangle" width="36" height="36" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 9v4" /><path d="M10.363 3.591l-8.106 13.534a1.914 1.914 0 0 0 1.636 2.871h16.214a1.914 1.914 0 0 0 1.636 -2.87l-8.106 -13.536a1.914 1.914 0 0 0 -3.274 0z" /><path d="M12 16h.01" /></svg>
            </div>
            <h3 class="fw-bold text-danger mb-2" id="globalTablerErrorTitle">Gagal Disimpan / Tidak Dapat Dilaksanakan</h3>
            <p class="text-muted small mb-3">Sistem menemukan kendala saat memproses data Anda. Silakan periksa rincian penjelasan berikut untuk memperbaikinya:</p>
            <div class="text-start bg-danger-lt p-3 rounded border border-danger-subtle mb-0" id="globalTablerErrorBody">
              <!-- Detailed error items -->
            </div>
          </div>
          <div class="modal-footer bg-light">
            <div class="w-100">
              <button type="button" class="btn btn-danger w-100 fw-bold shadow-sm" data-bs-dismiss="modal">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M18 6l-12 12" /><path d="M6 6l12 12" /></svg>
                Tutup & Perbaiki Form
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <script>
      window.showTablerErrorModal = function(title, messages) {
        const titleEl = document.getElementById('globalTablerErrorTitle');
        const bodyEl = document.getElementById('globalTablerErrorBody');
        if (titleEl) titleEl.innerText = title || 'Gagal Disimpan / Tidak Dapat Dilaksanakan';

        let htmlContent = '';
        if (Array.isArray(messages) && messages.length > 0) {
          htmlContent = '<ul class="mb-0 ps-3 text-danger fw-semibold small">';
          messages.forEach(msg => {
            htmlContent += `<li class="mb-1">${msg}</li>`;
          });
          htmlContent += '</ul>';
        } else if (typeof messages === 'string' && messages.trim() !== '') {
          htmlContent = `<div class="text-danger fw-semibold small mb-0"><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-info-circle me-1" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 12a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M12 9h.01" /><path d="M11 12h1v4h1" /></svg> ${messages}</div>`;
        } else {
          htmlContent = '<div class="text-danger fw-semibold small">Terjadi kesalahan pada sistem. Silakan periksa kembali data masukan Anda.</div>';
        }

        if (bodyEl) bodyEl.innerHTML = htmlContent;

        const modalEl = document.getElementById('globalTablerErrorModal');
        if (modalEl) {
          const bsModal = new bootstrap.Modal(modalEl);
          bsModal.show();
        }
      };

      document.addEventListener("DOMContentLoaded", function() {
        @if($errors->any())
          const formErrors = [
            @foreach ($errors->all() as $error)
              "{{ addslashes($error) }}",
            @endforeach
          ];
          window.showTablerErrorModal("Gagal Disimpan / Form Tidak Valid", formErrors);
        @elseif(session('error_popup'))
          window.showTablerErrorModal("Gagal Mengeksekusi Operasi", "{{ addslashes(session('error_popup')) }}");
        @elseif(session('error'))
          window.showTablerErrorModal("Perhatian / Kendala Operasi", "{{ addslashes(session('error')) }}");
        @endif
      });
    </script>

    @yield('scripts')
  </body>
</html>
