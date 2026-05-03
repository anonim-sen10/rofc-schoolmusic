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

    public function getSchedulesByClass(int $class_id): JsonResponse
    {
        if (! Schema::hasTable('schedules')) {
            return response()->json(['grouped' => []]);
        }

        $schedules = Schedule::query()
            ->where('class_id', $class_id)
            ->orderByRaw("FIELD(day, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu')")
            ->orderBy('time')
            ->get(['id', 'day', 'time', 'status'])
            ->map(function ($s) {
                return [
                    'id' => $s->id,
                    'day' => $s->day,
                    'time' => substr((string) $s->time, 0, 5),
                    'status' => $s->status,
                ];
            });

        $grouped = $schedules->groupBy('day');

        return response()->json(['grouped' => $grouped]);
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
            'schedule_ids' => ['required', 'array', 'min:1'],
            'schedule_ids.*' => ['integer', 'exists:schedules,id'],
            'program_tambahan' => ['nullable', 'array'],
            'program_tambahan.*' => ['string', 'max:120'],

            'pengalaman' => ['required', 'boolean'],
            'deskripsi_pengalaman' => ['nullable', 'string', 'max:2000'],
            'start_date' => ['required', 'date', 'after_or_equal:today'],
            'duration_months' => ['required', 'integer', 'in:1,2,3,6,12'],
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

        $schedules = Schedule::query()
            ->whereIn('id', $validated['schedule_ids'])
            ->where('class_id', (int) $validated['class_id'])
            ->where('status', 'available')
            ->get(['id', 'day', 'time']);

        if ($schedules->count() !== count($validated['schedule_ids'])) {
            return back()
                ->withErrors(['schedule_ids' => 'Salah satu atau lebih slot jadwal sudah dibooking user lain. Silakan pilih slot lain.'])
                ->withInput();
        }

        $instrumentName = (string) $selectedClass->name;
        $scheduleTexts = $schedules->map(fn($s) => $s->day.' - '.substr((string) $s->time, 0, 5))->toArray();
        $scheduleText = implode(', ', $scheduleTexts);

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
            'schedule_id' => $validated['schedule_ids'][0] ?? null,
            'instrumen' => $instrumentName,
            'program_tambahan' => $validated['program_tambahan'] ?? [],
            'hari_pilihan' => $schedules->pluck('day')->unique()->values()->toArray(),

            'pengalaman' => (bool) $validated['pengalaman'],
            'deskripsi_pengalaman' => $validated['deskripsi_pengalaman'] ?? null,
            'start_date' => $validated['start_date'],
            'duration_months' => $validated['duration_months'],

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

        $registration = Registration::create($filteredPayload);
        
        if (method_exists($registration, 'schedules')) {
            $registration->schedules()->sync($validated['schedule_ids']);
        }

        return back()->with('success', 'Pendaftaran Anda berhasil dikirim. Kami akan menghubungi Anda untuk proses berikutnya.');
    }

    public function approve(int $id): RedirectResponse
    {
        try {
            DB::transaction(function () use ($id): void {
                $registration = Registration::query()
                    ->with(['schedules', 'class'])
                    ->lockForUpdate()
                    ->findOrFail($id);

                // 1. Validation Checks
                if (strtolower((string) $registration->status) === 'accepted') {
                    throw ValidationException::withMessages([
                        'registration' => 'Registrasi ini sudah berstatus accepted.',
                    ]);
                }

                $email = strtolower(trim((string) $registration->email));
                if ($email === '') {
                    throw ValidationException::withMessages(['email' => 'Email pendaftaran tidak valid.']);
                }

                if (User::query()->where('email', $email)->exists()) {
                    throw ValidationException::withMessages(['email' => 'Email sudah terdaftar pada akun lain.']);
                }

                // 2. Collect Schedules
                $requestedSchedules = collect();
                
                // Try pivot table first
                if ($registration->schedules->isNotEmpty()) {
                    $requestedSchedules = $registration->schedules()->lockForUpdate()->get();
                } 
                // Fallback to legacy single schedule_id column
                elseif ($registration->schedule_id) {
                    $sch = Schedule::query()->lockForUpdate()->find($registration->schedule_id);
                    if ($sch) {
                        $requestedSchedules->push($sch);
                    }
                }

                if ($requestedSchedules->isEmpty()) {
                    throw ValidationException::withMessages([
                        'schedules' => 'Pendaftaran ini tidak memiliki jadwal terpilih. Silakan edit dan pilih minimal 1 jadwal.'
                    ]);
                }

                // 3. Availability Check (CRITICAL)
                foreach ($requestedSchedules as $sch) {
                    if (strtolower((string) $sch->status) !== 'available') {
                        throw ValidationException::withMessages([
                            'schedule_id' => "Slot jadwal {$sch->day} ".substr((string)$sch->time, 0, 5)." sudah tidak tersedia (sudah dibooking). Silakan ganti jadwal terlebih dahulu."
                        ]);
                    }
                }

                // 4. Create User Account
                $studentName = trim((string) ($registration->nama_lengkap ?: $registration->full_name)) ?: 'Siswa ROFC';
                $user = User::query()->create([
                    'name' => $studentName,
                    'email' => $email,
                    'password' => Hash::make('123456'), // Default password
                ]);

                // Assign Student Role
                $studentRole = Role::query()->firstOrCreate(
                    ['slug' => 'student'],
                    ['name' => 'Student', 'description' => 'Portal siswa.']
                );
                $user->roles()->syncWithoutDetaching([$studentRole->id]);
                if (Schema::hasColumn('users', 'role')) {
                    $user->update(['role' => 'student']);
                }

                // 5. Create Student Profile
                $phone = trim((string) ($registration->no_hp_siswa ?: $registration->phone));
                $studentAge = $registration->tanggal_lahir 
                    ? Carbon::parse($registration->tanggal_lahir)->age 
                    : (is_numeric($registration->age) ? (int)$registration->age : null);

                $startDate = $registration->start_date ?? now()->toDateString();
                $duration = $registration->duration_months ?? 1;
                $endDate = Carbon::parse($startDate)->addMonths($duration)->toDateString();

                $student = Student::query()->create([
                    'user_id' => $user->id,
                    'name' => $studentName,
                    'age' => $studentAge,
                    'phone' => $phone ?: null,
                    'email' => $email,
                    'address' => $registration->alamat ?? null,
                    'is_active' => true,
                    'class_id' => $registration->class_id ?: $requestedSchedules->first()->class_id,
                    'start_date' => $startDate,
                    'duration_months' => $duration,
                    'end_date' => $endDate,
                ]);

                // 6. Assign Schedules & Update Status
                foreach ($requestedSchedules as $sch) {
                    $sch->update([
                        'student_id' => $student->id,
                        'status' => 'booked',
                    ]);

                    // GENERATE SESSIONS for next 4 weeks
                    $this->generateInitialSessions($student, $sch);
                }

                // 7. Finalize Registration
                $registration->update(['status' => 'accepted']);
            });

            return back()->with('success', 'Pendaftaran berhasil disetujui. Akun siswa telah dibuat dan jadwal telah di-book.');
        } catch (ValidationException $exception) {
            return back()->withErrors($exception->errors())->with('error', $exception->getMessage());
        } catch (\Throwable $exception) {
            report($exception);
            return back()->with('error', 'Terjadi kesalahan sistem saat menyetujui pendaftaran: ' . $exception->getMessage());
        }
    }

    private function generateInitialSessions(Student $student, Schedule $schedule): void
    {
        $dayName = $schedule->day; 
        $engDay = $this->mapDayToEnglish($dayName);
        
        $currentDate = Carbon::parse($student->start_date);
        $endDate = Carbon::parse($student->end_date);
        
        // Find first occurrence of $engDay on or after $startDate
        if (strtolower($currentDate->format('l')) !== strtolower($engDay)) {
            $currentDate->modify("next $engDay");
        }

        while ($currentDate->lte($endDate)) {
            \App\Models\ScheduleSession::create([
                'schedule_id' => $schedule->id,
                'student_id' => $student->id,
                'teacher_id' => $schedule->teacher_id,
                'class_id' => $schedule->class_id,
                'session_date' => $currentDate->toDateString(),
                'time' => $schedule->time,
                'status' => 'booked',
            ]);
            
            $currentDate->addWeek();
        }
    }

    private function mapDayToEnglish(string $day): string
    {
        $map = [
            'senin' => 'monday',
            'selasa' => 'tuesday',
            'rabu' => 'wednesday',
            'kamis' => 'thursday',
            'jumat' => 'friday',
            'sabtu' => 'saturday',
            'minggu' => 'sunday',
        ];
        
        $lower = strtolower($day);
        return $map[$lower] ?? $lower;
    }
}
