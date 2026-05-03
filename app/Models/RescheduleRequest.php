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
}
