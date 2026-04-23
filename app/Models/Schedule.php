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
        'status',
    ];

    public function musicClass(): BelongsTo
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

    public function students(): HasMany
    {
        return $this->hasMany(Student::class, 'schedule_id');
    }
}
