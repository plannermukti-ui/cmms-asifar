# Implementation Plan: System Audit, Consistency, and UUID Evaluation

Tujuan dari rencana ini adalah untuk melakukan peninjauan menyeluruh terhadap sistem CMMS, memastikan konsistensi UI/UX (termasuk penggunaan bahasa), melengkapi sistem *Audit Trail* (Log Aktivitas), serta memberikan evaluasi strategis mengenai penggunaan UUID.

## 1. Konsistensi Tampilan & Bahasa (UI/UX)
Berdasarkan permintaan Anda, kami akan menyelaraskan dan memformalkan istilah-istilah tombol dan navigasi agar seragam dan menggunakan Bahasa Indonesia Teknikal yang baik:
- Mengubah tombol `Lihat`, `Lihat Detail` menjadi **`Detail`**.
- Memastikan semua laporan cetak memiliki struktur informasi (*Header*, Kop Surat, *Body*, dan Tanda Tangan) yang konsisten. Template cetak untuk FAR, JWO, dan WO sudah menggunakan komponen grid dan Kop Surat A4 yang sangat profesional.

## 2. Perekaman Jejak Audit (Activity Logs)
Saat ini, **Log Aktivitas belum mencakup semua modul utama**. Hanya model seperti `User`, `Far`, dan `MasterUnit` yang merekam log.
Kami akan menambahkan *Spatie Activity Log* ke dalam semua model operasional vital, termasuk:
- `WorkOrder`
- `Jwo`
- `Part` & `Tool`
- `PlanBudget` & `StockOpname`
- `HourMeter`

Dengan ini, setiap aksi penambahan, pengubahan, atau penghapusan data di halaman-halaman tersebut akan tercatat rapi di menu *Activity Log*.

## 3. Keamanan Sistem & Perbaikan Error
- Semua relasi model dan pemanggilan fungsi (*seperti fungsi Global Helper yang error tempo hari*) telah ditambal (patched).
- Isolasi Data (*Multi-Tenancy*) dengan trait `BelongsToSite` sudah berfungsi baik; data Site A tidak akan bisa dibaca oleh Site B, yang merupakan standar keamanan CMMS multi-proyek.

## 4. Evaluasi & Rekomendasi UUID (Penting!)

> [!WARNING]
> Mengubah struktur *Primary Key* (*ID*) dari angka berurut (Auto-Increment) menjadi UUID pada **sistem yang sudah berjalan dan memiliki data** berisiko merusak sistem lama jika tidak dilakukan dengan sempurna. 
> Anda harus mengubah tipe kolom `id` dan semua `foreign_id` terkait di seluruh tabel.

**Saran Kami:**
Apakah kita memerlukannya? **TIDAK WAJIB**. Keamanan CMMS ini sudah dijaga oleh *Role-Based Access Control (RBAC)*. Jika seorang user menebak URL `work-orders/10` tetapi dia tidak punya akses ke Site tersebut, dia akan tetap diblokir (403 Forbidden).

Jika Anda hanya ingin menyembunyikan angka ID agar tidak mudah ditebak di URL (demi privasi total data perusahaan), **kami sangat menyarankan alternatif yang lebih aman 100% tanpa merusak database lama:**

**Gunakan "Hashids" atau "Route Key Number"**
Daripada membongkar database untuk UUID, kita bisa mengenkripsi ID di URL (*contoh URL:* `work-orders/a7B9xK`) atau menggunakan nomor aslinya (*contoh URL:* `work-orders/WO-08-26-001`). Ini sangat aman, cepat diterapkan, dan tidak akan merusak sistem database sama sekali.

---

## 📌 User Review Required

Silakan tinjau dan setujui rencana ini. Jika Anda menyetujui, kami akan mulai mengerjakan:
1. Penyeragaman tombol UI (`Detail`).
2. Penambahan fungsi Log Aktivitas ke sisa model utama.

*(Terkait UUID, kami akan menunda eksekusi UUID hingga Anda menyetujui saran keamanan di atas, atau jika Anda tetap bersikeras menggunakan UUID).*
