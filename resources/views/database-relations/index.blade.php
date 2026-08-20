@extends('layouts.tabler')

@section('title', 'Relasi Skema Database 3D - CMMS Aisfar')

@push('styles')
<style>
  /* =========================================================
     3D DATABASE SCHEMA VISUALIZER - CYBER INDUSTRIAL THEME
     ========================================================= */
  .schema-wrapper {
    position: relative;
    width: 100%;
    height: calc(100vh - 140px);
    min-height: 650px;
    background: radial-gradient(circle at 50% 30%, #1e293b 0%, #0f172a 70%, #020617 100%);
    border-radius: 12px;
    overflow: hidden;
    border: 1px solid rgba(245, 158, 11, 0.25);
    box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.7), inset 0 0 30px rgba(0, 0, 0, 0.5);
  }

  #graph-canvas {
    width: 100%;
    height: 100%;
    position: absolute;
    top: 0;
    left: 0;
    z-index: 1;
  }

  /* Grid overlay effect */
  .schema-grid-bg {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-image: 
      linear-gradient(rgba(245, 158, 11, 0.04) 1px, transparent 1px),
      linear-gradient(90deg, rgba(245, 158, 11, 0.04) 1px, transparent 1px);
    background-size: 40px 40px;
    pointer-events: none;
    z-index: 2;
  }

  /* Glassmorphism Panels */
  .glass-panel {
    background: rgba(15, 23, 42, 0.82);
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    border: 1px solid rgba(245, 158, 11, 0.25);
    border-radius: 10px;
    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.5);
  }

  /* Floating Top HUD */
  .floating-hud {
    position: absolute;
    top: 16px;
    left: 16px;
    right: 16px;
    z-index: 10;
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    align-items: center;
    justify-content: space-between;
    pointer-events: none;
  }
  .floating-hud > * {
    pointer-events: auto;
  }

  /* Stats HUD pill */
  .stat-pill {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
  }

  /* Floating Controls Bar (Bottom) */
  .floating-controls {
    position: absolute;
    bottom: 16px;
    left: 16px;
    z-index: 10;
    display: flex;
    gap: 8px;
    align-items: center;
    flex-wrap: wrap;
  }

  /* Module Filter Bar (Top under Search) */
  .module-chips-wrapper {
    position: absolute;
    top: 76px;
    left: 16px;
    right: 420px;
    z-index: 9;
    display: flex;
    gap: 6px;
    overflow-x: auto;
    padding-bottom: 4px;
    scrollbar-width: thin;
  }
  .module-chip {
    cursor: pointer;
    font-size: 0.72rem;
    padding: 4px 10px;
    border-radius: 16px;
    white-space: nowrap;
    border: 1px solid rgba(255, 255, 255, 0.1);
    background: rgba(15, 23, 42, 0.85);
    color: #94a3b8;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    gap: 6px;
  }
  .module-chip:hover, .module-chip.active {
    color: #fff;
    border-color: var(--chip-color, #f59e0b);
    background: rgba(15, 23, 42, 0.95);
    box-shadow: 0 0 12px var(--chip-glow, rgba(245, 158, 11, 0.4));
    transform: translateY(-1px);
  }
  .module-chip .dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background-color: var(--chip-color, #f59e0b);
    display: inline-block;
  }

  /* Sliding Inspector Drawer */
  .inspector-drawer {
    position: absolute;
    top: 16px;
    right: 16px;
    bottom: 16px;
    width: 380px;
    max-width: calc(100% - 32px);
    z-index: 20;
    transform: translateX(420px);
    transition: transform 0.35s cubic-bezier(0.16, 1, 0.3, 1);
    display: flex;
    flex-direction: column;
    overflow: hidden;
  }
  .inspector-drawer.open {
    transform: translateX(0);
  }

  .drawer-header {
    padding: 16px 20px;
    border-bottom: 1px solid rgba(245, 158, 11, 0.2);
    position: relative;
  }
  .drawer-body {
    padding: 16px 20px;
    overflow-y: auto;
    flex: 1;
  }

  /* Badges & Tables in Drawer */
  .badge-pk {
    background: rgba(245, 158, 11, 0.2);
    color: #fbbf24;
    border: 1px solid rgba(245, 158, 11, 0.4);
    font-size: 0.65rem;
    font-weight: 700;
  }
  .badge-fk {
    background: rgba(6, 182, 212, 0.2);
    color: #22d3ee;
    border: 1px solid rgba(6, 182, 212, 0.4);
    font-size: 0.65rem;
    font-weight: 700;
  }
  .badge-type {
    background: rgba(148, 163, 184, 0.15);
    color: #cbd5e1;
    font-family: monospace;
    font-size: 0.68rem;
  }

  /* Table Search Autocomplete */
  .search-dropdown {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    max-height: 280px;
    overflow-y: auto;
    z-index: 30;
    margin-top: 4px;
    display: none;
  }
  .search-dropdown.show {
    display: block;
  }
  .search-item {
    padding: 8px 12px;
    cursor: pointer;
    border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    transition: background 0.15s;
  }
  .search-item:hover {
    background: rgba(245, 158, 11, 0.15);
  }

  /* Loading Overlay */
  .schema-loader {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: #0f172a;
    z-index: 50;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    transition: opacity 0.5s ease;
  }
  .schema-loader.fade-out {
    opacity: 0;
    pointer-events: none;
  }

  .pulse-cube {
    width: 60px;
    height: 60px;
    border: 3px solid #f59e0b;
    border-radius: 8px;
    animation: cube-spin 2.2s infinite ease-in-out;
    box-shadow: 0 0 25px rgba(245, 158, 11, 0.6);
  }
  @keyframes cube-spin {
    0% { transform: perspective(120px) rotateX(0deg) rotateY(0deg); }
    50% { transform: perspective(120px) rotateX(-180.1deg) rotateY(0deg); border-color: #06b6d4; box-shadow: 0 0 25px rgba(6, 182, 212, 0.6); }
    100% { transform: perspective(120px) rotateX(-180deg) rotateY(-179.9deg); border-color: #10b981; box-shadow: 0 0 25px rgba(16, 185, 129, 0.6); }
  }

  /* Custom tooltips */
  .custom-3d-tooltip {
    position: absolute;
    padding: 6px 12px;
    background: rgba(15, 23, 42, 0.92);
    border: 1px solid rgba(245, 158, 11, 0.4);
    border-radius: 6px;
    color: #f8fafc;
    font-size: 0.75rem;
    pointer-events: none;
    z-index: 15;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.6);
    transform: translate(-50%, -120%);
    display: none;
  }
</style>
@endpush

@section('content')
<div class="container-xl">
  <!-- Page Header -->
  <div class="page-header d-print-none mb-3">
    <div class="row align-items-center">
      <div class="col">
        <div class="page-pretitle text-warning fw-bold d-flex align-items-center gap-1">
          <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-3d-cube-sphere text-warning" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6 17.6l-2 -1.1v-2.5" /><path d="M4 10v-2.5l2 -1.1" /><path d="M10 4.1l2 -1.1l2 1.1" /><path d="M18 6.4l2 1.1v2.5" /><path d="M20 14v2.5l-2 1.1" /><path d="M14 19.9l-2 1.1l-2 -1.1" /><path d="M12 12l8 -4.5" /><path d="M12 12v9" /><path d="M12 12l-8 -4.5" /></svg>
          DATABASE ARCHITECTURE
        </div>
        <h2 class="page-title text-white">
          Visualisasi Skema & Relasi Database 3D
        </h2>
        <p class="text-muted small mb-0">
          Eksplorasi graf relasi interaktif 3D antar tabel CMMS Aisfar secara visual dengan gravitasi fisik WebGL.
        </p>
      </div>
      <div class="col-auto ms-auto d-flex gap-2">
        <button class="btn btn-outline-warning btn-sm" id="btn-export-png" title="Download Screenshot PNG">
          <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2" /><path d="M7 11l5 5l5 -5" /><path d="M12 4l0 12" /></svg>
          Export PNG
        </button>
        <button class="btn btn-primary btn-sm" id="btn-reset-cam" title="Reset Posisi Kamera">
          <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -4v4h4" /><path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4" /></svg>
          Reset Kamera
        </button>
      </div>
    </div>
  </div>

  <!-- 3D Canvas Main Stage -->
  <div class="schema-wrapper" id="schema-container">
    <!-- Grid Overlay -->
    <div class="schema-grid-bg"></div>

    <!-- 3D WebGL Canvas Target -->
    <div id="graph-canvas"></div>

    <!-- Hover Tooltip -->
    <div id="custom-tooltip" class="custom-3d-tooltip"></div>

    <!-- Loading Screen -->
    <div class="schema-loader" id="schema-loader">
      <div class="pulse-cube mb-3"></div>
      <div class="text-white fw-bold h4 mb-1">Memuat Relasi Skema Database 3D...</div>
      <div class="text-muted small">Memindai tabel, foreign key, dan struktur relasi CMMS</div>
    </div>

    <!-- Top Floating HUD: Search & Summary Stats -->
    <div class="floating-hud">
      <!-- Search Box with Autocomplete -->
      <div class="position-relative" style="width: 320px;">
        <div class="input-icon">
          <span class="input-icon-addon">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon text-warning" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" /><path d="M21 21l-6 -6" /></svg>
          </span>
          <input type="text" id="search-input" class="form-control form-control-sm bg-dark text-white border-warning-subtle" placeholder="Cari tabel atau kolom (e.g. work_orders, unit)..." autocomplete="off">
        </div>
        <!-- Autocomplete dropdown -->
        <div id="search-dropdown" class="glass-panel search-dropdown"></div>
      </div>

      <!-- Live Database Stats Badge -->
      <div class="d-none d-md-flex align-items-center gap-2">
        <div class="stat-pill glass-panel text-warning border-warning">
          <span class="text-muted small">Tabel:</span>
          <span id="stat-tables" class="h5 mb-0 fw-bold">0</span>
        </div>
        <div class="stat-pill glass-panel text-cyan border-cyan">
          <span class="text-muted small">Relasi FK:</span>
          <span id="stat-links" class="h5 mb-0 fw-bold">0</span>
        </div>
        <div class="stat-pill glass-panel text-success border-success">
          <span class="text-muted small">Total Kolom:</span>
          <span id="stat-columns" class="h5 mb-0 fw-bold">0</span>
        </div>
        <div class="stat-pill glass-panel text-info border-info">
          <span class="text-muted small">Estimasi Records:</span>
          <span id="stat-rows" class="h5 mb-0 fw-bold">0</span>
        </div>
      </div>
    </div>

    <!-- Module Filter Chips (Horizontal Scrollable) -->
    <div class="module-chips-wrapper" id="module-chips">
      <button class="module-chip active" data-module="all" style="--chip-color: #f59e0b; --chip-glow: rgba(245, 158, 11, 0.4);">
        <span class="dot"></span> Semua Modul (<span id="count-all">0</span>)
      </button>
    </div>

    <!-- Floating Bottom Controls -->
    <div class="floating-controls">
      <!-- Layout Modes -->
      <div class="btn-group btn-group-sm glass-panel p-1">
        <button class="btn btn-dark text-warning active" id="btn-layout-3d" title="Mode 3D Force-Directed Galaxy">
          <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 3l8 4.5l0 9l-8 4.5l-8 -4.5l0 -9l8 -4.5" /></svg>
          3D Galaxy
        </button>
        <button class="btn btn-dark text-muted" id="btn-layout-sphere" title="Mode 3D Spherical Orbit">
          <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M12 3a4.5 9 0 0 0 0 18a4.5 9 0 0 0 0 -18" /></svg>
          3D Sphere
        </button>
        <button class="btn btn-dark text-muted" id="btn-layout-2d" title="Mode 2D Planar Projection">
          <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 4m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z" /></svg>
          2D Flat
        </button>
      </div>

      <!-- Action Toggles -->
      <button class="btn btn-dark btn-sm glass-panel text-white" id="btn-autorotate" title="Toggle Rotasi Otomatis">
        <svg xmlns="http://www.w3.org/2000/svg" class="icon text-primary me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -4v4h4" /><path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4" /></svg>
        <span id="rotate-status">Auto Rotate: OFF</span>
      </button>

      <button class="btn btn-dark btn-sm glass-panel text-white" id="btn-particles" title="Toggle Partikel Aliran FK">
        <svg xmlns="http://www.w3.org/2000/svg" class="icon text-cyan me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 3l1.465 1.638a2 2 0 0 0 1.5 .686h2.035a2 2 0 0 1 2 2v2.035a2 2 0 0 0 .686 1.5l1.638 1.465a2 2 0 0 1 0 2.96l-1.638 1.465a2 2 0 0 0 -.686 1.5v2.035a2 2 0 0 1 -2 2h-2.035a2 2 0 0 0 -1.5 .686l-1.465 1.638a2 2 0 0 1 -2.96 0l-1.465 -1.638a2 2 0 0 0 -1.5 -.686h-2.035a2 2 0 0 1 -2 -2v-2.035a2 2 0 0 0 -.686 -1.5l-1.638 -1.465a2 2 0 0 1 0 -2.96l1.638 -1.465a2 2 0 0 0 .686 -1.5v-2.035a2 2 0 0 1 2 -2h2.035a2 2 0 0 0 1.5 -.686l1.465 -1.638a2 2 0 0 1 2.96 0z" /></svg>
        Partikel FK: ON
      </button>

      <button class="btn btn-dark btn-sm glass-panel text-white" id="btn-fullscreen" title="Mode Layar Penuh">
        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 8v-2a2 2 0 0 1 2 -2h2" /><path d="M4 16v2a2 2 0 0 0 2 2h2" /><path d="M16 4h2a2 2 0 0 1 2 2v2" /><path d="M16 20h2a2 2 0 0 0 2 -2v-2" /></svg>
      </button>
    </div>

    <!-- Sliding Glassmorphic Inspector Drawer -->
    <div id="inspector-drawer" class="glass-panel inspector-drawer">
      <!-- Drawer Header -->
      <div class="drawer-header">
        <div class="d-flex align-items-center justify-content-between mb-1">
          <span id="drawer-module-badge" class="badge bg-warning-lt text-warning fw-bold">Modul</span>
          <button type="button" class="btn-close btn-close-white" id="btn-close-drawer" aria-label="Close"></button>
        </div>
        <h3 id="drawer-table-name" class="text-white mb-1 text-truncate">Nama Tabel</h3>
        <div class="d-flex align-items-center gap-2">
          <code id="drawer-raw-name" class="text-muted small">raw_table_name</code>
          <button class="btn btn-link btn-sm text-warning p-0 ms-auto" id="btn-fly-focus" title="Fokuskan Kamera">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="14" height="14" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" /><path d="M12 5l0 -2" /><path d="M12 19l0 2" /><path d="M5 12l-2 0" /><path d="M19 12l2 0" /></svg>
            Fly-To
          </button>
        </div>

        <!-- Mini Stats in Drawer -->
        <div class="row g-2 mt-2 pt-2 border-top border-dark">
          <div class="col-4 text-center">
            <div class="text-muted" style="font-size: 0.65rem;">RECORDS</div>
            <div id="drawer-stat-rows" class="fw-bold text-white">0</div>
          </div>
          <div class="col-4 text-center">
            <div class="text-muted" style="font-size: 0.65rem;">KOLOM</div>
            <div id="drawer-stat-cols" class="fw-bold text-cyan">0</div>
          </div>
          <div class="col-4 text-center">
            <div class="text-muted" style="font-size: 0.65rem;">RELASI</div>
            <div id="drawer-stat-rels" class="fw-bold text-warning">0</div>
          </div>
        </div>
      </div>

      <!-- Drawer Tabs -->
      <ul class="nav nav-tabs nav-fill bg-dark border-bottom border-dark" role="tablist" style="font-size: 0.75rem;">
        <li class="nav-item">
          <a class="nav-link active text-white py-2" data-bs-toggle="tab" href="#tab-columns" role="tab">
            Kolom (<span id="drawer-tab-col-count">0</span>)
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white py-2" data-bs-toggle="tab" href="#tab-relations" role="tab">
            Relasi (<span id="drawer-tab-rel-count">0</span>)
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white py-2" data-bs-toggle="tab" href="#tab-sample" role="tab" id="tab-sample-trigger">
            Sample Data
          </a>
        </li>
      </ul>

      <!-- Drawer Body Tabs Content -->
      <div class="drawer-body tab-content">
        <!-- Tab 1: Columns List -->
        <div class="tab-pane fade show active" id="tab-columns" role="tabpanel">
          <input type="text" id="filter-column-input" class="form-control form-control-sm bg-dark text-white border-dark mb-2" placeholder="Filter nama kolom...">
          <div id="columns-list" class="d-flex flex-column gap-1"></div>
        </div>

        <!-- Tab 2: Relationships List -->
        <div class="tab-pane fade" id="tab-relations" role="tabpanel">
          <div class="text-muted small fw-bold text-uppercase mb-1" style="font-size: 0.68rem;">Relasi Masuk (Referenced By)</div>
          <div id="incoming-relations-list" class="d-flex flex-column gap-1 mb-3"></div>

          <div class="text-muted small fw-bold text-uppercase mb-1" style="font-size: 0.68rem;">Relasi Keluar (References To)</div>
          <div id="outgoing-relations-list" class="d-flex flex-column gap-1"></div>
        </div>

        <!-- Tab 3: Data Sample -->
        <div class="tab-pane fade" id="tab-sample" role="tabpanel">
          <div id="sample-data-loading" class="text-center py-4">
            <div class="spinner-border spinner-border-sm text-warning mb-2" role="status"></div>
            <div class="text-muted small">Mengambil 5 baris sample data...</div>
          </div>
          <div id="sample-data-content" class="table-responsive" style="max-height: 400px; display: none;">
            <table class="table table-vcenter table-dark table-sm table-striped font-monospace" style="font-size: 0.7rem;" id="sample-data-table">
              <thead id="sample-data-head"></thead>
              <tbody id="sample-data-body"></tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<!-- Three.js and 3D Force Graph Library from CDN with fallbacks -->
<script src="https://unpkg.com/three@0.160.0/build/three.min.js"></script>
<script src="https://unpkg.com/three-spritetext@1.8.2/dist/three-spritetext.min.js"></script>
<script src="https://unpkg.com/3d-force-graph@1.73.3/dist/3d-force-graph.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const container = document.getElementById('graph-canvas');
  const loader = document.getElementById('schema-loader');
  const tooltip = document.getElementById('custom-tooltip');
  const drawer = document.getElementById('inspector-drawer');
  const searchInput = document.getElementById('search-input');
  const searchDropdown = document.getElementById('search-dropdown');

  let rawGraphData = { nodes: [], links: [] };
  let activeModule = 'all';
  let selectedNode = null;
  let highlightedNodes = new Set();
  let highlightedLinks = new Set();
  let isAutoRotating = false;
  let particleSpeed = 0.006;
  let currentLayout = '3d'; // '3d', 'sphere', '2d'

  // Initialize 3D Force Graph
  const Graph = ForceGraph3D()(container)
    .backgroundColor('rgba(0,0,0,0)')
    .showNavInfo(false)
    .nodeRelSize(5)
    .nodeVal(node => node.val || 20)
    .nodeColor(node => {
      if (highlightedNodes.size > 0) {
        return highlightedNodes.has(node.id) ? node.color : 'rgba(100, 116, 139, 0.2)';
      }
      return node.color;
    })
    .nodeResolution(24)
    // Custom Node 3D Object with Sphere & Floating 3D Text Label
    .nodeThreeObject(node => {
      const group = new THREE.Group();
      
      const isHighlighted = highlightedNodes.size === 0 || highlightedNodes.has(node.id);
      const isSelected = selectedNode && selectedNode.id === node.id;
      const baseColor = new THREE.Color(node.color || '#f59e0b');

      // 1. Central Core Sphere
      const sphereRadius = Math.max(3.5, Math.min(10, (node.val || 20) / 4));
      const sphereGeometry = new THREE.SphereGeometry(sphereRadius, 20, 20);
      
      const sphereMaterial = new THREE.MeshStandardMaterial({
        color: isHighlighted ? baseColor : new THREE.Color('#334155'),
        emissive: isHighlighted ? baseColor : new THREE.Color('#0f172a'),
        emissiveIntensity: isSelected ? 0.9 : (isHighlighted ? 0.45 : 0.05),
        roughness: 0.2,
        metalness: 0.8,
        transparent: true,
        opacity: isHighlighted ? 0.95 : 0.25
      });
      const sphere = new THREE.Mesh(sphereGeometry, sphereMaterial);
      group.add(sphere);

      // 2. Glowing Halo if Selected or Highlighted
      if (isSelected || (highlightedNodes.size > 0 && highlightedNodes.has(node.id))) {
        const haloGeometry = new THREE.RingGeometry(sphereRadius * 1.3, sphereRadius * 1.6, 24);
        const haloMaterial = new THREE.MeshBasicMaterial({
          color: baseColor,
          side: THREE.DoubleSide,
          transparent: true,
          opacity: isSelected ? 0.8 : 0.4
        });
        const halo = new THREE.Mesh(haloGeometry, haloMaterial);
        halo.lookAt(Graph.camera().position);
        group.add(halo);
      }

      // 3. Floating 3D Label Text (using SpriteText)
      if (typeof SpriteText !== 'undefined') {
        const sprite = new SpriteText(node.raw_name);
        sprite.color = isHighlighted ? '#ffffff' : 'rgba(148, 163, 184, 0.4)';
        sprite.textHeight = Math.max(3.2, sphereRadius * 0.7);
        sprite.backgroundColor = isHighlighted ? 'rgba(15, 23, 42, 0.75)' : 'transparent';
        sprite.borderColor = isHighlighted ? node.color : 'transparent';
        sprite.borderWidth = isHighlighted ? 0.8 : 0;
        sprite.borderRadius = 4;
        sprite.padding = [1.5, 3];
        sprite.fontFace = 'Inter, sans-serif';
        sprite.fontWeight = 'bold';
        sprite.position.y = sphereRadius + 4;
        group.add(sprite);
      }

      return group;
    })
    .nodeThreeObjectExtend(false)
    // Link Styling & Animated Particles
    .linkColor(link => {
      if (highlightedLinks.size > 0) {
        return highlightedLinks.has(link) ? link.color || '#f59e0b' : 'rgba(255,255,255,0.03)';
      }
      return link.color ? `${link.color}66` : 'rgba(245, 158, 11, 0.35)';
    })
    .linkWidth(link => (highlightedLinks.has(link) ? 2.5 : 1))
    .linkDirectionalParticles(link => (highlightedLinks.size > 0 && !highlightedLinks.has(link) ? 0 : 3))
    .linkDirectionalParticleWidth(link => (highlightedLinks.has(link) ? 3.5 : 2))
    .linkDirectionalParticleSpeed(particleSpeed)
    .linkDirectionalParticleColor(link => link.color || '#f59e0b')
    .linkCurvature(0.2)
    // Interaction Handlers
    .onNodeHover((node, prevNode) => {
      container.style.cursor = node ? 'pointer' : 'default';
      if (node) {
        tooltip.innerHTML = `
          <div class="fw-bold text-white" style="color: ${node.color} !important;">${node.name}</div>
          <div class="text-muted font-monospace small">${node.raw_name}</div>
          <div class="mt-1 small">
            <span class="badge badge-type">${node.columns_count} Kolom</span>
            <span class="badge badge-pk">${node.rows.toLocaleString()} Records</span>
            <span class="badge badge-fk">${node.connections_count} Relasi</span>
          </div>
        `;
        tooltip.style.display = 'block';
      } else {
        tooltip.style.display = 'none';
      }
    })
    .onNodeClick(node => {
      selectNode(node, true);
    })
    .onBackgroundClick(() => {
      clearSelection();
    });

  // Track mouse for custom tooltip
  container.addEventListener('mousemove', e => {
    if (tooltip.style.display === 'block') {
      const rect = container.getBoundingClientRect();
      tooltip.style.left = `${e.clientX - rect.left}px`;
      tooltip.style.top = `${e.clientY - rect.top}px`;
    }
  });

  // Setup Three.js Scene Lighting
  const scene = Graph.scene();
  const ambientLight = new THREE.AmbientLight(0xffffff, 0.8);
  scene.add(ambientLight);

  const dirLight1 = new THREE.DirectionalLight(0xf59e0b, 1.2);
  dirLight1.position.set(200, 300, 200);
  scene.add(dirLight1);

  const dirLight2 = new THREE.DirectionalLight(0x06b6d4, 0.8);
  dirLight2.position.set(-200, -200, -200);
  scene.add(dirLight2);

  // Setup Force Simulation Parameters
  Graph.d3Force('charge').strength(-250);
  Graph.d3Force('link').distance(70);

  // Fetch Data from Server
  fetch("{{ route('database-relations.data') }}")
    .then(res => res.json())
    .then(data => {
      if (!data.success) throw new Error('Data format invalid');
      rawGraphData = data;

      // Update HUD Stats
      document.getElementById('stat-tables').innerText = data.stats.total_tables;
      document.getElementById('stat-links').innerText = data.stats.total_links;
      document.getElementById('stat-columns').innerText = data.stats.total_columns.toLocaleString();
      document.getElementById('stat-rows').innerText = data.stats.total_rows.toLocaleString();
      document.getElementById('count-all').innerText = data.stats.total_tables;

      // Populate Module Filter Chips
      renderModuleChips(data.modules);

      // Render Graph
      renderGraph();

      // Fade out loader
      setTimeout(() => {
        loader.classList.add('fade-out');
        setTimeout(() => loader.remove(), 500);
      }, 400);
    })
    .catch(err => {
      console.error(err);
      loader.innerHTML = `
        <div class="text-danger fw-bold h4 mb-2">Gagal Memuat Data Skema</div>
        <div class="text-muted small mb-3">${err.message}</div>
        <button class="btn btn-warning btn-sm" onclick="location.reload()">Coba Lagi</button>
      `;
    });

  // Render Module Chips
  function renderModuleChips(modules) {
    const wrapper = document.getElementById('module-chips');
    modules.forEach(mod => {
      const chip = document.createElement('button');
      chip.className = 'module-chip';
      chip.dataset.module = mod.key;
      chip.style.setProperty('--chip-color', mod.color);
      chip.style.setProperty('--chip-glow', `${mod.color}66`);
      chip.innerHTML = `<span class="dot"></span> ${mod.name} (${mod.count})`;
      chip.addEventListener('click', () => {
        document.querySelectorAll('.module-chip').forEach(c => c.classList.remove('active'));
        chip.classList.add('active');
        filterByModule(mod.key);
      });
      wrapper.appendChild(chip);
    });

    document.querySelector('.module-chip[data-module="all"]').addEventListener('click', function () {
      document.querySelectorAll('.module-chip').forEach(c => c.classList.remove('active'));
      this.classList.add('active');
      filterByModule('all');
    });
  }

  // Filter Graph by Module
  function filterByModule(moduleKey) {
    activeModule = moduleKey;
    clearSelection();
    renderGraph();
  }

  // Render or Update Graph Data
  function renderGraph() {
    let filteredNodes = rawGraphData.nodes;
    if (activeModule !== 'all') {
      filteredNodes = rawGraphData.nodes.filter(n => n.module_key === activeModule);
    }
    const nodeIds = new Set(filteredNodes.map(n => n.id));
    const filteredLinks = rawGraphData.links.filter(l => {
      const srcId = typeof l.source === 'object' ? l.source.id : l.source;
      const tgtId = typeof l.target === 'object' ? l.target.id : l.target;
      return nodeIds.has(srcId) && nodeIds.has(tgtId);
    });

    Graph.graphData({
      nodes: filteredNodes,
      links: filteredLinks
    });

    // Adjust camera to fit all nodes
    setTimeout(() => {
      Graph.zoomToFit(800, 60);
    }, 500);
  }

  // Select Node and Fly Camera
  function selectNode(node, animateCamera = true) {
    selectedNode = node;
    highlightedNodes.clear();
    highlightedLinks.clear();

    if (node) {
      highlightedNodes.add(node.id);

      // Find all direct neighbors and links
      const currentLinks = Graph.graphData().links;
      currentLinks.forEach(link => {
        const srcId = typeof link.source === 'object' ? link.source.id : link.source;
        const tgtId = typeof link.target === 'object' ? link.target.id : link.target;

        if (srcId === node.id) {
          highlightedNodes.add(tgtId);
          highlightedLinks.add(link);
        } else if (tgtId === node.id) {
          highlightedNodes.add(srcId);
          highlightedLinks.add(link);
        }
      });

      // Fly camera smoothly to node
      if (animateCamera) {
        const distance = 90;
        const distRatio = 1 + distance / Math.hypot(node.x, node.y, node.z);
        Graph.cameraPosition(
          { x: node.x * distRatio, y: node.y * distRatio, z: node.z * distRatio },
          node,
          1200
        );
      }

      // Populate and Open Drawer
      openInspector(node);
    } else {
      closeInspector();
    }

    // Refresh node/link colors
    Graph.nodeColor(Graph.nodeColor())
         .linkColor(Graph.linkColor())
         .linkDirectionalParticles(Graph.linkDirectionalParticles());
  }

  function clearSelection() {
    selectedNode = null;
    highlightedNodes.clear();
    highlightedLinks.clear();
    closeInspector();
    Graph.nodeColor(Graph.nodeColor())
         .linkColor(Graph.linkColor())
         .linkDirectionalParticles(Graph.linkDirectionalParticles());
  }

  // Populate Inspector Drawer
  function openInspector(node) {
    document.getElementById('drawer-table-name').innerText = node.name;
    document.getElementById('drawer-raw-name').innerText = node.raw_name;
    const modBadge = document.getElementById('drawer-module-badge');
    modBadge.innerText = node.module_name;
    modBadge.style.backgroundColor = node.badge_bg;
    modBadge.style.color = node.color;

    document.getElementById('drawer-stat-rows').innerText = node.rows.toLocaleString();
    document.getElementById('drawer-stat-cols').innerText = node.columns_count;
    document.getElementById('drawer-stat-rels').innerText = node.connections_count;

    document.getElementById('drawer-tab-col-count').innerText = node.columns_count;

    // Render Columns
    renderColumnsList(node.columns);

    // Render Relations
    renderRelationsList(node);

    // Reset Sample Data
    document.getElementById('sample-data-loading').style.display = 'block';
    document.getElementById('sample-data-content').style.display = 'none';

    drawer.classList.add('open');
  }

  function closeInspector() {
    drawer.classList.remove('open');
  }

  document.getElementById('btn-close-drawer').addEventListener('click', clearSelection);

  // Render Columns in Drawer
  function renderColumnsList(columns) {
    const list = document.getElementById('columns-list');
    list.innerHTML = '';

    columns.forEach(col => {
      const item = document.createElement('div');
      item.className = 'p-2 rounded bg-dark border border-dark d-flex align-items-center justify-content-between';
      item.dataset.colName = col.name.toLowerCase();

      let badgesHtml = '';
      if (col.is_pk) badgesHtml += '<span class="badge badge-pk me-1">PK</span>';
      if (col.is_fk) badgesHtml += '<span class="badge badge-fk me-1">FK</span>';
      badgesHtml += `<span class="badge badge-type">${col.type}</span>`;

      item.innerHTML = `
        <div class="d-flex flex-column">
          <span class="text-white fw-bold small font-monospace">${col.name}</span>
          <span class="text-muted" style="font-size: 0.65rem;">${col.nullable ? 'Nullable' : 'Not Null'}${col.default ? ' | Default: ' + col.default : ''}</span>
        </div>
        <div class="d-flex align-items-center">${badgesHtml}</div>
      `;
      list.appendChild(item);
    });
  }

  // Filter columns input
  document.getElementById('filter-column-input').addEventListener('input', function (e) {
    const q = e.target.value.toLowerCase().trim();
    document.querySelectorAll('#columns-list > div').forEach(el => {
      el.style.display = el.dataset.colName.includes(q) ? 'flex' : 'none';
    });
  });

  // Render Relations in Drawer
  function renderRelationsList(node) {
    const inList = document.getElementById('incoming-relations-list');
    const outList = document.getElementById('outgoing-relations-list');
    inList.innerHTML = '';
    outList.innerHTML = '';

    let relCount = 0;
    const allLinks = rawGraphData.links;

    allLinks.forEach(link => {
      const srcId = typeof link.source === 'object' ? link.source.id : link.source;
      const tgtId = typeof link.target === 'object' ? link.target.id : link.target;

      if (tgtId === node.id) {
        relCount++;
        const card = createRelationCard(srcId, link.foreign_key, 'incoming');
        inList.appendChild(card);
      } else if (srcId === node.id) {
        relCount++;
        const card = createRelationCard(tgtId, link.foreign_key, 'outgoing');
        outList.appendChild(card);
      }
    });

    if (inList.children.length === 0) {
      inList.innerHTML = '<span class="text-muted small fst-italic">Tidak ada relasi masuk</span>';
    }
    if (outList.children.length === 0) {
      outList.innerHTML = '<span class="text-muted small fst-italic">Tidak ada relasi keluar</span>';
    }

    document.getElementById('drawer-tab-rel-count').innerText = relCount;
  }

  function createRelationCard(targetTableId, fkColumn, direction) {
    const targetNode = rawGraphData.nodes.find(n => n.id === targetTableId);
    const card = document.createElement('div');
    card.className = 'p-2 rounded bg-dark border border-dark d-flex align-items-center justify-content-between';
    card.style.cursor = 'pointer';

    const color = targetNode ? targetNode.color : '#f59e0b';
    const name = targetNode ? targetNode.name : targetTableId;

    card.innerHTML = `
      <div class="d-flex align-items-center gap-2">
        <span style="width: 8px; height: 8px; border-radius: 50%; background-color: ${color};"></span>
        <div class="d-flex flex-column">
          <span class="text-white fw-bold small">${name}</span>
          <span class="text-muted font-monospace" style="font-size: 0.65rem;">via ${fkColumn}</span>
        </div>
      </div>
      <svg xmlns="http://www.w3.org/2000/svg" class="icon text-muted" width="14" height="14" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l14 0" /><path d="M15 16l4 -4" /><path d="M15 8l4 4" /></svg>
    `;

    card.addEventListener('click', () => {
      if (targetNode) {
        selectNode(targetNode, true);
      }
    });

    return card;
  }

  // Sample Data Tab Trigger
  document.getElementById('tab-sample-trigger').addEventListener('click', function () {
    if (!selectedNode) return;
    const url = "{{ url('database-relations/sample') }}/" + selectedNode.id;

    fetch(url)
      .then(res => res.json())
      .then(data => {
        document.getElementById('sample-data-loading').style.display = 'none';
        const content = document.getElementById('sample-data-content');
        const thead = document.getElementById('sample-data-head');
        const tbody = document.getElementById('sample-data-body');

        if (data.error || !data.rows || data.rows.length === 0) {
          thead.innerHTML = '';
          tbody.innerHTML = '<tr><td class="text-muted text-center py-3">Tabel ini belum memiliki data atau kosong.</td></tr>';
          content.style.display = 'block';
          return;
        }

        // Headers
        const cols = Object.keys(data.rows[0]);
        thead.innerHTML = '<tr>' + cols.map(c => `<th class="text-warning">${c}</th>`).join('') + '</tr>';

        // Rows
        tbody.innerHTML = data.rows.map(row => {
          return '<tr>' + cols.map(c => {
            const val = row[c];
            return `<td>${val === null ? '<span class="text-muted">NULL</span>' : String(val).substring(0, 30)}</td>`;
          }).join('') + '</tr>';
        }).join('');

        content.style.display = 'block';
      })
      .catch(err => {
        document.getElementById('sample-data-loading').innerHTML = `<span class="text-danger small">${err.message}</span>`;
      });
  });

  // Search & Autocomplete
  searchInput.addEventListener('input', function (e) {
    const q = e.target.value.toLowerCase().trim();
    if (!q) {
      searchDropdown.classList.remove('show');
      searchDropdown.innerHTML = '';
      return;
    }

    const matches = rawGraphData.nodes.filter(n => {
      const matchTable = n.raw_name.toLowerCase().includes(q) || n.name.toLowerCase().includes(q);
      const matchCol = n.columns.some(c => c.name.toLowerCase().includes(q));
      return matchTable || matchCol;
    }).slice(0, 10);

    if (matches.length === 0) {
      searchDropdown.innerHTML = '<div class="p-2 text-muted small text-center">Tidak ditemukan tabel yang cocok</div>';
      searchDropdown.classList.add('show');
      return;
    }

    searchDropdown.innerHTML = matches.map(m => {
      return `
        <div class="search-item" data-id="${m.id}">
          <div class="d-flex align-items-center justify-content-between">
            <span class="text-white fw-bold small">${m.name}</span>
            <span class="badge" style="background-color: ${m.badge_bg}; color: ${m.color};">${m.module_name}</span>
          </div>
          <div class="text-muted font-monospace" style="font-size: 0.65rem;">${m.raw_name} • ${m.columns_count} kolom</div>
        </div>
      `;
    }).join('');

    searchDropdown.classList.add('show');

    searchDropdown.querySelectorAll('.search-item').forEach(item => {
      item.addEventListener('click', () => {
        const targetNode = rawGraphData.nodes.find(n => n.id === item.dataset.id);
        if (targetNode) {
          selectNode(targetNode, true);
          searchDropdown.classList.remove('show');
          searchInput.value = targetNode.raw_name;
        }
      });
    });
  });

  document.addEventListener('click', e => {
    if (!searchInput.contains(e.target) && !searchDropdown.contains(e.target)) {
      searchDropdown.classList.remove('show');
    }
  });

  // Reset Camera
  document.getElementById('btn-reset-cam').addEventListener('click', () => {
    Graph.zoomToFit(1000, 60);
  });

  document.getElementById('btn-fly-focus').addEventListener('click', () => {
    if (selectedNode) selectNode(selectedNode, true);
  });

  // Auto-Rotate Mode
  document.getElementById('btn-autorotate').addEventListener('click', function () {
    isAutoRotating = !isAutoRotating;
    document.getElementById('rotate-status').innerText = `Auto Rotate: ${isAutoRotating ? 'ON' : 'OFF'}`;
    this.classList.toggle('btn-warning', isAutoRotating);
    this.classList.toggle('btn-dark', !isAutoRotating);

    if (isAutoRotating) {
      let angle = 0;
      const rotateInterval = setInterval(() => {
        if (!isAutoRotating) {
          clearInterval(rotateInterval);
          return;
        }
        angle += Math.PI / 600;
        const distance = 400;
        Graph.cameraPosition({
          x: distance * Math.sin(angle),
          z: distance * Math.cos(angle)
        });
      }, 30);
    }
  });

  // Toggle Particles
  let particlesOn = true;
  document.getElementById('btn-particles').addEventListener('click', function () {
    particlesOn = !particlesOn;
    this.innerText = `Partikel FK: ${particlesOn ? 'ON' : 'OFF'}`;
    Graph.linkDirectionalParticles(link => (particlesOn ? (highlightedLinks.size > 0 && !highlightedLinks.has(link) ? 0 : 3) : 0));
  });

  // Layout Switcher (3D Galaxy, 3D Sphere, 2D Flat)
  document.getElementById('btn-layout-3d').addEventListener('click', function () {
    setLayout('3d');
  });
  document.getElementById('btn-layout-sphere').addEventListener('click', function () {
    setLayout('sphere');
  });
  document.getElementById('btn-layout-2d').addEventListener('click', function () {
    setLayout('2d');
  });

  function setLayout(mode) {
    currentLayout = mode;
    ['btn-layout-3d', 'btn-layout-sphere', 'btn-layout-2d'].forEach(id => {
      document.getElementById(id).classList.remove('text-warning', 'active');
      document.getElementById(id).classList.add('text-muted');
    });

    if (mode === '3d') {
      document.getElementById('btn-layout-3d').classList.add('text-warning', 'active');
      Graph.numDimensions(3);
      Graph.d3Force('charge').strength(-250);
      Graph.d3ReheatSimulation();
    } else if (mode === 'sphere') {
      document.getElementById('btn-layout-sphere').classList.add('text-warning', 'active');
      // Arrange in spherical shell
      const nodes = Graph.graphData().nodes;
      const count = nodes.length;
      const radius = 220;
      nodes.forEach((n, i) => {
        const phi = Math.acos(-1 + (2 * i) / count);
        const theta = Math.sqrt(count * Math.PI) * phi;
        n.fx = radius * Math.cos(theta) * Math.sin(phi);
        n.fy = radius * Math.sin(theta) * Math.sin(phi);
        n.fz = radius * Math.cos(phi);
      });
      Graph.numDimensions(3);
    } else if (mode === '2d') {
      document.getElementById('btn-layout-2d').classList.add('text-warning', 'active');
      const nodes = Graph.graphData().nodes;
      nodes.forEach(n => {
        n.fz = 0;
        n.z = 0;
      });
      Graph.numDimensions(2);
      Graph.d3ReheatSimulation();
    }
  }

  // Fullscreen
  document.getElementById('btn-fullscreen').addEventListener('click', () => {
    const el = document.getElementById('schema-container');
    if (!document.fullscreenElement) {
      el.requestFullscreen().catch(err => alert(err.message));
    } else {
      document.exitFullscreen();
    }
  });

  // Export Screenshot PNG
  document.getElementById('btn-export-png').addEventListener('click', () => {
    const renderer = Graph.renderer();
    if (renderer) {
      const imgURI = renderer.domElement.toDataURL('image/png');
      const a = document.createElement('a');
      a.download = `CMMS_Aisfar_Database_Schema_3D_${new Date().toISOString().slice(0,10)}.png`;
      a.href = imgURI;
      a.click();
    }
  });

  // Window Resize
  window.addEventListener('resize', () => {
    Graph.width(container.clientWidth);
    Graph.height(container.clientHeight);
  });
});
</script>
@endpush
