# Audiometri

## URL produksi

Deploy isi proyek ke folder `audiometri` pada document root hosting (bukan folder
`audiometri/audiometri`). Set environment server:

```text
APP_BASE_URL=https://wlydn.my.id/audiometri/
CI_ENV=production
```

`APP_BASE_URL` harus menyertakan trailing slash dan tidak menyertakan `index.php`
atau nama controller tambahan. Default lokal tetap `http://localhost:8080/audiometri/`.
Pastikan Apache `mod_rewrite` aktif dan `AllowOverride` mengizinkan `.htaccess` proyek.
Jangan mengganti konfigurasi database produksi ketika mengunggah perubahan.

URL kanonis:
- Dashboard: `https://wlydn.my.id/audiometri/`
- Pemeriksaan baru: `https://wlydn.my.id/audiometri/create`
- Daftar: `https://wlydn.my.id/audiometri/list_tests`
- Panel dokter: `https://wlydn.my.id/audiometri/doctor_view`

GET/HEAD ke rute lama dengan prefiks controller tambahan dialihkan (301) ke URL
pendek; query filter dipertahankan. POST lama tetap diterima tanpa redirect agar
body form tidak hilang. Fragment `#main-content` adalah target skip-link aksesibilitas,
bukan folder atau controller, dan sengaja dipertahankan.

## Nama folder dan repository

Nama aplikasi adalah **Audiometri**, nama folder deployment adalah `audiometri`.
Repository GitHub: `https://github.com/wlydn/audiometri`.
Remote Git: `https://github.com/wlydn/audiometri.git`.
Target folder lokal: `D:\Project\audiometri`.
Nama checkout/repository tidak menentukan URL aplikasi. Tutup workspace lama
sebelum membuka folder baru jika Windows mengunci folder saat pemindahan.
Jangan menimpa folder tujuan jika sudah ada.

Pada hosting, cadangkan konfigurasi dan database sebelum mengubah folder. Jika
folder publik sudah bernama `audiometri`, tidak perlu memindahkannya lagi.
Jika masih `web-audiometri`, pindahkan menjadi `audiometri` melalui file manager
atau SSH, lalu perbarui deployment path, remote Git, cron, dan konfigurasi web
server yang merujuk ke folder lama. Pertahankan konfigurasi database server.
Perubahan source code dan nama repository tidak otomatis memindahkan folder
hosting atau menerapkan environment server.

## Verifikasi setelah deployment

1. Buka dashboard dan pastikan menu/aset memakai `/audiometri/` hanya sekali.
2. Buka URL lama `/audiometri/audiometri` dan pastikan menuju `/audiometri/`.
3. Uji pencarian berfilter, pagination, form, review, dan ekspor pada database uji.
4. Jalankan `php tests/routing_regression.php` dan `php tests/audit_regression.php`.

## Catatan keamanan

- Header keamanan (CSP, HSTS pada HTTPS, `X-Frame-Options`, `Referrer-Policy`,
  `Permissions-Policy`, `Cache-Control: no-store`) dikirim oleh controller dan
  `.htaccess`, jadi tetap berlaku bila `mod_headers` tidak aktif.
- CSP masih memakai `'unsafe-inline'` karena view memakai script/style inline.
  Pindahkan ke file terpisah atau nonce sebelum menghapusnya.
- Log error aktif (`log_threshold = 1`) di `application/logs`; folder ini ditolak
  `.htaccess`. Pada Nginx/IIS, tambahkan aturan setara agar `application/` dan
  `system/` tidak dapat diakses publik.
- Di belakang Cloudflare, set `proxy_ips` di `application/config/config.php`
  ke rentang IP Cloudflare bila IP klien dibutuhkan (mis. audit). `sess_match_ip`
  dibiarkan `FALSE` agar sesi tidak putus saat IP berubah.
- SweetAlert2 dikunci ke versi `11.26.25` dengan SRI. Perbarui hash bersamaan
  dengan versi saat upgrade.
- **Belum ada login/otorisasi.** Batasi akses dengan HTTP Basic Auth / Cloudflare
  Access / VPN sampai autentikasi aplikasi dibuat. Lihat `AUDIT.md` (P0).

