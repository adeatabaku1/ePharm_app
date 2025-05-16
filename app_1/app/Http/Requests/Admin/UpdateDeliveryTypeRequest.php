<?php

namespace App\Http\Requests\Admin;
use Illuminate\Validtp\Requests\Admin;

use Illuminate\Fouation\Rule;

class UpdateDeliveryTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('delivery_type'));
    }

    public function rules(): array
    {
        return [
            'name' => ['required', Rule::unique('delivery_types','name')->ignore($this->route('delivery_type')->id)],
        ];
    }
}
