<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'recorded_at' => 'date',
    ];
}
