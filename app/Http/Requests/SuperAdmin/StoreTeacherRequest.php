<?php

namespace App\Http\Requests\SuperAdmin;

use Illuminate\Foundation\Http\FormRequest;

class StoreTeacherRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole('super_admin');
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:120', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'phone' => ['required', 'string', 'max:30'],
            'address' => ['required', 'string', 'max:500'],
            'gender' => ['required', 'in:laki-laki,perempuan'],
            'religion' => ['required', 'string', 'max:30'],
            'instrument' => ['nullable', 'string', 'max:80'],
            'photo' => ['nullable', 'image', 'max:2048'],
            'ktp' => ['nullable', 'image', 'max:2048'],
            'class_ids' => ['nullable', 'array'],
            'class_ids.*' => ['exists:classes,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique' => 'Email ini sudah terdaftar sebagai akun user.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'photo.image' => 'File foto profile harus berupa gambar.',
            'ktp.image' => 'File KTP harus berupa gambar.',
        ];
    }
}
