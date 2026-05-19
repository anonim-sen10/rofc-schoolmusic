<?php

namespace App\Http\Controllers\SuperAdmin\Traits;

use App\Http\Requests\SuperAdmin\StoreTeacherRequest;
use App\Http\Requests\SuperAdmin\UpdateTeacherRequest;
use App\Models\Teacher;
use App\Models\User;
use App\Models\MusicClass;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

trait ManagesTeachers
{
    public function storeTeacherAccount(StoreTeacherRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $role = $this->resolveCoreRole('teacher');

        $user = User::query()->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $user->roles()->sync([$role->id]);

        $payload = [
            'user_id' => $user->id,
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

        if ($request->hasFile('ktp')) {
            $payload['ktp_path'] = $request->file('ktp')->store('teachers/ktp', 'public');
        }

        $teacher = Teacher::query()->create($payload);

        if (!empty($data['class_ids'])) {
            $teacher->musicClasses()->sync($data['class_ids']);
            MusicClass::query()->whereIn('id', $data['class_ids'])->update(['teacher_id' => $teacher->id]);
        }

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
        $teacher->load('user', 'classes', 'musicClasses');

        return view('portal.super-admin.teacher-edit', [
            'roleKey' => 'super_admin',
            'portal' => $this->portalConfig(),
            'teacher' => $teacher,
            'classesForTeachers' => MusicClass::query()->orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function updateTeacher(UpdateTeacherRequest $request, Teacher $teacher): RedirectResponse
    {
        $data = $request->validated();

        if ($teacher->user) {
            $teacher->user->name = $data['name'];
            $teacher->user->email = $data['email'];

            if (!empty($data['password'])) {
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

        if ($request->hasFile('ktp')) {
            $payload['ktp_path'] = $request->file('ktp')->store('teachers/ktp', 'public');
        }

        $teacher->update($payload);

        $classIds = $data['class_ids'] ?? [];
        $teacher->musicClasses()->sync($classIds);

        // Sync old one-to-many column: clear for old classes, set for new classes
        MusicClass::query()->where('teacher_id', $teacher->id)
            ->whereNotIn('id', $classIds)
            ->update(['teacher_id' => null]);

        if (!empty($classIds)) {
            MusicClass::query()->whereIn('id', $classIds)->update(['teacher_id' => $teacher->id]);
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
}
