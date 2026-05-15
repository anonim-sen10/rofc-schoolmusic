<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Attendance;
use App\Models\Expense;
use App\Models\Invoice;
use App\Models\Material;
use App\Models\MusicClass;
use App\Models\Payment;
use App\Models\Registration;
use App\Models\RescheduleRequest;
use App\Models\Role;
use App\Models\Schedule;
use App\Models\Student;
use App\Models\StudentProgress;
use App\Models\Teacher;
use App\Models\TeacherAttendance;
use App\Models\TeacherSalary;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class SuperAdminController extends Controller
{
    use Traits\ManagesUsers,
        Traits\ManagesTeachers,
        Traits\ManagesStudents,
        Traits\ManagesAcademics,
        Traits\ManagesRegistrations,
        Traits\ManagesContent;

    private const TEACHER_CLASS_OPTIONS = [
        'drum' => 'Drum',
        'gitar' => 'Gitar',
        'vocal' => 'Vocal',
        'violin' => 'Violin',
        'piano' => 'Piano',
    ];

    private const CORE_ROLES = [
        'super_admin' => ['name' => 'Super Admin', 'description' => 'Akses penuh ke seluruh sistem.'],
        'admin' => ['name' => 'Admin', 'description' => 'Operasional akademik dan konten website.'],
        'finance' => ['name' => 'Finance', 'description' => 'Manajemen keuangan sekolah musik.'],
        'teacher' => ['name' => 'Teacher', 'description' => 'Portal pengajar dan akademik.'],
        'student' => ['name' => 'Student', 'description' => 'Portal siswa.'],
    ];

    private const SCHEDULE_DAY_OPTIONS = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];

    public function dashboard(): View
    {
        $income = Payment::query()->where('status', 'paid')->sum('amount');
        $expense = Expense::query()->sum('amount');
        $salary = TeacherSalary::query()->sum('total_paid');

        $months = $this->monthBuckets(6);
        $rangeStart = $months->first()->copy()->startOfMonth();
        $rangeEnd = $months->last()->copy()->endOfMonth();

        $monthKeys = $months->map(fn (Carbon $month) => $month->format('Y-m'));
        $chartLabels = $months->map(fn (Carbon $month) => $month->format('M Y'));

        $monthlyRevenueRows = Payment::query()
            ->selectRaw('YEAR(paid_at) as year, MONTH(paid_at) as month, SUM(amount) as total')
            ->where('status', 'paid')
            ->whereNotNull('paid_at')
            ->whereBetween('paid_at', [$rangeStart, $rangeEnd])
            ->groupBy('year', 'month')
            ->get()
            ->keyBy(fn ($row) => sprintf('%04d-%02d', (int) $row->year, (int) $row->month));

        $studentGrowthRows = Student::query()
            ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as total')
            ->whereBetween('created_at', [$rangeStart, $rangeEnd])
            ->groupBy('year', 'month')
            ->get()
            ->keyBy(fn ($row) => sprintf('%04d-%02d', (int) $row->year, (int) $row->month));

        $attendanceRows = Attendance::query()
            ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as total, SUM(CASE WHEN status IN ("present", "late") THEN 1 ELSE 0 END) as present_total')
            ->whereBetween('created_at', [$rangeStart, $rangeEnd])
            ->groupBy('year', 'month')
            ->get()
            ->keyBy(fn ($row) => sprintf('%04d-%02d', (int) $row->year, (int) $row->month));

        $monthlyRevenue = $monthKeys->map(fn (string $key) => (float) ($monthlyRevenueRows->get($key)->total ?? 0));
        $monthlyStudents = $monthKeys->map(fn (string $key) => (int) ($studentGrowthRows->get($key)->total ?? 0));
        $monthlyAttendanceRate = $monthKeys->map(function (string $key) use ($attendanceRows) {
            $total = (int) ($attendanceRows->get($key)->total ?? 0);
            $present = (int) ($attendanceRows->get($key)->present_total ?? 0);

            return $total > 0 ? round(($present / $total) * 100, 1) : 0;
        });

        $currentMonthStart = now()->copy()->startOfMonth();

        $studentsCurrent = Student::query()->where('is_active', true)->count();
        $studentsPrev = Student::query()->where('is_active', true)->where('created_at', '<', $currentMonthStart)->count();
        $teachersCurrent = Teacher::query()->where('is_active', true)->count();
        $teachersPrev = Teacher::query()->where('is_active', true)->where('created_at', '<', $currentMonthStart)->count();
        $revenueCurrent = (float) Payment::query()->where('status', 'paid')->whereBetween('paid_at', [$currentMonthStart, now()->copy()->endOfMonth()])->sum('amount');
        $revenuePrev = (float) Payment::query()->where('status', 'paid')->whereMonth('paid_at', now()->subMonth()->month)->sum('amount');
        $classesCurrent = MusicClass::query()->where('status', 'active')->count();
        $classesPrev = MusicClass::query()->where('status', 'active')->where('created_at', '<', $currentMonthStart)->count();

        $trend = function (float|int $current, float|int $previous): array {
            if ((float) $previous === 0.0) {
                return [
                    'direction' => $current > 0 ? 'up' : 'flat',
                    'label' => $current > 0 ? '+100%' : '0%',
                ];
            }

            $delta = (($current - $previous) / abs($previous)) * 100;

            return [
                'direction' => $delta > 0 ? 'up' : ($delta < 0 ? 'down' : 'flat'),
                'label' => ($delta > 0 ? '+' : '') . number_format($delta, 1) . '%',
            ];
        };

        $kpis = [
            [
                'label' => 'Total Students',
                'value' => number_format($studentsCurrent),
                'icon' => 'graduation-cap',
                'trend' => $trend($studentsCurrent, $studentsPrev),
            ],
            [
                'label' => 'Total Teachers',
                'value' => number_format($teachersCurrent),
                'icon' => 'users',
                'trend' => $trend($teachersCurrent, $teachersPrev),
            ],
            [
                'label' => 'Monthly Revenue',
                'value' => 'Rp' . number_format($revenueCurrent, 0, ',', '.'),
                'icon' => 'wallet',
                'trend' => $trend($revenueCurrent, $revenuePrev),
            ],
            [
                'label' => 'Active Classes',
                'value' => number_format($classesCurrent),
                'icon' => 'book-open',
                'trend' => $trend($classesCurrent, $classesPrev),
            ],
        ];

        $totalActivities = Activity::count();
        $todayActivities = Activity::whereDate('created_at', today())->count();
        $topUserRow = Activity::select('user_id', DB::raw('count(*) as total'))
            ->groupBy('user_id')
            ->orderByDesc('total')
            ->first();
        $topUserName = $topUserRow ? (User::find($topUserRow->user_id)?->name ?? 'System') : '-';

        return view('portal.super-admin.dashboard', [
            'roleKey' => 'super_admin',
            'portal' => $this->portalConfig(),
            'kpis' => $kpis,
            'stats' => [
                ['label' => 'Total Users', 'value' => User::count()],
                ['label' => 'Active Students', 'value' => Student::where('is_active', true)->count()],
                ['label' => 'Active Teachers', 'value' => Teacher::where('is_active', true)->count()],
                ['label' => 'Net Cashflow', 'value' => 'Rp' . number_format($income - $expense - $salary, 0, ',', '.')],
            ],
            'recentActivities' => \App\Models\Activity::with('user')->latest()->take(10)->get(),
            'chartData' => [
                'labels' => $chartLabels,
                'revenue' => $monthlyRevenue,
                'studentGrowth' => $monthlyStudents,
                'attendanceRate' => $monthlyAttendanceRate,
            ],
            'summary' => [
                'registrations_pending' => Registration::where('status', 'pending')->count(),
                'reschedule_requests_pending' => RescheduleRequest::where('status', 'pending')->count(),
                'invoices_unpaid' => Invoice::whereIn('status', ['draft', 'issued', 'overdue'])->count(),
                'teacher_attendance_today' => TeacherAttendance::whereDate('attendance_date', now()->toDateString())->count(),
                'student_attendance_today' => Attendance::whereDate('created_at', now()->toDateString())->count(),
                'progress_updates_today' => StudentProgress::whereDate('created_at', now()->toDateString())->count(),
                'materials_uploaded' => Material::count(),
            ],
            'recentRegistrations' => Registration::latest()->take(8)->get(),
            'recentPayments' => Payment::with('student')->latest()->take(8)->get(),
            'recentTeacherAttendances' => TeacherAttendance::with('teacher')->latest('attendance_date')->take(8)->get(),
            'recentStudentAttendances' => Attendance::with(['student', 'class'])->latest('created_at')->take(8)->get(),
            'recentProgress' => StudentProgress::latest()->take(8)->get(),
            'totalActivities' => $totalActivities,
            'todayActivities' => $todayActivities,
            'topUserName' => $topUserName,
        ]);
    }

    public function module(string $module): View
    {
        $moduleData = $this->moduleData($module);
        $scheduleFeatureReady = $this->hasSchedulesTable();

        $data = [
            'roleKey' => 'super_admin',
            'portal' => $this->portalConfig(),
            'moduleKey' => $module,
            'moduleTitle' => $moduleData['title'],
            'moduleDescription' => $moduleData['description'],
            'columns' => $moduleData['columns'],
            'rows' => $moduleData['rows'],
            'scheduleFeatureReady' => $scheduleFeatureReady,
            'dayOptions' => self::SCHEDULE_DAY_OPTIONS,
            'summary' => [
                'registrations_pending' => Registration::where('status', 'pending')->count(),
                'reschedule_requests_pending' => RescheduleRequest::where('status', 'pending')->count(),
                'invoices_unpaid' => Invoice::whereIn('status', ['draft', 'issued', 'overdue'])->count(),
                'teacher_attendance_today' => TeacherAttendance::whereDate('attendance_date', now()->toDateString())->count(),
                'student_attendance_today' => Attendance::whereDate('created_at', now()->toDateString())->count(),
                'progress_updates_today' => StudentProgress::whereDate('created_at', now()->toDateString())->count(),
                'materials_uploaded' => Material::count(),
            ],
            'instrumenOptions' => ['Drum', 'Piano', 'Guitar', 'Vocal', 'Violin', 'Bass', 'Keyboard', 'Music Theory'],
            'programTambahanOptions' => ['Teori Musik', 'Ensemble / Band', 'Skill Teknik (ajang kompetisi)', 'Ujian Sertifikat bertaraf international'],
            'hariOptions' => self::SCHEDULE_DAY_OPTIONS,
            'openRegistrationCreate' => session('openRegistrationCreate', false),
        ];

        $data['usersForRoles'] = collect();
        $data['rolesForManagement'] = collect();
        $data['classesForManagement'] = collect();
        $data['teachersForClassOptions'] = collect();
        $data['teachersForManagement'] = collect();
        $data['schedulesForManagement'] = collect();
        $data['studentsForManagement'] = collect();
        $data['approvedRegistrationsForStudents'] = collect();
        $data['registrationsForManagement'] = collect();
        $data['studentsForFinance'] = collect();
        $data['classesForFinance'] = collect();
        $data['logsForManagement'] = collect();
        $data['settingsForManagement'] = collect();
        $data['postsForManagement'] = collect();
        $data['galleriesForManagement'] = collect();
        $data['eventsForManagement'] = collect();
        $data['testimonialsForManagement'] = collect();

        switch ($module) {
            case 'users':
            case 'roles':
                $data['usersForRoles'] = User::with('roles')->latest()->take(100)->get();
                break;
            case 'classes':
                $data['classesForManagement'] = MusicClass::with(['teacher', 'schedules'])->orderBy('name')->get();
                $data['teachersForClassOptions'] = Teacher::query()->orderBy('name')->get(['id', 'name']);
                break;
            case 'teachers':
                $data['teachersForManagement'] = Teacher::query()->with(['user', 'classes'])->latest()->get();
                $data['classesForManagement'] = MusicClass::query()->orderBy('name')->get(['id', 'name']);
                break;
            case 'schedule':
                $data['classesForSchedule'] = MusicClass::query()->with(['teacher', 'schedules'])->orderBy('name')->get();
                $data['teachersForClassOptions'] = Teacher::query()->orderBy('name')->get(['id', 'name']);
                $data['schedulesForManagement'] = $scheduleFeatureReady
                    ? Schedule::query()
                        ->with(['musicClass.teacher', 'teacher', 'student.user'])
                        ->orderBy('time')
                        ->get()
                        ->sortBy(function ($schedule) {
                            $dayOrder = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
                            return array_search($schedule->day, $dayOrder);
                        })
                    : collect();
                break;
            case 'students':
                $data['studentsForManagement'] = Student::query()->with(['class', 'classes'])->latest()->get();
                $data['classesForManagement'] = MusicClass::query()->orderBy('name')->get(['id', 'name']);
                $data['approvedRegistrationsForStudents'] = Registration::query()
                    ->with('class')
                    ->where('status', 'accepted')
                    ->latest('updated_at')
                    ->get();
                $data['schedulesForManagement'] = $scheduleFeatureReady ? Schedule::where('status', 'available')->get() : collect();
                break;
            case 'registrations':
                $data['registrationsForManagement'] = Registration::query()->with(['class', 'schedules'])->latest()->get();
                $data['classesForManagement'] = MusicClass::query()->orderBy('name')->get(['id', 'name']);
                $data['schedulesForManagement'] = $scheduleFeatureReady ? Schedule::where('status', 'available')->get() : collect();
                break;
            case 'finance':
                $data['studentsForFinance'] = Student::query()->orderBy('name')->get(['id', 'name']);
                $data['classesForFinance'] = MusicClass::query()->orderBy('name')->get(['id', 'name']);
                break;
            case 'blog':
                $data['postsForManagement'] = DB::table('posts')->latest()->get();
                break;
            case 'gallery':
                $data['galleriesForManagement'] = DB::table('galleries')->latest()->get();
                break;
            case 'events':
                $data['eventsForManagement'] = DB::table('events')->latest()->get();
                break;
            case 'testimonials':
                $data['testimonialsForManagement'] = DB::table('testimonials')->latest()->get();
                break;
            case 'settings':
                $data['settingsForManagement'] = DB::table('settings')->orderBy('key')->get();
                break;
            case 'logs':
                $data['logsForManagement'] = \App\Models\Activity::with('user')->latest()->take(200)->get();
                break;
        }

        return view('portal.super-admin.module', $data);
    }

    private function monthBuckets(int $months): Collection
    {
        return collect(range($months - 1, 0))->map(
            fn (int $offset) => now()->copy()->startOfMonth()->subMonths($offset)
        );
    }

    private function portalConfig(): array
    {
        return [
            'title' => 'Super Admin Dashboard',
            'prefix' => 'super-admin',
            'menu' => [
                ['key' => 'dashboard', 'label' => 'Dashboard', 'icon' => 'layout-dashboard'],
                ['key' => 'roles', 'label' => 'Manajemen User', 'icon' => 'shield-check'],
                ['key' => 'classes', 'label' => 'Classes', 'icon' => 'book-open'],
                ['key' => 'teachers', 'label' => 'Teachers', 'icon' => 'music-2'],
                ['key' => 'schedule', 'label' => 'Schedule', 'icon' => 'calendar-days'],
                ['key' => 'students', 'label' => 'Students', 'icon' => 'graduation-cap'],
                ['key' => 'registrations', 'label' => 'Registrations', 'icon' => 'clipboard-list'],
                ['key' => 'reschedule', 'label' => 'Reschedule Requests', 'icon' => 'refresh-cw'],
                ['key' => 'attendance', 'label' => 'Attendance Monitoring', 'icon' => 'check-circle'],
                ['key' => 'finance', 'label' => 'Finance', 'icon' => 'wallet'],
                ['key' => 'reports', 'label' => 'Reports', 'icon' => 'bar-chart-3'],
                ['key' => 'blog', 'label' => 'Blog', 'icon' => 'newspaper'],
                ['key' => 'gallery', 'label' => 'Gallery', 'icon' => 'image'],
                ['key' => 'events', 'label' => 'Events', 'icon' => 'calendar'],
                ['key' => 'testimonials', 'label' => 'Testimonials', 'icon' => 'message-square-quote'],
                ['key' => 'settings', 'label' => 'Settings', 'icon' => 'settings'],
                ['key' => 'logs', 'label' => 'Logs', 'icon' => 'scroll-text'],
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
                'rows' => MusicClass::with(['teacher', 'teachers'])->latest()->take(30)->get()->map(fn (MusicClass $class) => [
                    $class->name,
                    $class->teachers->isNotEmpty() ? $class->teachers->pluck('name')->join(', ') : ($class->teacher?->name ?? '-'),
                    'Rp' . number_format($class->price ?? 0, 0, ',', '.'),
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
            'schedule' => [
                'title' => 'Schedule',
                'description' => 'Jadwal kelas berdasarkan hari dan jam.',
                'columns' => ['Class', 'Hari', 'Jam', 'Pengajar'],
                'rows' => $this->hasSchedulesTable()
                    ? Schedule::with(['musicClass', 'teacher'])->orderBy('day')->orderBy('time')->get()->map(fn (Schedule $schedule) => [
                        $schedule->musicClass?->name ?? '-',
                        $schedule->day,
                        substr((string) $schedule->time, 0, 5),
                        $schedule->teacher?->name ?? ($schedule->musicClass?->teacher?->name ?? '-'),
                    ])->all()
                    : [],
            ],
            'students' => [
                'title' => 'Students',
                'description' => 'Daftar siswa aktif dan manajemen profil.',
                'columns' => ['Nama', 'Email', 'Telepon', 'Kelas', 'Status'],
                'rows' => Student::with('classes')->latest()->take(30)->get()->map(fn (Student $student) => [
                    $student->name,
                    $student->email,
                    $student->phone,
                    $student->classes->pluck('name')->join(', '),
                    $student->is_active ? 'ACTIVE' : 'INACTIVE',
                ])->all(),
            ],
            'registrations' => [
                'title' => 'Registrations',
                'description' => 'Data pendaftaran masuk website.',
                'columns' => ['Nama', 'Email', 'Telepon', 'Instrumen', 'Jadwal', 'Status'],
                'rows' => Registration::with(['class', 'schedules'])->latest()->take(50)->get()->map(fn (Registration $registration) => [
                    $registration->full_name,
                    $registration->email,
                    $registration->phone,
                    $registration->class?->name ?? ($registration->instrumen ?? '-'),
                    $registration->schedules->count() . ' Slot',
                    strtoupper((string) $registration->status),
                ])->all(),
            ],
            'reschedule' => [
                'title' => 'Reschedule Requests',
                'description' => 'Permintaan pindah jadwal dari siswa.',
                'columns' => ['Siswa', 'Lama', 'Baru', 'Guru', 'Status', 'Aksi'],
                'rows' => \App\Models\RescheduleRequest::with(['student', 'oldSession', 'newSchedule.teacher'])->latest()->take(50)->get()->map(function ($r) {
                    $newLabel = '-';
                    if ($r->newSchedule && $r->oldSession) {
                        $dayMap = [
                            'Senin' => Carbon::MONDAY,
                            'Selasa' => Carbon::TUESDAY,
                            'Rabu' => Carbon::WEDNESDAY,
                            'Kamis' => Carbon::THURSDAY,
                            'Jumat' => Carbon::FRIDAY,
                            'Sabtu' => Carbon::SATURDAY,
                            'Minggu' => Carbon::SUNDAY,
                        ];
                        $newDayNum = $dayMap[$r->newSchedule->day] ?? Carbon::MONDAY;
                        $oldDate = Carbon::parse($r->oldSession->session_date);
                        $newDate = $oldDate->copy()->startOfWeek()->addDays($newDayNum - 1);
                        $newLabel = $newDate->translatedFormat('l, d M Y') . ' - ' . substr((string) $r->newSchedule->time, 0, 5);
                    } else if ($r->newSchedule) {
                        $newLabel = $r->newSchedule->day . ' ' . substr((string) $r->newSchedule->time, 0, 5);
                    }

                    return [
                        $r->student->name,
                        $r->oldSession
                            ? $r->oldSession->session_date->format('l, d M Y') . ' - ' . substr((string) $r->oldSession->time, 0, 5)
                            : ($r->oldSchedule ? $r->oldSchedule->day . ' ' . substr((string) $r->oldSchedule->time, 0, 5) : '-'),
                        $newLabel,
                        $r->newSchedule->teacher->name ?? '-',
                        strtoupper((string) $r->status),
                        $r
                    ];
                })->all(),
            ],
            'finance' => [
                'title' => 'Finance Summary',
                'description' => 'Ringkasan pemasukan dan pengeluaran.',
                'columns' => ['Metrik', 'Nilai'],
                'rows' => [
                    ['Total Invoice', (string) Payment::count()],
                    ['Pembayaran Berhasil', 'Rp' . number_format(Payment::where('status', 'paid')->sum('amount'), 0, ',', '.')],
                    ['Total Pengeluaran', 'Rp' . number_format(Expense::sum('amount'), 0, ',', '.')],
                    ['Total Gaji Guru', 'Rp' . number_format(TeacherSalary::sum('total_paid'), 0, ',', '.')],
                ],
            ],
            'reports' => [
                'title' => 'Cross Module Reports',
                'description' => 'Laporan agregat lintas modul akademik dan keuangan.',
                'columns' => ['Laporan', 'Jumlah'],
                'rows' => [
                    ['Absensi Guru Hari Ini', (string) TeacherAttendance::whereDate('attendance_date', now()->toDateString())->count()],
                    ['Absensi Siswa Hari Ini', (string) Attendance::whereDate('created_at', now()->toDateString())->count()],
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

    private function hasSchedulesTable(): bool
    {
        return Schema::hasTable('schedules');
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

    public function impersonate(User $user)
    {
        if (auth()->id() === $user->id) {
            return back()->with('error', 'Anda tidak bisa menyamar sebagai diri sendiri.');
        }

        session(['impersonator_id' => auth()->id()]);
        auth()->login($user);

        // Redirect based on role
        if ($user->hasRole('teacher')) {
            return redirect()->route('teacher.dashboard')->with('success', 'Anda sekarang login sebagai ' . $user->name);
        } elseif ($user->hasRole('student')) {
            return redirect()->route('student.dashboard')->with('success', 'Anda sekarang login sebagai ' . $user->name);
        } elseif ($user->hasRole('finance')) {
            return redirect()->route('finance.dashboard')->with('success', 'Anda sekarang login sebagai ' . $user->name);
        }

        return redirect()->route('portal.redirect')->with('success', 'Anda sekarang login sebagai ' . $user->name);
    }

    public function stopImpersonate()
    {
        $adminId = session('impersonator_id');

        if ($adminId) {
            $admin = User::find($adminId);
            if ($admin) {
                auth()->login($admin);
                session()->forget('impersonator_id');

                $route = $admin->hasRole('super_admin') ? 'super-admin.dashboard' : 'admin.dashboard';
                return redirect()->route($route)->with('success', 'Kembali ke panel Admin');
            }
        }

        return redirect('/login');
    }
}
