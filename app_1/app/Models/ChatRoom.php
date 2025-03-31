<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatRoom extends Model
{
    protected $fillable = [
        'doctor_id',
        'patient_id',
        'created_at',
    ];

    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}
