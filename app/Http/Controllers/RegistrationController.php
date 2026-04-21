<?php

namespace App\Http\Controllers;

use App\Models\MusicClass;
use App\Models\Role;
use App\Models\Registration;
use App\Models\Student;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class RegistrationController extends Controller
{
    public function create(): View
    {
        $classes = MusicClass::query()
            ->where('status', 'active')
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('pages.register', compact('classes'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:120'],
            'nama_panggilan' => ['required', 'string', 'max:80'],
            'jenis_kelamin' => ['required', 'in:laki-laki,perempuan'],
            'tempat_lahir' => ['required', 'string', 'max:120'],
            'tanggal_lahir' => ['required', 'date', 'before_or_equal:today'],
            'kewarganegaraan' => ['required', 'string', 'max:120'],
            'alamat' => ['required', 'string', 'max:2000'],
            'no_hp_siswa' => ['required', 'string', 'max:30'],
            'email' => ['required', 'email', 'max:120'],

            'nama_ortu' => ['required', 'string', 'max:120'],
            'pekerjaan_ortu' => ['nullable', 'string', 'max:120'],
            'no_hp_ortu' => ['required', 'string', 'max:30'],
            'email_ortu' => ['nullable', 'email', 'max:120'],

            'class_id' => [
                'required',
                Rule::exists('classes', 'id')->where(fn ($query) => $query->where('status', 'active')),
            ],
            'program_tambahan' => ['nullable', 'array'],
            'program_tambahan.*' => ['string', 'max:120'],

            'hari_pilihan' => ['required', 'array', 'min:1'],
            'hari_pilihan.*' => ['string', 'max:40'],

            'pengalaman' => ['required', 'boolean'],
            'deskripsi_pengalaman' => ['nullable', 'string', 'max:2000'],
        ]);

        $selectedClass = MusicClass::query()
            ->whereKey($validated['class_id'])
            ->where('status', 'active')
            ->first(['id', 'name']);

        if (! $selectedClass) {
            return back()
                ->withErrors(['class_id' => 'Instrumen tidak valid atau sudah tidak aktif.'])
                ->withInput();
        }

        $instrumentName = (string) $selectedClass->name;

        $tanggalLahir = Carbon::parse($validated['tanggal_lahir']);

        $payload = [
            'nama_lengkap' => $validated['nama_lengkap'],
            'nama_panggilan' => $validated['nama_panggilan'],
            'jenis_kelamin' => $validated['jenis_kelamin'],
            'tempat_lahir' => $validated['tempat_lahir'],
            'tanggal_lahir' => $validated['tanggal_lahir'],
            'kewarganegaraan' => $validated['kewarganegaraan'],
            'alamat' => $validated['alamat'],
            'no_hp_siswa' => $validated['no_hp_siswa'],
            'email' => $validated['email'],

            'nama_ortu' => $validated['nama_ortu'],
            'pekerjaan_ortu' => $validated['pekerjaan_ortu'] ?? null,
            'no_hp_ortu' => $validated['no_hp_ortu'],
            'email_ortu' => $validated['email_ortu'] ?? null,

            'class_id' => $validated['class_id'],
            'instrumen' => $instrumentName,
            'program_tambahan' => $validated['program_tambahan'] ?? [],
            'hari_pilihan' => $validated['hari_pilihan'],

            'pengalaman' => (bool) $validated['pengalaman'],
            'deskripsi_pengalaman' => $validated['deskripsi_pengalaman'] ?? null,

            // Backward compatibility for existing admin modules using legacy columns.
            'full_name' => $validated['nama_lengkap'],
            'age' => $tanggalLahir->age,
            'phone' => $validated['no_hp_siswa'],
            'preferred_schedule' => implode(', ', $validated['hari_pilihan']),
            'notes' => $validated['deskripsi_pengalaman'] ?? null,
            'status' => 'pending',
        ];

        $existingColumns = Schema::getColumnListing('registrations');
        $filteredPayload = array_intersect_key($payload, array_flip($existingColumns));

        // If new columns are not migrated yet, preserve extra details in legacy notes.
        if (! in_array('nama_lengkap', $existingColumns, true)) {
            $extraNotes = [
                'Nama Panggilan: '.$validated['nama_panggilan'],
                'Jenis Kelamin: '.$validated['jenis_kelamin'],
                'Tempat/Tanggal Lahir: '.$validated['tempat_lahir'].', '.$validated['tanggal_lahir'],
                'Kewarganegaraan: '.$validated['kewarganegaraan'],
                'Alamat: '.$validated['alamat'],
                'Nama Ortu: '.$validated['nama_ortu'],
                'Pekerjaan Ortu: '.($validated['pekerjaan_ortu'] ?? '-'),
                'No HP Ortu: '.$validated['no_hp_ortu'],
                'Email Ortu: '.($validated['email_ortu'] ?? '-'),
                'Instrumen: '.$instrumentName,
                'Program Tambahan: '.implode(', ', $validated['program_tambahan'] ?? []),
                'Hari Pilihan: '.implode(', ', $validated['hari_pilihan']),
                'Pengalaman: '.((bool) $validated['pengalaman'] ? 'Ya' : 'Tidak'),
            ];

            if (! empty($validated['deskripsi_pengalaman'])) {
                $extraNotes[] = 'Deskripsi Pengalaman: '.$validated['deskripsi_pengalaman'];
            }

            $legacyNotes = trim(implode("\n", $extraNotes));
            $baseNotes = (string) ($filteredPayload['notes'] ?? '');
            $filteredPayload['notes'] = trim($baseNotes.($baseNotes !== '' ? "\n\n" : '').$legacyNotes);
        }

        Registration::create($filteredPayload);

        return back()->with('success', 'Pendaftaran Anda berhasil dikirim. Kami akan menghubungi Anda untuk proses berikutnya.');
    }

    public function approve(int $id): RedirectResponse
    {
        $registration = Registration::query()->findOrFail($id);

        if (strtolower((string) $registration->status) === 'accepted') {
            return back()->with('error', 'Registrasi ini sudah berstatus accepted.');
        }

        $email = strtolower(trim((string) $registration->email));

        if ($email === '') {
            return back()->with('error', 'Email pada registrasi tidak valid.');
        }

        if (User::query()->where('email', $email)->exists()) {
            return back()->with('error', 'Email sudah terdaftar pada akun user.');
        }

        try {
            DB::transaction(function () use ($registration, $email): void {
                $studentName = trim((string) ($registration->nama_lengkap ?: $registration->full_name));
                if ($studentName === '') {
                    $studentName = 'Siswa ROFC';
                }

                $phone = trim((string) ($registration->no_hp_siswa ?: $registration->phone));

                $studentAge = null;
                if (is_numeric($registration->age) && (int) $registration->age > 0) {
                    $studentAge = (int) $registration->age;
                } elseif ($registration->tanggal_lahir) {
                    $studentAge = Carbon::parse($registration->tanggal_lahir)->age;
                }

                $user = User::query()->create([
                    'name' => $studentName,
                    'email' => $email,
                    'password' => Hash::make('123456'),
                ]);

                // Backward compatibility if users table still has direct role column.
                if (Schema::hasColumn('users', 'role')) {
                    DB::table('users')->where('id', $user->id)->update(['role' => 'student']);
                }

                $studentRole = Role::query()->firstOrCreate(
                    ['slug' => 'student'],
                    ['name' => 'Student', 'description' => 'Portal siswa.']
                );
                $user->roles()->syncWithoutDetaching([$studentRole->id]);

                $student = Student::query()->create([
                    'user_id' => $user->id,
                    'name' => $studentName,
                    'age' => $studentAge,
                    'phone' => $phone !== '' ? $phone : null,
                    'email' => $email,
                    'is_active' => true,
                ]);

                // Backward compatibility if students table stores class/no_hp directly.
                if ($registration->class_id && Schema::hasColumn('students', 'class_id')) {
                    DB::table('students')->where('id', $student->id)->update(['class_id' => $registration->class_id]);
                }

                if ($phone !== '' && Schema::hasColumn('students', 'no_hp')) {
                    DB::table('students')->where('id', $student->id)->update(['no_hp' => $phone]);
                }

                if ($registration->class_id && method_exists($student, 'classes')) {
                    $student->classes()->syncWithoutDetaching([$registration->class_id]);
                }

                $registration->update(['status' => 'accepted']);
            });
        } catch (\Throwable $exception) {
            report($exception);

            return back()->with('error', 'Proses approve gagal. Silakan coba lagi.');
        }

        return back()->with('success', 'Registrasi berhasil di-approve. Akun user dan data siswa sudah dibuat.');
    }
}
