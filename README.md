# ROFC School Music

Sistem informasi manajemen sekolah musik berbasis Laravel untuk pengelolaan role, kelas, jadwal, absensi, progress siswa, dan keuangan.

## Menjalankan Lokal

1. Salin environment:
   cp .env.example .env
2. Generate key:
   php artisan key:generate
3. Migrasi database:
   php artisan migrate
4. Jalankan server aplikasi:
   php artisan serve
5. Build asset frontend:
   npm run build

## Auto Deploy Setelah Push

Workflow deploy ada di [.github/workflows/deploy.yml](.github/workflows/deploy.yml).

Alur deploy otomatis sekarang:

1. GitHub Actions menjalankan npm ci dan npm run build.
2. Kode aplikasi di-pull ke server lewat SSH.
3. Folder public/build hasil build dikirim otomatis ke server.
4. Cache Laravel dibersihkan dan dibangun ulang.

Dengan alur ini, hosting tidak perlu npm dan tidak perlu upload build manual lagi.

Agar deploy otomatis berjalan saat push ke branch main, isi GitHub Actions Secrets berikut:

1. SERVER_HOST
2. SERVER_USER
3. SSH_PRIVATE_KEY
4. SERVER_PORT (opsional, default 22)
5. SERVER_APP_DIR (opsional, default /home/rofcmusi/rofc-laravel)
6. SERVER_WEB_DIR (opsional, default /home/rofcmusi/public_html)
7. DEPLOY_BRANCH (opsional, default main)

Catatan path untuk shared hosting (cPanel):

1. SERVER_APP_DIR: folder source Laravel (contoh /home/USERNAME/rofc-laravel).
2. SERVER_WEB_DIR: document root domain (contoh /home/USERNAME/public_html atau /home/USERNAME/public_html/nama-domain).

Jika SERVER_WEB_DIR berbeda dengan SERVER_APP_DIR/public, workflow akan otomatis sync isi folder public Laravel ke web root domain.

Setelah secrets terisi, setiap git push ke main akan langsung deploy ke server tanpa perlu pull manual di hosting.
