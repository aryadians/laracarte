# Panduan Deployment LaraCarte

Panduan ini berisi langkah-langkah untuk mendeploy LaraCarte ke server produksi (VPS Ubuntu/Debian).

## 1. Persiapan Server
Pastikan server Anda memiliki:
*   PHP 8.2 atau lebih tinggi.
*   Ekstensi PHP: `bcmath`, `ctype`, `fileinfo`, `json`, `mbstring`, `openssl`, `pcre`, `pdo`, `tokenizer`, `xml`, `intl`.
*   MySQL 8.0+.
*   Nginx atau Apache.
*   Composer.
- Node.js & NPM (untuk compile asset).

## 2. Langkah Instalasi

### 1. Clone Repositori
```bash
cd /var/www
git clone https://github.com/username/laracarte.git
cd laracarte
```

### 2. Install Dependensi
```bash
composer install --no-dev --optimize-autoloader
npm install && npm run build
```

### 3. Konfigurasi Environment
Salin file `.env` dan sesuaikan nilainya:
```bash
cp .env.example .env
nano .env
```
*   `APP_ENV=production`
*   `APP_DEBUG=false`
*   `APP_URL=https://domainanda.com`
*   Atur koneksi database (`DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`).
*   Atur kredensial Midtrans untuk pembayaran.

### 4. Setup Database & Key
```bash
php artisan key:generate --force
php artisan migrate --force --seed
php artisan storage:link
```

## 3. Optimasi Produksi
Lari perintah ini untuk meningkatkan performa:
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## 4. Konfigurasi Supervisor (Wajib)
LaraCarte menggunakan **Reverb** (Real-time) dan **Queue**. Gunakan Supervisor untuk menjaganya tetap berjalan.

Buat file `/etc/supervisor/conf.d/laracarte-worker.conf`:
```ini
[program:laracarte-reverb]
process_name=%(program_name)s
command=php /var/www/laracarte/artisan reverb:start
autostart=true
autorestart=true
user=www-data
redirect_stderr=true
stdout_logfile=/var/www/laracarte/storage/logs/reverb.log

[program:laracarte-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/laracarte/artisan queue:work --sleep=3 --tries=3
autostart=true
autorestart=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/laracarte/storage/logs/worker.log
```

Kemudian jalankan:
```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start all
```

## 5. SSL & Keamanan
Sangat disarankan menggunakan HTTPS (Certbot/Let's Encrypt):
```bash
sudo apt install certbot python3-certbot-nginx
sudo certbot --nginx -d domainanda.com
```

---
Jika ada kendala, silakan hubungi tim pengelola.
