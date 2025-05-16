<?php

namespace App\Http\Resources\Doctor;

use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'sender' => $this->sender_id === auth()->id() ? 'doctor' : 'patient',
            'text' => $this->message,
            'timestamp' => $this->sent_at->toISOString(),
        ];
    }
}
