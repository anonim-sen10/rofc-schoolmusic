<?php

namespace App\Http\Requests\SuperAdmin;

use Illuminate\Foundation\Http\FormRequest;

class StoreRegistrationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole('super_admin');
    }

    public function rules(): array
    {
        return [
            'full_name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:120'],
            'phone' => ['required', 'string', 'max:30'],
            'address' => ['nullable', 'string', 'max:500'],
            'instrumen' => ['required', 'string', 'max:80'],
            'program_tambahan' => ['nullable', 'array'],
            'program_tambahan.*' => ['string', 'max:120'],
            'hari' => ['nullable', 'string', 'max:120'],
            'jam' => ['nullable', 'string', 'max:80'],
            'pengalaman' => ['nullable', 'boolean'],
            'deskripsi_pengalaman' => ['nullable', 'string'],
        ];
    }
}
