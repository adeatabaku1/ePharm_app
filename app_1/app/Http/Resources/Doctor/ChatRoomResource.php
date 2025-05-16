<?php

namespace App\Http\Resources\Doctor;

use Illuminate\Http\Resources\Json\JsonResource;

class ChatRoomResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'patient' => [
                'id' => $this->patient->id,
                'name' => $this->patient->user->name ?? 'Unknown',
            ],
            'lastMessage' => optional($this->messages()->latest('sent_at')->first())->message,
            'createdAt' => $this->created_at->toISOString(),
            'unread' => $this->messages()
                ->whereNull('read_at')
                ->where('sender_id', '!=', auth()->id())
                ->count(),
        ];
    }
}
