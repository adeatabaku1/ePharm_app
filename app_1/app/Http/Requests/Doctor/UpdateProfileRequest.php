<?php

namespace App\Http\Requests\Doctor;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        // You may restrict this if needed, e.g., only allow doctor users
        return true;
    }

    public function rules(): array
    {
        return [
            'name'             => ['required', 'string', 'max:255'],
            'email'            => ['required', 'email', 'max:255'],
            'phone'            => ['nullable', 'string', 'max:20'],
            'license_number'   => ['required', 'string', 'max:50'],
            'specialization'   => ['required', 'string', 'max:100'],
        ];
    }
}
