<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RescheduleRequest extends Model
{
    protected $fillable = [
        'student_id',
        'old_schedule_id',
        'old_session_id',
        'new_schedule_id',
        'new_date',
        'new_session_id',
        'reason',
        'status',
        'approved_by',
        'approved_at',
    ];

    public function oldSession(): BelongsTo
    {
        return $this->belongsTo(ScheduleSession::class, 'old_session_id');
    }

    public function newSession(): BelongsTo
    {
        return $this->belongsTo(ScheduleSession::class, 'new_session_id');
    }

    protected $casts = [
        'approved_at' => 'datetime',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function oldSchedule(): BelongsTo
    {
        return $this->belongsTo(Schedule::class, 'old_schedule_id');
    }

    public function newSchedule(): BelongsTo
    {
        return $this->belongsTo(Schedule::class, 'new_schedule_id');
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function getOldLabelAttribute(): string
    {
        if ($this->oldSession) {
            return \Carbon\Carbon::parse($this->oldSession->session_date)->translatedFormat('l, d M Y') . ' - ' . substr((string) $this->oldSession->time, 0, 5);
        }
        if ($this->oldSchedule) {
            return $this->oldSchedule->day . ' ' . substr((string) $this->oldSchedule->time, 0, 5);
        }
        return '-';
    }

    public function getNewLabelAttribute(): string
    {
        if (!$this->newSchedule) return '-';

        $isPushBack = $this->oldSession && ($this->new_schedule_id == $this->oldSession->schedule_id);
        if ($isPushBack) {
            return '➡️ Dorong Mundur 1 Minggu';
        }

        if ($this->new_date) {
            return \Carbon\Carbon::parse($this->new_date)->translatedFormat('l, d M Y') . ' - ' . substr((string) $this->newSchedule->time, 0, 5);
        }

        // Fallback to calculate from oldSession if no new_date
        if ($this->oldSession) {
            $dayMap = [
                'Senin' => \Carbon\Carbon::MONDAY,
                'Selasa' => \Carbon\Carbon::TUESDAY,
                'Rabu' => \Carbon\Carbon::WEDNESDAY,
                'Kamis' => \Carbon\Carbon::THURSDAY,
                'Jumat' => \Carbon\Carbon::FRIDAY,
                'Sabtu' => \Carbon\Carbon::SATURDAY,
                'Minggu' => \Carbon\Carbon::SUNDAY,
            ];
            $newDayNum = $dayMap[$this->newSchedule->day] ?? \Carbon\Carbon::MONDAY;
            $oldDate = \Carbon\Carbon::parse($this->oldSession->session_date);
            $newDate = $oldDate->copy()->startOfWeek()->addDays($newDayNum - 1);
            
            if ($newDate->lt(now()->startOfDay())) {
                $newDate->addWeek();
            }
            
            return $newDate->translatedFormat('l, d M Y') . ' - ' . substr((string) $this->newSchedule->time, 0, 5);
        }

        return $this->newSchedule->day . ' ' . substr((string) $this->newSchedule->time, 0, 5);
    }
}
