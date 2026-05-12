<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
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

        // Hanya ambil kelas yang sudah di-Accept oleh guru ini
        $classIds = $teacher->classes()
            ->where('assignment_status', 'accepted')
            ->pluck('classes.id');
        
        $students = Student::query()
            ->select(['students.id', 'students.name', 'students.is_active', 'students.phone', 'students.email', 'students.address'])
            ->whereHas('classes', fn ($query) => $query->whereIn('classes.id', $classIds))
            ->with([
                'classes' => fn ($query) => $query
                    ->select(['classes.id', 'classes.name'])
                    ->whereIn('classes.id', $classIds)
                    ->orderBy('classes.name'),
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