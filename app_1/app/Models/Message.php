<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [
        'chat_room_id',
        'sender_id',
        'message',
        'sent_at',
    ];

    public function chatRoom()
    {
        return $this->belongsTo(ChatRoom::class);
    }
}
