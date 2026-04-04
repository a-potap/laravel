<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\AddParticipantsRequest;
use App\Http\Requests\Api\CreateChatRoomRequest;
use App\Http\Requests\Api\GetOrCreatePrivateChatRequest;
use App\Http\Resources\Api\ChatRoomCollection;
use App\Http\Resources\Api\ChatRoomResource;
use App\Models\ChatRoom;
use App\Models\User;
use Illuminate\Http\Response;

class ChatRoomController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $chatRooms = $user->chatRooms()
            ->with(['latestMessage.user', 'participants'])
            ->paginate(10);

        return new ChatRoomCollection($chatRooms);
    }

    public function store(CreateChatRoomRequest $request)
    {
        $this->authorize('create', ChatRoom::class);

        $chatRoom = ChatRoom::create([
            'type' => 'group',
            'name' => $request->name,
            'description' => $request->description,
            'created_by' => auth()->id(),
        ]);

        $participantIds = array_merge([auth()->id()], $request->participant_ids);
        $participantIds = array_unique($participantIds);

        $chatRoom->participants()->attach($participantIds, [
            'joined_at' => now()
        ]);

        return (new ChatRoomResource($chatRoom->load('participants')))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show($id)
    {
        $chatRoom = ChatRoom::with('participants')->findOrFail($id);

        $this->authorize('view', $chatRoom);

        return new ChatRoomResource($chatRoom);
    }

    public function getOrCreatePrivate(GetOrCreatePrivateChatRequest $request)
    {
        $user = auth()->user();
        $recipient = User::findOrFail($request->recipient_id);

        $chatRoom = ChatRoom::getOrCreatePrivateChat($user, $recipient);

        return new ChatRoomResource($chatRoom->load('participants'));
    }

    public function addParticipants($id, AddParticipantsRequest $request)
    {
        $chatRoom = ChatRoom::findOrFail($id);

        $this->authorize('addParticipants', $chatRoom);

        $chatRoom->participants()->syncWithoutDetaching(
            collect($request->user_ids)->mapWithKeys(function ($userId) {
                return [$userId => ['joined_at' => now()]];
            })
        );

        return new ChatRoomResource($chatRoom->load('participants'));
    }

    public function removeParticipant($id, $userId)
    {
        $chatRoom = ChatRoom::findOrFail($id);

        $this->authorize('removeParticipant', [$chatRoom, $userId]);

        $chatRoom->participants()->detach($userId);

        return response()->json(['message' => 'Participant removed successfully']);
    }

    public function markAsRead($id)
    {
        $chatRoom = ChatRoom::findOrFail($id);

        $this->authorize('view', $chatRoom);

        $chatRoom->markAsReadForUser(auth()->user());

        return response()->json(['message' => 'Messages marked as read']);
    }
}
