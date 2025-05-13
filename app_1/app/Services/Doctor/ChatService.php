<?php

namespace App\Services\Doctor;

use App\Models\ChatRoom;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;

class ChatService
{
    public function getDoctorChatRooms($doctorId)
    {
        return ChatRoom::with(['patient.user'])
            ->where('doctor_id', $doctorId)
            ->orderByDesc('created_at')
            ->get();
    }

    public function getMessages($chatRoomId)
    {
        return Message::where('chat_room_id', $chatRoomId)
            ->orderBy('sent_at')
            ->get();
    }

    public function sendMessage($chatRoomId, array $data)
    {
        return Message::create([
            'chat_room_id' => $chatRoomId,
            'sender_id' => Auth::id(),
            'message' => $data['message'],
            'sent_at' => now(),
        ]);
    }
}
