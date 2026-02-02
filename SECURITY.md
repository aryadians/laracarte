# Kebijakan Keamanan (Security Policy)

## Versi yang Didukung

Kami sangat menyarankan Anda untuk selalu menggunakan versi terbaru dari LaraCarte. Berikut adalah dukungan keamanan saat ini:

| Versi | Didukung |
|-------|----------|
| 1.0.x | ✅ Ya |
| < 1.0 | ❌ Tidak |

## Pelaporan Kerentanan

Jika Anda menemukan kerentanan keamanan di LaraCarte, mohon **jangan** membuka issue publik. Sebagai gantinya, silakan ikuti langkah berikut:

1.  Kirimkan email ke tim pengelola keamanan kami di `security@laracarte.com` (ganti dengan email asli jika ada).
2.  Berikan deskripsi detail tentang kerentanan tersebut.
3.  Berikan langkah-langkah untuk mereproduksi masalah tersebut (Proof of Concept).

Kami akan berusaha memberikan tanggapan awal dalam waktu **24-48 jam** dan akan bekerja sama dengan Anda untuk mendiskusikan langkah-langkah perbaikan hingga patch keamanan dirilis.

## Kebijakan Pengungkapan (Disclosure Policy)

Demi keamanan seluruh pengguna platform, kami meminta Anda untuk memberikan waktu bagi tim pengembang untuk memperbaiki masalah sebelum informasi kerentanan tersebut diungkapkan ke publik (Responsible Disclosure).

## Keamanan di Produksi

Pastikan Anda selalu:
*   Menonaktifkan `APP_DEBUG` di lingkungan produksi.
*   Menggunakan protokol HTTPS.
*   Mengatur `APP_KEY` dengan aman dan rahasia.
*   Melakukan pembaruan terhadap dependensi paket Laravel secara berkala (`composer update`).

Terima kasih telah membantu menjaga keamanan ekosistem LaraCarte!
