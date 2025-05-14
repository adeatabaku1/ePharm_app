<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\DeliveryType;

class StoreDeliveryTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', DeliveryType::class);
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|unique:delivery_types,name',
        ];
    }
}
