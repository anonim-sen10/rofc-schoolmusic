<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\ScheduleSession;
use App\Models\MusicClass;
use App\Models\Schedule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ScheduleController extends Controller
{
    public const DAY_OPTIONS = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];

    public function index(): View
    {
        return app(SuperAdminController::class)->module('schedule');
    }

    public function store(Request $request): RedirectResponse
    {
        if (! $this->hasSchedulesTable()) {
            return back()->with('error', 'Tabel schedules belum tersedia. Jalankan migrasi terlebih dahulu.');
        }

        $data = $request->validate([
            'class_id' => ['required', 'integer', 'exists:classes,id'],
            'teacher_id' => ['nullable', 'integer', 'exists:teachers,id'],
            'days' => ['required', 'array', 'min:1'],
            'days.*' => ['required', 'string', Rule::in(self::DAY_OPTIONS)],
            'start_time' => ['required', 'date_format:H:i', 'before_or_equal:end_time'],
            'end_time' => ['required', 'date_format:H:i', 'after_or_equal:start_time'],
            'interval' => ['required', 'integer', 'min:1'],
        ]);

        $class = MusicClass::query()->findOrFail($data['class_id']);
        $teacherId = $data['teacher_id'] ?: $class->teacher_id;
        $insertedCount = 0;
        $skippedCount = 0;
        $hasStatusColumn = Schema::hasColumn('schedules', 'status');

        foreach ($data['days'] as $day) {
            $startTime = \Carbon\Carbon::parse($data['start_time']);
            $endTime = \Carbon\Carbon::parse($data['end_time']);
            $interval = (int) $data['interval'];
            $currentTime = $startTime->copy();

            while ($currentTime->lte($endTime)) {
                $timeString = $currentTime->format('H:i');

                $duplicateExists = Schedule::query()
                    ->where('class_id', $class->id)
                    ->where('day', $day)
                    ->where('time', $timeString)
                    ->where('teacher_id', $teacherId)
                    ->exists();

                if (! $duplicateExists) {
                    $payload = [
                        'class_id' => $class->id,
                        'day' => $day,
                        'time' => $timeString,
                        'teacher_id' => $teacherId,
                    ];

                    if ($hasStatusColumn) {
                        $payload['status'] = 'available';
                    }

                    Schedule::query()->create($payload);
                    $insertedCount++;
                } else {
                    $skippedCount++;
                }

                $currentTime->addMinutes($interval);
            }
        }

        $message = "Berhasil membuat {$insertedCount} jadwal baru untuk ".count($data['days'])." hari.";
        if ($skippedCount > 0) {
            $message .= " Dilewati {$skippedCount} jadwal karena duplikat.";
        }

        return back()->with('success', $message);
    }

    public function update(Request $request, Schedule $schedule): RedirectResponse
    {
        if (! $this->hasSchedulesTable()) {
            return back()->with('error', 'Tabel schedules belum tersedia. Jalankan migrasi terlebih dahulu.');
        }

        $data = $this->validatePayload($request, $schedule->id);
        $class = MusicClass::query()->findOrFail($data['class_id']);
        $teacherId = $request->input('teacher_id') ?: ($class->teacher_id);

        $schedule->update([
            'class_id' => $class->id,
            'day' => $data['day'],
            'time' => $data['time'],
            'teacher_id' => $teacherId,
        ]);

        return back()->with('success', 'Jadwal kelas berhasil diperbarui.');
    }

    public function destroy(Schedule $schedule): RedirectResponse
    {
        if (! $this->hasSchedulesTable()) {
            return back()->with('error', 'Tabel schedules belum tersedia. Jalankan migrasi terlebih dahulu.');
        }

        $schedule->delete();

        return back()->with('success', 'Jadwal kelas berhasil dihapus.');
    }

    private function validatePayload(Request $request, ?int $ignoreId = null): array
    {
        $data = $request->validate([
            'class_id' => ['required', 'integer', 'exists:classes,id'],
            'day' => ['required', 'string', Rule::in(self::DAY_OPTIONS)],
            'time' => ['required', 'date_format:H:i'],
        ]);

        $duplicateQuery = Schedule::query()
            ->where('class_id', $data['class_id'])
            ->where('day', $data['day'])
            ->where('time', $data['time']);

        if ($ignoreId) {
            $duplicateQuery->whereKeyNot($ignoreId);
        }

        if ($duplicateQuery->exists()) {
            throw ValidationException::withMessages([
                'time' => 'Jadwal dengan hari dan jam yang sama untuk class ini sudah ada.',
            ]);
        }

        return $data;
    }

    private function hasSchedulesTable(): bool
    {
        return Schema::hasTable('schedules');
    }

    public function assignSubstitute(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'original_teacher_id' => ['required', 'integer', 'exists:teachers,id'],
            'substitute_teacher_id' => ['required', 'integer', 'exists:teachers,id', 'different:original_teacher_id'],
            'student_id' => ['nullable', 'integer', 'exists:students,id'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
        ]);

        $query = ScheduleSession::query()
            ->where('teacher_id', $data['original_teacher_id'])
            ->whereBetween('session_date', [$data['start_date'], $data['end_date']]);

        if (!empty($data['student_id'])) {
            $query->where('student_id', $data['student_id']);
        }

        $updatedCount = $query->update([
            'substitute_teacher_id' => $data['substitute_teacher_id']
        ]);

        $msg = "Berhasil menugaskan guru pengganti untuk {$updatedCount} sesi kelas.";
        if (!empty($data['student_id'])) {
            $msg = "Berhasil menugaskan guru pengganti untuk {$updatedCount} sesi kelas siswa yang dipilih.";
        }

        return back()->with('success', $msg);
    }
}
