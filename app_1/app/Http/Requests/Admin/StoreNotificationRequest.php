<?php

namespace App\Http\Requests\Admin;
use Illuminate\Foundation\Http\FormRequest;
use App\Models\Notification;

class StoreNotificationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // system-generated notifications
    }

    public function rules(): array
    {
        return [
            'user_id'  => 'required|exists:users,id',
            'type'     => 'required|string',
            'title'    => 'required|string',
            'message'  => 'required|string',
            'is_read'  => 'sometimes|boolean',
        ];
    }
}

