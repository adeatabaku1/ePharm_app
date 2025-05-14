<?php

namespace App\Http\Requests\Admin;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePermissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('permission'));
    }

    public function rules(): array
    {
        return [
            'name'        => ['required', Rule::unique('permissions','name')->ignore($this->route('permission')->id)],
            'description' => 'nullable|string',
        ];
    }
}

