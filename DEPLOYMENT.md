# 🚀 Panduan Deployment LaraCarte

Dokumen ini menjelaskan cara menginstal aplikasi **LaraCarte** di server produksi (VPS Ubuntu/Debian disarankan).

---

## 📋 Persyaratan Sistem
*   **PHP 8.2+** (dengan ekstensi: bcmath, intl, gd, zip)
*   **MySQL 8.0+**
*   **Node.js & NPM** (untuk build assets & Reverb)
*   **Composer**
*   **Nginx** (sebagai Reverse Proxy)

---

## 🛠️ Langkah Instalasi

### 1. Clone & Persiapan Awal
```bash
git clone <url-repo-anda> /var/www/laracarte
cd /var/www/laracarte
composer install --optimize-autoloader --no-dev
cp .env.example .env
php artisan key:generate
```

### 2. Konfigurasi Database
Edit file `.env` dan sesuaikan `DB_DATABASE`, `DB_USERNAME`, dan `DB_PASSWORD`. Kemudian jalankan migrasi:
```bash
php artisan migrate --force
php artisan db:seed --class=SettingSeeder
```

### 3. Build Assets
```bash
npm install
npm run build
```

### 4. Konfigurasi Laravel Reverb (PENTING)
Agar fitur Real-time KDS berjalan di server dengan HTTPS, pastikan `.env` Anda diatur seperti ini:
```env
REVERB_SERVER_HOST=0.0.0.0
REVERB_SERVER_PORT=8081
REVERB_HOST="domain-anda.com"
REVERB_PORT=443
REVERB_SCHEME=https

VITE_REVERB_HOST="${REVERB_HOST}"
VITE_REVERB_PORT="${REVERB_PORT}"
VITE_REVERB_SCHEME="${REVERB_SCHEME}"
```

---

## 🔄 Menjalankan Layanan Background
Produksi membutuhkan **Supervisor** untuk memastikan layanan tetap berjalan jika server restart.

### A. Supervisor untuk Queue (Antrian)
Buat file `/etc/supervisor/conf.d/laracarte-worker.conf`:
```ini
[program:laracarte-worker]
command=php /var/www/laracarte/artisan queue:work --sleep=3 --tries=3
autostart=true
autorestart=true
user=www-data
```

### B. Supervisor untuk Reverb (WebSocket)
Buat file `/etc/supervisor/conf.d/laracarte-reverb.conf`:
```ini
[program:laracarte-reverb]
command=php /var/www/laracarte/artisan reverb:start --port=8081
autostart=true
autorestart=true
user=www-data
```

---

## 🌐 Konfigurasi Nginx
Anda perlu memetakan traffic WebSocket ke port Reverb (8081). Tambahkan ini di blok `server` Nginx Anda:

```nginx
location /app {
    proxy_http_version 1.1;
    proxy_set_header Host $http_host;
    proxy_set_header Scheme $scheme;
    proxy_set_header SERVER_PORT $server_port;
    proxy_set_header REMOTE_ADDR $remote_addr;
    proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
    proxy_set_header Upgrade $http_upgrade;
    proxy_set_header Connection "Upgrade";

    proxy_pass http://127.0.0.1:8081;
}
```

---

## 🔒 Security Checklist
1.  Pastikan `APP_DEBUG=false` di `.env`.
2.  Jalankan `php artisan storage:link`.
3.  Gunakan SSL (Certbot/Let's Encrypt).
4.  Folder `storage` dan `bootstrap/cache` harus memiliki akses tulis (`chmod -R 775`).

---
*LaraCarte v1.0.0 Deployment Guide*
