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
            ->whereHas('classes', function ($query) use ($teacher) {
                $query->where('classes.teacher_id', $teacher->id);

                if ($this->hasAssignmentStatusColumn()) {
                    $query->where('classes.assignment_status', 'accepted');
                }
            })
            ->with([
                'classes' => function ($query) use ($teacher) {
                    $query->where('classes.teacher_id', $teacher->id)
                        ->orderBy('classes.name');

                    if ($this->hasAssignmentStatusColumn()) {
                        $query->where('classes.assignment_status', 'accepted');
                    }
                },
            ])
            ->first();

        if (! $student) {
            abort(403, 'Siswa bukan milik teacher yang sedang login.');
        }

        return $student;
    }

    public function show(Request $request, int $student_id): View
    {
        $teacher = $this->teacherFromUser($request->user()->id);
        $student = $this->teacherStudentOrAbort($teacher, $student_id);

        $classIds = $student->classes->pluck('id');
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

        $isStudentClassOwnedByTeacher = $student->classes->contains(
            fn ($class) => (int) $class->id === (int) $data['class_id']
        );

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
