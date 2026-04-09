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

Agar deploy otomatis berjalan saat push ke branch main, isi GitHub Actions Secrets berikut:

1. SERVER_HOST
2. SERVER_USER
3. SSH_PRIVATE_KEY
4. SERVER_PORT (opsional, default 22)
5. SERVER_APP_DIR (opsional, default /home/rofcmusi/rofc-laravel)
6. DEPLOY_BRANCH (opsional, default main)

Setelah secrets terisi, setiap git push ke main akan langsung deploy ke server tanpa perlu pull manual di hosting.
