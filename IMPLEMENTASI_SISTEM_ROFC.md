# Implementasi Sistem ROFC School Music

Dokumen ini merangkum hasil implementasi sistem, alur proses per role, dan status fitur saat ini.

## 1. Gambaran Umum

Sistem berkembang dari website company profile menjadi **Music School Management System** berbasis Laravel dengan 5 role utama:

- Super Admin
- Admin
- Finance
- Teacher
- Student

Ruang lingkup implementasi:

- Website publik (company profile)
- Login + RBAC (Role-Based Access Control)
- Dashboard/portal per role
- Modul akademik, keuangan, dan operasional guru/siswa
- Konsolidasi data lintas role ke dashboard Super Admin

## 2. Fondasi Teknis

- Framework: Laravel 12
- Backend: PHP 8.2
- Frontend: Blade + Vite (CSS/JS)
- Authentication: session-based login/logout (custom)
- RBAC: tabel roles, pivot user_roles, middleware role
- Database: migrasi domain akademik, finance, CMS, dan log aktivitas

## 3. Fitur yang Sudah Selesai

### 3.1 Website Publik

Halaman publik yang sudah tersedia:

- Home
- About
- Programs
- Teachers
- Gallery
- Events
- Blog
- Contact
- Register

Form contact dan register sudah dilengkapi validasi serta flash message.

### 3.2 Authentication dan RBAC

- Login/logout custom aktif.
- User diarahkan otomatis ke portal sesuai role melalui endpoint portal.
- Middleware role aktif per prefix route.

### 3.3 Portal per Role

- Super Admin: dashboard dan modul konsolidasi data lintas role.
- Admin: CRUD classes, teachers, students, registrations.
- Finance: invoices, payments, expenses, teacher salaries, reports, export CSV.
- Teacher: attendance, student progress, materials.
- Student: my class, schedule, payment status, progress, materials, profile.

### 3.4 Super Admin Data Center

Super Admin sudah menggunakan data nyata (bukan placeholder):

- Ringkasan KPI lintas modul.
- Tabel data terbaru (registrasi, pembayaran, absensi, progress).
- Modul data: users, roles, classes, teachers, students, registrations, finance, reports, blog, gallery, events, testimonials, settings, logs.

### 3.5 Super Admin Buat Akun Login

Super Admin dapat membuat akun login untuk:

- Admin
- Teacher
- Student

Mencakup:

- Validasi form (nama, email unik, role, password + konfirmasi).
- Assign role ke user.
- Otomatis membuat profil teacher/student saat role terkait dipilih.

### 3.6 Teacher Attendance (Update Terbaru)

- Guru dapat input nama siswa langsung.
- Jika siswa belum ada, sistem membuat data siswa otomatis.
- Absen guru mendukung live location (location_text, latitude, longitude).
- Form absen siswa muncul setelah guru absen diri sendiri.
- Backend guard mencegah bypass submit jika guru belum absen.
- Jika guru belum di-assign kelas, sistem menampilkan pesan jelas dan submit ditolak dengan error ramah.

## 4. Alur Sistem End-to-End

### 4.1 Alur Login dan Redirect Role

1. User login.
2. Sistem memverifikasi email dan password.
3. Sistem membaca primary role user.
4. User diarahkan ke dashboard sesuai role:
5. super_admin -> /super-admin
6. admin -> /admin
7. finance -> /finance
8. teacher -> /teacher
9. student -> /student

### 4.2 Alur Super Admin Buat Akun

1. Super Admin membuka modul Users.
2. Isi form akun baru (nama, email, role, password).
3. Sistem membuat user baru.
4. Sistem meng-assign role ke tabel user_roles.
5. Jika role teacher/student, sistem membuat profil domain terkait.

### 4.3 Alur Admin Kelola Akademik

1. Admin membuka modul Classes/Teachers/Students/Registrations.
2. Admin melakukan CRUD data akademik.
3. Data tersimpan dan dapat dipantau oleh Super Admin.

### 4.4 Alur Finance

1. Finance membuat invoice.
2. Finance mencatat pembayaran.
3. Jika total pembayaran cukup, status invoice otomatis menjadi paid.
4. Finance mencatat expense dan salary.
5. Laporan tersedia dan bisa diekspor ke CSV.

### 4.5 Alur Teacher Attendance

1. Teacher membuka halaman attendance.
2. Teacher melakukan absen diri sendiri terlebih dahulu.
3. Setelah absen guru tersimpan, form absen siswa muncul.
4. Teacher memilih kelas yang memang di-assign ke teacher tersebut.
5. Teacher input nama siswa:
6. jika siswa sudah ada -> gunakan data lama
7. jika siswa belum ada -> buat data baru
8. Sistem mengaitkan siswa ke kelas jika belum terhubung.
9. Data attendance siswa tersimpan.

### 4.6 Alur Student Portal

1. Student login ke portal student.
2. Student melihat kelas, jadwal, pembayaran, progress, dan materi.
3. Student dapat memperbarui profil.

## 5. Isu yang Sudah Ditangani

- SQL ambiguity pada query primary role diperbaiki dengan prefix kolom roles.id.
- Error tabel attendances diperbaiki dengan mapping model ke tabel attendance.
- Dropdown kelas kosong pada teacher diperjelas dengan pesan assignment kelas.
- Data absensi berhasil dibersihkan saat diminta (attendance dan teacher_attendances).

## 6. File Kunci yang Diubah atau Ditambah

- routes/web.php
- app/Http/Controllers/Portal/PortalController.php
- app/Http/Controllers/Teacher/TeacherPortalController.php
- app/Http/Controllers/Finance/FinanceManagementController.php
- app/Http/Controllers/SuperAdmin/SuperAdminController.php
- resources/views/portal/teacher/attendance.blade.php
- resources/views/portal/super-admin/dashboard.blade.php
- resources/views/portal/super-admin/module.blade.php
- app/Models/Attendance.php
- app/Models/TeacherAttendance.php
- database/migrations/2026_04_05_000007_add_location_fields_to_teacher_attendances_table.php

## 7. Status Saat Ini

Status implementasi: **functional baseline** dan siap untuk operasional dasar.

Area peningkatan berikutnya:

- Super Admin: edit/hapus akun, reset password, toggle active/inactive.
- Validasi anti-duplikasi attendance (aturan 1x per hari).
- Hardening audit log untuk seluruh aksi CRUD.
- Penyempurnaan reporting lintas role (grafik dan filter periode).

## 8. Catatan Operasional Cepat

- Jika teacher tidak bisa memilih kelas, pastikan kelas sudah di-assign ke teacher tersebut.
- Jika form absen siswa tidak muncul, pastikan teacher sudah absen diri sendiri pada tanggal yang sama.
- Akun dari seeder dapat digunakan untuk testing role-based login.

## 9. Checklist Sebelum Upload

1. Pastikan file environment production sudah benar.
2. Jalankan migrasi database pada server tujuan.
3. Pastikan permission storage dan cache sudah writable.
4. Jalankan cache build produksi:
5. config cache
6. route cache
7. view cache
8. Uji login semua role setelah upload.
9. Uji alur kritikal:
10. Super Admin buat akun role lain
11. Admin assign kelas ke teacher
12. Teacher attendance (guru dulu, lalu siswa)
13. Finance invoice dan pembayaran
