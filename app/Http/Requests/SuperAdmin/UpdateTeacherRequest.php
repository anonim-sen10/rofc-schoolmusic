<?php

namespace App\Http\Requests\SuperAdmin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTeacherRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole('super_admin');
    }

    public function rules(): array
    {
        $teacher = $this->route('teacher');
        $userId = $teacher ? $teacher->user_id : 'NULL';

        return [
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:120', 'unique:users,email,' . $userId],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'phone' => ['required', 'string', 'max:30'],
            'address' => ['required', 'string', 'max:500'],
            'gender' => ['required', 'in:laki-laki,perempuan'],
            'religion' => ['required', 'string', 'max:30'],
            'instrument' => ['nullable', 'string', 'max:80'],
            'class_id' => ['nullable', 'integer', 'exists:classes,id'],
            'photo' => ['nullable', 'image', 'max:2048'],
            'ktp' => ['nullable', 'image', 'max:2048'],
        ];
    }
}
