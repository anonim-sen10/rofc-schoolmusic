# PERATURAN KRITIKAL PROJECT (CRITICAL RULES)

**1. JANGAN PERNAH MENGHAPUS / WIPE / DROP DATABASE**
Mulai tanggal 13 Juni 2026, database lokal (Localhost) di project ini sudah menggunakan **DATA ASLI (PRODUCTION DATA)** hasil tarikan dari server live hosting. 
**DILARANG KERAS** menjalankan perintah seperti:
- `php artisan db:wipe`
- `php artisan migrate:fresh`
- `php artisan migrate:refresh`
- Menghapus tabel secara manual

**2. HATI-HATI DENGAN DATA**
Setiap operasi yang mengubah, menghapus, atau memanipulasi data (Update/Delete) harus dipikirkan matang-matang karena ini adalah data asli milik Rofc Music School. Jangan sampai data murid, absen, kelas, atau transaksi hilang.

**3. MIGRATION HANYA UNTUK TABEL BARU**
Jika membutuhkan perubahan struktur database, buat file *migration* baru dan hanya jalankan `php artisan migrate`. Jangan pernah me-reset ulang *migration* yang sudah ada.
