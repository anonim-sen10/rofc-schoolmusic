<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreMusicClassRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'schedule' => ['nullable', 'string', 'max:120'],
            'teacher_id' => ['nullable', 'exists:teachers,id'],
            'status' => ['required', 'in:active,inactive'],
        ];
    }
}
