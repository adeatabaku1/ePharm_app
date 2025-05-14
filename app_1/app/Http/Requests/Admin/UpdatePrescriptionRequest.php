<?php

namespace App\Http\Requests\Admin;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePrescriptionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('prescription'));
    }

    public function rules(): array
    {
        return [
            'diagnosis'          => 'sometimes|string',
            'notes'              => 'nullable|string',
            'is_sent_to_patient' => 'sometimes|boolean',
            'discount_code_id'   => 'nullable|exists:discount_codes,id',
        ];
    }
}
