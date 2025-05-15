<?php

namespace App\Http\Requests\Pharmacy;

use Illuminate\Foundation\Http\FormRequest;

class PharmacySettingsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|max:255',
            'phone' => 'sometimes|required|string|max:20',
            'address' => 'sometimes|required|string|max:255',
            'opening_hours' => 'sometimes|nullable|string|max:255',
            'logo' => 'sometimes|nullable|image|max:2048',
            'description' => 'sometimes|nullable|string',
            'website' => 'sometimes|nullable|url|max:255',
            'social_media' => 'sometimes|nullable|array',
            'delivery_options' => 'sometimes|nullable|array',
            'payment_methods' => 'sometimes|nullable|array',
        ];
    }
}
