<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'age',
        'phone',
        'email',
        'address',
        'no_hp',
        'class_id',
        'schedule_id',
        'is_active',
        'start_date',
        'duration_months',
        'end_date',
    ];

    protected $casts = [
        'is_active' => 'bool',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(Schedule::class, 'schedule_id');
    }

    public function class(): BelongsTo
    {
        return $this->belongsTo(MusicClass::class, 'class_id');
    }

    public function classes(): BelongsToMany
    {
        return $this->belongsToMany(MusicClass::class, 'class_students', 'student_id', 'class_id')->withTimestamps();
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }
    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }

    public function rescheduleRequests(): HasMany
    {
        return $this->hasMany(RescheduleRequest::class);
    }
}
