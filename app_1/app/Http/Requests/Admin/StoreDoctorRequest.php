<?php

namespace App\Http\Requests\Admin;

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreDoctorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Doctor::class);
    }

    public function rules(): array
    {
        return [
            'user_id'        => 'required|exists:users,id',
            'license_number' => 'required|string|unique:doctors,license_number',
            'specialization' => 'required|string|max:255',
            'is_verified'    => 'sometimes|boolean',
        ];
    }
}

