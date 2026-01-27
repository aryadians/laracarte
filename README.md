<p align="center">
  <img src="https://api.iconify.design/mdi/silverware-fork-knife.svg?color=%234f46e5&width=120&height=120" alt="LaraCarte Logo">
</p>

<h1 align="center">LaraCarte</h1>

<p align="center">
  Modern, Real-Time Restaurant & Cafe Point of Sale (POS) System.
  <br>
  Built with the <strong>TALL Stack</strong>: Tailwind, Alpine.js, Laravel, and Livewire.
</p>

<p align="center">
  <img src="https://img.shields.io/badge/PHP-8.2+-777BB4.svg?style=for-the-badge&logo=php" alt="PHP 8.2+">
  <img src="https://img.shields.io/badge/Laravel-11.x-FF2D20.svg?style=for-the-badge&logo=laravel" alt="Laravel 11.x">
  <img src="https://img.shields.io/badge/Livewire-3.x-4d55d2.svg?style=for-the-badge&logo=livewire" alt="Livewire 3">
  <img src="https://img.shields.io/badge/License-MIT-green.svg?style=for-the-badge" alt="License MIT">
</p>

---

**LaraCarte** adalah sistem manajemen restoran dan kafe yang intuitif dan *real-time*. Didesain untuk menyederhanakan operasional, mulai dari manajemen menu, pesanan pelanggan, hingga proses pembayaran di kasir. Pelanggan dapat dengan mudah memesan melalui QR code di meja mereka, dan semua pesanan akan langsung tampil di dasbor admin secara instan.

## ✨ Fitur Utama

### 🤵 Panel Admin
- 📈 **Dasbor Interaktif**: Pantau penjualan, pesanan masuk, dan produk terlaris secara *real-time*.
- 📦 **Manajemen Produk**: Atur menu, kategori, dan ketersediaan stok dengan mudah.
- 🍽️ **Manajemen Meja**: Kelola status meja (tersedia, terisi) untuk efisiensi layanan.
- 🛒 **Manajemen Pesanan**: Lihat dan kelola status pesanan (pending, dimasak, selesai) dalam satu layar.
- 💰 **Antarmuka Kasir**: Proses pembayaran dari pelanggan dengan cepat dan efisien.
- 📜 **Riwayat Transaksi**: Catat dan lihat semua transaksi yang telah selesai untuk keperluan akuntansi.
- 🔔 **Notifikasi Real-Time**: Dapatkan pemberitahuan instan untuk pesanan baru atau panggilan pelayan.

### 📱 Sisi Pelanggan
- 🤳 **Pemesanan via QR Code**: Pelanggan memindai QR code di meja untuk melihat menu dan memesan.
- 🛍️ **Keranjang Belanja Dinamis**: Pelanggan dapat menambah atau mengubah pesanan mereka secara langsung.
- 🙋 **Panggil Pelayan**: Tombol khusus bagi pelanggan untuk memanggil pelayan ke meja mereka.

## 🛠️ Teknologi yang Digunakan

| Kategori | Teknologi |
| :--- | :--- |
| **Framework** | 🚀 Laravel 11 |
| **UI/Frontend** | ⚡ Livewire 3, 🍃 Alpine.js, 💨 Tailwind CSS |
| **Database** | 🗄️ MySQL (default), PostgreSQL, SQLite |
| **Server-side** | 🐘 PHP 8.2+ |
| **Dev Tools** | 🎨 Vite, 📦 Composer, 📮 NPM |

## 🏁 Instalasi & Setup

Ikuti langkah-langkah berikut untuk menjalankan proyek ini di lingkungan lokal Anda.

1.  **Clone Repository**
    ```sh
    git clone https://github.com/your-username/laracarte.git
    cd laracarte
    ```

2.  **Install Dependencies**
    ```sh
    composer install
    npm install
    ```

3.  **Setup Environment**
    Salin file `.env.example` dan buat kunci aplikasi.
    ```sh
    cp .env.example .env
    php artisan key:generate
    ```

4.  **Konfigurasi Database**
    Buka file `.env` dan sesuaikan pengaturan database Anda (`DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`).

5.  **Jalankan Migrasi & Seeder**
    Perintah ini akan membuat struktur tabel dan mengisi data awal (termasuk akun admin).
    ```sh
    php artisan migrate --seed
    ```

6.  **Jalankan Server**
    ```sh
    npm run dev
    php artisan serve
    ```

7.  **Selesai!**
    Aplikasi Anda sekarang berjalan di `http://127.0.0.1:8000`.
    -   **Admin Email**: `admin@laracarte.com`
    -   **Password**: `password`

## 🤝 Berkontribusi

Kontribusi Anda sangat kami hargai! Jika Anda ingin berkontribusi, silakan fork proyek ini dan buat *pull request*.

1.  Fork the Project
2.  Create your Feature Branch (`git checkout -b feature/AmazingFeature`)
3.  Commit your Changes (`git commit -m 'Add some AmazingFeature'`)
4.  Push to the Branch (`git push origin feature/AmazingFeature`)
5.  Open a Pull Request

## 📄 Lisensi

Didistribusikan di bawah Lisensi MIT. Lihat `LICENSE` untuk informasi lebih lanjut.