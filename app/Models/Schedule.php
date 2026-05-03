<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'class_id',
        'day',
        'time',
        'teacher_id',
        'student_id',
        'status',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function musicClass(): BelongsTo
    {
        return $this->belongsTo(MusicClass::class, 'class_id');
    }

    public function class(): BelongsTo
    {
        return $this->belongsTo(MusicClass::class, 'class_id');
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(Registration::class, 'schedule_id');
    }

    public function attendance(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Attendance::class);
    }
    public function rescheduleRequests(): HasMany
    {
        return $this->hasMany(RescheduleRequest::class, 'old_schedule_id');
    }
}
