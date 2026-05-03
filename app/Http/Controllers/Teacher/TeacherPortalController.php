<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Material;
use App\Models\MusicClass;
use App\Models\Student;
use App\Models\StudentProgress;
use App\Models\Teacher;
use App\Models\TeacherAttendance;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class TeacherPortalController extends Controller
{
    private function hasAssignmentStatusColumn(): bool
    {
        return Schema::hasColumn('classes', 'assignment_status');
    }

    private function teacherAcceptedClassesQuery(int $teacherId)
    {
        return MusicClass::query()->where('teacher_id', $teacherId);
    }

    private function teacherFromUser(int $userId): Teacher
    {
        return Teacher::firstOrCreate(
            ['user_id' => $userId],
            ['name' => 'Teacher User '.$userId, 'instrument' => 'General', 'is_active' => true]
        );
    }

public function dashboard(Request $request): View
    {
        $teacher = $this->teacherFromUser($request->user()->id);
        $todayDay = now()->dayOfWeek; // 0 = Sunday, 1 = Monday, etc.
        $today = now()->toDateString();
        
        // Map Laravel dayOfWeek to our day names
        $dayMap = [
            0 => 'sunday',
            1 => 'monday',
            2 => 'tuesday',
            3 => 'wednesday',
            4 => 'thursday',
            5 => 'friday',
            6 => 'saturday',
        ];
        $todayDayName = $dayMap[$todayDay];
        
        // Get today's sessions (booked only)
        $todaySchedules = \App\Models\ScheduleSession::query()
            ->where('teacher_id', $teacher->id)
            ->where('session_date', $today)
            ->where('status', 'booked')
            ->with([
                'musicClass:id,name',
                'student:id,name,address'
            ])
            ->orderBy('time')
            ->get();
        
        // Count completed (attended) lessons for today
        $completedCount = Attendance::query()
            ->where('teacher_id', $teacher->id)
            ->whereDate('created_at', $today)
            ->where('status', 'present')
            ->count();

        $acceptedClasses = $this->teacherAcceptedClassesQuery($teacher->id)->orderBy('name')->get(['id', 'name', 'schedule']);
        $classIds = $acceptedClasses->pluck('id');

        return view('portal.teacher.dashboard', [
            'teacher' => $teacher,
            'todaySchedules' => $todaySchedules,
            'completedCount' => $completedCount,
            'classCount' => $classIds->count(),
            'studentCount' => Student::whereHas('classes', fn ($q) => $q->whereIn('classes.id', $classIds))->count(),
            'attendanceCount' => Attendance::where('teacher_id', $teacher->id)->whereDate('created_at', now()->toDateString())->count() + TeacherAttendance::where('teacher_id', $teacher->id)->whereDate('attendance_date', now()->toDateString())->count(),
            'progressCount' => StudentProgress::where('teacher_id', $teacher->id)->count(),
            'assignedClasses' => $acceptedClasses,
            'latestProgress' => StudentProgress::with('student:id,name')->where('teacher_id', $teacher->id)->latest()->take(5)->get(),
            'hasTeacherAttendanceToday' => TeacherAttendance::query()->where('teacher_id', $teacher->id)->whereDate('attendance_date', now()->toDateString())->exists(),
            'pendingRescheduleRequests' => \App\Models\RescheduleRequest::with(['student:id,name', 'oldSchedule', 'newSchedule'])
                ->whereHas('oldSchedule', fn($q) => $q->where('teacher_id', $teacher->id))
                ->where('status', 'pending')
                ->latest()
                ->get(),
        ]);
    }

    public function attendance(Request $request): View
    {
        $teacher = $this->teacherFromUser($request->user()->id);
        $classes = $this->teacherAcceptedClassesQuery($teacher->id)
            ->with(['students:id,name'])
            ->get();
        $today = now()->toDateString();
        $hasTeacherAttendanceToday = TeacherAttendance::query()
            ->where('teacher_id', $teacher->id)
            ->whereDate('attendance_date', $today)
            ->exists();
        $hasAssignedClasses = $classes->isNotEmpty();
        $classStudents = $classes->mapWithKeys(function (MusicClass $class) {
            return [
                $class->id => $class->students
                    ->sortBy('name')
                    ->map(fn (Student $student) => [
                        'id' => $student->id,
                        'name' => $student->name,
                    ])
                    ->values(),
            ];
        });

        return view('portal.teacher.attendance', [
            'teacher' => $teacher,
            'classOptions' => $classes,
            'hasAssignedClasses' => $hasAssignedClasses,
            'classStudents' => $classStudents,
            'records' => Attendance::with(['class', 'student'])->where('teacher_id', $teacher->id)->latest('created_at')->take(20)->get(),
            'teacherRecords' => TeacherAttendance::with('teacher')->where('teacher_id', $teacher->id)->latest('attendance_date')->take(20)->get(),
            'hasTeacherAttendanceToday' => $hasTeacherAttendanceToday,
        ]);
    }

    public function storeAttendance(Request $request): RedirectResponse
    {
        $teacher = $this->teacherFromUser($request->user()->id);
        $teacherClassIds = $this->teacherAcceptedClassesQuery($teacher->id)->pluck('id');

        if ($teacherClassIds->isEmpty()) {
            return back()->withErrors([
                'class_id' => 'Belum ada kelas yang di-assign ke guru ini. Hubungi Admin/Super Admin untuk assign kelas.',
            ])->withInput();
        }

        $data = $request->validate([
            'class_id' => ['required', 'exists:classes,id'],
            'student_id' => ['required', 'exists:students,id'],
            'attendance_date' => ['required', 'date'],
            'status' => ['required', 'in:present,absent,late'],
            'note' => ['nullable', 'string'],
        ]);

        $class = MusicClass::query()
            ->where('id', $data['class_id'])
            ->where('teacher_id', $teacher->id)
            ->first();

        if ($this->hasAssignmentStatusColumn()) {
            $class = MusicClass::query()
                ->where('id', $data['class_id'])
                ->where('teacher_id', $teacher->id)
                ->where('assignment_status', 'accepted')
                ->first();
        }

        if (! $class) {
            return back()->withErrors([
                'class_id' => 'Kelas yang dipilih tidak terdaftar pada guru ini.',
            ])->withInput();
        }

        $student = Student::query()->find($data['student_id']);
        if (! $student || ! $class->students()->where('students.id', $student->id)->exists()) {
            return back()->withErrors([
                'student_id' => 'Siswa tidak terdaftar pada kelas yang dipilih.',
            ])->withInput();
        }

        $teacherHasAttendance = TeacherAttendance::query()
            ->where('teacher_id', $teacher->id)
            ->whereDate('attendance_date', $data['attendance_date'])
            ->exists();

        if (! $teacherHasAttendance) {
            return back()->withErrors([
                'attendance_date' => 'Guru wajib absen terlebih dahulu pada tanggal tersebut sebelum mengisi absen siswa.',
            ])->withInput();
        }

        Attendance::create([
            'class_id' => $class->id,
            'student_id' => $student->id,
            'teacher_id' => $teacher->id,
            'created_at' => $data['attendance_date'],
            'status' => $data['status'],
            'note' => $data['note'] ?? null,
        ]);

        return back()->with('success', 'Absensi berhasil disimpan.');
    }

    public function storeTeacherAttendance(Request $request): RedirectResponse
    {
        $teacher = $this->teacherFromUser($request->user()->id);

        $data = $request->validate([
            'attendance_date' => ['required', 'date'],
            'status' => ['required', 'in:present,absent,late'],
            'location_text' => ['nullable', 'string', 'max:255'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'note' => ['nullable', 'string'],
        ]);

        $data['teacher_id'] = $teacher->id;
        TeacherAttendance::create($data);

        return back()->with('success', 'Absensi guru berhasil disimpan.');
    }

    public function progress(Request $request): View
    {
        $teacher = $this->teacherFromUser($request->user()->id);
        $classes = $this->teacherAcceptedClassesQuery($teacher->id)
            ->with(['students:id,name'])
            ->orderBy('name')
            ->get();

        $students = $classes
            ->flatMap(fn (MusicClass $class) => $class->students)
            ->unique('id')
            ->sortBy('name')
            ->values();

        $selectedStudentId = $request->integer('student_id');
        if ($selectedStudentId && ! $students->contains(fn (Student $student) => (int) $student->id === $selectedStudentId)) {
            abort(403, 'Siswa bukan milik teacher yang sedang login.');
        }

        $selectedClassId = null;
        if ($selectedStudentId) {
            $selectedClass = $classes->first(function (MusicClass $class) use ($selectedStudentId) {
                return $class->students->contains(fn (Student $student) => (int) $student->id === $selectedStudentId);
            });

            $selectedClassId = $selectedClass?->id;
        }

        $classStudents = $classes->mapWithKeys(function (MusicClass $class) {
            return [
                $class->id => $class->students
                    ->sortBy('name')
                    ->map(fn (Student $student) => [
                        'id' => $student->id,
                        'name' => $student->name,
                    ])
                    ->values(),
            ];
        });

        return view('portal.teacher.progress', [
            'teacher' => $teacher,
            'classes' => $classes,
            'students' => $students,
            'records' => StudentProgress::where('teacher_id', $teacher->id)->latest()->take(20)->get(),
            'classStudents' => $classStudents,
            'selectedStudentId' => $selectedStudentId,
            'selectedClassId' => $selectedClassId,
        ]);
    }

    public function progressForStudent(Request $request, int $student_id): View
    {
        $request->merge(['student_id' => $student_id]);

        return $this->progress($request);
    }

    public function storeProgress(Request $request): RedirectResponse
    {
        $teacher = $this->teacherFromUser($request->user()->id);

        $data = $request->validate([
            'class_id' => ['required', 'exists:classes,id'],
            'student_id' => ['required', 'exists:students,id'],
            'topic' => ['nullable', 'string', 'max:120'],
            'note' => ['nullable', 'string'],
            'score' => ['nullable', 'string', 'max:20'],
            'recorded_at' => ['nullable', 'date'],
        ]);

        $class = $this->teacherAcceptedClassesQuery($teacher->id)
            ->where('id', $data['class_id'])
            ->first();

        if (! $class) {
            return back()->withErrors([
                'class_id' => 'Kelas tidak terdaftar untuk teacher yang sedang login.',
            ])->withInput();
        }

        $studentInClass = $class->students()
            ->where('students.id', $data['student_id'])
            ->exists();

        if (! $studentInClass) {
            return back()->withErrors([
                'student_id' => 'Siswa tidak terdaftar pada kelas teacher ini.',
            ])->withInput();
        }

        $data['teacher_id'] = $teacher->id;
        StudentProgress::create($data);

        return back()->with('success', 'Progress siswa berhasil disimpan.');
    }

    public function materials(Request $request): View
    {
        $teacher = $this->teacherFromUser($request->user()->id);

        return view('portal.teacher.materials', [
            'teacher' => $teacher,
            'classes' => $this->teacherAcceptedClassesQuery($teacher->id)->get(),
            'materials' => Material::where('teacher_id', $teacher->id)->latest()->get(),
        ]);
    }

public function schedule(Request $request): View
    {
        $teacher = $this->teacherFromUser($request->user()->id);

        $schedules = \App\Models\ScheduleSession::query()
            ->where('teacher_id', $teacher->id)
            ->whereIn('status', ['booked', 'rescheduled', 'completed'])
            ->with(['musicClass', 'student.user', 'attendance'])
            ->orderBy('session_date')
            ->orderBy('time')
            ->get();

        return view('portal.teacher.schedule', [
            'teacher' => $teacher,
            'schedules' => $schedules,
        ]);
    }

    public function storeScheduleAttendance(Request $request)
    {
        $teacher = $this->teacherFromUser($request->user()->id);

        $validated = $request->validate([
            'session_id' => 'required|exists:schedule_sessions,id',
            'status' => 'required|in:present,absent,reschedule',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'note' => 'nullable|string',
        ]);

        $session = \App\Models\ScheduleSession::where('id', $validated['session_id'])
            ->where('teacher_id', $teacher->id)
            ->where('status', 'booked')
            ->firstOrFail();

        if ($session->attendance()->exists()) {
            return back()->with('error', 'Attendance already exists for this session.');
        }

        $session->attendance()->create([
            'teacher_id' => $teacher->id,
            'student_id' => $session->student_id,
            'status' => $validated['status'],
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
            'note' => $validated['note'],
            'session_id' => $session->id,
            'schedule_id' => $session->schedule_id,
        ]);

        $session->update(['status' => 'completed']);

        return back()->with('success', 'Attendance recorded successfully.');
    }

    public function myClasses(Request $request): View
    {
        $teacher = $this->teacherFromUser($request->user()->id);

        $classes = MusicClass::query()
            ->where('teacher_id', $teacher->id)
            ->with(['students:id,name'])
            ->withCount('students')
            ->withCount('schedules')
            ->orderBy('name')
            ->get();

        return view('portal.teacher.my-classes', [
            'teacher' => $teacher,
            'classes' => $classes,
        ]);
    }

    public function respondSchedule(Request $request, MusicClass $class): RedirectResponse
    {
        $teacher = $this->teacherFromUser($request->user()->id);

        if (! $this->hasAssignmentStatusColumn()) {
            return back()->withErrors([
                'schedule' => 'Fitur respon jadwal belum aktif karena database belum di-migrate. Jalankan php artisan migrate terlebih dahulu.',
            ]);
        }

        if ((int) $class->teacher_id !== (int) $teacher->id) {
            abort(403, 'Jadwal ini bukan untuk teacher yang sedang login.');
        }

        $data = $request->validate([
            'action' => ['required', 'in:accepted,rejected'],
            'note' => ['nullable', 'string', 'max:500'],
        ]);

        $class->update([
            'assignment_status' => $data['action'],
            'assignment_note' => $data['note'] ?? null,
            'responded_at' => now(),
        ]);

        return back()->with('success', 'Respon jadwal berhasil disimpan.');
    }

    public function storeMaterial(Request $request): RedirectResponse
    {
        $teacher = $this->teacherFromUser($request->user()->id);

        $data = $request->validate([
            'class_id' => ['nullable', 'exists:classes,id'],
            'title' => ['required', 'string', 'max:120'],
            'description' => ['nullable', 'string'],
            'file' => ['required', 'file', 'max:10240'],
        ]);

        $data['teacher_id'] = $teacher->id;
        $data['file_path'] = $request->file('file')->store('materials', 'public');
        unset($data['file']);

        Material::create($data);

        return back()->with('success', 'Materi berhasil diupload.');
    }
}
