<?php

namespace App\Http\Requests\Admin;
use Illuminate\Foundation\Http\FormRequest;
use App\Models\GeneralSetting;

class StoreGeneralSettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', GeneralSetting::class);
    }

    public function rules(): array
    {
        return [
            'platform_name'     => 'required|string|max:255',
            'support_email'     => 'required|email',
            'default_language'  => 'required|string',
            'date_format'       => 'required|string',
            'two_factor_auth'   => 'boolean',
            'password_policy'   => 'boolean',
            'session_timeout'   => 'boolean',
            'session_length'    => 'required_if:session_timeout,true|integer|min:1',
            'email_notifications'=> 'boolean',
            'system_alerts'     => 'boolean',
        ];
    }
}

