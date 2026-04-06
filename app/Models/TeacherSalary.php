<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeacherSalary extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'period',
        'base_salary',
        'bonus',
        'deduction',
        'total_paid',
        'paid_at',
    ];

    protected $casts = [
        'base_salary' => 'float',
        'bonus' => 'float',
        'deduction' => 'float',
        'total_paid' => 'float',
        'paid_at' => 'date',
    ];

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }
}
