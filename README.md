# 🍽️ LaraCarte
### Modern Restaurant POS & Management System

![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel)
![Livewire](https://img.shields.io/badge/Livewire-3.x-4E56A6?style=for-the-badge&logo=livewire)
![TailwindCSS](https://img.shields.io/badge/Tailwind_CSS-3.x-38B2AC?style=for-the-badge&logo=tailwind-css)
![AlpineJS](https://img.shields.io/badge/Alpine.js-3.x-8BC0D0?style=for-the-badge&logo=alpine.js)
![MySQL](https://img.shields.io/badge/MySQL-8.0+-4479A1?style=for-the-badge&logo=mysql)
![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)

**LaraCarte** adalah solusi lengkap manajemen restoran berbasis web yang dirancang untuk kecepatan dan efisiensi. Dari pemesanan mandiri via QR Code hingga tampilan dapur real-time, semuanya terintegrasi dalam satu sistem yang mulus.

---

## 🚀 Fitur Unggulan (v1.0.0)

### 📱 1. Self-Service QR Ordering
Pelanggan memesan langsung dari meja mereka tanpa menunggu pelayan.
- **Scan & Order:** Akses menu instan via QR Code unik per meja.
- **Product Variants:** Dukungan untuk varian kompleks (e.g., Level Pedas, Extra Topping).
- **Flexible Cart:** Keranjang belanja cerdas yang memisahkan item berdasarkan varian.
- **Call Waitress:** Tombol panggil pelayan digital.

### 👨‍🍳 2. Real-Time Kitchen Display System (KDS)
Ditenagai oleh **Laravel Reverb (WebSocket)**.
- **Instant Updates:** Pesanan muncul di layar dapur detik itu juga tanpa refresh.
- **Visual Status:** Indikator warna untuk status *Pending* (Baru), *Cooking* (Dimasak), dan *Served* (Saji).
- **Audio Alert:** Notifikasi suara saat pesanan baru masuk atau ada panggilan pelayan.

### 💰 3. Modern Point of Sales (POS)
Kasir yang cepat dan responsif.
- **Quick Checkout:** Proses pembayaran Tunai & QRIS yang efisien.
- **Thermal Printing:**
    - **Direct Print:** Cetak langsung ke printer ESC/POS (Server-side).
    - **Popup Print:** Cetak struk rapi via browser (Client-side).
- **QRIS Validation:** Fitur konfirmasi pembayaran digital.

### 📊 4. Laporan & Analitik
Pantau performa bisnis Anda secara akurat.
- **Dashboard Interaktif:** Grafik omzet harian, stok menipis, dan produk terlaris.
- **Export Data:** Download laporan penjualan dalam format **Excel** (.xlsx) dan **PDF**.
- **Date Filter:** Filter laporan berdasarkan rentang tanggal kustom.

### ⚙️ 5. Manajemen Toko Dinamis
- **Settings UI:** Ubah Nama Toko, Pajak (%), Service Charge (%), dan Nama Printer langsung dari admin panel.
- **Table Management:** Generate dan cetak kartu QR meja secara otomatis.
- **Inventory:** Peringatan stok menipis otomatis.

---

## 🛠️ Persyaratan Sistem

- PHP 8.2+
- Composer
- Node.js & NPM
- MySQL
- Printer Thermal (Opsional, untuk fitur cetak fisik)

---

## 📦 Instalasi (Local Development)

1.  **Clone Repository**
    ```bash
    git clone https://github.com/username/laracarte.git
    cd laracarte
    ```

2.  **Install Dependencies**
    ```bash
    composer install
    npm install
    ```

3.  **Setup Environment**
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```
    *Edit file `.env` dan sesuaikan koneksi database Anda.*

4.  **Migrasi & Seeding**
    ```bash
    php artisan migrate --seed
    # Seed akan mengisi user admin default dan setting awal
    ```

5.  **Setup Storage**
    ```bash
    php artisan storage:link
    ```

6.  **Jalankan Aplikasi**
    Anda perlu menjalankan 3 terminal terpisah:
    
    *Terminal 1 (Server Laravel):*
    ```bash
    php artisan serve
    ```
    
    *Terminal 2 (Vite Assets):*
    ```bash
    npm run dev
    ```
    
    *Terminal 3 (Reverb WebSocket):*
    ```bash
    php artisan reverb:start
    ```

---

## 📘 Dokumentasi

- **[Panduan Pengguna (User Guide)](PANDUAN_PENGGUNA.md)**: Cara menggunakan aplikasi untuk Kasir, Koki, dan Admin.
- **[Panduan Deployment](DEPLOYMENT.md)**: Cara mengonlinekan aplikasi ke VPS/Hosting.

---

## 🔐 Akun Default (Seeder)

*   **Email:** `admin@laracarte.com` (Cek `DatabaseSeeder.php` jika berbeda)
*   **Password:** `password`

---

## ❤️ Credits

Dibuat dengan framework **Laravel** yang luar biasa.
- **Livewire** untuk interaktivitas full-stack.
- **Tailwind CSS** untuk desain modern.
- **Laravel Reverb** untuk WebSocket real-time.
- **Mike42/Escpos** untuk thermal printing.

---

**LaraCarte** - *Digitize Your Restaurant Today.*
