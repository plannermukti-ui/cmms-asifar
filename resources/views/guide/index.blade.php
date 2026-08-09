@extends('layouts.tabler', ['hideSidebar' => true])

@section('title', 'Pusat Bantuan & Documentation Hub - CMMS Aisfar')

@section('content')
<style>
  .guide-wrapper {
    background-color: var(--tblr-bg-surface-secondary);
    min-height: calc(100vh - 60px);
  }
  .guide-sidebar {
    position: sticky;
    top: 5rem;
    height: calc(100vh - 6rem);
    overflow-y: auto;
    padding-right: 0.75rem;
  }
  .guide-nav-link {
    display: flex;
    align-items: center;
    padding: 0.6rem 0.85rem;
    color: var(--tblr-body-color);
    text-decoration: none;
    border-radius: var(--tblr-border-radius);
    margin-bottom: 0.35rem;
    font-weight: 500;
    font-size: 0.88rem;
    transition: all 0.2s ease-in-out;
  }
  .guide-nav-link:hover {
    background-color: var(--tblr-primary-lt);
    color: var(--tblr-primary);
    transform: translateX(3px);
  }
  .guide-nav-link.active {
    background-color: var(--tblr-primary);
    color: #fff;
    font-weight: 600;
    box-shadow: 0 4px 8px rgba(32, 107, 196, 0.25);
  }
  .guide-nav-link .icon {
    margin-right: 0.75rem;
    width: 20px;
    height: 20px;
  }
  .guide-section {
    display: none;
    animation: fadeIn 0.35s ease-in-out;
  }
  .guide-section.active {
    display: block;
  }
  @keyframes fadeIn {
    from { opacity: 0; transform: translateY(12px); }
    to { opacity: 1; transform: translateY(0); }
  }
  .step-box {
    border-left: 3px solid var(--tblr-primary);
    padding-left: 1.5rem;
    position: relative;
    margin-bottom: 2rem;
  }
  .step-box::before {
    content: attr(data-step);
    position: absolute;
    left: -18px;
    top: 0;
    width: 34px;
    height: 34px;
    background-color: var(--tblr-primary);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    box-shadow: 0 0 0 4px var(--tblr-bg-surface);
  }
  .feature-icon-box {
    width: 52px;
    height: 52px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 12px;
    margin-bottom: 1rem;
  }
  .content-card {
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    border: 1px solid var(--tblr-border-color);
    border-radius: 10px;
    background: var(--tblr-bg-surface);
    padding: 2.25rem;
    margin-bottom: 2rem;
  }
  .content-title {
    font-size: 1.6rem;
    font-weight: 800;
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid var(--tblr-border-color-light);
    color: var(--tblr-primary);
  }
  .badge-tech {
    font-size: 0.75rem;
    padding: 0.35rem 0.65rem;
    border-radius: 6px;
  }
</style>

<div class="guide-wrapper py-4">
  <div class="container-xl">
    
    <!-- Top Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
      <div>
        <h1 class="text-primary mb-1 d-flex align-items-center fw-extrabold">
          <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-shield-check me-2 text-primary" width="36" height="36" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M11.46 20.846a12 12 0 0 1 -7.96 -14.846a12 12 0 0 0 8.5 -3a12 12 0 0 0 8.5 3a12 12 0 0 1 -1.116 9.376" /><path d="M15 12l2 2l4 -4" /></svg>
          Pusat Dokumentasi & Bantuan Sistem CMMS AISFAR
        </h1>
        <p class="text-muted mb-0 fs-3">Panduan komprehensif Operasional Pemeliharaan Aset (*Maintenance*), Manajemen Produksi Tambang (*Fleet Management*), K3/HSE, serta Analitik Finansial berbasis Standar Internasional.</p>
      </div>
      <a href="{{ route('dashboard') }}" class="btn btn-primary d-print-none shadow-sm fw-bold">
        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrow-left me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l14 0" /><path d="M5 12l6 6" /><path d="M5 12l6 -6" /></svg>
        Kembali ke Dashboard
      </a>
    </div>

    <div class="row">
      <!-- Sidebar Navigation -->
      <div class="col-lg-3 d-none d-lg-block">
        <div class="guide-sidebar">
          
          <div class="text-muted small fw-bold text-uppercase mb-2 px-2">Memulai</div>
          <a href="#quick-start" class="guide-nav-link active" data-target="quick-start">
            <svg class="icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M13 10l7.383 7.418c.823 .82 .823 2.148 0 2.967a2.11 2.11 0 0 1 -2.976 0l-7.407 -7.385" /><path d="M6 9l4 4" /><path d="M13 10l-4 -4" /><path d="M3 21l3 -3" /><path d="M7 5l7.943 -7.924a2.11 2.11 0 0 1 2.976 0c.823 .819 .823 2.147 0 2.967l-7.919 7.957" /></svg>
            Langkah Pertama
          </a>
          
          <div class="text-muted small fw-bold text-uppercase mt-4 mb-2 px-2">Engineer & Produksi</div>
          <a href="#production" class="guide-nav-link" data-target="production">
            <svg class="icon text-azure" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 4l-8 4l8 4l8 -4l-8 -4" /><path d="M4 12l8 4l8 -4" /><path d="M4 16l8 4l8 -4" /></svg>
            Laporan Produksi Harian (Fleet)
          </a>
          <a href="#master-units" class="guide-nav-link" data-target="master-units">
            <svg class="icon text-primary" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><rect x="4" y="4" width="16" height="16" rx="2" /><line x1="9" y1="12" x2="15" y2="12" /></svg>
            Populasi Unit & 360° Asset History
          </a>
          
          <div class="text-muted small fw-bold text-uppercase mt-4 mb-2 px-2">Modul Maintenance</div>
          <a href="#work-order" class="guide-nav-link" data-target="work-order">
            <svg class="icon text-blue" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2" /><path d="M9 3m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z" /><path d="M9 14h.01" /><path d="M13 14h.01" /><path d="M17 14h.01" /><path d="M9 17h.01" /><path d="M13 17h.01" /><path d="M17 17h.01" /></svg>
            Work Order (WO) & Multi-Category
          </a>
          <a href="#kpi-iso" class="guide-nav-link" data-target="kpi-iso">
            <svg class="icon text-warning" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><rect x="4" y="5" width="16" height="16" rx="2" /><line x1="16" y1="3" x2="16" y2="7" /><line x1="8" y1="3" x2="8" y2="7" /><line x1="4" y1="11" x2="20" y2="11" /></svg>
            Analitik KPI & ISO 8601
          </a>
          <a href="#far" class="guide-nav-link" data-target="far">
            <svg class="icon text-danger" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 20h4l10.5 -10.5a1.5 1.5 0 0 0 -4 -4l-10.5 10.5v4" /><path d="M13.5 6.5l4 4" /></svg>
            Laporan Kerusakan (FAR)
          </a>
          <a href="#jwo" class="guide-nav-link" data-target="jwo">
            <svg class="icon text-cyan" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 4h6a2 2 0 0 1 2 2v14l-5 -3l-5 3v-14a2 2 0 0 1 2 -2" /></svg>
            Job Work Order (JWO) & Vendor
          </a>
          <a href="#hse" class="guide-nav-link" data-target="hse">
            <svg class="icon text-success" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 3a12 12 0 0 0 8.5 3a12 12 0 0 1 -8.5 15a12 12 0 0 1 -8.5 -15a12 12 0 0 0 8.5 -3" /></svg>
            Keselamatan K3 (JSA, PTW, LOTO)
          </a>
          <a href="#budget" class="guide-nav-link" data-target="budget">
            <svg class="icon text-yellow" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 8v-3a1 1 0 0 0 -1 -1h-10a2 2 0 0 0 0 4h12a1 1 0 0 1 1 1v3m0 4v3a1 1 0 0 1 -1 1h-12a2 2 0 0 1 -2 -2v-12" /><path d="M20 12v4h-4a2 2 0 0 1 0 -4h4" /></svg>
            Perencanaan Budget & Cost
          </a>
          <a href="#pm" class="guide-nav-link" data-target="pm">
            <svg class="icon text-indigo" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 8v4l3 3" /><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /></svg>
            Preventive Maintenance (PM)
          </a>
          <a href="#toolroom" class="guide-nav-link" data-target="toolroom">
            <svg class="icon text-orange" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 10h3v-3l-3 3z" /><path d="M10 14v-4h-4l4 4z" /><path d="M14 4a1 1 0 0 1 1 1v2a1 1 0 0 1 -1 1h-2a1 1 0 0 1 -1 -1v-2a1 1 0 0 1 1 -1h2z" /><path d="M17 14h3v-3l-3 3z" /></svg>
            ToolRoom & Stok Part
          </a>
          
          <div class="text-muted small fw-bold text-uppercase mt-4 mb-2 px-2">Fitur Tambahan</div>
          <a href="#collaboration" class="guide-nav-link" data-target="collaboration">
            <svg class="icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 9h8" /><path d="M8 13h6" /><path d="M18 4a3 3 0 0 1 3 3v8a3 3 0 0 1 -3 3h-5l-5 3v-3h-2a3 3 0 0 1 -3 -3v-8a3 3 0 0 1 3 -3h12z" /></svg>
            Live Chat & Kolaborasi
          </a>
          <a href="#signatures" class="guide-nav-link" data-target="signatures">
            <svg class="icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 19c3.333 -2 5 -4 5 -6c0 -3 -1 -3 -2 -3s-2.032 1.087 -2 3c.034 2.048 1.658 2.877 2.5 4c1.5 2 2.5 2.5 3.5 1c.667 -1 1.167 -1.833 1.5 -2.5c1 -2.333 2.333 -3.5 4 -3.5h.5" /><path d="M20 17v-12c0 -1.121 -.879 -2 -2 -2s-2 .879 -2 2v12l2 2l2 -2z" /><path d="M16 7h4" /></svg>
            Tanda Tangan Digital
          </a>
          <a href="#admin-backup" class="guide-nav-link" data-target="admin-backup">
            <svg class="icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2" /><path d="M7 11l5 5l5 -5" /><path d="M12 4l0 12" /></svg>
            Backup & Administrasi
          </a>

          <div class="text-muted small fw-bold text-uppercase mt-4 mb-2 px-2">Keamanan & Teknologi</div>
          <a href="#architecture" class="guide-nav-link" data-target="architecture">
            <svg class="icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 14v-3a6 6 0 0 1 6 -6h4" /><path d="M10 9l4 -4l-4 -4" /><path d="M20 10v3a6 6 0 0 1 -6 6h-4" /><path d="M14 23l-4 -4l4 -4" /></svg>
            Arsitektur & Teknologi Enterprise
          </a>
        </div>
      </div>

      <!-- Main Content Area -->
      <div class="col-lg-9">
        
        <!-- SECTION 1: QUICK START -->
        <div id="quick-start" class="guide-section active">
          <div class="content-card">
            <h2 class="content-title">Langkah Pertama Menggunakan Sistem</h2>
            <p class="mb-4 fs-3">Selamat datang di sistem <strong>CMMS AISFAR</strong>! Bagian ini akan membantu Anda memahami cara memulai menggunakan aplikasi CMMS secara efisien dari hari pertama Anda bergabung.</p>
            
            <div class="step-box" data-step="1">
              <h4 class="fw-bold text-primary mb-2">Lengkapi Profil Anda</h4>
              <p class="text-muted mb-2">Langkah pertama yang sangat penting adalah memastikan identitas Anda jelas bagi rekan tim lain.</p>
              <ul class="text-muted mb-0">
                <li>Klik nama Anda di pojok kanan atas, pilih <strong>Profil Saya</strong>.</li>
                <li>Unggah <strong>Foto Profil</strong> Anda (wajah terlihat jelas).</li>
                <li>Lengkapi nomor <strong>WhatsApp</strong> agar mudah dihubungi saat ada WO darurat.</li>
              </ul>
            </div>

            <div class="step-box" data-step="2">
              <h4 class="fw-bold text-primary mb-2">Pahami Peran (Role) Anda</h4>
              <p class="text-muted mb-2">Tampilan menu di sebelah kiri (Sidebar) menyesuaikan dengan peran Anda:</p>
              <div class="row g-2 mt-2">
                <div class="col-sm-4">
                  <div class="card p-3 bg-primary-lt border-0 h-100">
                    <strong>👷‍♂️ Mekanik / Operator</strong><br>
                    <small>Fokus pada menu Work Order, JWO, Laporan Kerusakan, dan Input Hour Meter.</small>
                  </div>
                </div>
                <div class="col-sm-4">
                  <div class="card p-3 bg-azure-lt border-0 h-100">
                    <strong>🚜 Engineer & Production</strong><br>
                    <small>Pencatatan Laporan Produksi Harian (Fleet Digger & Hauler) dan Monitoring Unit.</small>
                  </div>
                </div>
                <div class="col-sm-4">
                  <div class="card p-3 bg-success-lt border-0 h-100">
                    <strong>👨‍💼 Supervisor / Admin</strong><br>
                    <small>Dapat melihat Approval, Master Data (Parts, Unit), dan Pengaturan Sistem.</small>
                  </div>
                </div>
              </div>
            </div>

            <div class="step-box" data-step="3" style="border-left-color: transparent;">
              <h4 class="fw-bold text-primary mb-2">Membaca Notifikasi & Pesan</h4>
              <p class="text-muted mb-0">
                Setiap kali Anda ditugaskan pada sebuah Work Order, atau dokumen Anda disetujui, Anda akan menerima notifikasi di ikon lonceng (kanan atas). Jangan abaikan ikon ini. Selain itu, periksa <strong>Live Chat</strong> jika ada pesan langsung dari tim.
              </p>
            </div>
          </div>
        </div>

        <!-- NEW SECTION: PRODUCTION REPORT (FLEET MANAGEMENT) -->
        <div id="production" class="guide-section">
          <div class="content-card border-top-0 border-start-0 border-end-0 border-bottom-0" style="border-top: 4px solid var(--tblr-azure) !important; border-radius: 8px;">
            <h2 class="content-title text-azure">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="32" height="32" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 4l-8 4l8 4l8 -4l-8 -4" /><path d="M4 12l8 4l8 -4" /><path d="M4 16l8 4l8 -4" /></svg>
              Laporan Produksi Harian (Fleet & Pit Management)
            </h2>
            <p class="fs-4 text-muted mb-4">Modul khusus bagi divisi <strong>Engineer & Produksi Pertambangan</strong> untuk mencatat aktivitas operasional pemindahan tanah (*Overburden*) dan penambangan batubara (*Coal Mining*) secara terstruktur dan terintegrasi.</p>

            <div class="row g-3 mb-4">
              <div class="col-md-6">
                <div class="card p-3 h-100 bg-azure-lt border-0">
                  <h4 class="fw-bold text-azure mb-2">1. Konsep Satu Form per Shift (Multi-Fleet)</h4>
                  <p class="small text-dark mb-0">Dispatcher tidak perlu membuat laporan berkali-kali. Cukup buka 1 Form Laporan Produksi untuk **Day Shift (DS)** atau **Night Shift (NS)**, lalu tambahkan sebanyak mungkin Digger/Fleet beserta barisan unit Dump Truck (Hauler) yang bertugas di Fleet tersebut.</p>
                </div>
              </div>
              <div class="col-md-6">
                <div class="card p-3 h-100 bg-azure-lt border-0">
                  <h4 class="fw-bold text-azure mb-2">2. Wajib Input Ritasi Per Jam (Jam 1 - 12)</h4>
                  <p class="small text-dark mb-0">Untuk memastikan keakuratan performa operasional, ritasi tiap Dump Truck wajib diisi per jam operasional (Jam ke-1 s/d Jam ke-12). Sistem secara otomatis mengalikan Ritasi dengan *Payload* (Kapasitas Ton/BCM) untuk menghitung total tonase produksi.</p>
                </div>
              </div>
              <div class="col-md-6">
                <div class="card p-3 h-100 bg-azure-lt border-0">
                  <h4 class="fw-bold text-azure mb-2">3. Penanganan Jenis Material Tambang</h4>
                  <p class="small text-dark mb-0">Mendukung berbagai jenis material tambang yang umum di lapangan, seperti: <strong>OB (Overburden)</strong>, <strong>Coal (Batubara)</strong>, <strong>Top Soil</strong>, <strong>Mud (Lumpur)</strong>, <strong>Sub Soil</strong>, dan <strong>Waste</strong> lengkap dengan jarak angkut (KM).</p>
                </div>
              </div>
              <div class="col-md-6">
                <div class="card p-3 h-100 bg-azure-lt border-0">
                  <h4 class="fw-bold text-azure mb-2">4. Alat Support & Delay Management</h4>
                  <p class="small text-dark mb-0">Pencatatan HM Awal & HM Akhir untuk unit Support (Dozer, Grader, Compactor) serta pencatatan kendala (Delay/Standby) seperti Hujan (Rain), Licin (Slippery), atau Breakdown yang dapat diarahkan ke Fleet tertentu.</p>
                </div>
              </div>
            </div>

            <div class="alert alert-info">
              <strong>🖨️ Format Cetak Laporan Resmi:</strong> Laporan Produksi Harian dilengkapi tampilan cetak PDF/Print ber-Kop Surat Perusahaan, ringkasan 4 indikator metrik eksekutif, serta 3 kolom Lembar Pengesahan (*Dispatcher*, *Supervisor*, dan *Superintendent Produksi*).
            </div>
          </div>
        </div>

        <!-- NEW SECTION: MASTER UNITS & 360 ASSET HISTORY -->
        <div id="master-units" class="guide-section">
          <div class="content-card border-top-0 border-start-0 border-end-0 border-bottom-0" style="border-top: 4px solid var(--tblr-primary) !important; border-radius: 8px;">
            <h2 class="content-title text-primary">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="32" height="32" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><rect x="4" y="4" width="16" height="16" rx="2" /><line x1="9" y1="12" x2="15" y2="12" /></svg>
              Populasi Asset Unit & 360° History Tracking
            </h2>
            <p class="fs-4 text-muted mb-4">Modul pengelolaan aset utama perusahaan (*Heavy Equipment & Fleet Asset*). Setiap unit memiliki halaman **Detail & Histori 360 Derajat** yang merekam seluruh riwayat hidup alat sejak pertama kali diterima hingga saat ini.</p>

            <div class="row g-3 mb-4">
              <div class="col-md-4">
                <div class="border rounded p-3 h-100 bg-body-tertiary">
                  <h4 class="fw-bold text-primary mb-2">Spesifikasi Lengkap Unit</h4>
                  <p class="small text-muted mb-0">Merekam S/N Chassis, Engine Make/Model, S/N Engine, Power (HP/KW), Tahun Perakitan, Capacity, No Polisi, Status Keaktifan (*Active/Inactive/In Service*), dan Site Lokasi Kerja.</p>
                </div>
              </div>
              <div class="col-md-4">
                <div class="border rounded p-3 h-100 bg-body-tertiary">
                  <h4 class="fw-bold text-danger mb-2">Total Cost Maintenance (Biaya)</h4>
                  <p class="small text-muted mb-0">Kalkulasi otomatis pengeluaran finansial perbaikan unit dari referensi Work Order & JWO Vendor (Rp). Manajer dapat memantau unit mana yang boros biaya pemeliharaan.</p>
                </div>
              </div>
              <div class="col-md-4">
                <div class="border rounded p-3 h-100 bg-body-tertiary">
                  <h4 class="fw-bold text-success mb-2">5 Tab Histori Riwayat</h4>
                  <p class="small text-muted mb-0">Tabbed Navigation yang mengelompokkan Riwayat Work Order, Riwayat Produksi Tambang (Digger/Hauler/Support), FAR & JWO, PM & Hour Meter, serta Log Audit Aktivitas Perubahan Data.</p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- NEW SECTION: KPI ANALYTICS & ISO 8601 -->
        <div id="kpi-iso" class="guide-section">
          <div class="content-card border-top-0 border-start-0 border-end-0 border-bottom-0" style="border-top: 4px solid var(--tblr-warning) !important; border-radius: 8px;">
            <h2 class="content-title text-warning">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="32" height="32" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><rect x="4" y="5" width="16" height="16" rx="2" /><line x1="16" y1="3" x2="16" y2="7" /><line x1="4" y1="11" x2="20" y2="11" /></svg>
              Analitik KPI & Filter Berstandar ISO 8601
            </h2>
            <p class="fs-4 text-muted mb-4">Modul analisis Master Data KPI untuk memantau reliabilitas alat berat, waktu *breakdown*, dan durasi perbaikan dengan standar kalender internasional.</p>

            <div class="row g-4 mb-4">
              <div class="col-md-6">
                <div class="card p-3 h-100 bg-warning-lt border-0">
                  <h4 class="fw-bold text-warning mb-2">📅 Standar ISO 8601 (Nomor Minggu Pertambangan)</h4>
                  <p class="small text-dark mb-0">Sesuai standar ISO 8601, satu minggu kerja dihitung secara konsisten dari **Senin hingga Minggu**. Sistem menyediakan filter **Week Sekian (Minggu Ke-X)** yang secara otomatis menyesuaikan Range Date Waktu BD secara instan tanpa perlu memilih tanggal manual satu per satu.</p>
                </div>
              </div>
              <div class="col-md-6">
                <div class="card p-3 h-100 bg-warning-lt border-0">
                  <h4 class="fw-bold text-warning mb-2">📊 Clear Filter & Export Excel Profesional</h4>
                  <p class="small text-dark mb-0">Dilengkapi tombol **Clear Filter** sekali klik untuk mengembalikan pencarian ke kondisi semula, serta fasilitas **Download Excel** berformat tabel rapi yang memuat seluruh relasi data (No WO, Status, Tipe, Breakdown Type, Component Group, Kategori Level 1-5, Durasi BD, dan Deskripsi Problem).</p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- SECTION 2: WORK ORDER -->
        <div id="work-order" class="guide-section">
          <div class="content-card">
            <div class="row align-items-center mb-4">
              <div class="col-md-8">
                <h2 class="content-title">Manajemen Work Order (WO) & Multi-Category</h2>
                <p class="fs-4 text-muted mb-0">Work Order (Perintah Kerja) adalah jantung dari sistem pemeliharaan. Semua aktivitas perbaikan harus didasari oleh sebuah WO.</p>
              </div>
              <div class="col-md-4 text-center text-md-end mt-3 mt-md-0">
                <img src="{{ asset('guide-images/mechanic_wo.png') }}" class="img-fluid rounded shadow-sm border" alt="Mechanic Work Order" style="max-height: 180px; object-fit: cover;">
              </div>
            </div>
            <div class="row mb-4">
              <div class="col-md-4 text-center mb-3">
                <div class="feature-icon-box bg-blue-lt mx-auto">
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon text-blue" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2" /><path d="M9 3m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z" /><path d="M9 14l2 2l4 -4" /></svg>
                </div>
                <h4 class="fw-bold">Buat WO & Kategori 1-5</h4>
                <p class="text-muted small">Catat masalah unit, lokasi, serta pilih kategorisasi bertingkat dari Level 1 (Sistem Utama) hingga Level 5 (Detail Komponen).</p>
              </div>
              <div class="col-md-4 text-center mb-3">
                <div class="feature-icon-box bg-orange-lt mx-auto">
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon text-orange" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 4l-8 4l8 4l8 -4l-8 -4" /><path d="M4 12l8 4l8 -4" /><path d="M4 16l8 4l8 -4" /></svg>
                </div>
                <h4 class="fw-bold">Alokasi & Eksekusi</h4>
                <p class="text-muted small">Tugaskan mekanik (*Manpower*), catat alat yang dipakai, dan sparepart yang diganti.</p>
              </div>
              <div class="col-md-4 text-center mb-3">
                <div class="feature-icon-box bg-green-lt mx-auto">
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon text-green" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10" /></svg>
                </div>
                <h4 class="fw-bold">Close / Selesai</h4>
                <p class="text-muted small">Tandatangani dokumen secara digital, WO ditutup, Unit kembali bekerja (RFU).</p>
              </div>
            </div>
          </div>
        </div>

        <!-- SECTION 3: FAR -->
        <div id="far" class="guide-section">
          <div class="content-card border-top-0 border-start-0 border-end-0 border-bottom-0" style="border-top: 4px solid var(--tblr-danger) !important; border-radius: 8px;">
            <h2 class="content-title text-danger">Failure Analysis Report (FAR)</h2>
            <p class="fs-4 text-muted mb-4">Modul investigasi mendalam berstandar industri untuk menangani kerusakan fatal, kegagalan komponen utama (Major Component Failure), atau insiden unit yang berulang.</p>

            <div class="row g-4 mb-4">
              <div class="col-md-6">
                <h4 class="fw-bold text-danger border-bottom pb-2">Komponen Inti Laporan FAR</h4>
                <ul class="list-group list-group-flush list-group-transparent">
                  <li class="list-group-item px-0">
                    <strong>1. Integrasi Langsung dengan Work Order</strong><br>
                    <span class="small text-muted">Pembuatan FAR diwajibkan merujuk pada satu Nomor WO untuk auto-fetch data unit.</span>
                  </li>
                  <li class="list-group-item px-0">
                    <strong>2. Database Part Number Otomatis</strong><br>
                    <span class="small text-muted">Menarik Deskripsi Part dari Master Data Parts secara instan saat Part Number diketik.</span>
                  </li>
                  <li class="list-group-item px-0">
                    <strong>3. Empat Pilar Analisa RCA</strong><br>
                    <span class="small text-muted">Investigasi 4 kolom: Gambaran Kejadian, Latar Belakang, Analisa Teknis, dan Kesimpulan/Rekomendasi.</span>
                  </li>
                </ul>
              </div>
              <div class="col-md-6">
                <div class="card bg-danger-lt border-0 h-100 p-3">
                  <h4 class="fw-bold text-danger">Teknologi Pendukung</h4>
                  <div class="mb-3">
                    <strong>📸 Smart Auto-Compress Image</strong><br>
                    <span class="small text-dark">Mengompres foto dari HP (15MB -> &lt;2MB) secara otomatis di browser sebelum diunggah ke server.</span>
                  </div>
                  <div class="mb-3">
                    <strong>✍️ Tanda Tangan Digital & PDF Kop Surat</strong><br>
                    <span class="small text-dark">Sistem 3 tingkat Tanda Tangan Digital yang siap di-print menjadi dokumen PDF resmi.</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- SECTION 4: JWO -->
        <div id="jwo" class="guide-section">
          <div class="content-card border-top-0 border-start-0 border-end-0 border-bottom-0" style="border-top: 4px solid var(--tblr-cyan) !important; border-radius: 8px;">
            <h2 class="content-title text-cyan">Job Work Order (JWO) / Vendor Pihak Ketiga</h2>
            <p class="fs-4 text-muted mb-4">Penanganan perbaikan komponen oleh bengkel/vendor eksternal (*Outsource*) lengkap dengan pelacakan biaya (*Cost*) dan status barang.</p>
          </div>
        </div>

        <!-- SECTION 5: HSE -->
        <div id="hse" class="guide-section">
          <div class="content-card">
            <h2 class="content-title text-success">Manajemen Risiko Keselamatan (HSE / K3)</h2>
            <p class="fs-4 text-muted mb-4">Tiga pilar mitigasi bahaya K3: <strong>JSA (Job Safety Analysis)</strong>, <strong>PTW (Permit to Work)</strong>, dan <strong>LOTO (Lockout/Tagout)</strong>.</p>
          </div>
        </div>

        <!-- SECTION 5B: BUDGET -->
        <div id="budget" class="guide-section">
          <div class="content-card border-top-0 border-start-0 border-end-0 border-bottom-0" style="border-top: 4px solid var(--tblr-yellow) !important; border-radius: 8px;">
            <h2 class="content-title text-yellow">Manajemen Perencanaan Budget & Cost Control</h2>
            <p class="fs-4 text-muted mb-4">Pengendalian biaya anggaran maintenance (Estimasi vs Realisasi) yang terikat langsung dengan nomor Work Order perbaikan.</p>
          </div>
        </div>

        <!-- SECTION 5C: PM -->
        <div id="pm" class="guide-section">
          <div class="content-card border-top-0 border-start-0 border-end-0 border-bottom-0" style="border-top: 4px solid var(--tblr-indigo) !important; border-radius: 8px;">
            <h2 class="content-title text-indigo">Preventive Maintenance (PM Templates & Schedules)</h2>
            <p class="fs-4 text-muted mb-4">Penjadwalan perawatan berkala otomatis berdasarkan interval *Hour Meter* (HM) dan konversi *Auto-Generate WO* 1-klik.</p>
          </div>
        </div>

        <!-- SECTION 5D: TOOLROOM -->
        <div id="toolroom" class="guide-section">
          <div class="content-card border-top-0 border-start-0 border-end-0 border-bottom-0" style="border-top: 4px solid var(--tblr-orange) !important; border-radius: 8px;">
            <h2 class="content-title text-orange">Administrasi ToolRoom & Stok Part</h2>
            <p class="fs-4 text-muted mb-4">Sirkulasi peminjaman alat perkakas (SST/Tools), Stock Opname, dan Minimum Stock Alert.</p>
          </div>
        </div>

        <!-- SECTION 6: COLLABORATION -->
        <div id="collaboration" class="guide-section">
          <div class="content-card">
            <h2 class="content-title text-indigo">Live Chat & Kolaborasi Tim</h2>
            <p class="fs-4 text-muted mb-4">Komunikasi instan antar mekanik/supervisor dan pembagian tautan laporan WO/FAR sekali klik.</p>
          </div>
        </div>

        <!-- SECTION 7: SIGNATURES -->
        <div id="signatures" class="guide-section">
          <div class="content-card">
            <h2 class="content-title text-purple">Sistem Tanda Tangan Digital (Signatures)</h2>
            <p class="fs-4 text-muted mb-4">Pengesahan dokumen paperless 3 tingkat: *Prepared By*, *Reviewed By*, dan *Approved By*.</p>
          </div>
        </div>

        <!-- SECTION 7B: ADMIN -->
        <div id="admin-backup" class="guide-section">
          <div class="content-card">
            <h2 class="content-title text-indigo">Administrasi Sistem & One-Click Backup</h2>
            <p class="fs-4 text-muted mb-4">Fitur pembuatan cadangan database (`.sql.gz`) sekali klik dan manajemen matriks persetujuan.</p>
          </div>
        </div>

        <!-- SECTION 8: ARCHITECTURE -->
        <div id="architecture" class="guide-section">
          <div class="content-card border-top-0 border-start-0 border-end-0 border-bottom-0 shadow-sm" style="border-top: 4px solid var(--tblr-green) !important; border-radius: 8px;">
            <h2 class="content-title text-success border-bottom pb-3">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="32" height="32" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 14v-3a6 6 0 0 1 6 -6h4" /><path d="M10 9l4 -4l-4 -4" /><path d="M20 10v3a6 6 0 0 1 -6 6h-4" /><path d="M14 23l-4 -4l4 -4" /></svg>
              Infrastruktur Keamanan & Arsitektur Kelas Enterprise
            </h2>
            <p class="text-muted fs-4 mb-4">Sistem dibangun dengan Laravel Framework, Tabler UI, Vinkla Hashids (Anti-IDOR), Spatie Audit Log & RBAC, serta DomPDF Generator.</p>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const navLinks = document.querySelectorAll('.guide-nav-link');
    const sections = document.querySelectorAll('.guide-section');

    navLinks.forEach(link => {
      link.addEventListener('click', function(e) {
        e.preventDefault();
        
        // Remove active states
        navLinks.forEach(l => l.classList.remove('active'));
        sections.forEach(s => s.classList.remove('active'));
        
        // Add active state to clicked tab
        this.classList.add('active');
        
        // Show corresponding section
        const targetId = this.getAttribute('data-target');
        const targetSection = document.getElementById(targetId);
        if(targetSection) {
          targetSection.classList.add('active');
          
          if (window.innerWidth < 992) {
             window.scrollTo({
                top: 0,
                behavior: 'smooth'
             });
          }
        }
      });
    });
  });
</script>
@endsection
