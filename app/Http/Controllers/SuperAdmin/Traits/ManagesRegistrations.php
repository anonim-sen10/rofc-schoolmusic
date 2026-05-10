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
        // For update, we can still use manual validation or create UpdateRegistrationRequest
        $data = $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:120'],
            'status' => ['required', 'in:pending,accepted,rejected'],
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
            'email' => $data['email'],
            'phone' => $data['phone'] ?? '',
            'address' => $data['address'] ?? null,
            'instrumen' => $data['instrumen'] ?? null,
            'program_tambahan' => $data['program_tambahan'] ?? [],
            'pengalaman' => (bool) ($data['pengalaman'] ?? false),
            'deskripsi_pengalaman' => $data['deskripsi_pengalaman'] ?? null,
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
                'name' => $registration->full_name,
                'email' => $registration->email,
                'phone' => $registration->phone,
                'address' => $registration->address,
                'is_active' => true,
            ]
        );

        if ($registration->class_id) {
            $student->classes()->syncWithoutDetaching([$registration->class_id]);
        }
    }
}
