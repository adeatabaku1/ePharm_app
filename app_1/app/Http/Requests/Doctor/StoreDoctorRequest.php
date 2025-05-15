<?php

namespace App\Http\Requests\Doctor;

use Illuminate\Foundation\Http\FormRequest;

class StoreDoctorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    /**
     * @OA\Schema(
     *     schema="StoreDoctorRequest",
     *     type="object",
     *     required={"name", "email", "phone", "license_number", "specialization"},
     *     @OA\Property(property="name", type="string", example="Dr. John Smith"),
     *     @OA\Property(property="email", type="string", example="john@example.com"),
     *     @OA\Property(property="phone", type="string", example="123456789"),
     *     @OA\Property(property="license_number", type="string", example="ABC123456"),
     *     @OA\Property(property="specialization", type="string", example="Cardiology")
     * )
     */

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:20',
            'license_number' => 'required|string|max:100',
            'specialization' => 'required|string|max:100',
        ];
    }
}
