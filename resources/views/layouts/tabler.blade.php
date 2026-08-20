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
         DYNAMIC THEME ACCENT SYSTEM & INDUSTRIAL PALETTES
         ========================================================= */
      :root {
        --app-accent: #f59e0b;
        --app-accent-rgb: 245, 158, 11;
        --app-accent-hover: #d97706;
        --app-accent-active: #b45309;
        --app-accent-light: #fbbf24;
        --app-accent-glow: rgba(245, 158, 11, 0.22);
        --app-accent-glow-strong: rgba(245, 158, 11, 0.45);
        --app-accent-bg-subtle: rgba(245, 158, 11, 0.15);
        --app-accent-gradient: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        --app-accent-gradient-hover: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
      }

      [data-bs-theme="dark"] {
        --tblr-primary: var(--app-accent, #f59e0b);
        --tblr-primary-rgb: var(--app-accent-rgb, 245, 158, 11);
        --tblr-primary-fg: #0f172a;
        --tblr-primary-hover: var(--app-accent-hover, #d97706);
        --tblr-primary-active: var(--app-accent-active, #b45309);

        --tblr-bg-surface: #1e293b;
        --tblr-bg-surface-secondary: #0f172a;
        --tblr-body-bg: #0f172a;
        --tblr-border-color: var(--app-accent-glow, rgba(245, 158, 11, 0.18));
        --tblr-border-color-translucent: rgba(var(--app-accent-rgb, 245, 158, 11), 0.12);
      }

      body[data-bs-theme="dark"] {
        background-color: #0f172a !important;
        color: #f1f5f9 !important;
        font-feature-settings: "cv03", "cv04", "cv11";
        background-image:
          radial-gradient(circle at 50% 20%, var(--app-accent-glow, rgba(245, 158, 11, 0.08)), transparent 50%),
          linear-gradient(rgba(255, 255, 255, 0.02) 1px, transparent 1px),
          linear-gradient(90deg, rgba(255, 255, 255, 0.02) 1px, transparent 1px) !important;
        background-size: 100% 100%, 32px 32px, 32px 32px !important;
      }

      body[data-bs-theme="dark"] .navbar-vertical {
        background-color: #0f172a !important;
        border-right: 1px solid var(--app-accent-glow, rgba(245, 158, 11, 0.2)) !important;
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

      /* Sticky Top Navbar Header with Glassmorphism */
      header.navbar {
        position: sticky !important;
        top: 0 !important;
        z-index: 1020 !important;
        backdrop-filter: blur(12px) !important;
        -webkit-backdrop-filter: blur(12px) !important;
        transition: background-color 0.3s ease, border-color 0.3s ease, box-shadow 0.3s ease;
        min-height: 3.5rem !important;
        padding-top: 0.35rem !important;
        padding-bottom: 0.35rem !important;
      }

      /* Compact, optimal spacing between header and page body */
      .page-wrapper {
        margin-top: 0 !important;
        padding-top: 0 !important;
      }

      .page-body {
        margin-top: 1rem !important;
        margin-bottom: 1.5rem !important;
        padding-top: 0 !important;
      }

      body[data-bs-theme="dark"] header.navbar {
        background-color: rgba(30, 41, 59, 0.92) !important;
        border-bottom: 1px solid rgba(245, 158, 11, 0.25) !important;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.4) !important;
      }

      body:not([data-bs-theme="dark"]) header.navbar {
        background-color: rgba(255, 255, 255, 0.94) !important;
        border-bottom: 1px solid rgba(0, 0, 0, 0.08) !important;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05) !important;
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
          var(--app-accent, #f59e0b),
          var(--app-accent, #f59e0b) 12px,
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
        border: 1px solid var(--app-accent-glow, rgba(245, 158, 11, 0.2)) !important;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.35) !important;
        border-radius: 12px !important;
        color: #f8fafc !important;
      }

      [data-bs-theme="dark"] .card-header {
        border-bottom: 1px solid var(--app-accent-glow, rgba(245, 158, 11, 0.2)) !important;
        background: rgba(15, 23, 42, 0.5) !important;
        color: #ffffff !important;
      }

      [data-bs-theme="dark"] .card-title {
        color: var(--app-accent-light, #fbbf24) !important;
        font-weight: 700 !important;
      }

      /* Primary Buttons in Dark Mode */
      [data-bs-theme="dark"] .btn-primary {
        background: var(--app-accent-gradient, linear-gradient(135deg, #f59e0b 0%, #d97706 100%)) !important;
        border: none !important;
        color: #0f172a !important;
        font-weight: 700 !important;
        box-shadow: 0 4px 14px var(--app-accent-glow, rgba(245, 158, 11, 0.3)) !important;
      }

      [data-bs-theme="dark"] .btn-primary:hover,
      [data-bs-theme="dark"] .btn-primary:focus,
      [data-bs-theme="dark"] .btn-primary:active {
        background: var(--app-accent-gradient-hover, linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%)) !important;
        color: #000000 !important;
        box-shadow: 0 6px 18px var(--app-accent-glow-strong, rgba(245, 158, 11, 0.5)) !important;
      }

      /* Active Nav Links in Dark Mode */
      [data-bs-theme="dark"] .nav-link.active,
      [data-bs-theme="dark"] .dropdown-item.active {
        background: var(--app-accent-bg-subtle, rgba(245, 158, 11, 0.18)) !important;
        color: var(--app-accent-light, #fbbf24) !important;
        font-weight: 700 !important;
      }

      /* Tables styling in Dark Mode */
      [data-bs-theme="dark"] .table {
        color: #e2e8f0 !important;
      }
      [data-bs-theme="dark"] .table th {
        background-color: rgba(15, 23, 42, 0.8) !important;
        color: var(--app-accent-light, #fbbf24) !important;
        border-bottom: 2px solid var(--app-accent-glow, rgba(245, 158, 11, 0.3)) !important;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.05em;
      }
      [data-bs-theme="dark"] .table td {
        border-color: var(--app-accent-glow, rgba(245, 158, 11, 0.1)) !important;
      }

      /* Form inputs in Dark Mode */
      [data-bs-theme="dark"] .form-control,
      [data-bs-theme="dark"] .form-select {
        background-color: rgba(15, 23, 42, 0.7) !important;
        border: 1px solid var(--app-accent-glow, rgba(245, 158, 11, 0.25)) !important;
        color: #ffffff !important;
      }
      [data-bs-theme="dark"] .form-control:focus,
      [data-bs-theme="dark"] .form-select:focus {
        border-color: var(--app-accent, #f59e0b) !important;
        box-shadow: 0 0 0 3px var(--app-accent-glow, rgba(245, 158, 11, 0.25)) !important;
        background-color: rgba(15, 23, 42, 0.9) !important;
        color: #ffffff !important;
      }

      /* Modal styling in Dark Mode */
      [data-bs-theme="dark"] .modal-content {
        background-color: #1e293b !important;
        border: 1px solid var(--app-accent-glow, rgba(245, 158, 11, 0.3)) !important;
        color: #f8fafc !important;
      }
      [data-bs-theme="dark"] .modal-header {
        border-bottom: 1px solid var(--app-accent-glow, rgba(245, 158, 11, 0.2)) !important;
      }
      [data-bs-theme="dark"] .modal-footer {
        border-top: 1px solid var(--app-accent-glow, rgba(245, 158, 11, 0.2)) !important;
      }

      /* =========================================================
         COOL INDUSTRIAL CRUD LOADING SYSTEM & MICRO-INTERACTIONS
         ========================================================= */
      /* Top High-Tech Progress Bar */
      #industrialTopProgress {
        position: fixed;
        top: 0;
        left: 0;
        width: 0%;
        height: 3.5px;
        background: linear-gradient(90deg, var(--app-accent, #f59e0b), var(--app-accent-hover, #ea580c), var(--app-accent, #f59e0b));
        background-size: 200% 100%;
        z-index: 100000;
        box-shadow: 0 0 12px var(--app-accent, rgba(245, 158, 11, 0.9)), 0 0 6px var(--app-accent-hover, rgba(234, 88, 12, 0.7));
        transition: width 0.25s ease-out, opacity 0.3s ease;
        opacity: 0;
        pointer-events: none;
      }
      #industrialTopProgress.active {
        opacity: 1;
        animation: industrialProgressShimmer 1.5s linear infinite;
      }
      @keyframes industrialProgressShimmer {
        0% { background-position: 100% 0; }
        100% { background-position: -100% 0; }
      }

      /* Global Glassmorphism CRUD Loader Overlay */
      #globalCrudLoader {
        position: fixed;
        inset: 0;
        background-color: rgba(15, 23, 42, 0.82);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        z-index: 99999;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.25s ease, visibility 0.25s ease;
      }
      #globalCrudLoader.show {
        opacity: 1;
        visibility: visible;
      }
      
      .industrial-loader-card {
        background: #1e293b;
        border: 1px solid rgba(245, 158, 11, 0.4);
        border-radius: 18px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.6), 0 0 30px rgba(245, 158, 11, 0.2);
        padding: 2.25rem 2rem;
        max-width: 390px;
        width: 90%;
        text-align: center;
        position: relative;
        overflow: hidden;
        transform: scale(0.92);
        transition: transform 0.28s cubic-bezier(0.34, 1.56, 0.64, 1);
      }
      #globalCrudLoader.show .industrial-loader-card {
        transform: scale(1);
      }
      .industrial-loader-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 5px;
        background: repeating-linear-gradient(
          -45deg,
          #f59e0b,
          #f59e0b 12px,
          #0f172a 12px,
          #0f172a 24px
        );
      }

      /* Animated Heavy Gear Container */
      .industrial-gear-wrapper {
        position: relative;
        width: 90px;
        height: 90px;
        margin: 0 auto 1.5rem auto;
      }
      .gear-primary {
        position: absolute;
        top: 2px;
        left: 2px;
        width: 62px;
        height: 62px;
        color: #f59e0b;
        animation: spinGearClockwise 4.5s linear infinite;
        filter: drop-shadow(0 0 8px rgba(245, 158, 11, 0.5));
      }
      .gear-secondary {
        position: absolute;
        bottom: 4px;
        right: 4px;
        width: 44px;
        height: 44px;
        color: #ea580c;
        animation: spinGearCounter 3s linear infinite;
        filter: drop-shadow(0 0 6px rgba(234, 88, 12, 0.5));
      }
      .gear-pulse-ring {
        position: absolute;
        inset: -6px;
        border-radius: 50%;
        border: 2px dashed rgba(245, 158, 11, 0.3);
        animation: spinPulseRing 9s linear infinite;
      }
      @keyframes spinGearClockwise {
        100% { transform: rotate(360deg); }
      }
      @keyframes spinGearCounter {
        100% { transform: rotate(-360deg); }
      }
      @keyframes spinPulseRing {
        100% { transform: rotate(360deg); }
      }

      /* Loader Sub-dots animation */
      .loader-dots {
        display: inline-flex;
        gap: 5px;
        justify-content: center;
        align-items: center;
      }
      .loader-dots span {
        width: 7px;
        height: 7px;
        background-color: #f59e0b;
        border-radius: 50%;
        animation: loaderDotPulse 1.2s infinite ease-in-out both;
        box-shadow: 0 0 6px rgba(245, 158, 11, 0.6);
      }
      .loader-dots span:nth-child(1) { animation-delay: -0.32s; }
      .loader-dots span:nth-child(2) { animation-delay: -0.16s; }
      .loader-dots span:nth-child(3) { animation-delay: 0s; }
      @keyframes loaderDotPulse {
        0%, 80%, 100% { transform: scale(0.3); opacity: 0.3; }
        40% { transform: scale(1.15); opacity: 1; }
      }

      /* Button active loading micro-animation */
      .btn.is-submitting {
        position: relative !important;
        pointer-events: none !important;
        opacity: 0.85 !important;
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

        // =========================================================
        // DYNAMIC ACCENT COLOR PRESETS & ENGINE
        // =========================================================
        window.ACCENT_PRESETS = {
          amber: {
            key: 'amber',
            name: 'CAT Mining Amber',
            primary: '#f59e0b',
            rgb: '245, 158, 11',
            hover: '#d97706',
            active: '#b45309',
            light: '#fbbf24',
            glow: 'rgba(245, 158, 11, 0.22)',
            glowStrong: 'rgba(245, 158, 11, 0.45)',
            bgSubtle: 'rgba(245, 158, 11, 0.15)',
            gradient: 'linear-gradient(135deg, #f59e0b 0%, #d97706 100%)',
            gradientHover: 'linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%)'
          },
          cyan: {
            key: 'cyan',
            name: 'Komatsu Cyber Cyan',
            primary: '#06b6d4',
            rgb: '6, 182, 212',
            hover: '#0891b2',
            active: '#0e7490',
            light: '#22d3ee',
            glow: 'rgba(6, 182, 212, 0.22)',
            glowStrong: 'rgba(6, 182, 212, 0.45)',
            bgSubtle: 'rgba(6, 182, 212, 0.15)',
            gradient: 'linear-gradient(135deg, #06b6d4 0%, #0284c7 100%)',
            gradientHover: 'linear-gradient(135deg, #22d3ee 0%, #06b6d4 100%)'
          },
          emerald: {
            key: 'emerald',
            name: 'Hitachi Emerald',
            primary: '#10b981',
            rgb: '16, 185, 129',
            hover: '#059669',
            active: '#047857',
            light: '#34d399',
            glow: 'rgba(16, 185, 129, 0.22)',
            glowStrong: 'rgba(16, 185, 129, 0.45)',
            bgSubtle: 'rgba(16, 185, 129, 0.15)',
            gradient: 'linear-gradient(135deg, #10b981 0%, #059669 100%)',
            gradientHover: 'linear-gradient(135deg, #34d399 0%, #10b981 100%)'
          },
          orange: {
            key: 'orange',
            name: 'Safety Flame Orange',
            primary: '#f97316',
            rgb: '249, 115, 22',
            hover: '#ea580c',
            active: '#c2410c',
            light: '#fb923c',
            glow: 'rgba(249, 115, 22, 0.22)',
            glowStrong: 'rgba(249, 115, 22, 0.45)',
            bgSubtle: 'rgba(249, 115, 22, 0.15)',
            gradient: 'linear-gradient(135deg, #f97316 0%, #ea580c 100%)',
            gradientHover: 'linear-gradient(135deg, #fb923c 0%, #f97316 100%)'
          },
          blue: {
            key: 'blue',
            name: 'Liebherr Sapphire Blue',
            primary: '#3b82f6',
            rgb: '59, 130, 246',
            hover: '#2563eb',
            active: '#1d4ed8',
            light: '#60a5fa',
            glow: 'rgba(59, 130, 246, 0.22)',
            glowStrong: 'rgba(59, 130, 246, 0.45)',
            bgSubtle: 'rgba(59, 130, 246, 0.15)',
            gradient: 'linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%)',
            gradientHover: 'linear-gradient(135deg, #60a5fa 0%, #3b82f6 100%)'
          },
          purple: {
            key: 'purple',
            name: 'Neon Amethyst Purple',
            primary: '#8b5cf6',
            rgb: '139, 92, 246',
            hover: '#7c3aed',
            active: '#6d28d9',
            light: '#a78bfa',
            glow: 'rgba(139, 92, 246, 0.22)',
            glowStrong: 'rgba(139, 92, 246, 0.45)',
            bgSubtle: 'rgba(139, 92, 246, 0.15)',
            gradient: 'linear-gradient(135deg, #8b5cf6 0%, #6366f1 100%)',
            gradientHover: 'linear-gradient(135deg, #a78bfa 0%, #8b5cf6 100%)'
          }
        };

        window.applyAccentColor = function(accentKey) {
          const preset = window.ACCENT_PRESETS[accentKey] || window.ACCENT_PRESETS.amber;
          const root = document.documentElement;
          root.style.setProperty('--app-accent', preset.primary);
          root.style.setProperty('--app-accent-rgb', preset.rgb);
          root.style.setProperty('--app-accent-hover', preset.hover);
          root.style.setProperty('--app-accent-active', preset.active);
          root.style.setProperty('--app-accent-light', preset.light);
          root.style.setProperty('--app-accent-glow', preset.glow);
          root.style.setProperty('--app-accent-glow-strong', preset.glowStrong);
          root.style.setProperty('--app-accent-bg-subtle', preset.bgSubtle);
          root.style.setProperty('--app-accent-gradient', preset.gradient);
          root.style.setProperty('--app-accent-gradient-hover', preset.gradientHover);
          root.style.setProperty('--tblr-primary', preset.primary);
          root.style.setProperty('--tblr-primary-rgb', preset.rgb);
          
          try { localStorage.setItem('tablerAccentColor', preset.key); } catch (e) {}

          document.querySelectorAll('.accent-picker-item').forEach(el => {
            const isCurrent = el.dataset.accent === preset.key;
            el.classList.toggle('active', isCurrent);
            const check = el.querySelector('.accent-check');
            if (check) check.style.display = isCurrent ? 'inline-block' : 'none';
          });

          const activeDot = document.getElementById('currentAccentDot');
          if (activeDot) {
            activeDot.style.backgroundColor = preset.primary;
            activeDot.style.boxShadow = `0 0 8px ${preset.primary}`;
          }
        };

        // Apply saved accent color immediately before render
        var savedAccent = localStorage.getItem('tablerAccentColor') || 'amber';
        window.applyAccentColor(savedAccent);
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

    <!-- GLOBAL TABLER UI CONFIRMATION POPUP MODAL -->
    <div class="modal modal-blur fade" id="globalTablerConfirmModal" tabindex="-1" role="dialog" aria-hidden="true" style="z-index: 10000;" data-bs-backdrop="static">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content shadow-lg border-0">
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          <div class="modal-status bg-warning" id="globalTablerConfirmStatus"></div>
          <div class="modal-body text-center py-4">
            <div class="avatar avatar-lg bg-warning-lt text-warning mb-3 rounded-circle mx-auto" id="globalTablerConfirmIcon">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-alert-triangle" width="36" height="36" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 9v4" /><path d="M10.363 3.591l-8.106 13.534a1.914 1.914 0 0 0 1.636 2.871h16.214a1.914 1.914 0 0 0 1.636 -2.87l-8.106 -13.536a1.914 1.914 0 0 0 -3.274 0z" /><path d="M12 16h.01" /></svg>
            </div>
            <h3 class="fw-bold mb-2 text-dark" id="globalTablerConfirmTitle">Konfirmasi Tindakan</h3>
            <div class="text-muted small" id="globalTablerConfirmMessage">Apakah Anda yakin ingin melanjutkan tindakan ini?</div>
          </div>
          <div class="modal-footer bg-light py-2">
            <div class="w-100">
              <div class="row g-2">
                <div class="col">
                  <button type="button" class="btn btn-secondary w-100" data-bs-dismiss="modal" id="globalTablerConfirmBtnCancel">Batal</button>
                </div>
                <div class="col">
                  <button type="button" class="btn btn-warning w-100 fw-bold text-dark" id="globalTablerConfirmBtnOk">Lanjutkan</button>
                </div>
              </div>
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

      window.showTablerConfirm = function(options) {
        const title = options.title || 'Konfirmasi Tindakan';
        const message = options.message || 'Apakah Anda yakin ingin melanjutkan tindakan ini?';
        const confirmText = options.confirmText || 'Lanjutkan';
        const cancelText = options.cancelText || 'Batal';
        const type = options.type || 'warning'; // warning, danger, primary
        const onConfirm = options.onConfirm || function() {};
        const onCancel = options.onCancel || function() {};

        const titleEl = document.getElementById('globalTablerConfirmTitle');
        const msgEl = document.getElementById('globalTablerConfirmMessage');
        const okBtn = document.getElementById('globalTablerConfirmBtnOk');
        const cancelBtn = document.getElementById('globalTablerConfirmBtnCancel');
        const statusEl = document.getElementById('globalTablerConfirmStatus');
        const iconEl = document.getElementById('globalTablerConfirmIcon');

        if (titleEl) titleEl.innerText = title;
        if (msgEl) msgEl.innerHTML = message;
        if (okBtn) okBtn.innerText = confirmText;
        if (cancelBtn) cancelBtn.innerText = cancelText;

        if (type === 'danger') {
            if (statusEl) statusEl.className = 'modal-status bg-danger';
            if (iconEl) iconEl.className = 'avatar avatar-lg bg-danger-lt text-danger mb-3 rounded-circle mx-auto';
            if (okBtn) okBtn.className = 'btn btn-danger w-100 fw-bold';
        } else if (type === 'primary') {
            if (statusEl) statusEl.className = 'modal-status bg-primary';
            if (iconEl) iconEl.className = 'avatar avatar-lg bg-primary-lt text-primary mb-3 rounded-circle mx-auto';
            if (okBtn) okBtn.className = 'btn btn-primary w-100 fw-bold';
        } else {
            if (statusEl) statusEl.className = 'modal-status bg-warning';
            if (iconEl) iconEl.className = 'avatar avatar-lg bg-warning-lt text-warning mb-3 rounded-circle mx-auto';
            if (okBtn) okBtn.className = 'btn btn-warning w-100 fw-bold text-dark';
        }

        const modalEl = document.getElementById('globalTablerConfirmModal');
        if (modalEl) {
          const bsModal = new bootstrap.Modal(modalEl);

          const handleOk = function() {
            okBtn.removeEventListener('click', handleOk);
            bsModal.hide();
            window.showCrudLoader('Menjalankan Tindakan...', 'Mohon tunggu sementara sistem memproses konfirmasi Anda.', 'CONFIRMED ACTION');
            onConfirm();
          };
          okBtn.addEventListener('click', handleOk, { once: true });

          modalEl.addEventListener('hidden.bs.modal', function() {
            okBtn.removeEventListener('click', handleOk);
          }, { once: true });

          bsModal.show();
        }
      };

      // =========================================================
      // GLOBAL INDUSTRIAL LOADER & CRUD ACTION INTERCEPTORS
      // =========================================================
      let topProgressTimer = null;
      window.startTopProgress = function() {
        const bar = document.getElementById('industrialTopProgress');
        if (!bar) return;
        clearInterval(topProgressTimer);
        bar.classList.add('active');
        bar.style.width = '20%';
        bar.style.opacity = '1';
        let currentWidth = 20;
        topProgressTimer = setInterval(() => {
          if (currentWidth < 85) {
            currentWidth += Math.random() * 15;
            bar.style.width = currentWidth + '%';
          }
        }, 300);
      };

      window.finishTopProgress = function() {
        const bar = document.getElementById('industrialTopProgress');
        if (!bar) return;
        clearInterval(topProgressTimer);
        bar.style.width = '100%';
        setTimeout(() => {
          bar.style.opacity = '0';
          setTimeout(() => {
            bar.classList.remove('active');
            bar.style.width = '0%';
          }, 300);
        }, 200);
      };

      window.showCrudLoader = function(title, subtext, badge) {
        const loader = document.getElementById('globalCrudLoader');
        const titleEl = document.getElementById('globalCrudLoaderTitle');
        const subtextEl = document.getElementById('globalCrudLoaderSubtext');
        const badgeEl = document.getElementById('globalCrudLoaderBadge');

        if (titleEl) titleEl.innerText = title || 'Memproses Data...';
        if (subtextEl) subtextEl.innerText = subtext || 'Mohon tunggu, sistem sedang memproses transaksi Anda.';
        if (badgeEl) badgeEl.innerText = badge || 'PROCESSING TRANSACTION';

        if (loader) loader.classList.add('show');
        window.startTopProgress();
      };

      window.hideCrudLoader = function() {
        const loader = document.getElementById('globalCrudLoader');
        if (loader) loader.classList.remove('show');
        window.finishTopProgress();

        // Restore any disabled submitting buttons
        document.querySelectorAll('.btn.is-submitting').forEach(btn => {
          btn.classList.remove('is-submitting');
          if (btn.dataset.origHtml) {
            btn.innerHTML = btn.dataset.origHtml;
          }
        });
      };

      // Reset loader if page is loaded from bfcache (Back/Forward Cache)
      window.addEventListener('pageshow', function(e) {
        if (e.persisted) {
          window.hideCrudLoader();
        }
      });

      // Hook form submissions globally
      document.addEventListener('submit', function(e) {
        const form = e.target;
        if (!form || form.hasAttribute('data-no-loader')) return;

        const method = (form.getAttribute('method') || 'GET').toUpperCase();
        const hasMethodOverride = form.querySelector('input[name="_method"]');
        const effectiveMethod = hasMethodOverride ? hasMethodOverride.value.toUpperCase() : method;

        let title = 'Menyimpan Data...';
        let subtext = 'Sistem sedang memproses data transaksi Anda.';
        let badge = 'SAVING DATA';

        const action = (form.getAttribute('action') || '').toLowerCase();
        const isDelete = effectiveMethod === 'DELETE' || action.includes('destroy') || action.includes('delete') || action.includes('cancel') || action.includes('reject');
        const isUpload = form.querySelector('input[type="file"]') !== null || (form.getAttribute('enctype') || '').includes('multipart');
        const isUpdate = effectiveMethod === 'PUT' || effectiveMethod === 'PATCH' || action.includes('update') || action.includes('edit');
        const isExportOrGen = action.includes('export') || action.includes('generate') || action.includes('download');

        if (isDelete) {
          title = 'Menghapus Data...';
          subtext = 'Sistem sedang menghapus data terkait dari database.';
          badge = 'DELETING RECORD';
        } else if (isUpload) {
          title = 'Mengunggah & Memproses Berkas...';
          subtext = 'Mohon tunggu sementara berkas Anda diunggah dan diverifikasi.';
          badge = 'UPLOADING FILE';
        } else if (isExportOrGen) {
          title = 'Menyiapkan Dokumen...';
          subtext = 'Sistem sedang menyusun dan mengunduh data dokumen Anda.';
          badge = 'GENERATING FILE';
        } else if (isUpdate) {
          title = 'Menyimpan Perubahan...';
          subtext = 'Memperbarui data pada sistem.';
          badge = 'UPDATING DATA';
        } else if (effectiveMethod === 'POST') {
          title = 'Menyimpan Data Baru...';
          subtext = 'Menyimpan catatan transaksi ke sistem.';
          badge = 'CREATING RECORD';
        } else {
          title = 'Memuat Data...';
          subtext = 'Sistem sedang menyiapkan data untuk Anda.';
          badge = 'FETCHING DATA';
        }

        // Apply button spinner
        const submitBtn = form.querySelector('button[type="submit"]:focus') || form.querySelector('button[type="submit"]') || form.querySelector('input[type="submit"]');
        if (submitBtn && !submitBtn.classList.contains('is-submitting')) {
          submitBtn.classList.add('is-submitting');
          submitBtn.dataset.origHtml = submitBtn.innerHTML;
          submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1.5" role="status" aria-hidden="true"></span> ' + (isDelete ? 'Menghapus...' : (isUpload ? 'Mengunggah...' : 'Memproses...'));
        }

        window.showCrudLoader(title, subtext, badge);
      });

      // Hook AJAX / Fetch globally to run top progress bar
      (function() {
        const originalFetch = window.fetch;
        if (originalFetch) {
          window.fetch = function() {
            window.startTopProgress();
            return originalFetch.apply(this, arguments)
              .then(response => {
                window.finishTopProgress();
                return response;
              })
              .catch(error => {
                window.finishTopProgress();
                throw error;
              });
          };
        }
      })();

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

    <!-- INDUSTRIAL TOP PROGRESS BAR -->
    <div id="industrialTopProgress"></div>

    <!-- GLOBAL INDUSTRIAL CRUD LOADER OVERLAY -->
    <div id="globalCrudLoader" role="status" aria-live="polite">
      <div class="industrial-loader-card">
        <div class="industrial-gear-wrapper">
          <div class="gear-pulse-ring"></div>
          <!-- Primary Gear -->
          <svg class="gear-primary" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none">
            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
            <path d="M10.325 4.317c.426 -1.756 2.924 -1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543 -.94 3.31 .826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756 .426 1.756 2.924 0 3.35a1.724 1.724 0 0 0 -1.066 2.573c.94 1.543 -.826 3.31 -2.37 2.37a1.724 1.724 0 0 0 -2.572 1.065c-.426 1.756 -2.924 1.756 -3.35 0a1.724 1.724 0 0 0 -2.573 -1.066c-1.543 .94 -3.31 -.826 -2.37 -2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756 -.426 -1.756 -2.924 0 -3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94 -1.543 .826 -3.31 2.37 -2.37c1 .608 2.296 .07 2.572 -1.065z" />
            <path d="M9 12a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" />
          </svg>
          <!-- Secondary Gear -->
          <svg class="gear-secondary" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none">
            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
            <path d="M10.325 4.317c.426 -1.756 2.924 -1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543 -.94 3.31 .826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756 .426 1.756 2.924 0 3.35a1.724 1.724 0 0 0 -1.066 2.573c.94 1.543 -.826 3.31 -2.37 2.37a1.724 1.724 0 0 0 -2.572 1.065c-.426 1.756 -2.924 1.756 -3.35 0a1.724 1.724 0 0 0 -2.573 -1.066c-1.543 .94 -3.31 -.826 -2.37 -2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756 -.426 -1.756 -2.924 0 -3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94 -1.543 .826 -3.31 2.37 -2.37c1 .608 2.296 .07 2.572 -1.065z" />
            <path d="M9 12a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" />
          </svg>
        </div>

        <div class="badge bg-warning-lt text-warning fw-bold px-2.5 py-1 mb-2 text-uppercase font-monospace" style="font-size: 0.7rem; letter-spacing: 1px;" id="globalCrudLoaderBadge">
          PROCESSING TRANSACTION
        </div>
        <h4 class="fw-bold text-white mb-1" id="globalCrudLoaderTitle">Memproses Data...</h4>
        <p class="text-secondary small mb-3" id="globalCrudLoaderSubtext">Mohon tunggu, sistem sedang memproses transaksi Anda.</p>
        
        <div class="loader-dots">
          <span></span>
          <span></span>
          <span></span>
        </div>
      </div>
    </div>

    @yield('scripts')
  </body>
</html>
