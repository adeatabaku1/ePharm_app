<?php

namespace App\Http\Requests\Admin;

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDoctorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('doctor'));
    }

    public function rules(): array
    {
        return [
            'license_number' => [
                'required',
                Rule::unique('doctors','license_number')->ignore($this->route('doctor')->id),
            ],
            'specialization' => 'required|string|max:255',
            'is_verified'    => 'sometimes|boolean',
        ];
    }
}
