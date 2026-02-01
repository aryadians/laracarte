# 📘 Panduan Pengguna LaraCarte POS

Selamat datang di sistem manajemen restoran **LaraCarte**. Dokumen ini berisi panduan lengkap untuk staf dalam mengoperasikan sistem mulai dari pemesanan hingga laporan.

---

## 👨‍🍳 1. Alur Pemesanan Pelanggan (Self-Service)
Pelanggan melakukan pemesanan secara mandiri melalui HP mereka.
1.  **Scan QR:** Pelanggan memindai QR Code yang ada di meja.
2.  **Pilih Menu:** Pelanggan memilih makanan/minuman dan menentukan jumlahnya.
3.  **Checkout:** Pelanggan memasukkan nama dan memilih metode pembayaran:
    *   **Bayar di Kasir:** Pelanggan memesan dulu, bayar setelah makan.
    *   **Scan QRIS:** Pelanggan membayar langsung via transfer (bukti ditunjukkan ke kasir).
4.  **Konfirmasi:** Pesanan akan otomatis muncul di layar Dapur (KDS).

---

## 🔪 2. Layar Dapur (Kitchen Display System)
Staf Dapur menggunakan menu **"Dapur (KDS)"** untuk memantau antrian.
*   **Warna Merah (Pending):** Pesanan baru masuk dan belum diproses. Klik tombol **"Mulai Masak"** untuk mengubah status.
*   **Warna Oranye (Cooking):** Pesanan sedang dimasak. Jika masakan sudah selesai, klik tombol **"Siap Saji"**.
*   **Warna Biru (Served):** Makanan sudah diantar ke meja pelanggan. Pesanan akan hilang dari layar dapur setelah pelanggan membayar di kasir.

---

## 💰 3. Operasional Kasir (POS)
Staf Kasir bertanggung jawab atas pembayaran dan struk.
1.  Buka menu **"Kasir (POS)"**.
2.  Cari meja pelanggan yang ingin membayar (status biasanya "Siap Bayar").
3.  Klik tombol **"Bayar / Struk"**.
4.  **Proses Pembayaran:**
    *   **Tunai:** Masukkan jumlah uang yang diterima, sistem akan menghitung kembalian secara otomatis. Klik **"Bayar Tunai"**.
    *   **QRIS:** Jika pelanggan sudah bayar via HP, cek mutasi bank Anda. Jika uang sudah masuk, klik **"Uang Masuk (Lunas)"**.
5.  **Cetak Struk:** Setelah lunas, klik **"Direct Print"** untuk mencetak ke printer thermal atau **"Review"** untuk mencetak manual via browser.

---

## ⚙️ 4. Pengaturan & Master Data (Admin Only)
### A. Menambah Produk Baru
*   Masuk ke menu **"Produk & Menu"**.
*   Klik **"Tambah Produk Baru"**.
*   Pastikan memasukkan **Stok Awal** dan **Min. Alert**. Jika stok mencapai angka *Min. Alert*, sistem akan memberi peringatan warna merah.

### B. Manajemen Meja & QR
*   Masuk ke menu **"Manajemen Meja"**.
*   Klik tombol **"Print"** pada kartu meja untuk mencetak QR Code. Tempelkan cetakan ini pada meja yang sesuai di restoran.

### C. Konfigurasi Toko
*   Masuk ke menu **"Pengaturan"**.
*   Di sini Anda bisa mengubah **Nama Restoran**, **Alamat** (yang tampil di struk), serta besaran **Pajak (PPN)** dan **Service Charge**.
*   **Nama Printer:** Isi dengan nama printer thermal yang terdeteksi di Windows (contoh: `POS-58` atau `GP-58`).

---

## 📊 5. Laporan Keuangan
*   **Dashboard:** Ringkasan penjualan hari ini, total pesanan, dan grafik mingguan.
*   **Laporan Analitik:** Filter data berdasarkan tanggal untuk melihat total omzet dan produk terlaris.
*   **Export:** Anda bisa mengunduh data transaksi ke format **Excel** atau **PDF** untuk keperluan pembukuan.

---

## 🆘 Bantuan Cepat
*   **Pesanan tidak muncul di dapur?** Pastikan perintah `php artisan reverb:start` sedang berjalan di server.
*   **Printer tidak merespon?** Cek koneksi kabel USB dan pastikan Nama Printer di menu "Pengaturan" sudah sama persis dengan yang ada di Control Panel Windows.

---
*LaraCarte v1.0.0 - Dibuat dengan ❤️ untuk kemajuan bisnismu.*
