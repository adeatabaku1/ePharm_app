<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StorePatientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Patient::class);
    }

    public function rules(): array
    {
        return [
            'user_id'    => 'required|exists:users,id',
            'birthdate'  => 'required|date',
            'gender'     => 'required|in:Male,Female,Other',
            'address'    => 'nullable|string|max:500',
        ];
    }
}

