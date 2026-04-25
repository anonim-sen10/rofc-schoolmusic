# Auto Deployment Setup Guide

## 1. GitHub Secrets Configuration

Anda perlu mengatur secrets berikut di GitHub repository:

### Required Secrets (Wajib):

- `SERVER_HOST` - IP atau domain server hosting Anda
- `SERVER_USER` - Username SSH (contoh: rofcmusi)
- `SSH_PRIVATE_KEY` - Private SSH key untuk authentikasi

### Optional Secrets (Opsional, jika berbeda dengan default):

- `SERVER_APP_DIR` - Path aplikasi Laravel (default: `/home/rofcmusi/rofc-laravel`)
- `SERVER_WEB_DIR` - Path web root (default: `/home/rofcmusi/public_html`)
- `DEPLOY_BRANCH` - Branch untuk deploy (default: `main`)
- `SERVER_PORT` - SSH Port (default: `22`)
- `SSH_PASSPHRASE` - Passphrase SSH key (jika ada)

## 2. Generate SSH Key

Jalankan di server hosting Anda:

```bash
ssh-keygen -t ed25519 -f ~/.ssh/deploy_key -N ""
cat ~/.ssh/deploy_key.pub >> ~/.ssh/authorized_keys
chmod 600 ~/.ssh/authorized_keys
chmod 700 ~/.ssh
```

Kemudian copy isi file `~/.ssh/deploy_key` ke GitHub secret `SSH_PRIVATE_KEY`

## 3. Setup di GitHub Repository

1. Buka **Settings** → **Secrets and variables** → **Actions**
2. Click **New repository secret**
3. Tambahkan setiap secret yang diperlukan

Contoh:

```
SERVER_HOST: 192.168.1.100
SERVER_USER: rofcmusi
SSH_PRIVATE_KEY: -----BEGIN OPENSSH PRIVATE KEY-----
                 [isi private key di sini]
                 -----END OPENSSH PRIVATE KEY-----
```

## 4. Periksa Prasyarat di Server

Di server hosting, pastikan:

```bash
# Verifikasi Node.js tersedia
node --version
npm --version

# Verifikasi PHP dan Composer
php --version
composer --version

# Verifikasi git
git --version

# Pastikan folder aplikasi memiliki permission yang tepat
ls -la /home/rofcmusi/
```

## 5. Deploy Workflow Cara Kerja

Ketika Anda push ke branch `main`, workflow akan:

1. ✅ **Checkout code** dari repository
2. ✅ **Setup Node.js 20** dengan npm cache
3. ✅ **Install dependencies** dengan `npm ci`
4. ✅ **Build assets** dengan `npm run build`
5. ✅ **Create stable asset names** (app.css, app.js, portal.css, portal.js)
6. ✅ **Upload assets** ke server `$SERVER_APP_DIR/public/build`
7. ✅ **Sync public folder** ke `$SERVER_WEB_DIR` (public_html)
8. ✅ **Clear Laravel cache** dan optimize

## 6. Testing & Troubleshooting

### Test SSH Connection Locally:

```bash
ssh -i path/to/private_key -p 22 rofcmusi@SERVER_HOST
```

### Manual Deploy Push:

```bash
git push origin main
```

Workflow akan otomatis berjalan. Lihat progress di **GitHub** → **Actions** tab

### Jika ada error:

- Check **Actions** tab untuk detail error
- Verifikasi SSH key permission: `chmod 600 ~/.ssh/deploy_key`
- Verifikasi secrets di GitHub (jangan sampai ada typo)
- Pastikan server bisa diakses dari GitHub

## 7. Post-Deploy Manual Steps (Jika ada perubahan aplikasi)

Jika ada perubahan database migration atau config:

```bash
# SSH ke server
ssh -i deploy_key rofcmusi@SERVER_HOST

# Masuk ke folder aplikasi
cd /home/rofcmusi/rofc-laravel

# Pull kode terbaru
git pull origin main

# Install Composer dependencies
composer install --no-dev --optimize-autoloader

# Run migrations
php artisan migrate --force

# Refresh cache
php artisan optimize:clear
```

## 8. Continuous Improvement

Dalam pengembangan, Anda bisa:

- Menambahkan unit/feature tests di workflow
- Menambahkan database backup sebelum migration
- Menambahkan slack notification untuk status deployment
- Menambahkan approval step sebelum production deploy
