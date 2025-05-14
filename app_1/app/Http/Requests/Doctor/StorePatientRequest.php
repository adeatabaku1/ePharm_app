<?php

namespace App\Http\Requests\Doctor;

use Illuminate\Foundation\Http\FormRequest;

class StorePatientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'license_number' => 'nullable|string|max:255',
            'birthdate' => 'required|date',
            'gender' => 'required|string|in:male,female,other',
            'address' => 'nullable|string|max:255',
        ];
    }
}
