<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\MusicClass;
use App\Models\Registration;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PortalController extends Controller
{
    public function redirectByRole(Request $request): RedirectResponse
    {
        $role = $request->user()->primaryRole();

        if (! $role) {
            return redirect()->route('login')->withErrors([
                'email' => 'Akun Anda belum memiliki role portal. Silakan hubungi admin.',
            ]);
        }

        return match ($role) {
            'super_admin' => redirect()->route('super-admin.dashboard'),
            'admin' => redirect()->route('admin.dashboard'),
            'finance' => redirect()->route('finance.dashboard'),
            'teacher' => redirect()->route('teacher.dashboard'),
            'student' => redirect()->route('student.dashboard'),
            default => redirect()->route('portal.custom.dashboard'),
        };
    }

    public function customDashboard(Request $request): View
    {
        $role = $request->user()->primaryRole() ?? 'custom_role';

        return view('portal.custom-dashboard', [
            'roleKey' => $role,
        ]);
    }

    public function dashboard(string $role): View
    {
        $config = $this->roleConfig($role);

        return view('portal.dashboard', [
            'roleKey' => $role,
            'portal' => $config,
            'stats' => $config['stats'],
            'recentActivities' => $this->recentActivities($role),
            'notifications' => $this->notifications($role),
            'reminders' => $this->reminders($role),
        ]);
    }

    public function module(string $role, string $module): View
    {
        $config = $this->roleConfig($role);

        if (! array_key_exists($module, $config['modules'])) {
            abort(404);
        }

        return view('portal.module', [
            'roleKey' => $role,
            'portal' => $config,
            'moduleKey' => $module,
            'module' => $config['modules'][$module],
        ]);
    }

    private function roleConfig(string $role): array
    {
        $map = [
            'super_admin' => [
                'title' => 'Super Admin Dashboard',
                'prefix' => 'super-admin',
                'menu' => [
                    ['key' => 'dashboard', 'label' => 'Dashboard'],
                    ['key' => 'users', 'label' => 'Users'],
                    ['key' => 'roles', 'label' => 'Roles'],
                    ['key' => 'classes', 'label' => 'Classes'],
                    ['key' => 'teachers', 'label' => 'Teachers'],
                    ['key' => 'students', 'label' => 'Students'],
                    ['key' => 'registrations', 'label' => 'Registrations'],
                    ['key' => 'finance', 'label' => 'Finance'],
                    ['key' => 'reports', 'label' => 'Reports'],
                    ['key' => 'blog', 'label' => 'Blog'],
                    ['key' => 'gallery', 'label' => 'Gallery'],
                    ['key' => 'events', 'label' => 'Events'],
                    ['key' => 'testimonials', 'label' => 'Testimonials'],
                    ['key' => 'settings', 'label' => 'Settings'],
                    ['key' => 'logs', 'label' => 'Logs'],
                ],
                'stats' => [
                    ['label' => 'Total Users', 'value' => 62],
                    ['label' => 'Active Students', 'value' => 486],
                    ['label' => 'Monthly Revenue', 'value' => 'Rp128jt'],
                    ['label' => 'Open Issues', 'value' => 7],
                ],
                'modules' => [
                    'users' => ['title' => 'Manage Users', 'description' => 'Tambah, edit, dan kelola akun user sistem.'],
                    'roles' => ['title' => 'Manage Roles', 'description' => 'Atur role dan hak akses semua user.'],
                    'classes' => ['title' => 'Classes', 'description' => 'Kelola data kelas musik dan kurikulum.'],
                    'teachers' => ['title' => 'Teachers', 'description' => 'Kelola data pengajar dan assignment kelas.'],
                    'students' => ['title' => 'Students', 'description' => 'Kelola data siswa aktif dan histori.'],
                    'registrations' => ['title' => 'Registrations', 'description' => 'Verifikasi pendaftaran calon siswa.'],
                    'finance' => ['title' => 'Finance', 'description' => 'Akses pembayaran, invoice, expense, dan gaji guru.'],
                    'reports' => ['title' => 'Reports', 'description' => 'Laporan akademik dan keuangan lintas modul.'],
                    'blog' => ['title' => 'Blog', 'description' => 'Konten artikel dan berita sekolah.'],
                    'gallery' => ['title' => 'Gallery', 'description' => 'Kelola foto dan video kegiatan.'],
                    'events' => ['title' => 'Events', 'description' => 'Kelola event workshop, recital, dan konser.'],
                    'testimonials' => ['title' => 'Testimonials', 'description' => 'Kelola testimoni siswa dan orang tua.'],
                    'settings' => ['title' => 'Settings', 'description' => 'Konfigurasi website dan sistem utama.'],
                    'logs' => ['title' => 'System Logs', 'description' => 'Audit trail aktivitas sistem.'],
                ],
            ],
            'admin' => [
                'title' => 'Admin Dashboard',
                'prefix' => 'admin',
                'menu' => [
                    ['key' => 'dashboard', 'label' => 'Dashboard'],
                    ['key' => 'classes', 'label' => 'Classes'],
                    ['key' => 'teachers', 'label' => 'Teachers'],
                    ['key' => 'students', 'label' => 'Students'],
                    ['key' => 'registrations', 'label' => 'Registrations'],
                    ['key' => 'gallery', 'label' => 'Gallery'],
                    ['key' => 'blog', 'label' => 'Blog'],
                    ['key' => 'events', 'label' => 'Events'],
                    ['key' => 'testimonials', 'label' => 'Testimonials'],
                ],
                'stats' => [
                    ['label' => 'Active Classes', 'value' => MusicClass::query()->where('status', 'active')->count()],
                    ['label' => 'Teachers', 'value' => Teacher::query()->where('is_active', true)->count()],
                    ['label' => 'Students', 'value' => Student::query()->where('is_active', true)->count()],
                    ['label' => 'Pending Registrations', 'value' => Registration::query()->where('status', 'pending')->count()],
                ],
                'modules' => [
                    'classes' => ['title' => 'Classes', 'description' => 'CRUD kelas musik.'],
                    'teachers' => ['title' => 'Teachers', 'description' => 'CRUD pengajar sekolah musik.'],
                    'students' => ['title' => 'Students', 'description' => 'CRUD data siswa.'],
                    'registrations' => ['title' => 'Registrations', 'description' => 'Approve/reject pendaftaran siswa.'],
                    'gallery' => ['title' => 'Gallery', 'description' => 'Upload dan kelola media website.'],
                    'blog' => ['title' => 'Blog', 'description' => 'Buat dan kelola artikel sekolah.'],
                    'events' => ['title' => 'Events', 'description' => 'Kelola event dan jadwal kegiatan.'],
                    'testimonials' => ['title' => 'Testimonials', 'description' => 'Kelola testimoni siswa/orang tua.'],
                ],
            ],
            'finance' => [
                'title' => 'Finance Dashboard',
                'prefix' => 'finance',
                'menu' => [
                    ['key' => 'dashboard', 'label' => 'Dashboard'],
                    ['key' => 'payments', 'label' => 'Payments'],
                    ['key' => 'invoices', 'label' => 'Invoices'],
                    ['key' => 'expenses', 'label' => 'Expenses'],
                    ['key' => 'teacher_salary', 'label' => 'Teacher Salary'],
                    ['key' => 'financial_reports', 'label' => 'Financial Reports'],
                    ['key' => 'transactions', 'label' => 'Transactions'],
                ],
                'stats' => [
                    ['label' => 'Collected This Month', 'value' => 'Rp128jt'],
                    ['label' => 'Outstanding Invoice', 'value' => 34],
                    ['label' => 'Operational Expense', 'value' => 'Rp32jt'],
                    ['label' => 'Teacher Payroll', 'value' => 'Rp46jt'],
                ],
                'modules' => [
                    'payments' => ['title' => 'Payments', 'description' => 'Input dan verifikasi pembayaran siswa.'],
                    'invoices' => ['title' => 'Invoices', 'description' => 'Buat invoice dan status penagihan.'],
                    'expenses' => ['title' => 'Expenses', 'description' => 'Catat pengeluaran operasional.'],
                    'teacher_salary' => ['title' => 'Teacher Salary', 'description' => 'Kelola komponen gaji guru.'],
                    'financial_reports' => ['title' => 'Financial Reports', 'description' => 'Ringkasan cashflow dan laporan keuangan.'],
                    'transactions' => ['title' => 'Transactions', 'description' => 'Riwayat transaksi keuangan.'],
                ],
            ],
            'teacher' => [
                'title' => 'Teacher Portal',
                'prefix' => 'teacher',
                'menu' => [
                    ['key' => 'dashboard', 'label' => 'Dashboard'],
                    ['key' => 'my_classes', 'label' => 'My Classes'],
                    ['key' => 'my_students', 'label' => 'My Students'],
                    ['key' => 'schedule', 'label' => 'Schedule'],
                    ['key' => 'attendance', 'label' => 'Attendance'],
                    ['key' => 'student_progress', 'label' => 'Student Progress'],
                    ['key' => 'materials', 'label' => 'Materials'],
                ],
                'stats' => [
                    ['label' => 'Classes Today', 'value' => 4],
                    ['label' => 'My Students', 'value' => 36],
                    ['label' => 'Attendance Rate', 'value' => '92%'],
                    ['label' => 'Progress Notes', 'value' => 58],
                ],
                'modules' => [
                    'my_classes' => ['title' => 'My Classes', 'description' => 'Lihat kelas yang Anda ajar.'],
                    'my_students' => ['title' => 'My Students', 'description' => 'Daftar siswa per kelas.'],
                    'schedule' => ['title' => 'Schedule', 'description' => 'Jadwal mengajar dan reminder.'],
                    'attendance' => ['title' => 'Attendance', 'description' => 'Input absensi siswa per sesi.'],
                    'student_progress' => ['title' => 'Student Progress', 'description' => 'Isi catatan perkembangan siswa.'],
                    'materials' => ['title' => 'Materials', 'description' => 'Upload materi pembelajaran.'],
                ],
            ],
            'student' => [
                'title' => 'Student Portal',
                'prefix' => 'student',
                'menu' => [
                    ['key' => 'dashboard', 'label' => 'Dashboard'],
                    ['key' => 'my_class', 'label' => 'My Class'],
                    ['key' => 'schedule', 'label' => 'Schedule'],
                    ['key' => 'payment', 'label' => 'Payment'],
                    ['key' => 'progress', 'label' => 'Progress'],
                    ['key' => 'events', 'label' => 'Events'],
                    ['key' => 'materials', 'label' => 'Materials'],
                    ['key' => 'profile', 'label' => 'Profile'],
                ],
                'stats' => [
                    ['label' => 'My Classes', 'value' => 2],
                    ['label' => 'Upcoming Sessions', 'value' => 5],
                    ['label' => 'Payment Status', 'value' => 'Paid'],
                    ['label' => 'Progress Score', 'value' => 'A-'],
                ],
                'modules' => [
                    'my_class' => ['title' => 'My Class', 'description' => 'Lihat detail kelas aktif Anda.'],
                    'schedule' => ['title' => 'Schedule', 'description' => 'Jadwal pembelajaran mingguan.'],
                    'payment' => ['title' => 'Payment', 'description' => 'Status pembayaran dan invoice.'],
                    'progress' => ['title' => 'Progress', 'description' => 'Perkembangan belajar dan evaluasi.'],
                    'events' => ['title' => 'Events', 'description' => 'Event dan performance sekolah musik.'],
                    'materials' => ['title' => 'Materials', 'description' => 'Unduh materi pembelajaran.'],
                    'profile' => ['title' => 'Profile', 'description' => 'Kelola profil akun siswa.'],
                ],
            ],
        ];

        if (! array_key_exists($role, $map)) {
            abort(404);
        }

        return $map[$role];
    }

    private function recentActivities(string $role): array
    {
        return [
            ['title' => 'Data updated for '.$role, 'time' => '5 menit lalu'],
            ['title' => 'New registration submitted', 'time' => '20 menit lalu'],
            ['title' => 'System generated monthly summary', 'time' => '1 jam lalu'],
        ];
    }

    private function notifications(string $role): array
    {
        return [
            ['label' => '2 notifikasi baru', 'type' => 'info'],
            ['label' => 'Backup sistem berhasil', 'type' => 'success'],
            ['label' => strtoupper(str_replace('_', ' ', $role)).' permissions aktif', 'type' => 'warning'],
        ];
    }

    private function reminders(string $role): array
    {
        return [
            ['label' => 'Review jadwal minggu ini'],
            ['label' => 'Perbarui data yang pending'],
            ['label' => 'Cek laporan terbaru'],
        ];
    }
}
