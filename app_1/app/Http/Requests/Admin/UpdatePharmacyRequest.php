<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePharmacyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('pharmacy'));
    }

    public function rules(): array
    {
        return [
            'name'          => 'sometimes|string|max:255',
            'address'       => 'sometimes|string',
            'license_number'=> [
                'required',
                Rule::unique('pharmacies','license_number')->ignore($this->route('pharmacy')->id),
            ],
            'email'         => 'nullable|email',
            'phone'         => 'nullable|string',
            'is_verified'   => 'sometimes|boolean',
        ];
    }
}

