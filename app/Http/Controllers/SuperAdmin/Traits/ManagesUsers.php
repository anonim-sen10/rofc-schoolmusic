<?php

namespace App\Http\Controllers\SuperAdmin\Traits;

use App\Models\Role;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

trait ManagesUsers
{
    public function storeUser(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:120', 'unique:users,email'],
            'role' => ['required', 'in:super_admin,admin,finance,teacher,student'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'instrument' => ['nullable', 'string', 'max:80'],
            'phone' => ['nullable', 'string', 'max:30'],
        ]);

        $roleSlug = $data['role'];
        $role = $this->resolveCoreRole($roleSlug);

        $user = User::query()->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $user->roles()->syncWithoutDetaching([$role->id]);

        if ($roleSlug === 'teacher') {
            Teacher::query()->firstOrCreate(
                ['user_id' => $user->id],
                [
                    'name' => $user->name,
                    'instrument' => $data['instrument'] ?? 'General',
                    'is_active' => true,
                ]
            );
        }

        if ($roleSlug === 'student') {
            Student::query()->firstOrCreate(
                ['user_id' => $user->id],
                [
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $data['phone'] ?? null,
                    'is_active' => true,
                ]
            );
        }

        return back()->with('success', 'Akun login berhasil dibuat.');
    }

    public function showUser(User $user): View
    {
        $user->load('roles');

        return view('portal.super-admin.user-detail', [
            'roleKey' => 'super_admin',
            'portal' => $this->portalConfig(),
            'user' => $user,
        ]);
    }

    public function editUser(User $user): View
    {
        $user->load('roles');

        return view('portal.super-admin.user-edit', [
            'roleKey' => 'super_admin',
            'portal' => $this->portalConfig(),
            'user' => $user,
            'availableRoles' => Role::all(),
        ]);
    }

    public function updateUser(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:120', 'unique:users,email,' . $user->id],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'string', 'exists:roles,slug'],
            'phone' => ['nullable', 'string', 'max:30'],
        ]);

        $user->update([
            'name' => $data['name'],
            'email' => $data['email'],
        ]);

        if (!empty($data['password'])) {
            $user->update(['password' => Hash::make($data['password'])]);
        }

        $role = Role::where('slug', $data['role'])->first();
        if ($role) {
            $user->roles()->sync([$role->id]);
        }

        // Update associated profiles if needed
        if ($data['role'] === 'student') {
            $student = Student::query()->firstOrCreate(['user_id' => $user->id], [
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $data['phone'] ?? null,
                'is_active' => true,
            ]);

            $student->update([
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $data['phone'] ?? $student->phone,
            ]);
        }

        return redirect()->route('super-admin.module', ['module' => 'roles'])->with('success', 'Data user berhasil diperbarui.');
    }

    public function destroyUser(Request $request, User $user): RedirectResponse
    {
        if ((int) $request->user()->id === (int) $user->id) {
            return back()->withErrors([
                'user' => 'Akun yang sedang dipakai tidak bisa dihapus.',
            ]);
        }

        DB::transaction(function () use ($user) {
            Student::where('user_id', $user->id)->delete();
            Teacher::where('user_id', $user->id)->delete();
            $user->delete();
        });

        return back()->with('success', 'User dan seluruh data terkait berhasil dihapus.');
    }

    public function storeRole(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'slug' => ['nullable', 'string', 'max:120', 'regex:/^[a-z0-9_-]+$/', 'unique:roles,slug'],
            'description' => ['nullable', 'string', 'max:500'],
        ]);

        $slug = $data['slug']
            ? Str::of($data['slug'])->trim()->lower()->replace('-', '_')->replace(' ', '_')->toString()
            : Str::of($data['name'])->trim()->lower()->replace('-', '_')->replace(' ', '_')->toString();

        if (Role::query()->where('slug', $slug)->exists()) {
            return back()->withErrors([
                'slug' => 'Slug role sudah digunakan. Gunakan slug lain.',
            ])->withInput();
        }

        Role::query()->create([
            'name' => $data['name'],
            'slug' => $slug,
            'description' => $data['description'] ?? null,
        ]);

        return back()->with('success', 'Role baru berhasil dibuat.');
    }
}
