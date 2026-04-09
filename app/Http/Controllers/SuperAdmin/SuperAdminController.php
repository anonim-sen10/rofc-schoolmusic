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
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SuperAdminController extends Controller
{
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

    public function storeTeacherAccount(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:120', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'phone' => ['required', 'string', 'max:30'],
            'address' => ['required', 'string', 'max:500'],
            'gender' => ['required', 'in:laki-laki,perempuan'],
            'religion' => ['required', 'string', 'max:30'],
            'class_name' => ['required', 'in:drum,gitar,vocal,violin,piano'],
            'photo' => ['nullable', 'image', 'max:2048'],
        ]);

        $role = $this->resolveCoreRole('teacher');

        $user = User::query()->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $user->roles()->sync([$role->id]);

        $selectedClassName = self::TEACHER_CLASS_OPTIONS[$data['class_name']];

        $payload = [
            'user_id' => $user->id,
            'name' => $data['name'],
            'phone' => $data['phone'],
            'address' => $data['address'],
            'gender' => $data['gender'],
            'religion' => $data['religion'],
            'instrument' => $selectedClassName,
            'is_active' => true,
        ];

        if ($request->hasFile('photo')) {
            $payload['photo_path'] = $request->file('photo')->store('teachers', 'public');
        }

        $teacher = Teacher::query()->create($payload);

        $class = MusicClass::query()->firstOrCreate(
            ['name' => $selectedClassName],
            [
                'description' => 'Kelas '.$selectedClassName,
                'price' => 0,
                'schedule' => null,
                'status' => 'active',
            ]
        );

        $class->update(['teacher_id' => $teacher->id]);

        return back()->with('success', 'Akun teacher dan data guru berhasil dibuat.');
    }

    public function showTeacher(Teacher $teacher): View
    {
        $teacher->load('user', 'classes');

        return view('portal.super-admin.teacher-detail', [
            'roleKey' => 'super_admin',
            'portal' => $this->portalConfig(),
            'teacher' => $teacher,
        ]);
    }

    public function editTeacher(Teacher $teacher): View
    {
        $teacher->load('user', 'classes');

        return view('portal.super-admin.teacher-edit', [
            'roleKey' => 'super_admin',
            'portal' => $this->portalConfig(),
            'teacher' => $teacher,
            'classesForTeachers' => MusicClass::query()->orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function updateTeacher(Request $request, Teacher $teacher): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:120', 'unique:users,email,'.($teacher->user_id ?? 'NULL')],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'phone' => ['required', 'string', 'max:30'],
            'address' => ['required', 'string', 'max:500'],
            'gender' => ['required', 'in:laki-laki,perempuan'],
            'religion' => ['required', 'string', 'max:30'],
            'instrument' => ['nullable', 'string', 'max:80'],
            'class_id' => ['nullable', 'integer', 'exists:classes,id'],
            'photo' => ['nullable', 'image', 'max:2048'],
        ]);

        if ($teacher->user) {
            $teacher->user->name = $data['name'];
            $teacher->user->email = $data['email'];

            if (! empty($data['password'])) {
                $teacher->user->password = Hash::make($data['password']);
            }

            $teacher->user->save();
        }

        $payload = [
            'name' => $data['name'],
            'phone' => $data['phone'],
            'address' => $data['address'],
            'gender' => $data['gender'],
            'religion' => $data['religion'],
            'instrument' => $data['instrument'] ?? 'General',
            'is_active' => true,
        ];

        if ($request->hasFile('photo')) {
            $payload['photo_path'] = $request->file('photo')->store('teachers', 'public');
        }

        $teacher->update($payload);

        if (! empty($data['class_id'])) {
            MusicClass::query()->whereKey($data['class_id'])->update([
                'teacher_id' => $teacher->id,
            ]);
        }

        return redirect()->route('super-admin.module', ['module' => 'teachers'])->with('success', 'Data teacher berhasil diperbarui.');
    }

    public function destroyTeacher(Request $request, Teacher $teacher): RedirectResponse
    {
        $linkedUser = $teacher->user;

        $teacher->delete();

        if ($linkedUser) {
            $linkedUser->delete();
        }

        return back()->with('success', 'Teacher berhasil dihapus.');
    }

    public function storeClass(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'description' => ['nullable', 'string'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'schedule' => ['nullable', 'string', 'max:120'],
            'teacher_id' => ['nullable', 'integer', 'exists:teachers,id'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        MusicClass::query()->create([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'price' => $data['price'] ?? 0,
            'schedule' => $data['schedule'] ?? null,
            'teacher_id' => $data['teacher_id'] ?? null,
            'status' => $data['status'],
        ]);

        return back()->with('success', 'Class berhasil ditambahkan.');
    }

    public function updateClass(Request $request, MusicClass $class): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'description' => ['nullable', 'string'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'schedule' => ['nullable', 'string', 'max:120'],
            'teacher_id' => ['nullable', 'integer', 'exists:teachers,id'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        $class->update([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'price' => $data['price'] ?? 0,
            'schedule' => $data['schedule'] ?? null,
            'teacher_id' => $data['teacher_id'] ?? null,
            'status' => $data['status'],
        ]);

        return back()->with('success', 'Class berhasil diperbarui.');
    }

    public function destroyClass(MusicClass $class): RedirectResponse
    {
        $class->students()->detach();
        $class->delete();

        return back()->with('success', 'Class berhasil dihapus.');
    }

    public function storeStudent(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'age' => ['nullable', 'integer', 'min:4', 'max:80'],
            'phone' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:120', 'unique:students,email'],
            'address' => ['nullable', 'string', 'max:500'],
            'is_active' => ['required', 'in:1,0'],
            'class_ids' => ['nullable', 'array'],
            'class_ids.*' => ['integer', 'exists:classes,id'],
        ]);

        $student = Student::query()->create([
            'name' => $data['name'],
            'age' => $data['age'] ?? null,
            'phone' => $data['phone'] ?? null,
            'email' => $data['email'] ?? null,
            'address' => $data['address'] ?? null,
            'is_active' => (bool) ($data['is_active'] ?? '1'),
        ]);

        $student->classes()->sync($data['class_ids'] ?? []);

        return back()->with('success', 'Siswa berhasil ditambahkan.');
    }

    public function updateStudent(Request $request, Student $student): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'age' => ['nullable', 'integer', 'min:4', 'max:80'],
            'phone' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:120', 'unique:students,email,'.$student->id],
            'address' => ['nullable', 'string', 'max:500'],
            'is_active' => ['required', 'in:1,0'],
            'class_ids' => ['nullable', 'array'],
            'class_ids.*' => ['integer', 'exists:classes,id'],
        ]);

        $student->update([
            'name' => $data['name'],
            'age' => $data['age'] ?? null,
            'phone' => $data['phone'] ?? null,
            'email' => $data['email'] ?? null,
            'address' => $data['address'] ?? null,
            'is_active' => (bool) ($data['is_active'] ?? '1'),
        ]);

        $student->classes()->sync($data['class_ids'] ?? []);

        return back()->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function destroyStudent(Student $student): RedirectResponse
    {
        $student->classes()->detach();
        $student->delete();

        return back()->with('success', 'Siswa berhasil dihapus.');
    }

    public function storeRegistration(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'full_name' => ['required', 'string', 'max:120'],
            'age' => ['required', 'integer', 'min:4', 'max:80'],
            'phone' => ['required', 'string', 'max:30'],
            'email' => ['required', 'email', 'max:120'],
            'class_id' => ['nullable', 'integer', 'exists:classes,id'],
            'preferred_schedule' => ['required', 'string', 'max:120'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'status' => ['required', 'in:pending,accepted,rejected'],
        ]);

        $registration = Registration::query()->create($data);

        if ($registration->status === 'accepted') {
            $student = Student::query()->updateOrCreate(
                ['email' => $registration->email],
                [
                    'name' => $registration->full_name,
                    'age' => $registration->age,
                    'phone' => $registration->phone,
                    'email' => $registration->email,
                    'is_active' => true,
                ]
            );

            if ($registration->class_id) {
                $student->classes()->syncWithoutDetaching([$registration->class_id]);
            }
        }

        return back()->with('success', 'Registrasi berhasil ditambahkan.');
    }

    public function updateRegistration(Request $request, Registration $registration): RedirectResponse
    {
        $data = $request->validate([
            'full_name' => ['required', 'string', 'max:120'],
            'age' => ['required', 'integer', 'min:4', 'max:80'],
            'phone' => ['required', 'string', 'max:30'],
            'email' => ['required', 'email', 'max:120'],
            'class_id' => ['nullable', 'integer', 'exists:classes,id'],
            'preferred_schedule' => ['required', 'string', 'max:120'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'status' => ['required', 'in:pending,accepted,rejected'],
        ]);

        $registration->update($data);

        if ($registration->status === 'accepted') {
            $student = Student::query()->updateOrCreate(
                ['email' => $registration->email],
                [
                    'name' => $registration->full_name,
                    'age' => $registration->age,
                    'phone' => $registration->phone,
                    'email' => $registration->email,
                    'is_active' => true,
                ]
            );

            if ($registration->class_id) {
                $student->classes()->syncWithoutDetaching([$registration->class_id]);
            }
        }

        return back()->with('success', 'Registrasi berhasil diperbarui.');
    }

    public function destroyRegistration(Registration $registration): RedirectResponse
    {
        $registration->delete();

        return back()->with('success', 'Registrasi berhasil dihapus.');
    }

    public function storeContent(Request $request, string $module): RedirectResponse
    {
        $data = $this->validateContentPayload($request, $module);

        DB::table($this->contentTable($module))->insert(array_merge($data, [
            'created_at' => now(),
            'updated_at' => now(),
        ]));

        return back()->with('success', 'Data '.$module.' berhasil ditambahkan.');
    }

    public function updateContent(Request $request, string $module, int $id): RedirectResponse
    {
        $data = $this->validateContentPayload($request, $module, $id);

        DB::table($this->contentTable($module))
            ->where('id', $id)
            ->update(array_merge($data, ['updated_at' => now()]));

        return back()->with('success', 'Data '.$module.' berhasil diperbarui.');
    }

    public function destroyContent(string $module, int $id): RedirectResponse
    {
        DB::table($this->contentTable($module))->where('id', $id)->delete();

        return back()->with('success', 'Data '.$module.' berhasil dihapus.');
    }

    public function destroyLog(int $id): RedirectResponse
    {
        DB::table('activities')->where('id', $id)->delete();

        return back()->with('success', 'Log aktivitas berhasil dihapus.');
    }

    public function assignScheduleTeacher(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'class_id' => ['required', 'integer', 'exists:classes,id'],
            'teacher_id' => ['required', 'integer', 'exists:teachers,id'],
        ]);

        MusicClass::query()->whereKey($data['class_id'])->update([
            'teacher_id' => $data['teacher_id'],
            'assignment_status' => 'pending',
            'assignment_note' => null,
            'assigned_at' => now(),
            'responded_at' => null,
        ]);

        return back()->with('success', 'Pengajar berhasil ditentukan untuk class.');
    }

    public function assignScheduleStudents(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'class_id' => ['required', 'integer', 'exists:classes,id'],
            'student_ids' => ['required', 'array', 'min:1'],
            'student_ids.*' => ['integer', 'exists:students,id'],
        ]);

        $class = MusicClass::query()->findOrFail($data['class_id']);
        $class->students()->syncWithoutDetaching($data['student_ids']);

        return back()->with('success', 'Siswa berhasil ditambahkan ke class.');
    }

    public function unassignScheduleTeacher(MusicClass $class): RedirectResponse
    {
        $class->update([
            'teacher_id' => null,
            'assignment_status' => 'pending',
            'assignment_note' => null,
            'assigned_at' => null,
            'responded_at' => null,
        ]);

        return back()->with('success', 'Pengajar berhasil dilepas dari class.');
    }

    public function removeScheduleStudent(MusicClass $class, Student $student): RedirectResponse
    {
        $class->students()->detach([$student->id]);

        return back()->with('success', 'Siswa berhasil dihapus dari class.');
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
            ->selectRaw('YEAR(attendance_date) as year, MONTH(attendance_date) as month, COUNT(*) as total, SUM(CASE WHEN status IN ("present", "late") THEN 1 ELSE 0 END) as present_total')
            ->whereBetween('attendance_date', [$rangeStart, $rangeEnd])
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
        $previousMonthStart = now()->copy()->subMonth()->startOfMonth();
        $previousMonthEnd = now()->copy()->subMonth()->endOfMonth();

        $studentsCurrent = Student::query()->where('is_active', true)->count();
        $studentsPrev = Student::query()->where('is_active', true)->where('created_at', '<', $currentMonthStart)->count();
        $teachersCurrent = Teacher::query()->where('is_active', true)->count();
        $teachersPrev = Teacher::query()->where('is_active', true)->where('created_at', '<', $currentMonthStart)->count();
        $revenueCurrent = (float) Payment::query()->where('status', 'paid')->whereBetween('paid_at', [$currentMonthStart, now()->copy()->endOfMonth()])->sum('amount');
        $revenuePrev = (float) Payment::query()->where('status', 'paid')->whereBetween('paid_at', [$previousMonthStart, $previousMonthEnd])->sum('amount');
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
                'label' => ($delta > 0 ? '+' : '').number_format($delta, 1).'%',
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
                'value' => 'Rp'.number_format($revenueCurrent, 0, ',', '.'),
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

        return view('portal.super-admin.dashboard', [
            'roleKey' => 'super_admin',
            'portal' => $this->portalConfig(),
            'kpis' => $kpis,
            'stats' => [
                ['label' => 'Total Users', 'value' => User::count()],
                ['label' => 'Active Students', 'value' => Student::where('is_active', true)->count()],
                ['label' => 'Active Teachers', 'value' => Teacher::where('is_active', true)->count()],
                ['label' => 'Net Cashflow', 'value' => 'Rp'.number_format($income - $expense - $salary, 0, ',', '.')],
            ],
            'chartData' => [
                'labels' => $chartLabels,
                'revenue' => $monthlyRevenue,
                'studentGrowth' => $monthlyStudents,
                'attendanceRate' => $monthlyAttendanceRate,
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

    private function monthBuckets(int $months): Collection
    {
        return collect(range($months - 1, 0))->map(
            fn (int $offset) => now()->copy()->startOfMonth()->subMonths($offset)
        );
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
            'classTypeOptions' => self::TEACHER_CLASS_OPTIONS,
            'teachersForManagement' => Teacher::query()->with(['user', 'classes'])->latest()->take(50)->get(),
            'teachersForClassOptions' => Teacher::query()->orderBy('name')->get(['id', 'name']),
            'classesForManagement' => MusicClass::query()->with(['teacher'])->orderBy('name')->get(),
            'classesForSchedule' => MusicClass::query()->with(['teacher', 'students'])->orderBy('name')->get(),
            'studentsForManagement' => Student::query()->with('classes')->orderBy('name')->get(),
            'registrationsForManagement' => Registration::query()->with('class')->latest()->get(),
            'studentsForSchedule' => Student::query()->where('is_active', true)->orderBy('name')->get(['id', 'name', 'email']),
            'postsForManagement' => DB::table('posts')->latest()->get(),
            'galleriesForManagement' => DB::table('galleries')->latest()->get(),
            'eventsForManagement' => DB::table('events')->latest()->get(),
            'testimonialsForManagement' => DB::table('testimonials')->latest()->get(),
            'settingsForManagement' => DB::table('settings')->orderBy('key')->get(),
            'logsForManagement' => DB::table('activities')->latest()->take(100)->get(),
        ]);
    }

    private function contentTable(string $module): string
    {
        return match ($module) {
            'blog' => 'posts',
            'gallery' => 'galleries',
            'events' => 'events',
            'testimonials' => 'testimonials',
            'settings' => 'settings',
            default => abort(404),
        };
    }

    private function validateContentPayload(Request $request, string $module, ?int $id = null): array
    {
        return match ($module) {
            'blog' => $request->validate([
                'title' => ['required', 'string', 'max:255'],
                'slug' => ['required', 'string', 'max:255', Rule::unique('posts', 'slug')->ignore($id)],
                'excerpt' => ['nullable', 'string'],
                'content' => ['nullable', 'string'],
                'cover_image' => ['nullable', 'string', 'max:255'],
                'status' => ['required', 'in:draft,published'],
                'published_at' => ['nullable', 'date'],
            ]),
            'gallery' => $request->validate([
                'title' => ['required', 'string', 'max:255'],
                'category' => ['nullable', 'string', 'max:255'],
                'type' => ['required', 'in:photo,video'],
                'file_path' => ['required', 'string', 'max:255'],
            ]),
            'events' => $request->validate([
                'title' => ['required', 'string', 'max:255'],
                'description' => ['nullable', 'string'],
                'event_date' => ['nullable', 'date'],
                'location' => ['nullable', 'string', 'max:255'],
                'status' => ['required', 'in:draft,upcoming,completed,cancelled'],
            ]),
            'testimonials' => $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'role' => ['nullable', 'string', 'max:255'],
                'message' => ['required', 'string'],
                'is_published' => ['required', 'in:1,0'],
            ]),
            'settings' => $request->validate([
                'key' => ['required', 'string', 'max:255', Rule::unique('settings', 'key')->ignore($id)],
                'value' => ['nullable', 'string'],
            ]),
            default => abort(404),
        };
    }

    private function portalConfig(): array
    {
        return [
            'title' => 'Super Admin Dashboard',
            'prefix' => 'super-admin',
            'menu' => [
                ['key' => 'dashboard', 'label' => 'Dashboard', 'icon' => 'layout-dashboard'],
                ['key' => 'users', 'label' => 'Users', 'icon' => 'user-round'],
                ['key' => 'roles', 'label' => 'Manajemen User', 'icon' => 'shield-check'],
                ['key' => 'classes', 'label' => 'Classes', 'icon' => 'book-open'],
                ['key' => 'teachers', 'label' => 'Teachers', 'icon' => 'music-2'],
                ['key' => 'schedule', 'label' => 'Schedule', 'icon' => 'calendar-days'],
                ['key' => 'students', 'label' => 'Students', 'icon' => 'graduation-cap'],
                ['key' => 'registrations', 'label' => 'Registrations', 'icon' => 'clipboard-list'],
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
            'schedule' => [
                'title' => 'Schedule',
                'description' => 'Penentuan class untuk pengajar dan siswa.',
                'columns' => ['Class', 'Pengajar', 'Jumlah Siswa'],
                'rows' => MusicClass::with(['teacher', 'students'])->orderBy('name')->get()->map(fn (MusicClass $class) => [
                    $class->name,
                    ($class->teacher?->name ?? '-').' ('.strtoupper($class->assignment_status ?? 'pending').')',
                    (string) $class->students->count(),
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
