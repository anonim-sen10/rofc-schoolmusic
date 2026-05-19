<?php

namespace App\Http\Controllers\SuperAdmin\Traits;

use App\Http\Requests\SuperAdmin\StoreStudentRequest;
use App\Models\Student;
use App\Models\MusicClass;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

trait ManagesStudents
{
    public function storeStudent(StoreStudentRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $student = Student::query()->create([
            'name' => $data['name'],
            'nama_panggilan' => $data['nama_panggilan'] ?? null,
            'jenis_kelamin' => $data['jenis_kelamin'] ?? null,
            'tempat_lahir' => $data['tempat_lahir'] ?? null,
            'tanggal_lahir' => $data['tanggal_lahir'] ?? null,
            'kewarganegaraan' => $data['kewarganegaraan'] ?? 'Indonesia',
            'age' => $data['age'] ?? null,
            'phone' => $data['phone'] ?? null,
            'email' => $data['email'] ?? null,
            'address' => $data['address'] ?? null,
            'nama_ortu' => $data['nama_ortu'] ?? null,
            'pekerjaan_ortu' => $data['pekerjaan_ortu'] ?? null,
            'no_hp_ortu' => $data['no_hp_ortu'] ?? null,
            'email_ortu' => $data['email_ortu'] ?? null,
            'is_active' => (bool) ($data['is_active'] ?? '1'),
            'program_tambahan' => $data['program_tambahan'] ?? [],
            'pengalaman' => (bool) ($data['pengalaman'] ?? false),
            'deskripsi_pengalaman' => $data['deskripsi_pengalaman'] ?? null,
            'favorite_song' => $data['favorite_song'] ?? null,
            'ig_siswa' => $data['ig_siswa'] ?? null,
            'ig_ortu' => $data['ig_ortu'] ?? null,
            'start_date' => $data['start_date'] ?? null,
            'duration_months' => $data['duration_months'] ?? null,
        ]);

        $student->class_id = $data['class_id'];
        $student->save();
        $student->classes()->sync([$data['class_id']]);

        // Link to schedule and mark as booked
        $schedule = \App\Models\Schedule::find($data['schedule_id']);
        if ($schedule) {
            $schedule->update([
                'student_id' => $student->id,
                'status' => 'booked'
            ]);
        }

        return back()->with('success', 'Siswa berhasil ditambahkan.');
    }

    public function updateStudent(Request $request, Student $student): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'nama_panggilan' => ['nullable', 'string', 'max:80'],
            'jenis_kelamin' => ['nullable', 'in:laki-laki,perempuan'],
            'tempat_lahir' => ['nullable', 'string', 'max:120'],
            'tanggal_lahir' => ['nullable', 'date'],
            'kewarganegaraan' => ['nullable', 'string', 'max:120'],
            'age' => ['nullable', 'integer', 'min:4', 'max:80'],
            'phone' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:120', 'unique:students,email,' . $student->id],
            'address' => ['nullable', 'string', 'max:500'],
            'nama_ortu' => ['nullable', 'string', 'max:120'],
            'pekerjaan_ortu' => ['nullable', 'string', 'max:120'],
            'no_hp_ortu' => ['nullable', 'string', 'max:30'],
            'email_ortu' => ['nullable', 'email', 'max:120'],
            'is_active' => ['required', 'in:1,0'],
            'class_ids' => ['nullable', 'array'],
            'class_ids.*' => ['integer', 'exists:classes,id'],
            'start_date' => ['nullable', 'date'],
            'duration_months' => ['nullable', 'integer', 'min:1', 'max:12'],
            'pengalaman' => ['nullable', 'boolean'],
            'deskripsi_pengalaman' => ['nullable', 'string', 'max:2000'],
            'favorite_song' => ['nullable', 'string', 'max:120'],
            'ig_siswa' => ['nullable', 'string', 'max:100'],
            'ig_ortu' => ['nullable', 'string', 'max:100'],
        ]);

        $payload = [
            'name' => $data['name'],
            'nama_panggilan' => $data['nama_panggilan'] ?? null,
            'jenis_kelamin' => $data['jenis_kelamin'] ?? null,
            'tempat_lahir' => $data['tempat_lahir'] ?? null,
            'tanggal_lahir' => $data['tanggal_lahir'] ?? null,
            'kewarganegaraan' => $data['kewarganegaraan'] ?? 'Indonesia',
            'age' => $data['age'] ?? null,
            'phone' => $data['phone'] ?? null,
            'email' => $data['email'] ?? null,
            'address' => $data['address'] ?? null,
            'nama_ortu' => $data['nama_ortu'] ?? null,
            'pekerjaan_ortu' => $data['pekerjaan_ortu'] ?? null,
            'no_hp_ortu' => $data['no_hp_ortu'] ?? null,
            'email_ortu' => $data['email_ortu'] ?? null,
            'is_active' => $request->boolean('is_active'),
            'start_date' => $data['start_date'] ?? null,
            'duration_months' => $data['duration_months'] ?? null,
            'pengalaman' => (bool) ($data['pengalaman'] ?? false),
            'deskripsi_pengalaman' => $data['deskripsi_pengalaman'] ?? null,
            'favorite_song' => $data['favorite_song'] ?? null,
            'ig_siswa' => $data['ig_siswa'] ?? null,
            'ig_ortu' => $data['ig_ortu'] ?? null,
        ];

        \Illuminate\Support\Facades\Log::info("Updating Student ID: {$student->id} is_active to: " . ($payload['is_active'] ? '1' : '0'));

        if (!empty($payload['start_date']) && !empty($payload['duration_months'])) {
            $payload['end_date'] = \Carbon\Carbon::parse($payload['start_date'])->addMonths((int)$payload['duration_months'])->toDateString();
        }

        if (!$payload['is_active']) {
            // Release schedules booked by this student
            \App\Models\Schedule::where('student_id', $student->id)->update([
                'student_id' => null,
                'status' => 'available'
            ]);
            $payload['schedule_id'] = null;
        }

        $student->update($payload);

        // Sync with Login Account (User) if exists
        if ($student->user) {
            $student->user->update([
                'name' => $data['name'],
                'email' => $data['email'] ?? $student->user->email,
                'is_active' => $payload['is_active'],
            ]);
        }

        $student->classes()->sync($data['class_ids'] ?? []);

        return back()->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function destroyStudent(Student $student): RedirectResponse
    {
        DB::transaction(function () use ($student) {
            // Release schedules booked by this student
            \App\Models\Schedule::where('student_id', $student->id)->update([
                'student_id' => null,
                'status' => 'available'
            ]);

            $user = $student->user;
            if ($user) {
                // Cascades will handle student and related data
                $user->delete();
            } else {
                $student->delete();
            }
        });

        return back()->with('success', 'Siswa dan data terkait berhasil dihapus.');
    }
}
