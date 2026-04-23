<?php

namespace App\Http\Controllers;

use App\Models\MusicClass;
use App\Models\Role;
use App\Models\Registration;
use App\Models\Schedule;
use App\Models\Student;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegistrationController extends Controller
{
    private const DAY_OPTIONS = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];

    public function create(): View
    {
        $classes = MusicClass::query()
            ->where('status', 'active')
            ->orderBy('name')
            ->get(['id', 'name']);

        $dayOptions = self::DAY_OPTIONS;

        return view('pages.register', compact('classes', 'dayOptions'));
    }

    public function getAvailableSchedules(int $class_id, string $day): JsonResponse
    {
        if (! Schema::hasTable('schedules') || ! Schema::hasColumn('schedules', 'status')) {
            return response()->json(['schedules' => []]);
        }

        $normalizedDay = urldecode($day);

        if (! in_array($normalizedDay, self::DAY_OPTIONS, true)) {
            return response()->json(['schedules' => []]);
        }

        $query = Schedule::query()
            ->where('class_id', $class_id)
            ->where('day', $normalizedDay)
            ->where('status', 'available')
            ->orderBy('time');

        $schedules = $query->get(['id', 'day', 'time'])
            ->map(function (Schedule $schedule): array {
                $time = substr((string) $schedule->time, 0, 5);

                return [
                    'id' => $schedule->id,
                    'day' => $schedule->day,
                    'time' => $time,
                    'label' => $schedule->day.' - '.$time,
                ];
            })
            ->values();

        return response()->json(['schedules' => $schedules]);
    }

    public function store(Request $request): RedirectResponse
    {
        if (! Schema::hasTable('schedules') || ! Schema::hasColumn('schedules', 'status')) {
            return back()
                ->withErrors(['schedule_id' => 'Fitur booking slot belum siap. Jalankan migrasi schedules terlebih dahulu.'])
                ->withInput();
        }

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
            'day' => ['required', 'string', Rule::in(self::DAY_OPTIONS)],
            'schedule_id' => ['required', 'integer', 'exists:schedules,id'],
            'program_tambahan' => ['nullable', 'array'],
            'program_tambahan.*' => ['string', 'max:120'],

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

        $scheduleQuery = Schedule::query()
            ->whereKey((int) $validated['schedule_id'])
            ->where('class_id', (int) $validated['class_id'])
            ->where('day', $validated['day'])
            ->where('status', 'available');

        $selectedSchedule = $scheduleQuery->first(['id', 'day', 'time']);

        if (! $selectedSchedule) {
            return back()
                ->withErrors(['schedule_id' => 'Slot jadwal sudah dibooking user lain. Silakan pilih slot lain.'])
                ->withInput();
        }

        $instrumentName = (string) $selectedClass->name;
        $scheduleText = $selectedSchedule->day.' - '.substr((string) $selectedSchedule->time, 0, 5);

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
            'schedule_id' => $validated['schedule_id'],
            'instrumen' => $instrumentName,
            'program_tambahan' => $validated['program_tambahan'] ?? [],
            'hari_pilihan' => [$validated['day']],

            'pengalaman' => (bool) $validated['pengalaman'],
            'deskripsi_pengalaman' => $validated['deskripsi_pengalaman'] ?? null,

            // Backward compatibility for existing admin modules using legacy columns.
            'full_name' => $validated['nama_lengkap'],
            'age' => $tanggalLahir->age,
            'phone' => $validated['no_hp_siswa'],
            'preferred_schedule' => $scheduleText,
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
                'Hari Pilihan: '.$validated['day'],
                'Jadwal Terpilih: '.$scheduleText,
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
        try {
            DB::transaction(function () use ($id): void {
                $registration = Registration::query()->lockForUpdate()->findOrFail($id);

                if (strtolower((string) $registration->status) === 'accepted') {
                    throw ValidationException::withMessages([
                        'registration' => 'Registrasi ini sudah berstatus accepted.',
                    ]);
                }

                $email = strtolower(trim((string) $registration->email));

                if ($email === '') {
                    throw ValidationException::withMessages([
                        'email' => 'Email pada registrasi tidak valid.',
                    ]);
                }

                if (User::query()->where('email', $email)->exists()) {
                    throw ValidationException::withMessages([
                        'email' => 'Email sudah terdaftar pada akun user.',
                    ]);
                }

                $assignedSchedule = null;
                if (Schema::hasTable('schedules') && Schema::hasColumn('registrations', 'schedule_id') && $registration->schedule_id) {
                    if (! Schema::hasColumn('schedules', 'status')) {
                        throw ValidationException::withMessages([
                            'schedule_id' => 'Kolom status pada schedule belum tersedia. Jalankan migrasi terlebih dahulu.',
                        ]);
                    }

                    $scheduleQuery = Schedule::query()
                        ->lockForUpdate()
                        ->whereKey((int) $registration->schedule_id)
                        ->where('status', 'available');

                    $assignedSchedule = $scheduleQuery->first();

                    if (! $assignedSchedule) {
                        throw ValidationException::withMessages([
                            'schedule_id' => 'Slot jadwal sudah diambil user lain. Silakan pilih slot lain.',
                        ]);
                    }
                }

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

                $studentPayload = [
                    'user_id' => $user->id,
                    'name' => $studentName,
                    'age' => $studentAge,
                    'phone' => $phone !== '' ? $phone : null,
                    'email' => $email,
                    'is_active' => true,
                ];

                if ($registration->class_id && Schema::hasColumn('students', 'class_id')) {
                    $studentPayload['class_id'] = $registration->class_id;
                }

                if (Schema::hasColumn('students', 'schedule_id') && $registration->schedule_id) {
                    $studentPayload['schedule_id'] = $registration->schedule_id;
                }

                $student = Student::query()->create($studentPayload);

                // Backward compatibility if students table stores class/no_hp directly.
                if ($registration->class_id && Schema::hasColumn('students', 'class_id')) {
                    DB::table('students')->where('id', $student->id)->update(['class_id' => $registration->class_id]);
                }

                if ($registration->schedule_id && Schema::hasColumn('students', 'schedule_id')) {
                    DB::table('students')->where('id', $student->id)->update(['schedule_id' => $registration->schedule_id]);
                }

                if ($phone !== '' && Schema::hasColumn('students', 'no_hp')) {
                    DB::table('students')->where('id', $student->id)->update(['no_hp' => $phone]);
                }

                if ($registration->class_id && method_exists($student, 'classes')) {
                    $student->classes()->syncWithoutDetaching([$registration->class_id]);
                }

                $registration->update(['status' => 'accepted']);

                if ($assignedSchedule) {
                    $assignedSchedule->update(['status' => 'booked']);
                }
            });
        } catch (ValidationException $exception) {
            $errors = $exception->errors();
            $message = collect($errors)->flatten()->first() ?: 'Validasi approve gagal.';

            return back()->withErrors($errors)->with('error', $message);
        } catch (\Throwable $exception) {
            report($exception);

            return back()->with('error', 'Proses approve gagal. Silakan coba lagi.');
        }

        return back()->with('success', 'Registrasi berhasil di-approve. Akun user dan data siswa sudah dibuat.');
    }
}
