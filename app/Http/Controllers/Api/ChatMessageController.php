<?php

namespace App\Http\Controllers\Api;

use App\Events\MessageSent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\SendMessageRequest;
use App\Http\Resources\Api\ChatMessageCollection;
use App\Http\Resources\Api\ChatMessageResource;
use App\Models\ChatMessage;
use App\Models\ChatRoom;
use Illuminate\Http\Response;

class ChatMessageController extends Controller
{
    public function index($roomId)
    {
        $chatRoom = ChatRoom::findOrFail($roomId);

        $this->authorize('view', $chatRoom);

        $messages = ChatMessage::where('chat_room_id', $roomId)
            ->with('user')
            ->orderBy('date', 'desc')
            ->paginate(50);

        return new ChatMessageCollection($messages);
    }

    public function store($roomId, SendMessageRequest $request)
    {
        $chatRoom = ChatRoom::findOrFail($roomId);

        $this->authorize('sendMessage', $chatRoom);

        $message = ChatMessage::create([
            'chat_room_id' => $roomId,
            'user_id' => auth()->id(),
            'message' => $request->message,
        ]);

        $message->load('user');

        broadcast(new MessageSent($message))->toOthers();

        return (new ChatMessageResource($message))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }
}
