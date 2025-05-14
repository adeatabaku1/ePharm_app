<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreMedicineRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Medicine::class);
    }

    public function rules(): array
    {
        return [
            'tenant_id'     => 'required|exists:tenants,id',
            'name'          => 'required|string|max:255',
            'description'   => 'nullable|string',
            'price'         => 'required|numeric|min:0',
            'stock_quantity'=> 'required|integer|min:0',
            'expire_date'   => 'required|date',
        ];
    }
}
