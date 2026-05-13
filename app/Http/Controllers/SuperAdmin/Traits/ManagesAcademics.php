<?php

namespace App\Http\Controllers\SuperAdmin\Traits;

use App\Models\MusicClass;
use App\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

trait ManagesAcademics
{
    public function storeClass(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'description' => ['nullable', 'string'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'schedule' => ['nullable', 'string', 'max:120'],
            'teacher_ids' => ['nullable', 'array'],
            'teacher_ids.*' => ['integer', 'exists:teachers,id'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        $class = MusicClass::query()->create([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'price' => $data['price'] ?? 0,
            'schedule' => $data['schedule'] ?? null,
            'teacher_id' => !empty($data['teacher_ids']) ? $data['teacher_ids'][0] : null,
            'status' => $data['status'],
        ]);

        if (!empty($data['teacher_ids'])) {
            $class->teachers()->sync($data['teacher_ids']);
        }

        return back()->with('success', 'Class berhasil ditambahkan.');
    }

    public function updateClass(Request $request, MusicClass $class): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'description' => ['nullable', 'string'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'schedule' => ['nullable', 'string', 'max:120'],
            'teacher_ids' => ['nullable', 'array'],
            'teacher_ids.*' => ['integer', 'exists:teachers,id'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        $class->update([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'price' => $data['price'] ?? 0,
            'schedule' => $data['schedule'] ?? null,
            'teacher_id' => !empty($data['teacher_ids']) ? $data['teacher_ids'][0] : null,
            'status' => $data['status'],
        ]);

        if (isset($data['teacher_ids'])) {
            $class->teachers()->sync($data['teacher_ids']);
        } else {
            $class->teachers()->detach();
        }

        return back()->with('success', 'Class berhasil diperbarui.');
    }

    public function destroyClass(MusicClass $class): RedirectResponse
    {
        $class->students()->detach();
        $class->delete();

        return back()->with('success', 'Class berhasil dihapus.');
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

        return back()->with('success', 'Teacher berhasil di-assign ke kelas ini.');
    }

    public function assignScheduleStudents(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'class_id' => ['required', 'integer', 'exists:classes,id'],
            'student_ids' => ['required', 'array'],
            'student_ids.*' => ['integer', 'exists:students,id'],
        ]);

        $class = MusicClass::findOrFail($data['class_id']);
        $class->students()->syncWithoutDetaching($data['student_ids']);

        return back()->with('success', count($data['student_ids']) . ' siswa berhasil di-assign ke kelas.');
    }

    public function unassignScheduleTeacher(MusicClass $class): RedirectResponse
    {
        $class->update([
            'teacher_id' => null,
            'assignment_status' => null,
            'assigned_at' => null,
            'responded_at' => null,
        ]);

        return back()->with('success', 'Teacher berhasil di-unassign dari kelas ini.');
    }

    public function removeScheduleStudent(MusicClass $class, Student $student): RedirectResponse
    {
        $class->students()->detach($student->id);

        return back()->with('success', 'Siswa berhasil dihapus dari kelas.');
    }
}
