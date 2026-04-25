# ✅ Auto Deployment Checklist

## Quick Start Checklist

### Step 1: Server SSH Setup ⚠️ HARUS DILAKUKAN DULU
- [ ] SSH ke server: `ssh rofcmusi@SERVER_HOST`
- [ ] Generate SSH key: `ssh-keygen -t ed25519 -f ~/.ssh/deploy_key -N ""`
- [ ] Add public key: `cat ~/.ssh/deploy_key.pub >> ~/.ssh/authorized_keys`
- [ ] Fix permissions:
  ```bash
  chmod 600 ~/.ssh/authorized_keys
  chmod 700 ~/.ssh
  ```
- [ ] Copy private key content untuk GitHub secret

### Step 2: GitHub Secrets Setup
Di repository GitHub Anda → Settings → Secrets and variables → Actions

**Required (Wajib):**
- [ ] `SERVER_HOST` - IP atau domain server (contoh: 192.168.1.100)
- [ ] `SERVER_USER` - SSH username (contoh: rofcmusi)
- [ ] `SSH_PRIVATE_KEY` - Isi file `~/.ssh/deploy_key` dari server

**Optional:**
- [ ] `SERVER_APP_DIR` - Path Laravel (default: `/home/rofcmusi/rofc-laravel`)
- [ ] `SERVER_WEB_DIR` - Path web root (default: `/home/rofcmusi/public_html`)
- [ ] `SSH_PASSPHRASE` - Jika private key punya passphrase

### Step 3: Server Prerequisites Verification
SSH ke server dan jalankan:

```bash
# Check Node.js & npm
node --version  # minimal v20
npm --version

# Check PHP & Composer
php --version
composer --version

# Check git
git --version
```

Jika ada yang tidak terinstall, install dulu sebelum test deploy.

### Step 4: Application Folder Setup

Di server, persiapkan folder aplikasi:

```bash
# Buat folder jika belum ada
mkdir -p /home/rofcmusi/rofc-laravel
cd /home/rofcmusi/rofc-laravel

# Jika sudah ada git repository, skip clone
# Workflow akan auto git pull di deploy

# Pastikan folder web_root siap
mkdir -p /home/rofcmusi/public_html
```

### Step 5: Test Deployment

**First Time Only - Setup Repository on Server:**

```bash
cd /home/rofcmusi/rofc-laravel

# Jika folder kosong, clone repository dulu
# (atau workflow akan otomatis clone pada first deploy)
```

**Test Deploy:**

```bash
# Push ke main branch
git push origin main

# Lihat progress di GitHub → Actions tab
# Status akan tampil realtime
```

### Step 6: Monitor Deployment

**Watch in UI:**
1. Buka GitHub repo
2. Klik tab **Actions**
3. Lihat workflow run terbaru
4. Klik untuk lihat detail log

**Check Status Manually:**

```bash
# SSH ke server
ssh rofcmusi@SERVER_HOST

# Check if deployment folder updated
ls -lah /home/rofcmusi/rofc-laravel/

# Check if web root updated
ls -lah /home/rofcmusi/public_html/

# Check build folder
ls -lah /home/rofcmusi/public_html/build/
```

## Deployment Flow Diagram

```
┌─────────────────────────────────────────────────────────┐
│ Developer push to main branch (git push origin main)    │
└────────────────────┬────────────────────────────────────┘
                     │
                     ▼
┌─────────────────────────────────────────────────────────┐
│ GitHub Actions triggered (Auto Deploy workflow)         │
└────────────────┬────────────────────────────────────────┘
                 │
                 ▼
    ┌────────────────────────────────┐
    │ Build Frontend Assets          │
    │ - npm ci                       │
    │ - npm run build                │
    │ - Create stable asset names    │
    └────────┬───────────────────────┘
             │
             ▼
    ┌────────────────────────────────┐
    │ Deploy to Server via SSH        │
    │ ✓ Git pull on server           │
    │ ✓ Composer install             │
    │ ✓ Upload build assets          │
    │ ✓ Sync public folder           │
    │ ✓ Clear caches                 │
    └────────┬───────────────────────┘
             │
             ▼
┌─────────────────────────────────────────────────────────┐
│ ✅ Production Ready on public_html                       │
│ - Assets at /home/rofcmusi/public_html/build/           │
│ - Application code updated                              │
│ - Caches refreshed                                      │
└─────────────────────────────────────────────────────────┘
```

## Troubleshooting

### SSH Connection Failed
```bash
# Test SSH key locally
ssh -i ~/.ssh/deploy_key -p 22 rofcmusi@SERVER_HOST

# Check if key added to authorized_keys
cat ~/.ssh/authorized_keys
```

### Composer Install Fails
```bash
# SSH ke server dan check
php -v
composer -v

# Jika composer tidak ada, install:
curl -sS https://getcomposer.org/installer | php -- --install-dir /usr/local/bin --filename composer
```

### Git Clone Fails
```bash
# Check git installed
git --version

# Manual test clone
git clone https://github.com/your-repo.git /tmp/test-clone
```

### Assets Not Updated
```bash
# Check build folder exist
ls -la /home/rofcmusi/public_html/build/

# Check manifest file
cat /home/rofcmusi/public_html/build/manifest.json
```

## Manual Deployment (Jika workflow gagal)

```bash
# SSH ke server
ssh rofcmusi@SERVER_HOST

# Masuk folder app
cd /home/rofcmusi/rofc-laravel

# Pull kode terbaru
git pull origin main

# Install composer
composer install --no-dev --optimize-autoloader

# Build assets (local)
npm ci && npm run build

# Sync ke public_html
rsync -a --delete public/ /home/rofcmusi/public_html/

# Clear caches
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Status: READY TO DEPLOY ✅

Semua sudah siap. Tinggal:
1. Setup GitHub Secrets
2. SSH ke server dan setup SSH keysfile
3. Test dengan `git push origin main`
4. Monitor di GitHub Actions tab
