<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PharmacyRegistrationRequest extends FormRequest
{
    public function authorize() { return true; }

    public function rules()
    {
        return [
            'business_name'    => 'required|string',
            'registration_num' => 'required|string',
            'fiscal_num'       => 'nullable|string',
            'owner_id'         => 'nullable|string',
            'primary_activity' => 'nullable|string',
            'other_activity'   => 'nullable|string',
            'account_name'     => 'required|string|max:255',
            'password'         => 'required|string|confirmed|min:8',
        ];
    }
}
