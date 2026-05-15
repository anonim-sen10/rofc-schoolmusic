<?php

namespace App\Http\Controllers\SuperAdmin\Traits;

use App\Http\Requests\SuperAdmin\StoreRegistrationRequest;
use App\Models\Registration;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

trait ManagesRegistrations
{
    public function storeRegistration(StoreRegistrationRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $registration = Registration::query()->create(
            $this->buildRegistrationPayload($data)
        );

        $this->syncStudentFromRegistration($registration);

        return back()->with('success', 'Registrasi berhasil ditambahkan.');
    }

    public function updateRegistration(Request $request, Registration $registration): RedirectResponse
    {
        $data = $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:120'],
            'nama_panggilan' => ['nullable', 'string', 'max:100'],
            'jenis_kelamin' => ['nullable', 'in:laki-laki,perempuan'],
            'tempat_lahir' => ['nullable', 'string', 'max:120'],
            'tanggal_lahir' => ['nullable', 'date'],
            'kewarganegaraan' => ['nullable', 'string', 'max:100'],
            'alamat' => ['nullable', 'string', 'max:500'],
            'no_hp_siswa' => ['nullable', 'string', 'max:30'],
            'ig_siswa' => ['nullable', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:120'],
            'nama_ortu' => ['nullable', 'string', 'max:120'],
            'pekerjaan_ortu' => ['nullable', 'string', 'max:120'],
            'no_hp_ortu' => ['nullable', 'string', 'max:30'],
            'email_ortu' => ['nullable', 'email', 'max:120'],
            'ig_ortu' => ['nullable', 'string', 'max:100'],
            'status' => ['required', 'in:pending,accepted,rejected'],
            'favorite_song' => ['nullable', 'string', 'max:120'],
            'instrumen' => ['nullable', 'string', 'max:80'],
        ]);

        $registration->update($data);

        $this->syncStudentFromRegistration($registration);

        return back()->with('success', 'Registrasi berhasil diperbarui.');
    }

    public function destroyRegistration(Registration $registration): RedirectResponse
    {
        $registration->delete();

        return back()->with('success', 'Registrasi berhasil dihapus.');
    }

    private function buildRegistrationPayload(array $data): array
    {
        $payload = [
            'full_name' => $data['full_name'] ?? ($data['nama_lengkap'] ?? ''),
            'nama_panggilan' => $data['nama_panggilan'] ?? null,
            'jenis_kelamin' => $data['jenis_kelamin'] ?? null,
            'tempat_lahir' => $data['tempat_lahir'] ?? null,
            'tanggal_lahir' => $data['tanggal_lahir'] ?? null,
            'kewarganegaraan' => $data['kewarganegaraan'] ?? 'Indonesia',
            'email' => $data['email'],
            'phone' => $data['phone'] ?? ($data['no_hp_siswa'] ?? ''),
            'no_hp_siswa' => $data['no_hp_siswa'] ?? ($data['phone'] ?? ''),
            'ig_siswa' => $data['ig_siswa'] ?? null,
            'address' => $data['address'] ?? ($data['alamat'] ?? null),
            'alamat' => $data['alamat'] ?? ($data['address'] ?? null),
            'nama_ortu' => $data['nama_ortu'] ?? null,
            'pekerjaan_ortu' => $data['pekerjaan_ortu'] ?? null,
            'no_hp_ortu' => $data['no_hp_ortu'] ?? null,
            'email_ortu' => $data['email_ortu'] ?? null,
            'ig_ortu' => $data['ig_ortu'] ?? null,
            'instrumen' => $data['instrumen'] ?? null,
            'program_tambahan' => $data['program_tambahan'] ?? [],
            'pengalaman' => (bool) ($data['pengalaman'] ?? false),
            'deskripsi_pengalaman' => $data['deskripsi_pengalaman'] ?? null,
            'favorite_song' => $data['favorite_song'] ?? null,
            'status' => $data['status'] ?? 'pending',
        ];

        // Ensure we only return columns that exist in the database
        $existingColumns = Schema::getColumnListing('registrations');
        return array_intersect_key($payload, array_flip($existingColumns));
    }

    private function syncStudentFromRegistration(Registration $registration): void
    {
        if ($registration->status !== 'accepted') {
            return;
        }

        $student = Student::query()->updateOrCreate(
            ['email' => $registration->email],
            [
                'name' => $registration->full_name ?: $registration->nama_lengkap,
                'nama_panggilan' => $registration->nama_panggilan,
                'jenis_kelamin' => $registration->jenis_kelamin,
                'tempat_lahir' => $registration->tempat_lahir,
                'tanggal_lahir' => $registration->tanggal_lahir,
                'kewarganegaraan' => $registration->kewarganegaraan,
                'email' => $registration->email,
                'phone' => $registration->no_hp_siswa ?: $registration->phone,
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
            $student->classes()->syncWithoutDetaching([$registration->class_id]);
        }
    }
}
