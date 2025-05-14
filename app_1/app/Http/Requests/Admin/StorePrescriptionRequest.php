<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Prescription;

class StorePrescriptionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Prescription::class);
    }

    public function rules(): array
    {
        return [
            'tenant_id'   => 'required|exists:tenants,id',
            'doctor_id'   => 'required|exists:doctors,id',
            'patient_id'  => 'required|exists:patients,id',
            'diagnosis'   => 'required|string',
            'notes'       => 'nullable|string',
            'is_sent_to_patient'=> 'sometimes|boolean',
            'discount_code_id'   => 'nullable|exists:discount_codes,id',
        ];
    }
}

