<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\LogsActivity;

class ScheduleSession extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'schedule_id',
        'student_id',
        'teacher_id',
        'class_id',
        'session_date',
        'time',
        'status',
        'is_reminder_sent',
        'substitute_teacher_id',
    ];

    protected $casts = [
        'session_date' => 'date',
    ];

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(Schedule::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

    public function substituteTeacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class, 'substitute_teacher_id');
    }

    public function musicClass(): BelongsTo
    {
        return $this->belongsTo(MusicClass::class, 'class_id');
    }

    public function attendance(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Attendance::class, 'session_id'); // We'll need to update Attendance table later
    }

    public function rescheduleRequests(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(RescheduleRequest::class, 'old_session_id');
    }

    protected static function booted()
    {
        static::creating(function ($session) {
            if ($session->teacher_id && $session->session_date) {
                $leave = TeacherLeave::where('teacher_id', $session->teacher_id)
                    ->whereDate('start_date', '<=', $session->session_date)
                    ->whereDate('end_date', '>=', $session->session_date)
                    ->first();
                
                if ($leave) {
                    $session->substitute_teacher_id = $leave->substitute_teacher_id;
                }
            }
        });
    }
}
