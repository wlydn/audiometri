# Audit aplikasi audiometri

## Kesimpulan dan batasan
Belum layak dinyatakan 10/10 atau siap produksi untuk data medis. Audit mencakup controller, model, konfigurasi, library ekspor, view utama dan alur JavaScript; bukan sertifikasi keamanan/klinis. Tidak semua cabang fungsi telah dieksekusi.

PHP CLI yang dipakai: 8.1.30, tanpa ekstensi mysqli. Belum dilakukan browser desktop/mobile, database/CRUD end-to-end, pemeriksaan Excel di Office, PDF visual, dependency vulnerability scan, load test atau validasi klinis.

## Perbaikan dan bukti
- Nilai 0 dB dipertahankan; hanya null/string kosong menjadi null.
- Field wajib menolak array tanpa TypeError; umur pecahan ditolak; tanggal non-string ditolak.
- Sel right_bc_250 pada detail memakai nilai kanan, bukan kiri.
- Menambahkan error_page dan patient_data_partial yang sebelumnya dipanggil tetapi tidak tersedia. Output di-escape; detail exception disembunyikan pada production.
- Tes: `php tests/audit_regression.php`. Baseline 7 lolos/5 gagal; setelah perbaikan 12 lolos/0 gagal. Menggunakan stub input/reflection, bukan HTTP/database.
- Lint awal 41 file PHP aplikasi, tidak termasuk third_party: 0 syntax error.

## Temuan terbuka

### P0: penghalang produksi
1. Tidak ada autentikasi/otorisasi pada controller Audiometri. CSRF/header AJAX bukan kontrol akses. CRUD, review, riwayat dan ekspor perlu akun dan pemeriksaan izin per endpoint.
2. Tidak ada jejak audit pengguna untuk perubahan/review/penghapusan. Tentukan role operator/dokter/admin, kebijakan retensi, penghapusan dan backup-restore.

### P1: integritas, keamanan dan fungsi utama
3. Ditemukan output filename berbasis nama pasien tanpa escaping pada title dan tanpa normalisasi Content-Disposition di Pdf_generator::_generate_html_fallback. Sudah ditambal dengan htmlspecialchars dan allowlist filename; verifikasi browser/download end-to-end masih diperlukan.
4. export_excel mengabaikan filter laporan dan memakai get_all_tests tanpa batas. Ekspor aktif berupa HTML berakhiran .xls, bukan workbook asli; hanya AC diekspor. Perlindungan formula injection dan preservasi nol awal No RM belum terverifikasi.
5. update_impression memanggil update_test dengan impression saja, padahal model mewajibkan data pasien lengkap. review_dokter menulis ulang seluruh snapshot pasien: berisiko menimpa perubahan paralel. Perlu operasi model khusus review.
6. Validasi scalar belum menyeluruh: filter GET, pencarian No RM, impression dan input langsung ke model masih memiliki jalur trim/DateTime yang dapat menerima array. catch Exception tidak menangkap TypeError.
7. Validasi save/update model tidak identik; batas panjang field/allowlist kolom perlu ditetapkan. Pemeriksaan duplikat dilakukan sebelum transaksi dan perlu constraint database untuk race condition.
8. audiometri_db.sql tidak berisi CREATE TABLE. Skema/index/constraint aktual belum terverifikasi; instalasi baru tidak reproducible dari dump ini.
9. Form melakukan form.submit() otomatis saat fetch/JSON gagal. Respons hilang setelah penyimpanan dapat memicu POST ulang; hindari retry mutasi otomatis dan pertimbangkan idempotency.

### P2: struktur dan penggunaan
10. list_tests menerima page negatif; normalisasi sebelum query. Model memanggil escape_like_str sebelum Query Builder like: pencarian %, _ dan ! perlu tes regresi.
11. PDF generator mengecek class_exists sebelum memuat TCPDF; jalur normal tanpa autoloader cenderung fallback HTML. Fallback menyisipkan dokumen HTML lengkap ke dokumen lain.
12. Excel_export merujuk autoloader PhpSpreadsheet yang tidak tersedia pada path tersebut, memakai setCellValue untuk teks pengguna, dan range('A', 'AK') bukan enumerasi kolom Excel yang benar. Library ini tidak dipakai ekspor aktif.
13. Controller mencampur validasi, SVG, ekspor dan review. Renderer grafik berulang pada form/detail/review/PDF. Pisahkan bertahap dengan tes.
14. Simbol/warna AC/BC, rata-rata frekuensi, pembulatan cetak, batas tanggal 5 tahun dan klasifikasi hearing loss perlu persetujuan klinisi; hasil otomatis bukan diagnosis terverifikasi.
15. Input umur bertipe text sehingga min/max tidak bekerja; simpan type button tidak menjalankan validasi native tanpa reportValidity. Masih ada atribut Bootstrap lama dengan Bootstrap 5, serta alert hilang otomatis setelah 5 detik.
16. Submit ganda, non-JSON, CSRF kedaluwarsa, keyboard dan responsive belum diuji browser. CDN tanpa SRI dan SweetAlert major version floating: tetapkan versi/aset lokal sesuai kebutuhan offline.
17. log_threshold=0 mematikan log error aplikasi. Logging harus aktif secara aman, tanpa membocorkan data pasien.
18. composer.json menyatakan PHP >=5.3.7 tetapi kode memakai fitur modern; composer.lock diabaikan; konfigurasi tes bawaan tidak tersedia. Tetapkan runtime supported dan dependency lock konsisten.
19. .htaccess hanya melindungi jika Apache menerapkannya. Nginx/IIS/PHP built-in memerlukan konfigurasi setara.

## Kriteria menuju kualitas produksi
- Selesaikan P0/P1 dengan acceptance test dan role/izin yang disepakati.
- Sediakan skema/migrasi dan database pengujian terisolasi, bukan data pasien nyata.
- Uji HTTP untuk CRUD, review atomik, filter, pagination, duplikat paralel dan ekspor.
- Uji XSS, CSRF, otorisasi, formula injection, input array, tanggal dan idempotency.
- Uji browser desktop/mobile/keyboard; bandingkan grafik/tabel/cetak dengan fixture yang disetujui klinisi.
- Verifikasi dependency, logging, backup-restore dan deployment HTTPS.
- Tidak ada temuan kritis/tinggi terbuka; instalasi dan operasional terdokumentasi.

Lint dan 12 tes terbatas bukan dasar pemberian skor 10/10.
