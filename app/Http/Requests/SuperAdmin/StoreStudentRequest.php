<?php

namespace App\Http\Requests\SuperAdmin;

use Illuminate\Foundation\Http\FormRequest;

class StoreStudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole('super_admin');
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
            'nama_panggilan' => ['nullable', 'string', 'max:80'],
            'jenis_kelamin' => ['nullable', 'in:laki-laki,perempuan'],
            'tempat_lahir' => ['nullable', 'string', 'max:120'],
            'tanggal_lahir' => ['nullable', 'date'],
            'kewarganegaraan' => ['nullable', 'string', 'max:120'],
            'age' => ['nullable', 'integer', 'min:4', 'max:80'],
            'phone' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:120', 'unique:students,email'],
            'address' => ['nullable', 'string', 'max:500'],
            'nama_ortu' => ['nullable', 'string', 'max:120'],
            'pekerjaan_ortu' => ['nullable', 'string', 'max:120'],
            'no_hp_ortu' => ['nullable', 'string', 'max:30'],
            'email_ortu' => ['nullable', 'email', 'max:120'],
            'is_active' => ['required', 'in:1,0'],
            'class_id' => ['required', 'integer', 'exists:classes,id'],
            'schedule_id' => ['required', 'integer', 'exists:schedules,id'],
            'start_date' => ['nullable', 'date'],
            'duration_months' => ['nullable', 'integer', 'in:1,2,3,6,12'],
            'program_tambahan' => ['nullable', 'array'],
            'program_tambahan.*' => ['string', 'max:120'],
            'pengalaman' => ['nullable', 'boolean'],
            'deskripsi_pengalaman' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
