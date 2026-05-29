<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreMusicClassRequest;
use App\Http\Requests\Admin\StoreStudentRequest;
use App\Http\Requests\Admin\StoreTeacherRequest;
use App\Http\Requests\Admin\UpdateRegistrationStatusRequest;
use App\Models\MusicClass;
use App\Models\Registration;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AcademicManagementController extends Controller
{
    public function classes(): View
    {
        return view('portal.admin.classes', [
            'classList' => MusicClass::with('teacher')->latest()->get(),
            'teachers' => Teacher::orderBy('name')->get(),
        ]);
    }

    public function storeClass(StoreMusicClassRequest $request): RedirectResponse
    {
        MusicClass::create($request->validated());

        return back()->with('success', 'Kelas berhasil ditambahkan.');
    }

    public function updateClass(StoreMusicClassRequest $request, MusicClass $class): RedirectResponse
    {
        $class->update($request->validated());

        return back()->with('success', 'Kelas berhasil diperbarui.');
    }

    public function destroyClass(MusicClass $class): RedirectResponse
    {
        $class->delete();

        return back()->with('success', 'Kelas berhasil dihapus.');
    }

    public function teachers(): View
    {
        return view('portal.admin.teachers', [
            'teachers' => Teacher::with('user')->latest()->get(),
        ]);
    }

    public function storeTeacher(StoreTeacherRequest $request): RedirectResponse
    {
        $payload = $request->validated();
        $payload['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('photo')) {
            $payload['photo_path'] = $request->file('photo')->store('teachers', 'public');
        }

        Teacher::create($payload);

        return back()->with('success', 'Guru berhasil ditambahkan.');
    }

    public function updateTeacher(StoreTeacherRequest $request, Teacher $teacher): RedirectResponse
    {
        $payload = $request->validated();
        $payload['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('photo')) {
            $payload['photo_path'] = $request->file('photo')->store('teachers', 'public');
        }

        $teacher->update($payload);

        return back()->with('success', 'Data guru berhasil diperbarui.');
    }

    public function destroyTeacher(Teacher $teacher): RedirectResponse
    {
        $teacher->delete();

        return back()->with('success', 'Guru berhasil dihapus.');
    }

    public function students(): View
    {
        return view('portal.admin.students', [
            'students' => Student::with('classes')->latest()->get(),
            'classList' => MusicClass::orderBy('name')->get(),
        ]);
    }

    public function storeStudent(StoreStudentRequest $request): RedirectResponse
    {
        $payload = $request->validated();
        $payload['is_active'] = $request->boolean('is_active');
        unset($payload['class_ids']);

        if (!empty($payload['start_date']) && !empty($payload['duration_months'])) {
            $payload['end_date'] = \Carbon\Carbon::parse($payload['start_date'])->addMonths((int)$payload['duration_months'])->toDateString();
        }

        $student = Student::create($payload);
        $student->classes()->sync($request->input('class_ids', []));

        return back()->with('success', 'Siswa berhasil ditambahkan.');
    }

    public function updateStudent(StoreStudentRequest $request, Student $student): RedirectResponse
    {
        $payload = $request->validated();
        $payload['is_active'] = $request->boolean('is_active');
        unset($payload['class_ids']);

        if (!empty($payload['start_date']) && !empty($payload['duration_months'])) {
            $payload['end_date'] = \Carbon\Carbon::parse($payload['start_date'])->addMonths((int)$payload['duration_months'])->toDateString();
        }

        $student->update($payload);
        $student->classes()->sync($request->input('class_ids', []));

        return back()->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function destroyStudent(Student $student): RedirectResponse
    {
        \Illuminate\Support\Facades\DB::transaction(function () use ($student) {
            $user = $student->user;
            if ($user) {
                $user->delete();
            } else {
                $student->delete();
            }
        });

        return back()->with('success', 'Siswa berhasil dihapus.');
    }

    public function schedule(): View
    {
        return view('portal.admin.schedule', [
            'classList' => MusicClass::with(['teacher', 'students'])->orderBy('name')->get(),
            'teachers' => Teacher::orderBy('name')->get(['id', 'name']),
            'students' => Student::where('is_active', true)->orderBy('name')->get(['id', 'name', 'email']),
        ]);
    }

    public function attendance(Request $request): View
    {
        $date = $request->input('date');
        $teacherId = $request->input('teacher_id');
        $classId = $request->input('class_id');

        $query = \App\Models\Attendance::with([
            'schedule.class',
            'schedule.teacher',
            'schedule.student.user'
        ]);

        if ($date) {
            $query->whereDate('created_at', $date);
        }

        if ($teacherId) {
            $query->where('teacher_id', $teacherId);
        }

        if ($classId) {
            $query->whereHas('schedule', function ($q) use ($classId) {
                $q->where('class_id', $classId);
            });
        }

        $attendances = $query->latest()->paginate(15);
        $teachers = \App\Models\Teacher::orderBy('name')->get();
        $classes = \App\Models\MusicClass::orderBy('name')->get();

        // Calculate summary across all pages, not just the current paginated view
        $totalCount = $attendances->total();
        
        // Clone query to count statuses accurately across all results
        $presentQuery = clone $query;
        $presentCount = $presentQuery->where('status', 'present')->count();

        $absentQuery = clone $query;
        $absentCount = $absentQuery->where('status', 'absent')->count();

        $rescQuery = clone $query;
        $rescCount = $rescQuery->where('status', 'reschedule')->count();

        // Detect whether we are in super-admin or admin context
        $routePrefix = str_contains($request->route()->getName() ?? '', 'super-admin.')
            ? 'super-admin'
            : 'admin';

        return view('portal.admin.attendance', compact('attendances', 'teachers', 'classes', 'date', 'teacherId', 'classId', 'routePrefix', 'totalCount', 'presentCount', 'absentCount', 'rescCount'));
    }

    public function exportAttendance(Request $request)
    {
        $date = $request->input('date');
        $teacherId = $request->input('teacher_id');
        $classId = $request->input('class_id');

        $query = \App\Models\Attendance::with([
            'schedule.class',
            'schedule.teacher',
            'schedule.student.user'
        ]);

        if ($date) {
            $query->whereDate('created_at', $date);
        }
        if ($teacherId) {
            $query->where('teacher_id', $teacherId);
        }
        if ($classId) {
            $query->whereHas('schedule', function ($q) use ($classId) {
                $q->where('class_id', $classId);
            });
        }

        $attendances = $query->latest()->get();
        $teacher = $teacherId ? \App\Models\Teacher::find($teacherId) : null;
        $class = $classId ? \App\Models\MusicClass::find($classId) : null;

        $presentCount = $attendances->where('status', 'present')->count();
        $absentCount = $attendances->where('status', 'absent')->count();
        $rescCount = $attendances->where('status', 'reschedule')->count();
        $totalCount = $attendances->count();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('portal.admin.pdf.attendance', compact(
            'attendances', 'teacher', 'class', 'date', 'presentCount', 'absentCount', 'rescCount', 'totalCount'
        ));

        // Set paper size and orientation if needed
        $pdf->setPaper('A4', 'landscape');

        return $pdf->download('Rekap_Absensi_' . now()->format('Ymd_His') . '.pdf');
    }

    public function updateAttendance(Request $request, $id): RedirectResponse
    {
        $attendance = \App\Models\Attendance::findOrFail($id);
        $data = $request->validate([
            'status' => ['required', 'string', 'in:present,absent,reschedule'],
            'note' => ['nullable', 'string', 'max:500'],
        ]);

        $attendance->update($data);

        return back()->with('success', 'Data absensi berhasil diperbarui.');
    }

    public function destroyAttendance($id): RedirectResponse
    {
        $attendance = \App\Models\Attendance::findOrFail($id);
        $attendance->delete();

        return back()->with('success', 'Data absensi berhasil dihapus.');
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

    public function updateRegistrationStatus(UpdateRegistrationStatusRequest $request, Registration $registration): RedirectResponse
    {
        $registration->update($request->validated());

        if ($registration->status === 'accepted') {
            $student = Student::query()->updateOrCreate(
                ['email' => $registration->email],
                [
                    'name' => $registration->full_name ?: $registration->nama_lengkap,
                    'age' => $registration->age,
                    'phone' => $registration->phone ?: $registration->no_hp_siswa,
                    'email' => $registration->email,
                    'nama_panggilan' => $registration->nama_panggilan,
                    'jenis_kelamin' => $registration->jenis_kelamin,
                    'tempat_lahir' => $registration->tempat_lahir,
                    'tanggal_lahir' => $registration->tanggal_lahir,
                    'kewarganegaraan' => $registration->kewarganegaraan,
                    'address' => $registration->alamat ?: $registration->address,
                    'nama_ortu' => $registration->nama_ortu,
                    'pekerjaan_ortu' => $registration->pekerjaan_ortu,
                    'no_hp_ortu' => $registration->no_hp_ortu,
                    'email_ortu' => $registration->email_ortu,
                    'ig_siswa' => $registration->ig_siswa,
                    'ig_ortu' => $registration->ig_ortu,
                    'favorite_song' => $registration->favorite_song,
                    'is_active' => true,
                ]
            );

            if ($registration->class_id) {
                $student->update(['class_id' => $registration->class_id]);
                $student->classes()->syncWithoutDetaching([$registration->class_id]);
            }

            // Generate Sessions
            if ($registration->schedule_id && $registration->start_date) {
                $schedule = \App\Models\Schedule::find($registration->schedule_id);
                if ($schedule) {
                    $startDate = \Carbon\Carbon::parse($registration->start_date);
                    $durationMonths = (int)($registration->duration_months ?: 1);
                    $totalSessions = $durationMonths * 4;
                    
                    $dayName = $schedule->day;
                    $engDay = $this->mapDayToEnglish($dayName);
                    
                    // Find first occurrence of $engDay on or after $startDate
                    if (strtolower($startDate->format('l')) !== strtolower($engDay)) {
                        $startDate->modify("next $engDay");
                    }

                    for ($i = 0; $i < $totalSessions; $i++) {
                        \App\Models\ScheduleSession::create([
                            'registration_id' => $registration->id,
                            'student_id' => $student->id,
                            'schedule_id' => $schedule->id,
                            'class_id' => $registration->class_id,
                            'teacher_id' => $schedule->teacher_id,
                            'session_date' => $startDate->toDateString(),
                            'time' => $schedule->time,
                            'status' => 'booked',
                        ]);
                        $startDate->addWeek();
                    }
                }
            }
        }

        return back()->with('success', 'Status pendaftaran berhasil diperbarui.');
    }

    private function mapDayToEnglish(string $day): string
    {
        $map = [
            'senin' => 'monday',
            'selasa' => 'tuesday',
            'rabu' => 'wednesday',
            'kamis' => 'thursday',
            'jumat' => 'friday',
            'sabtu' => 'saturday',
            'minggu' => 'sunday',
        ];
        
        $lower = strtolower($day);
        return $map[$lower] ?? $lower;
    }
}
