# 🚀 Manual Deployment Guide via GitHub Actions + cPanel

## Overview

Karena SSH tidak tersedia, kami menggunakan workflow ini:

```
Local Development
    ↓ (git push)
GitHub Actions (Build & Create Artifact)
    ↓ (download artifact)
Manual Upload via cPanel
    ↓
Live on Server ✅
```

---

## Langkah-Langkah Deployment

### 1️⃣ Push Code ke GitHub

Di lokal, jalankan:

```bash
# Build assets terlebih dahulu (opsional, GitHub akan rebuild)
npm run build

# Add semua changes
git add -A
git commit -m "Build: $(date)"

# Push ke main branch
git push origin main
```

### 2️⃣ GitHub Actions Otomatis Build

Setelah push:
1. Buka repository GitHub: https://github.com/anonim-sen10/rofc-schoolmusic
2. Klik tab **Actions**
3. Lihat workflow run terbaru (nama: "Auto Deploy Laravel")
4. Status akan berubah dari 🟡 (running) → 🟢 (success)

**Workflow akan:**
- ✅ Setup Node.js 20
- ✅ Install npm dependencies
- ✅ Build frontend assets
- ✅ Create stable asset names
- ✅ **Create download artifact** (build folder)

**Waktu:** ±5-10 menit

### 3️⃣ Download Build Artifact

Setelah workflow selesai (status: ✅ Success/Green):

1. **Di halaman workflow run**, scroll ke bawah
2. Cari bagian **"Artifacts"**
3. Klik **"build-assets"** untuk download
4. File akan ter-download sebagai `build-assets.zip`

```
Expected structure dalam ZIP:
build-assets/
├── manifest.json
├── manifest.json.sha256
├── assets/
│   ├── app.css
│   ├── app.js
│   ├── portal.css
│   ├── portal.js
│   ├── [hash files...]
└── [other asset files]
```

### 4️⃣ Extract & Upload via cPanel

#### A. Extract Locally
```
build-assets.zip
    ↓ (extract)
build/
├── manifest.json
├── assets/
└── ...
```

#### B. Upload via cPanel

1. **Login ke cPanel** (biasanya: https://rofcmusicschool.com:2083)
2. Buka **File Manager**
3. Navigate ke: `/public_html/build/`
4. **Delete folder/files lama** (atau backup dulu)
5. **Upload semua files** dari folder `build/` yang sudah di-extract
   - Atau drag-drop files ke cPanel
   - Atau extract ZIP langsung di cPanel

#### C. Verify Upload

- Cek di cPanel bahwa file-file sudah ter-upload
- File penting yang harus ada:
  - `manifest.json` ✅
  - `assets/app.css` ✅
  - `assets/app.js` ✅
  - `assets/portal.css` ✅
  - `assets/portal.js` ✅

### 5️⃣ Test di Browser

1. Open website: https://rofcmusicschool.com
2. **Hard refresh** (Ctrl+F5 atau Cmd+Shift+R)
3. Open **DevTools** (F12) → **Console**
4. Cek tidak ada error
5. Cek CSS/JS terload dengan benar

---

## Workflow Status Check

### Sukses ✅
- Workflow status: **Green / Success**
- Artifact tersedia untuk download
- Semua file terlihat di cPanel

### Gagal ❌
- Workflow status: **Red / Failed**
- Lihat error log di GitHub Actions
- Common errors:
  - npm install error → cek dependencies
  - Build error → cek code syntax
  - Manifest error → cek file structure

---

## Quick Command Reference

```bash
# Push code untuk trigger workflow
git push origin main

# Check workflow status (di GitHub Actions tab)
# atau command line:
gh workflow view "Auto Deploy Laravel" -w main

# Download artifact CLI (jika punya GitHub CLI)
gh run download <RUN_ID> -n build-assets
```

---

## Tips & Tricks

### 1. Automated Artifacts Cleanup
Artifacts akan otomatis terhapus setelah **7 hari** (untuk hemat storage GitHub)

### 2. Multiple Deployments
Jika deploy berkali-kali dalam sehari:
- Setiap push = 1 new artifact
- Hanya download artifact terbaru (latest)
- Artifacts lama bisa didelete manual

### 3. Verify Manifest
Jika ada doubt, cek isi `manifest.json`:
```bash
cat public/build/manifest.json | jq '.' # pretty print
```

Harus berisi keys:
- `resources/css/app.css`
- `resources/js/app.js`
- `resources/css/portal.css`
- `resources/js/portal.js`

### 4. Rollback Strategy
Jika deploy error, keep backup folder:
```
/public_html/build-backup/  ← old version
/public_html/build/          ← new version
```

Untuk rollback, rename folder back

---

## Troubleshooting

### Artifact tidak ada setelah workflow selesai
- ❌ Workflow gagal sebelum build step
- ✅ Buka workflow log, cari error
- ✅ Fix error di kode
- ✅ Push lagi

### Files not updating di browser
- ❌ Browser cache lama
- ✅ Hard refresh: Ctrl+F5 (Windows) atau Cmd+Shift+R (Mac)
- ✅ Atau clear DevTools cache

### Permission error di cPanel
- ❌ File ownership/permission issue
- ✅ Di cPanel → File Manager → select file → Can modify → Change
- ✅ Atau contact hosting support

### Build artifact terlalu besar
- ❌ Ada node_modules kemasuk (jangan)
- ✅ Cek `.github/workflows/deploy.yml → path: public/build/`

---

## Maintenance

### Weekly Checks
- [ ] Workflow runs successfully
- [ ] Artifact downloads fine
- [ ] Website loads assets correctly

### Monthly Cleanup
- [ ] Delete old artifacts (>1 month) in GitHub
- [ ] Check storage usage on cPanel
- [ ] Review error logs if any

---

## Next Steps (Future Improvements)

### Option 1: FTP Automation
Setup GitHub Actions to auto-upload via FTP (eliminate manual step)

### Option 2: Webhook
Setup hosting webhook to trigger deployment on push

### Option 3: SSH Alternative
Try different SSH port or setup reverse SSH tunnel

---

## Support

- 📚 GitHub Actions Docs: https://docs.github.com/en/actions
- 🖥️ cPanel Support: Contact your hosting provider
- 💬 Questions: Check GitHub Issues

---

**That's it!** 🎉

Setiap kali ada update, tinggal:
1. `git push origin main`
2. Download artifact dari GitHub Actions
3. Upload ke cPanel via File Manager
4. Hard refresh di browser

Done! ✅
