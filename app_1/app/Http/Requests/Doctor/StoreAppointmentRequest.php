<?php

namespace App\Http\Requests\Doctor;

use Illuminate\Foundation\Http\FormRequest;

class StoreAppointmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Apply authorization logic if needed
    }

    public function rules(): array
    {
        return [
            'patient_id' => ['required', 'exists:patients,id'],
            'appointment_date' => ['required', 'date', 'after_or_equal:today'],
            'description' => ['nullable', 'string'],
            'status' => ['nullable', 'in:pending,confirmed,cancelled'],
        ];
    }
}
