<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Expense;
use App\Models\Invoice;
use App\Models\Material;
use App\Models\MusicClass;
use App\Models\Payment;
use App\Models\Registration;
use App\Models\Role;
use App\Models\Student;
use App\Models\StudentProgress;
use App\Models\Teacher;
use App\Models\TeacherAttendance;
use App\Models\TeacherSalary;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SuperAdminController extends Controller
{
    private const CORE_ROLES = [
        'super_admin' => ['name' => 'Super Admin', 'description' => 'Akses penuh ke seluruh sistem.'],
        'admin' => ['name' => 'Admin', 'description' => 'Operasional akademik dan konten website.'],
        'finance' => ['name' => 'Finance', 'description' => 'Manajemen keuangan sekolah musik.'],
        'teacher' => ['name' => 'Teacher', 'description' => 'Portal pengajar dan akademik.'],
        'student' => ['name' => 'Student', 'description' => 'Portal siswa.'],
    ];

    public function storeUser(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:120', 'unique:users,email'],
            'role' => ['required', 'in:super_admin,admin,finance,teacher,student'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'instrument' => ['nullable', 'string', 'max:80'],
            'phone' => ['nullable', 'string', 'max:30'],
        ]);

        $roleSlug = $data['role'];

        $role = $this->resolveCoreRole($roleSlug);

        $user = User::query()->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $user->roles()->syncWithoutDetaching([$role->id]);

        if ($roleSlug === 'teacher') {
            Teacher::query()->firstOrCreate(
                ['user_id' => $user->id],
                [
                    'name' => $user->name,
                    'instrument' => $data['instrument'] ?? 'General',
                    'is_active' => true,
                ]
            );
        }

        if ($roleSlug === 'student') {
            Student::query()->firstOrCreate(
                ['user_id' => $user->id],
                [
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $data['phone'] ?? null,
                    'is_active' => true,
                ]
            );
        }

        return back()->with('success', 'Akun login berhasil dibuat.');
    }

    public function showUser(User $user): View
    {
        $user->load('roles');

        return view('portal.super-admin.user-detail', [
            'roleKey' => 'super_admin',
            'portal' => $this->portalConfig(),
            'user' => $user,
        ]);
    }

    public function editUser(User $user): View
    {
        $user->load('roles');

        return view('portal.super-admin.user-edit', [
            'roleKey' => 'super_admin',
            'portal' => $this->portalConfig(),
            'user' => $user,
            'roleOptions' => self::CORE_ROLES,
        ]);
    }

    public function updateUser(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:120', 'unique:users,email,'.$user->id],
            'role' => ['required', 'in:super_admin,admin,finance,teacher,student'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'instrument' => ['nullable', 'string', 'max:80'],
            'phone' => ['nullable', 'string', 'max:30'],
        ]);

        $user->name = $data['name'];
        $user->email = $data['email'];

        if (! empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        $user->save();

        $role = $this->resolveCoreRole($data['role']);
        $user->roles()->sync([$role->id]);

        if ($data['role'] === 'teacher') {
            Teacher::query()->firstOrCreate(
                ['user_id' => $user->id],
                [
                    'name' => $user->name,
                    'instrument' => $data['instrument'] ?? 'General',
                    'is_active' => true,
                ]
            );
        }

        if ($data['role'] === 'student') {
            Student::query()->firstOrCreate(
                ['user_id' => $user->id],
                [
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $data['phone'] ?? null,
                    'is_active' => true,
                ]
            );
        }

        return redirect()->route('super-admin.module', ['module' => 'roles'])->with('success', 'Data user berhasil diperbarui.');
    }

    public function destroyUser(Request $request, User $user): RedirectResponse
    {
        if ((int) $request->user()->id === (int) $user->id) {
            return back()->withErrors([
                'user' => 'Akun yang sedang dipakai tidak bisa dihapus.',
            ]);
        }

        $user->delete();

        return back()->with('success', 'User berhasil dihapus.');
    }

    public function storeRole(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'slug' => ['nullable', 'string', 'max:120', 'regex:/^[a-z0-9_-]+$/', 'unique:roles,slug'],
            'description' => ['nullable', 'string', 'max:500'],
        ]);

        $slug = $data['slug']
            ? Str::of($data['slug'])->trim()->lower()->replace('-', '_')->replace(' ', '_')->toString()
            : Str::of($data['name'])->trim()->lower()->replace('-', '_')->replace(' ', '_')->toString();

        if (Role::query()->where('slug', $slug)->exists()) {
            return back()->withErrors([
                'slug' => 'Slug role sudah digunakan. Gunakan slug lain.',
            ])->withInput();
        }

        Role::query()->create([
            'name' => $data['name'],
            'slug' => $slug,
            'description' => $data['description'] ?? null,
        ]);

        return back()->with('success', 'Role baru berhasil dibuat.');
    }

    public function dashboard(): View
    {
        $income = Payment::query()->where('status', 'paid')->sum('amount');
        $expense = Expense::query()->sum('amount');
        $salary = TeacherSalary::query()->sum('total_paid');

        return view('portal.super-admin.dashboard', [
            'roleKey' => 'super_admin',
            'portal' => $this->portalConfig(),
            'stats' => [
                ['label' => 'Total Users', 'value' => User::count()],
                ['label' => 'Active Students', 'value' => Student::where('is_active', true)->count()],
                ['label' => 'Active Teachers', 'value' => Teacher::where('is_active', true)->count()],
                ['label' => 'Net Cashflow', 'value' => 'Rp'.number_format($income - $expense - $salary, 0, ',', '.')],
            ],
            'summary' => [
                'registrations_pending' => Registration::where('status', 'pending')->count(),
                'invoices_unpaid' => Invoice::whereIn('status', ['draft', 'issued', 'overdue'])->count(),
                'teacher_attendance_today' => TeacherAttendance::whereDate('attendance_date', now()->toDateString())->count(),
                'student_attendance_today' => Attendance::whereDate('attendance_date', now()->toDateString())->count(),
                'progress_updates_today' => StudentProgress::whereDate('created_at', now()->toDateString())->count(),
                'materials_uploaded' => Material::count(),
            ],
            'recentRegistrations' => Registration::latest()->take(8)->get(),
            'recentPayments' => Payment::with('student')->latest()->take(8)->get(),
            'recentTeacherAttendances' => TeacherAttendance::with('teacher')->latest('attendance_date')->take(8)->get(),
            'recentStudentAttendances' => Attendance::with(['student', 'class'])->latest('attendance_date')->take(8)->get(),
            'recentProgress' => StudentProgress::latest()->take(8)->get(),
        ]);
    }

    public function module(string $module): View
    {
        $moduleData = $this->moduleData($module);

        return view('portal.super-admin.module', [
            'roleKey' => 'super_admin',
            'portal' => $this->portalConfig(),
            'moduleKey' => $module,
            'moduleTitle' => $moduleData['title'],
            'moduleDescription' => $moduleData['description'],
            'columns' => $moduleData['columns'],
            'rows' => $moduleData['rows'],
            'usersForRoles' => User::with('roles')->latest()->take(50)->get(),
        ]);
    }

    private function portalConfig(): array
    {
        return [
            'title' => 'Super Admin Dashboard',
            'prefix' => 'super-admin',
            'menu' => [
                ['key' => 'dashboard', 'label' => 'Dashboard'],
                ['key' => 'users', 'label' => 'Users'],
                ['key' => 'roles', 'label' => 'Manajemen User'],
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
        ];
    }

    private function moduleData(string $module): array
    {
        return match ($module) {
            'users' => [
                'title' => 'User Accounts',
                'description' => 'Data user lintas semua role.',
                'columns' => ['Nama', 'Email', 'Role', 'Created'],
                'rows' => User::with('roles')->latest()->take(30)->get()->map(fn (User $user) => [
                    $user->name,
                    $user->email,
                    $user->roles->pluck('slug')->implode(', '),
                    optional($user->created_at)->format('Y-m-d H:i'),
                ])->all(),
            ],
            'roles' => [
                'title' => 'Manajemen User',
                'description' => 'Kelola data akun user dalam satu halaman.',
                'columns' => ['Role', 'Slug', 'Total User'],
                'rows' => Role::withCount('users')->orderBy('id')->get()->map(fn (Role $role) => [
                    $role->name,
                    $role->slug,
                    (string) $role->users_count,
                ])->all(),
            ],
            'classes' => [
                'title' => 'Classes',
                'description' => 'Kelas yang dibuat Admin dan assignment guru.',
                'columns' => ['Nama Kelas', 'Guru', 'Harga', 'Status'],
                'rows' => MusicClass::with('teacher')->latest()->take(30)->get()->map(fn (MusicClass $class) => [
                    $class->name,
                    $class->teacher?->name ?? '-',
                    'Rp'.number_format($class->price ?? 0, 0, ',', '.'),
                    $class->status,
                ])->all(),
            ],
            'teachers' => [
                'title' => 'Teachers',
                'description' => 'Data pengajar dan instrumen.',
                'columns' => ['Nama', 'Instrumen', 'Aktif', 'Total Kelas'],
                'rows' => Teacher::withCount('classes')->latest()->take(30)->get()->map(fn (Teacher $teacher) => [
                    $teacher->name,
                    $teacher->instrument,
                    $teacher->is_active ? 'Ya' : 'Tidak',
                    (string) $teacher->classes_count,
                ])->all(),
            ],
            'students' => [
                'title' => 'Students',
                'description' => 'Data siswa dari pendaftaran dan operasional kelas.',
                'columns' => ['Nama', 'Email', 'Telepon', 'Aktif'],
                'rows' => Student::latest()->take(30)->get()->map(fn (Student $student) => [
                    $student->name,
                    $student->email ?? '-',
                    $student->phone ?? '-',
                    $student->is_active ? 'Ya' : 'Tidak',
                ])->all(),
            ],
            'registrations' => [
                'title' => 'Registrations',
                'description' => 'Data pendaftaran masuk website.',
                'columns' => ['Nama', 'Email', 'Telepon', 'Status'],
                'rows' => Registration::latest()->take(30)->get()->map(fn (Registration $registration) => [
                    $registration->full_name,
                    $registration->email,
                    $registration->phone,
                    $registration->status,
                ])->all(),
            ],
            'finance' => [
                'title' => 'Finance Summary',
                'description' => 'Ringkasan pemasukan dan pengeluaran.',
                'columns' => ['Metrik', 'Nilai'],
                'rows' => [
                    ['Total Invoice', (string) Invoice::count()],
                    ['Pembayaran Berhasil', 'Rp'.number_format(Payment::where('status', 'paid')->sum('amount'), 0, ',', '.')],
                    ['Total Pengeluaran', 'Rp'.number_format(Expense::sum('amount'), 0, ',', '.')],
                    ['Total Gaji Guru', 'Rp'.number_format(TeacherSalary::sum('total_paid'), 0, ',', '.')],
                ],
            ],
            'reports' => [
                'title' => 'Cross Module Reports',
                'description' => 'Laporan agregat lintas modul akademik dan keuangan.',
                'columns' => ['Laporan', 'Jumlah'],
                'rows' => [
                    ['Absensi Guru Hari Ini', (string) TeacherAttendance::whereDate('attendance_date', now()->toDateString())->count()],
                    ['Absensi Siswa Hari Ini', (string) Attendance::whereDate('attendance_date', now()->toDateString())->count()],
                    ['Update Progress Hari Ini', (string) StudentProgress::whereDate('created_at', now()->toDateString())->count()],
                    ['Upload Materi Total', (string) Material::count()],
                ],
            ],
            'blog' => [
                'title' => 'Blog',
                'description' => 'Data posting artikel CMS.',
                'columns' => ['Judul', 'Status', 'Published At'],
                'rows' => DB::table('posts')->latest()->take(30)->get()->map(fn ($post) => [
                    $post->title,
                    $post->status,
                    $post->published_at ? (string) $post->published_at : '-',
                ])->all(),
            ],
            'gallery' => [
                'title' => 'Gallery',
                'description' => 'Media foto/video yang tersimpan.',
                'columns' => ['Judul', 'Kategori', 'Tipe'],
                'rows' => DB::table('galleries')->latest()->take(30)->get()->map(fn ($gallery) => [
                    $gallery->title,
                    $gallery->category ?? '-',
                    $gallery->type,
                ])->all(),
            ],
            'events' => [
                'title' => 'Events',
                'description' => 'Daftar event dan status event.',
                'columns' => ['Judul', 'Tanggal', 'Lokasi', 'Status'],
                'rows' => DB::table('events')->latest()->take(30)->get()->map(fn ($event) => [
                    $event->title,
                    $event->event_date ? (string) $event->event_date : '-',
                    $event->location ?? '-',
                    $event->status,
                ])->all(),
            ],
            'testimonials' => [
                'title' => 'Testimonials',
                'description' => 'Testimoni siswa/orang tua.',
                'columns' => ['Nama', 'Role', 'Published'],
                'rows' => DB::table('testimonials')->latest()->take(30)->get()->map(fn ($testimonial) => [
                    $testimonial->name,
                    $testimonial->role ?? '-',
                    $testimonial->is_published ? 'Ya' : 'Tidak',
                ])->all(),
            ],
            'settings' => [
                'title' => 'Settings',
                'description' => 'Konfigurasi key-value sistem.',
                'columns' => ['Key', 'Value'],
                'rows' => DB::table('settings')->orderBy('key')->get()->map(fn ($setting) => [
                    $setting->key,
                    $setting->value ?? '-',
                ])->all(),
            ],
            'logs' => [
                'title' => 'System Logs',
                'description' => 'Aktivitas user terbaru.',
                'columns' => ['Waktu', 'User ID', 'Module', 'Action'],
                'rows' => DB::table('activities')->latest()->take(50)->get()->map(fn ($activity) => [
                    (string) $activity->created_at,
                    (string) ($activity->user_id ?? '-'),
                    $activity->module ?? '-',
                    $activity->action,
                ])->all(),
            ],
            default => abort(404),
        };
    }

    private function resolveCoreRole(string $roleSlug): Role
    {
        return Role::query()->firstOrCreate(
            ['slug' => $roleSlug],
            [
                'name' => self::CORE_ROLES[$roleSlug]['name'],
                'description' => self::CORE_ROLES[$roleSlug]['description'],
            ]
        );
    }
}
