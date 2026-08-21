<div align="center">
  <img src="public/img/mascot.png" width="400" alt="CMMS Aisfar For Mukti">
  <br>
  <h1>CMMS AISFAR</h1>
  <p><b>Enterprise Computerized Maintenance Management System untuk Industri Pertambangan & Alat Berat</b></p>
  
  [![PHP Version](https://img.shields.io/badge/PHP-8.1%2B-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net/)
  [![Laravel Version](https://img.shields.io/badge/Laravel-10.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com/)
  [![Three.js](https://img.shields.io/badge/3D%20Visualizer-WebGL%20%2F%20Three.js-000000?style=for-the-badge&logo=three.js&logoColor=white)](#)
  [![Theme](https://img.shields.io/badge/Theme-Dynamic%20Accent%20Industrial-F59E0B?style=for-the-badge&logo=caterpillar&logoColor=black)](#)
  [![Security](https://img.shields.io/badge/Security-Hardened%20Enterprise-059669?style=for-the-badge&logo=shield&logoColor=white)](#)
  [![License](https://img.shields.io/badge/License-Proprietary-blue.svg?style=for-the-badge)](#)
</div>

<br>

## 📖 Tentang Aplikasi
**CMMS AISFAR** adalah Sistem Manajemen Pemeliharaan Aset dan Logistik Terpadu yang dirancang khusus untuk ekosistem industri pertambangan dan alat berat. Platform ini bertindak sebagai *Single Source of Truth* untuk mengelola seluruh siklus hidup armada tambang (Excavator, Dozer, Hauler, Grader, Support Unit) mulai dari pelaporan kerusakan (Pra-WO), eksekusi Work Order digital, notulen rapat operasional & *action items tracker*, manajemen perkakas (ToolRoom), perencanaan anggaran & penggantian komponen (PCR & PM), visualisasi skema database 3D interaktif, hingga analitik KPI keandalan unit.

Aplikasi ini mendigitalisasi operasional workshop menjadi **100% Paperless** didukung fitur *Digital Signature*, *Real-time Live Chat & Collaboration*, visualisasi 3D WebGL, *Theme Accent Color Picker*, serta kontrol keamanan akses tingkat enterprise.

---

## ✨ Modul Utama (Ecosystem)

### 1. 🚜 Maintenance & Operasional Lapangan
- **Laporan Kerusakan (Pra-WO):** Pintu masuk pelaporan kerusakan unit dari lapangan untuk di-generate langsung menjadi Work Order resmi.
- **Work Order Management:** Pencatatan breakdown, backlog, dan servis unit. Terintegrasi dengan pemotongan stok Part, dokumen keselamatan K3 (JSA, PTW, LOTO), timeline durasi mekanik, dan persetujuan tanda tangan digital.
- **Kanban Board:** Visualisasi status pengerjaan unit secara dinamis (*Open, In Progress, Waiting Part, Completed*).
- **Hour Meter (HM):** Pencatatan dan import berkala jam kerja unit yang menjadi basis perhitungan otomatis jadwal PM dan estimasi PCR.
- **Job Work Order (JWO):** Pengelolaan pekerjaan fabrikasi dan servis yang dikerjakan oleh pihak ketiga (Vendor/Outsource) dengan dokumentasi foto sebelum/sesudah.
- **Laporan Produksi Harian:** Pencatatan ritasi *Digger* dan *Hauler* per jam lengkap dengan jenis material tambang (Coal, OB, Top Soil) serta pencatatan delay operasional.

### 2. 🔄 Planning & Reliability (Keandalan & Perencanaan)
- **Plan Component Replacement (PCR):** Modul estimasi dan jadwal penggantian komponen utama berdasarkan *Target Life (Hrs)*, *Current HM*, *Plan SMU*, dan *Daily Operating Hours*, lengkap dengan riwayat *Last Change Out* dan *Part Swapping*.
- **Preventive Maintenance (PM):** Template checklist servis berkala (PS1-PS4) terintegrasi jadwal otomatis berbasis Hour Meter (HM) dengan notifikasi *Due Date* dan *1-Click Auto-Generate WO*.
- **Plan Budget Bulanan (RAB):** Kontrol anggaran pemeliharaan (RAB vs Realisasi Biaya) per site dan per unit.
- **Failure Analysis Report (FAR):** Investigasi 4 pilar *Root Cause Analysis* (RCA) untuk kegagalan komponen mayor.
- **Swap Component Report:** Rekam jejak mutasi, kanibalisasi, dan perpindahan komponen antar-unit alat berat.

### 3. 🌐 3D Interactive Database Relation Visualizer (WebGL / Three.js)
- **3D Universe Graph:** Visualisasi 3D seluruh tabel database dan relasinya menggunakan WebGL (3D Force Graph & Three.js).
- **Pengelompokan 9 Modul Domain:** Mengelompokkan tabel ke dalam domain spesifik (Work Orders, Asset Fleet, Parts, ToolRoom, HSE, Production, Meetings, Auth, System) dengan warna *glowing emissive* unik per modul.
- **Animasi Aliran Partikel Relasi:** Garis koneksi melengkung dengan partikel bercahaya yang mengalir dinamis (*directional particle flow*).
- **Interaksi Kamera Sinematik:** Rotasi 360°, zoom in/out, pan, serta *fly-to-target* otomatis saat tabel diklik.
- **Sliding Glassmorphism Inspector:** Panel laci samping untuk memeriksa struktur kolom (PK/FK/Tipe Data), relasi terhubung yang dapat diklik, dan pratinjau sampel data tabel secara instan.
- **Toolbar Kontrol Canggih:** Live search tabel/kolom dengan autocomplete, filter chip modul, layout switcher (*3D Galaxy, 3D Spherical Orbit, 2D Flat*), auto-rotate, dan ekspor screenshot resolusi tinggi (PNG).

### 4. 💬 Real-Time Live Chat & Messenger Widget (Bebas Refresh)
- **Dua Mode Messenger:** Halaman Penuh (`/chat`) dan **Floating Messenger Widget** di pojok kanan bawah yang aktif di seluruh halaman sistem.
- **Pembaruan Otomatis (No Refresh):** Pesan masuk terupdate otomatis secara background (polling 3 detik) dengan *flicker-free DOM rendering*.
- **Audio Chime Notification:** Notifikasi suara sintetis lembut (*Web Audio API*) yang berbunyi otomatis saat ada pesan baru masuk dari rekan kerja.
- **Live Contact Badge & Tab Title:** Indikator badge merah unread pada daftar kontak diperbarui real-time dan judul tab browser otomatis menampilkan `(1) Pesan Baru - CMMS Aisfar`.
- **Fitur Operasional Lapangan:** Template pesan cepat operasional (*WO Selesai, Breakdown Urgent, Sparepart Ready*), pencarian & lampiran kartu dokumen Work Order / JWO interaktif, serta emoji picker lengkap.

### 5. 📋 Notulen Rapat & Action Items Tracker
- **Notulen Rapat Digital:** Pencatatan notulen rapat workshop/operasional (Agenda, Hasil Pembahasan, Daftar Peserta Hadir, dan Penanggung Jawab).
- **Continuous Action Items Tracker:** Pelacakan tindak lanjut keputusan rapat secara berkesinambungan lintas pertemuan dengan status progres (*Open, In Progress, Waiting Part, Closed*) dan tanggal tenggat (*Due Date*).
- **Export PDF Resmi:** Cetak laporan notulen rapat format A4 berstandar korporat lengkap dengan tanda tangan pimpinan rapat.

### 6. 🧰 ToolRoom & Workshop
- **Peminjaman Perkakas (Tool Loan):** Sirkulasi check-in dan check-out perkakas kerja mekanik.
- **Approval Stok Tool:** Alur pengajuan dan persetujuan penambahan inventaris perkakas baru.
- **Berita Acara (B.A) Insiden:** Laporan resmi kejadian kerusakan atau kehilangan alat kerja beserta unggahan dokumen hasil investigasi.
- **Stock Opname:** Audit fisik periodik dan penyesuaian inventaris perkakas dengan verifikasi tanda tangan digital.

### 7. 📈 KPI & Reporting
- **Report Breakdown & Downtime:** Analitik downtime unit, frekuensi kerusakan, serta klasifikasi breakdown.
- **KPI Master Data:** Kalkulasi metrik ketersediaan unit (*Physical Availability / PA*), MTTR (*Mean Time To Repair*), dan MTBF (*Mean Time Between Failures*) berstandar industri alat berat.

### 8. ⚙️ Master Data Terpusat
- **Master Unit (Asset):** Populasi alat berat, Model Unit, Tipe Unit, dan pembagian Site operasional.
- **Master Part & Komponen:** Katalog suku cadang terintegrasi multi-model unit (TomSelect) dan target jam kerja.
- **Master Vendor & Bengkel:** Database rekanan jasa servis dan fabrikasi luar.
- **Master Mekanik:** Data kepegawaian mekanik dengan pop-up riwayat lengkap kinerja & inventaris tool.
- **Master Tool & Kategori:** Database perkakas, spesifikasi, dan kuantitas stok.

### 9. 🔐 Administrator, Keamanan & Hak Akses
- **Manajemen User & Multi-Site:** Pengaturan pengguna berbasis hak akses dan lokasi tambang (Site).
- **Matriks Hak Akses (Categorized Permissions):** Manajemen perizinan Role dan User yang dikelompokkan secara rapi per kategori modul dengan tombol *quick-toggle* instan.
- **Approval Matrix & Digital Signatures:** Matriks persetujuan berjenjang untuk dokumen operasional.
- **Log Aktivitas & Audit Trail:** Perekaman audit trail komprehensif atas seluruh transaksi pengguna secara otomatis.
- **Backup Database Terintegrasi:** Fitur pencadangan instan seluruh database MySQL ke format file `.sql` aman.

---

## 🎨 UI/UX & Tema: Heavy Equipment Industrial Theme

- **🎨 Theme Accent Color Picker (Kustomisasi Warna Aksen Dinamis):**
  - Pemilih warna aksen terintegrasi di header atas dengan tombol palet dan indikator titik menyala (*glowing dot*).
  - **6 Pilihan Preset Warna Alat Berat:**
    - 🟡 **CAT Mining Amber** *(Default Emas Caterpillar)*
    - 🔵 **Komatsu Cyber Cyan** *(Electric Cyan Modern)*
    - 🟢 **Hitachi Emerald** *(Hijau Forest)*
    - 🔴 **Safety Flame Orange** *(Oranye Keselamatan Tambang)*
    - 🔵 **Liebherr Sapphire Blue** *(Biru Laut Elegan)*
    - 🟣 **Neon Amethyst Purple** *(Ungu Futuristik)*
  - Perubahan seketika pada seluruh elemen UI (tombol, glowing border, card header, nav active, hazard stripe) tanpa reload (*instant zero-reload*) dan tersimpan di `localStorage` (*zero-flicker on load*).
- **📌 Sticky Glassmorphism Header:** Header atas tetap melayang di posisi atas layar saat di-scroll dengan efek blur kaca dan border bayangan industri.
- **High-Tech Top Progress Bar:** Progress bar berkecepatan tinggi dengan efek shimmer neon di bagian atas layar pada setiap aksi pengguna dan navigasi halaman.
- **Glassmorphism Industrial CRUD Loader:**
  - Animasi roda gigi mesin (*industrial rotating heavy gears*) berputar presisi dengan cincin hidrolik berdenyut (*pulse ring*).
  - Deteksi konteks operasi CRUD otomatis: *Creating Record, Updating Data, Deleting Record, Uploading File, Generating File*.
- **Micro-Animations & Anti-Double-Submit:** Tombol form otomatis bertransformasi menjadi *spinner* aktif dan status *disabled* saat diklik untuk mencegah duplikasi data transaksi.

---

## 🛡️ Keamanan & Arsitektur Enterprise (Security Hardened)

Aplikasi telah melalui audit dan penguatan keamanan sistem berlapis:
- **HTTP Security Headers Middleware:** Proteksi *Anti-Clickjacking* (`X-Frame-Options: SAMEORIGIN`), *Anti-MIME Sniffing* (`X-Content-Type-Options: nosniff`), `Referrer-Policy: strict-origin-when-cross-origin`, dan `Permissions-Policy`.
- **Proteksi SQL Injection (SQLi):** 100% menggunakan Eloquent ORM & Query Builder dengan *prepared statements* dan *parameter binding*.
- **Proteksi Cross-Site Scripting (XSS):** Blade auto-escaping `{{ ... }}` dan sanitasi javascript helper `@js(...)`.
- **Proteksi Web Shell & Insecure Uploads:** Whitelist ekstensi berkas ketat (`mimes:jpeg,png,jpg,webp,pdf,docx,xlsx,zip`).
- **Pembersihan Berkas & Artefak:** Repository bersih dari berkas debug publik dan script sementara.

---

## 💻 Tech Stack

- **Backend:** Laravel 10 (PHP 8.1+)
- **Frontend / UI:** Blade Templating, Vanilla CSS/JS, Tabler UI Core (v1.0), TomSelect, VirtualSelect, SweetAlert2
- **3D Engine:** Three.js, 3D-Force-Graph (WebGL)
- **Chart & Analytics:** Chart.js, ApexCharts
- **Database:** MySQL / MariaDB / PostgreSQL
- **Security & Authorization:**
  - **Spatie Laravel-Permission:** Role-Based Access Control (RBAC) dengan granular permission per modul dan aksi.
  - **Hashids (Anti-IDOR):** Enkripsi parameter ID pada URL sensitif.
  - **Spatie Activitylog:** Perekaman audit trail aktivitas pengguna.
- **Document Engine:** DomPDF (Ekspor PDF A4 ber-Kop Surat resmi) & Maatwebsite Excel (Import/Export Spreadsheet).

---

## 🚀 Panduan Instalasi (Development)

### Persyaratan Sistem
- PHP >= 8.1 (dengan ekstensi `pdo_mysql`, `mbstring`, `openssl`, `gd`, `fileinfo`)
- Composer
- Node.js & NPM
- MySQL / MariaDB Server (disarankan mysqldump tersedia di *PATH* untuk fitur backup)

### Langkah Instalasi

1. **Clone Repository**
   ```bash
   git clone https://github.com/plannermukti-ui/cmms-asifar.git
   cd cmms-asifar
   ```

2. **Install Dependensi PHP & Node.js**
   ```bash
   composer install
   npm install
   ```

3. **Konfigurasi Environment**
   Salin file `.env.example` menjadi `.env`:
   ```bash
   cp .env.example .env
   ```
   Atur konfigurasi database pada file `.env`:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=cmms_aisfar
   DB_USERNAME=root
   DB_PASSWORD=
   ```

4. **Generate Application Key & Migration**
   ```bash
   php artisan key:generate
   php artisan storage:link
   php artisan migrate --seed
   ```

5. **Jalankan Aplikasi**
   ```bash
   npm run dev
   # Buka terminal baru
   php artisan serve
   ```
   Aplikasi dapat diakses melalui `http://localhost:8000`.

---

### 🔑 Akun Default (Default Login Credentials)

Setelah menjalankan `php artisan migrate --seed` atau `php artisan db:seed`, sistem otomatis menyediakan akun **Super Administrator** default:

| Role | Email Login | Password Default | Akses |
| :--- | :--- | :--- | :--- |
| **Super Admin** | `admin@cmms-aisfar.com` | `password` | **Full Access** (Seluruh Modul & Menu) |

> 💡 *Catatan: Setelah berhasil login pertama kali, sangat disarankan untuk memperbarui kata sandi melalui menu Profil / Pengaturan Akun.*

---

## 👥 Struktur Peran Pengguna (User Roles)

Sistem mendukung *Multi-Role* yang disesuaikan dengan struktur operasional industri pertambangan:
- **Super Administrator:** Akses penuh konfigurasi sistem, matriks hak akses, master data, backup database, dan log audit trail.
- **Maintenance Superintendent / Planner:** Manajemen jadwal PM, evaluasi PCR, budget RAB, dan approval akhir.
- **Supervisor / Foreman:** Verifikasi Work Order, penugasan teknisi, dan review dokumen keselamatan kerja (HSE).
- **Mechanic:** Pengerjaan tugas Work Order, input jam kerja, dan peminjaman perkakas kerja di toolroom.
- **Toolman / Warehouse:** Pengelolaan sirkulasi tool, permohonan stok, dan stock opname fisik.
- **Engineer / Production:** Pemantauan data produksi armada tambang harian.

---

## 📸 Antarmuka Aplikasi (Screenshots)

### 1. Dashboard Eksekutif
Pusat kendali utama yang menyajikan metrik operasional *real-time*, tren status Work Order, unit breakdown, dan ketersediaan armada.
![Dashboard Eksekutif](public/img/screenshots/dashboard.png)

### 2. Detail & Laporan Work Order (Execution Sheet)
Halaman rincian eksekusi pemeliharaan lengkap dengan pembagian subtask, kebutuhan part, dan integrasi dokumen keselamatan K3 (JSA, PTW, LOTO).
![Detail Work Order](public/img/screenshots/detail_wo.png)

---

## 📚 Pusat Panduan (Guide)

Aplikasi dilengkapi modul Panduan Interaktif yang dapat diakses pada rute `/guide` lengkap dengan infografis, animasi modern, dan petunjuk penggunaan operasional sistem.

---

<div align="center">
  <p>Dibuat dan dikembangkan untuk operasional <b>PT Mukti / CMMS AISFAR</b>.<br>Hak Cipta © 2026. Semua Hak Dilindungi.</p>
</div>
