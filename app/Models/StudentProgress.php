<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentProgress extends Model
{
    use HasFactory;

    protected $table = 'student_progress';

    protected $fillable = [
        'student_id',
        'class_id',
        'teacher_id',
        'topic',
        'note',
        'score',
        'recorded_at',
    ];

    protected $casts = [
        'score' => 'integer',
        'recorded_at' => 'date',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function musicClass(): BelongsTo
    {
        return $this->belongsTo(MusicClass::class, 'class_id');
    }
}
