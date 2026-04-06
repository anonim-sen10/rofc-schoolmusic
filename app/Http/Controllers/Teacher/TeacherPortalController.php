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
use Illuminate\View\View;

class TeacherPortalController extends Controller
{
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
        $classIds = $teacher->classes()->pluck('id');

        return view('portal.teacher.dashboard', [
            'teacher' => $teacher,
            'classCount' => $classIds->count(),
            'studentCount' => Student::whereHas('classes', fn ($q) => $q->whereIn('classes.id', $classIds))->count(),
            'attendanceCount' => Attendance::where('teacher_id', $teacher->id)->whereDate('attendance_date', now()->toDateString())->count() + TeacherAttendance::where('teacher_id', $teacher->id)->whereDate('attendance_date', now()->toDateString())->count(),
            'progressCount' => StudentProgress::where('teacher_id', $teacher->id)->count(),
        ]);
    }

    public function attendance(Request $request): View
    {
        $teacher = $this->teacherFromUser($request->user()->id);
        $classes = MusicClass::where('teacher_id', $teacher->id)->get();
        $today = now()->toDateString();
        $hasTeacherAttendanceToday = TeacherAttendance::query()
            ->where('teacher_id', $teacher->id)
            ->whereDate('attendance_date', $today)
            ->exists();
        $hasAssignedClasses = $classes->isNotEmpty();

        return view('portal.teacher.attendance', [
            'teacher' => $teacher,
            'classOptions' => $classes,
            'hasAssignedClasses' => $hasAssignedClasses,
            'students' => Student::whereHas('classes', fn ($q) => $q->whereIn('classes.id', $classes->pluck('id')))->get(),
            'records' => Attendance::with(['class', 'student'])->where('teacher_id', $teacher->id)->latest('attendance_date')->take(20)->get(),
            'teacherRecords' => TeacherAttendance::with('teacher')->where('teacher_id', $teacher->id)->latest('attendance_date')->take(20)->get(),
            'hasTeacherAttendanceToday' => $hasTeacherAttendanceToday,
        ]);
    }

    public function storeAttendance(Request $request): RedirectResponse
    {
        $teacher = $this->teacherFromUser($request->user()->id);
        $teacherClassIds = MusicClass::query()->where('teacher_id', $teacher->id)->pluck('id');

        if ($teacherClassIds->isEmpty()) {
            return back()->withErrors([
                'class_id' => 'Belum ada kelas yang di-assign ke guru ini. Hubungi Admin/Super Admin untuk assign kelas.',
            ])->withInput();
        }

        $data = $request->validate([
            'class_id' => ['required', 'exists:classes,id'],
            'student_name' => ['required', 'string', 'max:120'],
            'attendance_date' => ['required', 'date'],
            'status' => ['required', 'in:present,absent,late'],
            'note' => ['nullable', 'string'],
        ]);

        $class = MusicClass::query()
            ->where('id', $data['class_id'])
            ->where('teacher_id', $teacher->id)
            ->first();

        if (! $class) {
            return back()->withErrors([
                'class_id' => 'Kelas yang dipilih tidak terdaftar pada guru ini.',
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

        $studentName = trim($data['student_name']);
        $student = Student::query()
            ->whereRaw('LOWER(name) = ?', [mb_strtolower($studentName)])
            ->first();

        if (! $student) {
            $student = Student::create([
                'name' => $studentName,
                'is_active' => true,
            ]);
        }

        $student->classes()->syncWithoutDetaching([$class->id]);

        Attendance::create([
            'class_id' => $class->id,
            'student_id' => $student->id,
            'teacher_id' => $teacher->id,
            'attendance_date' => $data['attendance_date'],
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
        $classes = MusicClass::where('teacher_id', $teacher->id)->get();

        return view('portal.teacher.progress', [
            'teacher' => $teacher,
            'classes' => $classes,
            'students' => Student::whereHas('classes', fn ($q) => $q->whereIn('classes.id', $classes->pluck('id')))->get(),
            'records' => StudentProgress::where('teacher_id', $teacher->id)->latest()->take(20)->get(),
        ]);
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

        $data['teacher_id'] = $teacher->id;
        StudentProgress::create($data);

        return back()->with('success', 'Progress siswa berhasil disimpan.');
    }

    public function materials(Request $request): View
    {
        $teacher = $this->teacherFromUser($request->user()->id);

        return view('portal.teacher.materials', [
            'teacher' => $teacher,
            'classes' => MusicClass::where('teacher_id', $teacher->id)->get(),
            'materials' => Material::where('teacher_id', $teacher->id)->latest()->get(),
        ]);
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
