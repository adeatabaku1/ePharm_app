<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Pharmacy;

class StorePharmacyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Pharmacy::class);
    }

    public function rules(): array
    {
        return [
            'tenant_id'     => 'required|exists:tenants,id',
            'name'          => 'required|string|max:255',
            'address'       => 'required|string',
            'license_number'=> 'required|string|unique:pharmacies,license_number',
            'email'         => 'nullable|email',
            'phone'         => 'nullable|string',
            'is_verified'   => 'sometimes|boolean',
        ];
    }
}
