<div align="center">

  <img src="public/favicon.svg" alt="LaraCarte Logo" width="100" height="100">

  # LaraCarte
  ### Ultimate SaaS Restaurant Management & POS Ecosystem

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
    <strong>Solusi SaaS Terpadu: Kelola Ribuan Restoran dalam Satu Platform Modern.</strong>
  </p>

</div>

---

## 🚀 Arsitektur SaaS (Multi-Tenancy)

LaraCarte kini mendukung model bisnis **SaaS (Software as a Service)** dengan arsitektur *Single Database Multi-Tenancy*:
*   **Data Isolation:** Setiap restoran (tenant) memiliki ruang data yang terisolasi secara otomatis menggunakan Tenant Scoping.
*   **Independent Settings:** Setiap merchant dapat mengatur logo, alamat, pajak, dan branding mereka sendiri.
*   **Custom Domain/Slug:** Akses menu pelanggan melalui URL unik per restoran (misal: `/order/resto-bunda`).

## 👑 Modul Super Admin (Platform Control)

Dashboard eksklusif untuk pengelola platform untuk memantau seluruh ekosistem:
*   **Global statistics:** Pantau total tenant, total transaksi, dan pendapatan global real-time.
*   **Tenant Management:** Aktivasi, suspensi, dan monitoring performa setiap restoran.
*   **Impersonation Logic:** Login sebagai 'Owner' tenant manapun untuk memberikan bantuan teknis langsung.
*   **Platform Settings:** Kelola nama aplikasi global, email support, dan maintenance mode.

## 🌟 Fitur Unggulan Merchant

### 🎁 Loyalty & Point System
*   **Smart Membership:** Pelanggan cukup masukkan nomor WA saat checkout untuk menjadi member.
*   **Point Earn & Redemption:** Atur rasio poin per transaksi dan izinkan pelanggan menukarkan poin sebagai diskon langsung.
*   **Transaction History:** Lacak setiap penambahan dan pemakaian poin pelanggan secara detail.

### 📱 Pengalaman Pelanggan & POS
*   **Self-Order & Kiosk:** Pesan mandiri via QR Code meja atau tablet kiosk.
*   **Real-time Kitchen (KDS):** Sinkronisasi instan antara pesanan pelanggan dan monitor dapur.
*   **Inventory Automation:** Stok bahan baku (Recipe-based) terpotong otomatis saat menu terjual.

### 👥 Team Management (RBAC)
*   **Owner:** Akses penuh laporan, keuangan, dan pengaturan menu.
*   **Cashier:** Fokus pada verifikasi pembayaran dan cetak struk.
*   **Kitchen:** Tampilan khusus koki untuk memproses antrian pesanan.
*   **Waiter:** Manajemen meja dan layanan pelanggan.

---

## 🛠️ Instalasi & Pengembangan

### 1. Setup Awal
```bash
# Install dependencies
composer install && npm install

# Environment setup
cp .env.example .env
php artisan key:generate

# Database Migration with Seeders
php artisan migrate --seed
```

### 2. Membuat Super Admin
Gunakan command khusus untuk inisialisasi admin platform pertama kali:
```bash
php artisan app:create-super-admin
```

### 3. Real-time Service
Wajib menjalankan Reverb untuk fitur notifikasi dan KDS:
```bash
php artisan reverb:start
```

---

## 📘 Teknologi Utama
*   **Backend:** Laravel 12 (PHP 8.2+)
*   **Frontend:** Livewire 3 & Alpine.js
*   **Styling:** Tailwind CSS
*   **Real-time:** Laravel Reverb
*   **Payment:** Midtrans API

---

<div align="center">
  <p>Dibuat dengan ❤️ untuk Masa Depan Digitalisasi Kuliner.</p>
</div>