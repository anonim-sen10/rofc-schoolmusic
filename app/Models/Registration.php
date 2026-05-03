<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Registration extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'age',
        'phone',
        'email',
        'class_id',
        'schedule_id',
        'preferred_schedule',
        'notes',
        'status',
        'nama_lengkap',
        'nama_panggilan',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'kewarganegaraan',
        'alamat',
        'no_hp_siswa',
        'nama_ortu',
        'pekerjaan_ortu',
        'no_hp_ortu',
        'email_ortu',
        'instrumen',
        'program_tambahan',
        'hari_pilihan',
        'pengalaman',
        'deskripsi_pengalaman',
        'start_date',
        'duration_months',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'program_tambahan' => 'array',
        'hari_pilihan' => 'array',
        'pengalaman' => 'bool',
    ];

    public function class(): BelongsTo
    {
        return $this->belongsTo(MusicClass::class, 'class_id');
    }

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(Schedule::class, 'schedule_id');
    }
    public function schedules()
    {
        return $this->belongsToMany(Schedule::class, 'registration_schedules');
    }
}
