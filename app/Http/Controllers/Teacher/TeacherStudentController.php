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

        // 1. Ambil ID Kelas dari tabel Classes dan pivot table class_teacher
        $classIds = $teacher->musicClasses()->pluck('classes.id')
            ->merge($teacher->classes()->pluck('id'))
            ->unique();
        
        $teacherId = $teacher->id;

        $students = Student::query()
            ->select(['students.id', 'students.name', 'students.is_active', 'students.phone', 'students.email', 'students.address'])
            ->where(function($query) use ($classIds, $teacherId) {
                // 1. Siswa yang punya jadwal eksplisit dengan guru ini
                $query->whereHas('schedules', fn($q) => $q->where('teacher_id', $teacherId))
                      ->orWhereHas('scheduleSessions', fn($q) => $q->where(fn($sq) => $sq->where('teacher_id', $teacherId)->orWhere('substitute_teacher_id', $teacherId)))
                      // 2. ATAU siswa di kelas guru ini, TAPI tidak punya jadwal dengan guru LAIN di kelas tersebut
                      ->orWhereHas('classes', function ($q) use ($classIds, $teacherId) {
                          $q->whereIn('classes.id', $classIds)
                            ->whereNotExists(function ($sub) use ($teacherId) {
                                $sub->select(\Illuminate\Support\Facades\DB::raw(1))
                                    ->from('schedules')
                                    ->whereColumn('schedules.student_id', 'class_students.student_id')
                                    ->whereColumn('schedules.class_id', 'class_students.class_id')
                                    ->where('schedules.teacher_id', '!=', $teacherId);
                            });
                      });
            })
            ->with([
                'classes' => fn($q) => $q->select(['classes.id', 'classes.name'])
                    ->where(fn($sq) => $sq->where('classes.teacher_id', $teacher->id)
                        ->orWhereHas('teachers', fn($t) => $t->where('teachers.id', $teacher->id))
                    ),
                'scheduleSessions' => fn($q) => $q->where(fn($sq) => $sq->where('teacher_id', $teacher->id)->orWhere('substitute_teacher_id', $teacher->id))->with('musicClass:id,name')
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