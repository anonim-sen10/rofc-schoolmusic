# Deploy Configuration Guide

## Quick Setup

### 1. Copy SSH Private Key

Dari server hosting Anda, ambil SSH private key dan save di folder project:

```bash
# Di server hosting, copy private key
cat ~/.ssh/deploy_key

# Paste di lokal di folder project root sebagai file: deploy_key
# Pastikan permissions: chmod 600 deploy_key
```

### 2. Setup .env.deploy

File `.env.deploy` sudah dibuat. Edit dengan server details Anda:

```bash
# Buka .env.deploy dan isi dengan detail server:

DEPLOY_SERVER_HOST=192.168.1.100        # IP atau domain hosting
DEPLOY_SERVER_USER=rofcmusi              # SSH username
DEPLOY_SERVER_PORT=22                    # SSH port (default 22)
DEPLOY_SERVER_APP_DIR=/home/rofcmusi/rofc-laravel
DEPLOY_SERVER_WEB_DIR=/home/rofcmusi/public_html
DEPLOY_SSH_KEY_PATH=./deploy_key         # Path ke SSH private key
DEPLOY_SSH_PASSPHRASE=                   # Passphrase jika ada (kosongkan jika tidak)
AUTO_GIT_PUSH=true                       # Auto push ke git
AUTO_MIGRATE=false                       # Run migration (untuk nanti)
AUTO_SEED=false                          # Run seeder (untuk nanti)
```

### 3. Test SSH Connection

Sebelum deploy pertama kali, test SSH:

```bash
ssh -i ./deploy_key -p 22 rofcmusi@192.168.1.100

# Jika berhasil, you should be logged in ke server
# Type: exit untuk keluar
```

## Deployment Commands

### Deploy Lengkap (Build + Git Push + Deploy)
```bash
npm run deploy
```

Workflow:
1. `npm run build` - Build frontend assets
2. `git add`, `git commit`, `git push` - Push ke repository
3. SSH ke server dan:
   - `git pull` - Pull kode terbaru
   - `composer install` - Install PHP dependencies
   - `rsync` - Sync public folder ke web_root
   - `php artisan optimize:clear` - Clear caches

### Deploy Tanpa Build (Jika hanya code changes, bukan assets)
```bash
npm run deploy:no-build
```

Akan skip `npm run build` dan langsung git push + server deploy.

## Troubleshooting

### SSH Key Permission Error
```bash
# Fix permission
chmod 600 deploy_key
```

### SSH Connection Denied
```bash
# Test dengan verbose
ssh -v -i ./deploy_key rofcmusi@192.168.1.100

# Pastikan:
# 1. IP address benar
# 2. Username benar
# 3. Private key benar (sesuai dengan public key di server)
```

### Composer Install Fails
```bash
# SSH ke server dan test
ssh -i ./deploy_key rofcmusi@192.168.1.100
php -v
composer -v

# Jika tidak ada, install dulu
```

### Git Clone/Pull Fails
```bash
# SSH ke server dan test
cd /home/rofcmusi/rofc-laravel
git status

# Jika not a git repository, clone dulu:
cd /home/rofcmusi
git clone https://github.com/your-repo.git rofc-laravel
```

## Manual Deployment (Jika script error)

```bash
# Lokal
npm run build
git add -A
git commit -m "Build: $(date)"
git push origin main

# Di server
ssh -i deploy_key rofcmusi@192.168.1.100
cd /home/rofcmusi/rofc-laravel
git pull origin main
composer install --no-dev --optimize-autoloader
rsync -a --delete public/ /home/rofcmusi/public_html/
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Status Check

Setelah deploy, cek status:

```bash
# Check if files updated
ssh -i deploy_key rofcmusi@192.168.1.100 'ls -lah /home/rofcmusi/public_html/'
ssh -i deploy_key rofcmusi@192.168.1.100 'ls -lah /home/rofcmusi/public_html/build/'
```

## Next Steps

1. ✅ Download SSH private key dari server
2. ✅ Save sebagai `deploy_key` di folder project root
3. ✅ Edit `.env.deploy` dengan server details
4. ✅ Test SSH: `ssh -i ./deploy_key rofcmusi@SERVER_HOST`
5. 🚀 Siap deploy dengan: `npm run deploy`

---

**Note:** 
- `.env.deploy` dan `deploy_key` sudah di-add ke `.gitignore` - tidak akan ter-commit
- Jangan share SSH private key secara public!
- Jangan commit deploy_key ke git repository
