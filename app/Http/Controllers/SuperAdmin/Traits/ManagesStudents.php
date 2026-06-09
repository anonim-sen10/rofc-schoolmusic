<?php

namespace App\Http\Controllers\SuperAdmin\Traits;

use App\Http\Requests\SuperAdmin\StoreStudentRequest;
use App\Models\Schedule;
use App\Models\ScheduleSession;
use App\Models\Student;
use Carbon\Carbon;
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
        $this->syncStudentScheduleSlots($student, [$data['schedule_id']]);

        return back()->with('success', 'Siswa berhasil ditambahkan.');
    }

    public function updateStudent(Request $request, Student $student): RedirectResponse
    {
        \Illuminate\Support\Facades\Log::info("Request Data to update student ID {$student->id}:", $request->all());

        try {
            $data = $request->validate([
                'name' => ['required', 'string', 'max:120'],
                'nama_panggilan' => ['nullable', 'string', 'max:80'],
                'jenis_kelamin' => ['nullable', 'in:laki-laki,perempuan'],
                'tempat_lahir' => ['nullable', 'string', 'max:120'],
                'tanggal_lahir' => ['nullable', 'date'],
                'kewarganegaraan' => ['nullable', 'string', 'max:120'],
                'age' => ['nullable', 'integer', 'min:0', 'max:120'],
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
                'schedule_sync' => ['nullable', 'boolean'],
                'schedule_ids' => ['nullable', 'array'],
                'schedule_ids.*' => ['integer', 'exists:schedules,id'],
                'pengalaman' => ['nullable', 'boolean'],
                'deskripsi_pengalaman' => ['nullable', 'string', 'max:2000'],
                'favorite_song' => ['nullable', 'string', 'max:120'],
                'ig_siswa' => ['nullable', 'string', 'max:100'],
                'ig_ortu' => ['nullable', 'string', 'max:100'],
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Illuminate\Support\Facades\Log::error("Validation failed for student update ID {$student->id}:", $e->errors());
            throw $e;
        }

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
            
            // Delete future schedule sessions that haven't happened yet
            \App\Models\ScheduleSession::query()
                ->where('student_id', $student->id)
                ->where('status', 'booked')
                ->where('session_date', '>=', now()->toDateString())
                ->delete();

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

        if ($request->boolean('schedule_sync')) {
            $force = $request->boolean('force_generate_sessions');
            $this->syncStudentScheduleSlots($student->fresh(), $data['schedule_ids'] ?? [], $force);
        }

        return back()->with('success', 'Data siswa berhasil diperbarui.');
    }

    private function syncStudentScheduleSlots(Student $student, array $scheduleIds, bool $force = false): void
    {
        $scheduleIds = collect($scheduleIds)
            ->map(fn ($id) => (int) $id)
            ->filter()
            ->unique()
            ->values()
            ->all();

        DB::transaction(function () use ($student, $scheduleIds): void {
            $selectedSchedules = Schedule::query()
                ->whereIn('id', $scheduleIds)
                ->lockForUpdate()
                ->get();

            if ($selectedSchedules->count() !== count($scheduleIds)) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'schedule_ids' => 'Ada jadwal yang tidak valid. Silakan pilih ulang jadwal siswa.',
                ]);
            }

            $blockedSchedule = $selectedSchedules->first(function (Schedule $schedule) use ($student) {
                return $schedule->student_id && (int) $schedule->student_id !== (int) $student->id;
            });

            if ($blockedSchedule) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'schedule_ids' => "Slot {$blockedSchedule->day} ".substr((string) $blockedSchedule->time, 0, 5).' sudah dipakai siswa lain.',
                ]);
            }

            Schedule::query()
                ->where('student_id', $student->id)
                ->whereNotIn('id', $scheduleIds ?: [0])
                ->update([
                    'student_id' => null,
                    'status' => 'available',
                ]);

            ScheduleSession::query()
                ->where('student_id', $student->id)
                ->whereNotIn('schedule_id', $scheduleIds ?: [0])
                ->where('status', 'booked')
                ->where('session_date', '>=', now()->toDateString())
                ->delete();

            $primarySchedule = $selectedSchedules->first();
            $student->forceFill([
                'class_id' => $primarySchedule?->class_id,
                'schedule_id' => $primarySchedule?->id,
            ])->save();

            foreach ($selectedSchedules as $schedule) {
                $schedule->update([
                    'student_id' => $student->id,
                    'status' => 'booked',
                ]);

                $student->classes()->syncWithoutDetaching([$schedule->class_id]);

                $this->generateStudentSessionsForSchedule($student, $schedule, $force);
            }
        });
    }

    private function generateStudentSessionsForSchedule(Student $student, Schedule $schedule, bool $force = false): void
    {
        if (!$force) {
            $hasFutureSessions = ScheduleSession::query()
                ->where('student_id', $student->id)
                ->where('schedule_id', $schedule->id)
                ->where('status', 'booked')
                ->where('session_date', '>=', now()->toDateString())
                ->exists();

            if ($hasFutureSessions) {
                return;
            }
        }

        $currentDate = Carbon::parse($student->start_date ?: now()->toDateString());
        $dayName = $this->mapScheduleDayToEnglish((string) $schedule->day);

        if (strtolower($currentDate->format('l')) !== $dayName) {
            $currentDate->modify("next {$dayName}");
        }

        $totalSessions = (int) (($student->duration_months ?: 1) * 4);

        for ($i = 0; $i < $totalSessions; $i++) {
            ScheduleSession::query()->create([
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

    private function mapScheduleDayToEnglish(string $day): string
    {
        return [
            'senin' => 'monday',
            'selasa' => 'tuesday',
            'rabu' => 'wednesday',
            'kamis' => 'thursday',
            'jumat' => 'friday',
            'sabtu' => 'saturday',
            'minggu' => 'sunday',
        ][strtolower($day)] ?? strtolower($day);
    }

    public function extendStudent(Request $request, Student $student): RedirectResponse
    {
        $scheduleId = $student->schedule_id;

        if (!$scheduleId) {
            return back()->with('error', 'Siswa ini belum memiliki slot jadwal. Silakan gunakan fitur Edit untuk menambahkan jadwal terlebih dahulu.');
        }

        $schedule = Schedule::find($scheduleId);
        if (!$schedule) {
            return back()->with('error', 'Jadwal siswa tidak ditemukan.');
        }

        DB::transaction(function () use ($student, $schedule) {
            // Cari sesi terakhir siswa ini di jadwal yang sama
            $lastSession = ScheduleSession::where('student_id', $student->id)
                ->where('schedule_id', $schedule->id)
                ->orderBy('session_date', 'desc')
                ->first();

            if ($lastSession) {
                // 1 minggu dari sesi terakhir
                $startDate = Carbon::parse($lastSession->session_date)->addWeek();
            } else {
                // Jika belum ada jadwal sama sekali, mulai dari hari terdekat yang sesuai dengan jadwal
                $startDate = now();
                $dayName = $this->mapScheduleDayToEnglish((string) $schedule->day);
                if (strtolower($startDate->format('l')) !== $dayName) {
                    $startDate->modify("next {$dayName}");
                }
            }

            // Generate 4 sesi untuk 1 bulan
            $totalSessions = 4;
            $currentDate = $startDate->copy();

            for ($i = 0; $i < $totalSessions; $i++) {
                ScheduleSession::query()->create([
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

            // Update data siswa
            $student->update([
                'start_date' => $startDate->toDateString(),
                'duration_months' => 1,
                'end_date' => $startDate->copy()->addMonths(1)->toDateString(),
            ]);
        });

        return back()->with('success', 'Berhasil memperpanjang jadwal siswa selama 1 bulan ke depan.');
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
