<div align="center">

  <img src="public/favicon.svg" alt="LaraCarte Logo" width="120" height="120">

  # LaraCarte
  ### The Ultimate SaaS Restaurant & POS Ecosystem
  *Transforming Traditional Dining into a Digital Symphony*

  <p align="center">
    <a href="https://laravel.com">
      <img src="https://img.shields.io/badge/Laravel_12-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel">
    </a>
    <a href="https://livewire.laravel.com">
      <img src="https://img.shields.io/badge/Livewire_3-4E56A6?style=for-the-badge&logo=livewire&logoColor=white" alt="Livewire">
    </a>
    <a href="https://tailwindcss.com">
      <img src="https://img.shields.io/badge/Tailwind_CSS_3-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white" alt="TailwindCSS">
    </a>
    <a href="https://reverb.laravel.com">
      <img src="https://img.shields.io/badge/Real--time-Reverb-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Reverb">
    </a>
    <br>
    <a href="https://midtrans.com">
      <img src="https://img.shields.io/badge/Payment-Midtrans-002855?style=for-the-badge&logo=visa&logoColor=white" alt="Midtrans">
    </a>
    <a href="https://mysql.com">
      <img src="https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL">
    </a>
  </p>

  <p align="center">
    <strong>Sistem Manajemen Restoran SaaS yang Terintegrasi Penuh. Satu Platform, Ribuan Bisnis, Tanpa Batas.</strong>
  </p>

</div>

---

## 🛠️ Arsitektur Sistem (The SaaS Core)

LaraCarte dibangun dengan pondasi **Multi-Tenancy (Single Database)** yang memungkinkan skalabilitas tinggi bagi penyedia platform SaaS.

*   **Tenant Scoping:** Isolasi data tingkat tinggi. Restoran A tidak akan pernah bisa melihat data Restoran B.
*   **Dynamic Resolution:** Identifikasi tenant secara otomatis melalui URL slug (e.g., `laracarte.com/table/waroeng-sedap`).
*   **Global vs Local Settings:** Konfigurasi platform pusat oleh Super Admin vs Konfigurasi branding operasional oleh Owner.

---

## 👑 1. Modul Super Admin (Platform Governance)

Pusat kendali bagi pemilik platform untuk mengelola ekosistem LaraCarte secara keseluruhan.

- **Global Dashboard:** Visualisasi data agregat (Pendapatan Global, Pertumbuhan Tenant, Order harian seluruh platform).
- **Tenant Management:** Daftar lengkap merchant dengan fitur filtrasi, aktivasi, dan blokir.
- **Support Impersonation:** Fitur "Masuk sebagai Owner" untuk membantu troubleshooting tenant tanpa meminta password.
- **Financial Platform Reports:** Grafik performa keuangan antar restoran untuk melihat siapa top performer.
- **Platform Branding:** Pengaturan Nama App, Support Email, dan Toggle Maintenance Mode global.

---

## 🏢 2. Modul Owner / Merchant (Business Intelligence)

Didesain khusus untuk pemilik restoran agar bisa mengelola bisnis dengan presisi tinggi.

- **Smart Dashboard:** Metrik real-time (Revenue hari ini, Stok Menipis, Panggilan Meja Aktif).
- **Master Data Management:** Pengaturan menu, kategori (Food, Drink, Dessert), dan Manajemen Meja (Dynamic QR).
- **Employee Management:** Tambah dan kelola staf (Kasir, Dapur, Pelayan) dengan sistem RBAC (Role-Based Access Control).
- **Branding & Operational Settings:** Upload logo, alamat, pengaturan pajak (Tax), dan service charge.
- **Reporting Suite:** Laporan penjualan harian, bulanan, top products, hingga ekspor data ke Excel/PDF.

---

## 📱 3. Customer Experience (Order mandiri)

Memberikan pengalaman modern bagi pelanggan melalui HP mereka sendiri atau Tablet Kiosk.

- **QR-Code Self Order:** Scan meja -> Lihat menu -> Pilih variant (Level pedas, extra keju) -> Checkout.
- **Kiosk Mode:** Tampilan antarmuka khusus untuk tablet pesanan mandiri di depan outlet.
- **Loyalty Program Integration:** Cukup masukkan nomor WA untuk mendaftar member dan mengumpulkan poin otomatis.
- **Real-time Order Status:** Pantau status pesanan (Pending -> Cooking -> Served) langsung dari browser pelanggan.

---

## 🎁 4. Program Loyalitas & Member (Retention Engine)

Sistem cerdas untuk meningkatkan *customer lifetime value*.

- **Dynamic Points Logic:** Owner mengatur berapa Rupiah untuk mendapatkan 1 poin.
- **Point Redemption:** Pelanggan dapat menggunakan poin mereka sebagai diskon langsung saat checkout.
- **Member Directory:** Daftar pelanggan dengan histori transaksi lengkap mereka.
- **Automatic Registration:** Mengubah pelanggan biasa menjadi member hanya dalam satu klik saat pemesanan.

---

## 👨‍🍳 5. Dapur (KDS) & Operasional (Live Execution)

Memastikan operasional berjalan mulus dengan sinkronisasi real-time via **Laravel Reverb**.

- **Kitchen Display System (KDS):** Antrian pesanan digital yang masuk instan setelah pembayaran terverifikasi.
- **Waitress Call System:** Tombol visual "Panggil Pelayan" di dashboard admin dengan alert suara.
- **Expo / Runner Panel:** Verifikasi pesanan yang selesai dimasak untuk segera diantar ke meja.
- **Low Stock Inventory Alert:** Notifikasi otomatis jika bahan baku resep sudah hampir mencapai titik kritis.

---

## 💳 6. POS & Pembayaran (Seamless Payment)

- **Midtrans Integrated:** Pembayaran non-tunai via QRIS, Virtual Account, Credit Card dengan verifikasi status otomatis.
- **Cashier POS Interface:** Antarmuka kasir cepat untuk input pesanan manual atau verifikasi pembayaran tunai.
- **Raw Thermal Printing:** Simulasi cetak struk (Receipt) yang presisi untuk printer thermal 58mm/80mm.
- **Promotion Engine:** Diskon otomatis berbasis syarat minimum belanja.

---

## 🥦 7. Inventory & Cost Control (The Backend Logic)

- **Recipe System:** Hubungkan menu dengan berbagai bahan baku (misal: Nasi Goreng butuh Nasi, Telur, Kecap).
- **Real-time Stock Deduction:** Stok bahan baku terpotong otomatis setiap kali menu terkait dipesan.
- **Stock Movements:** Log detail setiap kali ada pemakaian atau pengadaan bahan baku.

---

## 🛠️ Instalasi & Pengembangan

### 1. Prasyarat
- PHP 8.2+ (Recommended PHP 8.4)
- Composer & Node.js (Latest LTS)
- MySQL 8.0+
- Printer Thermal (Opsional untuk testing struk)

### 2. Setup Cepat
```bash
# Clone & Install
git clone https://github.com/aryadians/laracarte.git
composer install && npm install

# Config
cp .env.example .env
php artisan key:generate

# Database & Seed (Default data admin)
php artisan migrate --seed
php artisan storage:link
```

### 3. Inisialisasi Admin Platform
Jalankan perintah ini untuk membuat akun akses tertinggi:
```bash
php artisan app:create-super-admin
```

---

## 🚀 Menjalankan Project
Buka 3 terminal berbeda untuk performa maksimal:
1. `php artisan serve` (Web Server)
2. `npm run dev` (Vite / Styles)
3. `php artisan reverb:start` (Real-time Engine)

---

<div align="center">
  <p>💡 <b>LaraCarte</b> - Menghadirkan teknologi restoran masa depan untuk semua ukuran bisnis.</p>
</div>