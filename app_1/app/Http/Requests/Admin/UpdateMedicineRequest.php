<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMedicineRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('medicine'));
    }

    public function rules(): array
    {
        return [
            'name'           => 'sometimes|string|max:255',
            'description'    => 'nullable|string',
            'price'          => 'sometimes|numeric|min:0',
            'stock_quantity' => 'sometimes|integer|min:0',
            'expire_date'    => 'sometimes|date',
        ];
    }
}
