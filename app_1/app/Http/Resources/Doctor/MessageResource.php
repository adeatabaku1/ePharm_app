<?php

namespace App\Http\Resources\Doctor;

use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'sender_id' => $this->sender_id,
            'message' => $this->message,
            'sent_at' => $this->sent_at->toDateTimeString(),
        ];
    }
}
