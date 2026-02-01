<div align="center">

  <img src="public/favicon.svg" alt="LaraCarte Logo" width="100" height="100">

  # LaraCarte
  ### Professional Restaurant POS & Management System

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
    <a href="https://alpinejs.dev">
      <img src="https://img.shields.io/badge/Alpine.js-8BC0D0?style=for-the-badge&logo=alpine.js&logoColor=white" alt="AlpineJS">
    </a>
    <br>
    <a href="https://mysql.com">
      <img src="https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL">
    </a>
    <a href="https://reverb.laravel.com">
      <img src="https://img.shields.io/badge/Laravel_Reverb-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Reverb">
    </a>
    <a href="https://midtrans.com">
      <img src="https://img.shields.io/badge/Midtrans-002855?style=for-the-badge&logo=visa&logoColor=white" alt="Midtrans">
    </a>
  </p>

  <p align="center">
    <strong>Solusi End-to-End untuk Restoran Modern: Dari Order Mandiri hingga Loyalty Member.</strong>
  </p>

</div>

---

## ✨ Fitur Unggulan Utama

LaraCarte bukan sekadar kasir biasa. Ini adalah ekosistem digital yang menghubungkan Pelanggan, Dapur, Pelayan, dan Pemilik Restoran secara real-time.

### 📱 Customer Experience (Self-Order)
*   **Smart QR Ordering:** Pelanggan scan QR meja untuk akses menu instan.
*   **Product Variants:** Pilihan kompleks seperti level pedas, topping tambahan, atau ukuran porsi.
*   **Loyalty & Member:** Sistem poin reward otomatis (Earn & Redeem) berdasarkan nomor WhatsApp pelanggan.
*   **Integrated Payment (Midtrans):** Pembayaran otomatis via QRIS, GoPay, OVO, ShopeePay, dan Virtual Account yang terverifikasi instan.

### 👨‍🍳 Operasional Dapur & Lantai (KDS & Expo)
*   **Real-Time KDS:** Pesanan masuk ke layar dapur dalam hitungan milidetik tanpa refresh halaman.
*   **Expo / Runner Screen:** Layar khusus pelayan untuk memastikan makanan yang sudah jadi segera diantar ke meja yang benar.
*   **Audio Notification:** Notifikasi suara otomatis untuk setiap pesanan baru atau panggilan pelayan digital.

### 🥦 Manajemen Stok & Resep (Inventory)
*   **Bahan Baku:** Kelola stok bahan mentah (gram, ml, butir, kg).
*   **Recipe-Based Inventory:** Stok bahan baku terpotong otomatis saat menu terjual berdasarkan resep yang telah diatur (Cost Control).
*   **Low Stock Alert:** Peringatan visual di dashboard saat bahan baku mencapai batas kritis.

### 💰 Kasir Profesional (POS)
*   **Cetak Struk Thermal:** Dukungan Direct Print (ESC/POS) dan Popup Print standar 58mm.
*   **WhatsApp Receipt:** Kirim struk belanja digital langsung ke WhatsApp pelanggan dalam satu klik.
*   **QRIS Manual & Otomatis:** Fleksibilitas dalam menerima pembayaran digital.

### 📊 Admin & Analitik Lanjutan
*   **Executive Dashboard:** Ringkasan omzet, pesanan aktif, stok menipis, dan menu terlaris dalam satu tampilan.
*   **Laporan Dinamis:** Filter penjualan berdasarkan rentang tanggal kustom.
*   **Export Data:** Unduh laporan lengkap dalam format **Excel (.xlsx)** atau **PDF**.
*   **Dinamis Settings:** Atur nama resto, alamat, pajak (%), biaya layanan, dan printer langsung dari UI.

---

## 🛠️ Instalasi & Pengembangan

### 1. Kebutuhan Sistem
*   PHP 8.2+
*   Composer
*   Node.js & NPM
*   MySQL 8.0+

### 2. Langkah Instalasi
```bash
# Clone & Install
git clone https://github.com/username/laracarte.git
cd laracarte
composer install
npm install

# Konfigurasi .env
cp .env.example .env
php artisan key:generate

# Database Setup
php artisan migrate --seed
php artisan storage:link
```

### 3. Menjalankan Aplikasi
Buka 3 terminal terpisah:
1. `php artisan serve` (Aplikasi)
2. `npm run dev` (Assets)
3. `php artisan reverb:start` (Real-time Engine)

---

## 📘 Dokumentasi Tambahan

*   **[Manual Pengguna](PANDUAN_PENGGUNA.md)** - Panduan operasional untuk Kasir, Koki, dan Pelayan.
*   **[Panduan Deployment](DEPLOYMENT.md)** - Petunjuk teknis untuk instalasi di server VPS/Hosting.

---

<div align="center">
  <p>Dibuat dengan ❤️ untuk Masa Depan Digital Food & Beverage.</p>
</div>
