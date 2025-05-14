<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Doctor\StoreMessageRequest;
use App\Http\Resources\Doctor\ChatRoomResource;
use App\Http\Resources\Doctor\MessageResource;
use App\Services\Doctor\ChatService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    protected ChatService $chatService;

    public function __construct(ChatService $chatService)
    {
        $this->chatService = $chatService;
    }

    public function getChatRooms(Request $request): JsonResponse
    {
        $rooms = $this->chatService->getDoctorChatRooms(auth()->id());
        return response()->json([
            'success' => true,
            'data' => ChatRoomResource::collection($rooms)
        ]);
    }

    public function getMessages($chatRoomId): JsonResponse
    {
        $messages = $this->chatService->getMessages($chatRoomId);
        return response()->json([
            'success' => true,
            'data' => MessageResource::collection($messages)
        ]);
    }

    public function sendMessage(StoreMessageRequest $request, $chatRoomId): JsonResponse
    {
        $message = $this->chatService->sendMessage($chatRoomId, $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Message sent successfully',
            'data' => new MessageResource($message)
        ]);
    }
}
