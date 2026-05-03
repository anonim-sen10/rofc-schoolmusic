<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    use HasFactory;

    protected $table = 'attendances';

    protected $fillable = [
        'schedule_id',
        'session_id',
        'student_id',
        'teacher_id',
        'status',
        'latitude',
        'longitude',
        'note',
    ];

    public function session(): BelongsTo
    {
        return $this->belongsTo(ScheduleSession::class, 'session_id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(Schedule::class);
    }

    public function class(): \Illuminate\Database\Eloquent\Relations\HasOneThrough
    {
        return $this->hasOneThrough(
            MusicClass::class,
            Schedule::class,
            'id',
            'id',
            'schedule_id',
            'class_id'
        );
    }
}
