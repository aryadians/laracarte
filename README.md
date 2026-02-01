<div align="center">

  <img src="public/favicon.svg" alt="LaraCarte Logo" width="100" height="100">

  # LaraCarte
  ### Restaurant POS & Management System

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
  </p>

  <p align="center">
    <strong>Sistem Manajemen Restoran Terintegrasi: Pesan, Masak, Bayar, Pantau.</strong>
  </p>

</div>

---

## ✨ Fitur Lengkap

LaraCarte dirancang untuk mendigitalkan seluruh alur kerja restoran, mulai dari pelanggan duduk hingga laporan keuangan harian.

### 📱 Pemesanan Mandiri (Customer Self-Service)
Pelanggan memegang kendali penuh atas pesanan mereka tanpa perlu memanggil pelayan berkali-kali.
*   **QR Code Ordering:** Pelanggan scan kode unik di meja untuk membuka menu digital.
*   **Kustomisasi Pesanan (Varian):** Dukungan penuh untuk opsi menu seperti "Level Pedas", "Topping Tambahan", atau "Ukuran Porsi".
*   **Keranjang Belanja Pintar:** Menampung berbagai kombinasi varian menu dalam satu transaksi.
*   **Panggil Pelayan:** Tombol digital untuk memanggil bantuan staf ke meja.
*   **Estimasi Harga:** Perhitungan otomatis Subtotal, Pajak, dan Service Charge sebelum checkout.

### 👨‍🍳 Sistem Dapur Real-Time (KDS)
Komunikasi instan antara pelanggan dan dapur tanpa kertas/teriakan.
*   **Live Updates:** Pesanan baru muncul detik itu juga di layar dapur (teknologi WebSocket).
*   **Status Warna:** Indikator visual intuitif (Merah: Baru, Oranye: Dimasak, Biru: Siap Saji).
*   **Notifikasi Suara:** Bunyi peringatan otomatis saat ada pesanan masuk atau panggilan pelayan.
*   **Detail Pesanan:** Koki bisa melihat rincian varian/topping dengan jelas agar tidak salah masak.

### 💰 Kasir & Pembayaran (POS)
Titik penjualan yang cepat, akurat, dan fleksibel.
*   **Multi-Payment:** Dukungan pembayaran Tunai dan konfirmasi manual transfer QRIS.
*   **Hitung Kembalian:** Kalkulator otomatis untuk mempercepat transaksi tunai.
*   **Cetak Struk (Thermal Printing):**
    *   **Direct Print:** Kirim perintah cetak langsung ke printer ESC/POS (Server-side).
    *   **Popup Print:** Cetak melalui dialog browser dengan format kertas 58mm yang rapi.
*   **Manajemen Meja:** Lihat status meja (Kosong/Terisi/Kotor) secara real-time.

### ⚙️ Manajemen Restoran (Back Office)
Kendali penuh di tangan pemilik/manajer.
*   **Manajemen Produk:** Tambah/Edit/Hapus menu, atur stok, upload foto, dan kelola varian harga.
*   **Generator QR Meja:** Buat dan cetak kartu meja berisi QR Code secara otomatis dari dashboard.
*   **Pengaturan Toko Dinamis:** Ubah nama resto, alamat struk, persentase pajak, dan service charge tanpa coding.
*   **Manajemen Stok:** Peringatan otomatis jika stok bahan baku menipis.

### 📊 Laporan & Analitik
Data akurat untuk keputusan bisnis yang lebih baik.
*   **Dashboard Eksekutif:** Ringkasan omzet hari ini, produk terlaris, dan antrian aktif.
*   **Laporan Penjualan:** Filter data berdasarkan rentang tanggal tertentu.
*   **Export Data:** Unduh laporan lengkap dalam format **Excel (.xlsx)** atau **PDF** siap cetak.

---

## 🛠️ Instalasi & Penggunaan

Ikuti langkah ini untuk menjalankan LaraCarte di komputer lokal Anda.

### 1. Persiapan
Pastikan Anda sudah menginstal:
*   PHP 8.2+
*   Composer
*   Node.js & NPM
*   MySQL

### 2. Instalasi Kode
```bash
# Clone repositori
git clone https://github.com/username/laracarte.git
cd laracarte

# Install dependensi PHP & JavaScript
composer install
npm install
```

### 3. Konfigurasi
```bash
# Setup file .env
cp .env.example .env
php artisan key:generate

# Edit .env sesuaikan database Anda:
# DB_DATABASE=laracarte
# DB_USERNAME=root
# DB_PASSWORD=
```

### 4. Database & Setup Awal
```bash
# Jalankan migrasi dan data awal (seeder)
php artisan migrate --seed
php artisan storage:link
```

### 5. Menjalankan Aplikasi
Anda perlu membuka **3 terminal** berbeda agar sistem berjalan penuh:

*Terminal 1 (Aplikasi Utama):*
```bash
php artisan serve
```

*Terminal 2 (Aset Frontend):*
```bash
npm run dev
```

*Terminal 3 (WebSocket Real-time):*
```bash
php artisan reverb:start
```

Buka browser dan akses: `http://127.0.0.1:8000`

---

## 📘 Dokumentasi Tambahan

*   **[Panduan Pengguna (User Guide)](PANDUAN_PENGGUNA.md)** - Cara pakai untuk staf.
*   **[Panduan Deployment](DEPLOYMENT.md)** - Cara upload ke server VPS.

---

<div align="center">
  <p>Dibuat dengan ❤️ menggunakan Laravel.</p>
</div>