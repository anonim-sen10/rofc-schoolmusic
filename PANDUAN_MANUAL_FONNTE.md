# Panduan Perintah Manual Fonnte (ROFC Music School)

Dokumen ini berisi panduan penggunaan perintah-perintah *(commands)* manual yang bisa dieksekusi di *terminal* atau *SSH Server* untuk mengirim ulang pesan WhatsApp (via Fonnte) secara paksa. 

Sangat berguna jika:
- Sistem otomatis (*cron job*) gagal berjalan.
- Internet/API Fonnte sedang gangguan saat jadwal kelas dimulai.
- Anda ingin melakukan *testing* pesan tanpa perlu mengubah jam server.

---

## 1. Perintah Manual: Pengingat Jadwal Kelas (Reminder)

Perintah ini digunakan untuk mengirimkan **Pesan Pengingat Kelas (Reminder)** ke grup WhatsApp (seperti "Sesi akan segera dimulai...").

### Cara Penggunaan Dasar:
```bash
php artisan app:force-remind-session
```
Jika Anda menjalankan perintah di atas tanpa tambahan parameter, sistem akan mencari **semua jadwal kelas di hari ini** dan bertanya kepada Anda kelas mana yang ingin dikirimi pesan.

### Cara Penggunaan Spesifik (Rekomendasi):
Anda bisa langsung membidik kelas spesifik dengan menambahkan nama guru dan jam kelasnya:

```bash
php artisan app:force-remind-session --teacher="Nama Guru" --time="HH:MM"
```

**Contoh:**
```bash
php artisan app:force-remind-session --teacher="TRI SUTRISNO" --time="18:00"
```

Jika jadwal ditemukan, sistem akan menampilkan data konfirmasi, tekan `Y` lalu tekan `Enter` untuk mengeksekusi pesan.

---

## 2. Perintah Manual: Laporan Kehadiran (Attendance)

Perintah ini digunakan untuk mengirimkan **Laporan/Konfirmasi Absensi** ke grup WhatsApp (bahwa kelas telah selesai dan guru sudah mencatat kehadiran). 

> **Catatan Penting:** Perintah ini hanya bisa dieksekusi **jika guru tersebut sudah benar-benar melakukan Submit Attendance** (sudah absen) di portal mereka. Jika belum absen, sistem akan menolak mengirim pesan.

### Cara Penggunaan Dasar:
```bash
php artisan app:force-attendance-notification
```

### Cara Penggunaan Spesifik (Rekomendasi):
Sama seperti pengingat, Anda bisa langsung menggunakan nama guru dan jam.

```bash
php artisan app:force-attendance-notification --teacher="Nama Guru" --time="HH:MM"
```

**Contoh:**
```bash
php artisan app:force-attendance-notification --teacher="TRI SUTRISNO" --time="18:00"
```

Sistem akan otomatis merangkum data absensi guru (lengkap dengan status *Hadir/Absen* serta catatannya) dan mem-*blasting* laporannya ke WhatsApp.

---

### Tips Tambahan
Jika sistem menemukan lebih dari 1 kelas dengan nama guru dan jam yang sama persis, sistem akan menampilkan daftar **ID Kelas (Session ID)**. Anda bisa mengirim pesan dengan menggunakan nomor ID tersebut agar sistem tidak bingung:

```bash
php artisan app:force-remind-session --id=25
```
*(Ganti 25 dengan nomor ID sesi yang muncul di terminal).*
