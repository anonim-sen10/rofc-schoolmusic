<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'category',
        'title',
        'amount',
        'expense_date',
        'note',
    ];

    protected $casts = [
        'amount' => 'float',
        'expense_date' => 'date',
    ];
}
