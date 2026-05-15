<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\StudentProgress;
use App\Models\Teacher;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class TeacherProgressController extends Controller
{
    private function hasAssignmentStatusColumn(): bool
    {
        return Schema::hasColumn('classes', 'assignment_status');
    }

    private function teacherFromUser(int $userId): Teacher
    {
        return Teacher::firstOrCreate(
            ['user_id' => $userId],
            ['name' => 'Teacher User '.$userId, 'instrument' => 'General', 'is_active' => true]
        );
    }

    private function teacherStudentOrAbort(Teacher $teacher, int $studentId): Student
    {
        $student = Student::query()
            ->where('id', $studentId)
            ->where(function($query) use ($teacher) {
                // Cek lewat Kelas
                $query->whereHas('classes', function ($q) use ($teacher) {
                    $q->where('classes.teacher_id', $teacher->id);
                })
                // ATAU Cek lewat Jadwal
                ->orWhereHas('scheduleSessions', function ($q) use ($teacher) {
                    $q->where('schedule_sessions.teacher_id', $teacher->id);
                });
            })
            ->with([
                'classes' => function ($query) use ($teacher) {
                    $query->where('classes.teacher_id', $teacher->id)
                        ->orderBy('classes.name');
                },
                'scheduleSessions' => function ($query) use ($teacher) {
                    $query->where('teacher_id', $teacher->id)
                        ->with('musicClass');
                }
            ])
            ->first();

        if (! $student) {
            abort(403, 'Siswa bukan milik teacher yang sedang login.');
        }

        return $student;
    }

    public function show(Request $request, $student_id): View
    {
        $teacher = $this->teacherFromUser($request->user()->id);
        $student = $this->teacherStudentOrAbort($teacher, $student_id);

        $classIds = $student->classes->pluck('id');
        if ($classIds->isEmpty()) {
            $classIds = $student->scheduleSessions->pluck('class_id')->filter()->unique();
        }
        
        $selectedClassId = old('class_id', $classIds->first());

        return view('portal.teacher.student-progress-show', [
            'teacher' => $teacher,
            'student' => $student,
            'selectedClassId' => $selectedClassId,
            'recentProgress' => StudentProgress::query()
                ->where('student_id', $student->id)
                ->whereIn('class_id', $classIds)
                ->with(['musicClass:id,name'])
                ->orderByDesc('recorded_at')
                ->orderByDesc('id')
                ->take(20)
                ->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $teacher = $this->teacherFromUser($request->user()->id);

        $data = $request->validate([
            'student_id' => ['required', 'integer', 'exists:students,id'],
            'class_id' => ['required', 'integer', 'exists:classes,id'],
            'topic' => ['required', 'string', 'max:150'],
            'score' => ['nullable', 'integer', 'min:0', 'max:100'],
            'note' => ['nullable', 'string'],
            'recorded_at' => ['required', 'date'],
        ]);

        $student = $this->teacherStudentOrAbort($teacher, (int) $data['student_id']);

        $classIds = $student->classes->pluck('id');
        if ($classIds->isEmpty()) {
            $classIds = $student->scheduleSessions->pluck('class_id')->filter()->unique();
        }

        $isStudentClassOwnedByTeacher = $classIds->contains((int) $data['class_id']);

        if (! $isStudentClassOwnedByTeacher) {
            abort(403, 'Kelas siswa tidak berada di bawah teacher yang sedang login.');
        }

        StudentProgress::create([
            'student_id' => $student->id,
            'class_id' => $data['class_id'],
            'teacher_id' => $teacher->id,
            'topic' => $data['topic'],
            'score' => $data['score'] ?? null,
            'note' => $data['note'] ?? null,
            'recorded_at' => $data['recorded_at'],
        ]);

        return redirect()
            ->route('teacher.student-progress.input', ['student_id' => $student->id])
            ->with('success', 'Progress siswa berhasil disimpan.');
    }
}
