<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

use App\Traits\LogsActivity;

class Teacher extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'user_id',
        'name',
        'phone',
        'address',
        'gender',
        'religion',
        'instrument',
        'bio',
        'experience',
        'photo_path',
        'ktp_path',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'bool',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function classes(): HasMany
    {
        return $this->hasMany(MusicClass::class, 'teacher_id');
    }

    public function musicClasses(): BelongsToMany
    {
        return $this->belongsToMany(MusicClass::class, 'class_teacher', 'teacher_id', 'class_id')->withTimestamps();
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }
}
