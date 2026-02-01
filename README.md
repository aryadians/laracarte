<div align="center">

  <img src="public/favicon.svg" alt="LaraCarte Logo" width="100" height="100">

  # LaraCarte
  ### Ultimate Restaurant Management & POS Ecosystem

  <p align="center">
    <a href="https://laravel.com">
      <img src="https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel">
    </a>
    <a href="https://livewire.laravel.com">
      <img src="https://img.shields.io/badge/Livewire-4E56A6?style=for-the-badge&logo=livewire&logoColor=white" alt="Livewire">
    </a>
    <a href="https://tailwindcss.com">
      <img src="https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white" alt="TailwindCSS">
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
    <strong>Solusi Digital Terpadu untuk Restoran: Dari Order Mandiri hingga Kontrol Stok Bahan Baku.</strong>
  </p>

</div>

---

## 🌟 Fitur Utama (Keseluruhan)

LaraCarte adalah sistem manajemen restoran modern yang mengintegrasikan seluruh alur operasional dalam satu platform yang sinkron secara real-time.

### 📱 Pengalaman Pelanggan (Self-Order)
*   **Smart QR Ordering:** Pelanggan pesan langsung dari meja via HP.
*   **Kiosk Tablet Mode:** Tampilan khusus tablet untuk pesanan mandiri yang lebih mewah.
*   **Product Variants & Modifiers:** Dukungan menu kompleks (Level pedas, extra topping, dll).
*   **Loyalty Member System:** Kumpulkan poin otomatis menggunakan nomor WhatsApp.
*   **Digital Receipt:** Kirim struk belanja langsung ke WhatsApp pelanggan.

### 💳 Integrasi Pembayaran
*   **Automated Payment (Midtrans):** Bayar otomatis via QRIS, E-Wallet (GoPay/OVO), VA, dan terverifikasi instan.
*   **Cashier Payment:** Opsi bayar tunai tradisional di meja kasir.
*   **Promo & Diskon Otomatis:** Sistem cerdas yang menerapkan diskon terbaik berdasarkan syarat belanja.

### 👨‍🍳 Operasional Real-Time
*   **Kitchen Display System (KDS):** Pesanan masuk ke dapur secara instan tanpa delay (WebSocket).
*   **Expo / Runner Screen:** Memastikan makanan yang sudah jadi segera diantar ke meja oleh pelayan.
*   **Waitress Call:** Tombol panggil pelayan digital dengan notifikasi suara di dashboard.

### 🥦 Inventory & Cost Control
*   **Recipe Management:** Satu menu terdiri dari berbagai bahan baku (misal: 200g beras, 1 telur).
*   **Auto-Deduct Stock:** Stok bahan mentah terpotong otomatis setiap kali menu terjual.
*   **Low Stock Alerts:** Peringatan dini saat bahan baku hampir habis untuk mencegah gangguan jualan.

### 💰 Kasir & Keamanan Keuangan
*   **Cash Shift Management:** Kontrol ketat modal awal dan rekonsiliasi uang fisik di akhir shift.
*   **Thermal Printing:** Cetak struk langsung (Direct Print) ke printer thermal 58mm/80mm.
*   **Transaction Audit:** Catatan detail setiap diskon, poin, dan metode bayar yang digunakan.

### 📊 Analitik & Laporan
*   **Interactive Dashboard:** Grafik penjualan, top product, dan statistik real-time.
*   **Advanced Reports:** Filter penjualan mendalam berdasarkan rentang tanggal.
*   **Data Export:** Download laporan keuangan dalam format **Excel (.xlsx)** atau **PDF**.

---

## 🛠️ Instalasi & Pengembangan

### 1. Prasyarat
*   PHP 8.2+
*   Composer & Node.js
*   MySQL 8.0+

### 2. Setup Cepat
```bash
# Clone & Install
git clone https://github.com/username/laracarte.git
cd laracarte
composer install && npm install

# Konfigurasi
cp .env.example .env
php artisan key:generate

# Database & Storage
php artisan migrate --seed
php artisan storage:link
```

### 3. Menjalankan Sistem
Anda wajib menjalankan **3 layanan** utama agar fitur real-time bekerja:
1. `php artisan serve` (Aplikasi)
2. `npm run dev` (Vite Assets)
3. `php artisan reverb:start` (Real-time Engine)

---

## 📘 Dokumentasi Operasional

*   **[Manual Pengguna](PANDUAN_PENGGUNA.md)** - Panduan cara pakai untuk Kasir, Koki, dan Pelayan.
*   **[Panduan Deployment](DEPLOYMENT.md)** - Petunjuk teknis untuk instalasi di server VPS (Nginx, Supervisor, SSL).

---

<div align="center">
  <p>Dibuat dengan ❤️ untuk Masa Depan Digitalisasi Kuliner.</p>
</div>