<?php

namespace App\Http\Requests\Admin;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePatientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('patient'));
    }

    public function rules(): array
    {
        return [
            'birthdate'  => 'sometimes|date',
            'gender'     => 'sometimes|in:Male,Female,Other',
            'address'    => 'nullable|string|max:500',
        ];
    }
}
