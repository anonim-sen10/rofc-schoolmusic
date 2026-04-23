<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
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

        $data = $this->validatePayload($request);
        $class = MusicClass::query()->findOrFail($data['class_id']);

        $payload = [
            'class_id' => $class->id,
            'day' => $data['day'],
            'time' => $data['time'],
            'teacher_id' => $class->teacher_id,
        ];

        if (Schema::hasColumn('schedules', 'status')) {
            $payload['status'] = 'available';
        }

        Schedule::query()->create($payload);

        return back()->with('success', 'Jadwal kelas berhasil ditambahkan.');
    }

    public function update(Request $request, Schedule $schedule): RedirectResponse
    {
        if (! $this->hasSchedulesTable()) {
            return back()->with('error', 'Tabel schedules belum tersedia. Jalankan migrasi terlebih dahulu.');
        }

        $data = $this->validatePayload($request, $schedule->id);
        $class = MusicClass::query()->findOrFail($data['class_id']);

        $schedule->update([
            'class_id' => $class->id,
            'day' => $data['day'],
            'time' => $data['time'],
            'teacher_id' => $class->teacher_id,
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
}
