<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\MusicClass;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class TeacherStudentController extends Controller
{
    private function hasAssignmentStatusColumn(): bool
    {
        return Schema::hasColumn('classes', 'assignment_status');
    }

    public function index(Request $request): View
    {
        $user = $request->user();

        $teacher = $user->teacher ?? Teacher::firstOrCreate(
            ['user_id' => $user->id],
            ['name' => 'Teacher User '.$user->id, 'instrument' => 'General', 'is_active' => true]
        );

        // 1. Ambil ID Kelas dari tabel Classes
        $classIds = $teacher->classes()->pluck('id');
        
        // 2. Ambil ID Siswa dari tabel ScheduleSession
        $studentIdsFromSchedule = \App\Models\ScheduleSession::where('teacher_id', $teacher->id)
            ->pluck('student_id')
            ->unique();

        $students = Student::query()
            ->select(['students.id', 'students.name', 'students.is_active', 'students.phone', 'students.email', 'students.address'])
            ->where(function($query) use ($classIds, $studentIdsFromSchedule) {
                $query->whereHas('classes', fn ($q) => $q->whereIn('classes.id', $classIds))
                      ->orWhereIn('students.id', $studentIdsFromSchedule);
            })
            ->with([
                'classes' => function ($query) use ($teacher) {
                    $query->select(['classes.id', 'classes.name'])
                        ->where(function($q) use ($teacher) {
                            $q->where('teacher_id', $teacher->id)
                              ->orWhereHas('scheduleSessions', fn($sq) => $sq->where('teacher_id', $teacher->id));
                        })
                        ->distinct();
                }
            ])
            ->orderBy('students.name')
            ->get();

        $selectedStudentId = $request->integer('student_id');
        $selectedStudent = null;

        if ($selectedStudentId) {
            $selectedStudent = $students->firstWhere('id', $selectedStudentId);

            if (! $selectedStudent) {
                abort(403, 'Siswa bukan milik teacher yang sedang login.');
            }
        }

        return view('portal.teacher.my-students', [
            'teacher' => $teacher,
            'students' => $students,
            'selectedStudent' => $selectedStudent,
        ]);
    }
}