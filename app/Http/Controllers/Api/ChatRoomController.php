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
/**
 * @OA\Get(
 *      path="/chat-rooms",
 *      operationId="getChatRoomsList",
 *      tags={"Chat Rooms"},
 *      summary="Get list of chat rooms",
 *      description="Returns list of chat rooms for authenticated user",
 *      security={{"sanctum": {}}},
 *      @OA\Parameter(
 *          name="page",
 *          description="page number",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Successful operation",
 *          @OA\JsonContent(ref="#/components/schemas/ChatRoomCollection")
 *       )
 * )
 */
    public function index()
    {
        $user = auth()->user();

        $chatRooms = $user->chatRooms()
            ->with(['latestMessage.user', 'participants'])
            ->paginate(10);

        return new ChatRoomCollection($chatRooms);
    }

    /**
     * @OA\Post(
     *      path="/chat-rooms",
     *      operationId="createChatRoom",
     *      tags={"Chat Rooms"},
     *      summary="Create new chat room",
     *      description="Creates a new group chat room with specified participants",
     *      security={{"sanctum": {}}},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"name","participant_ids"},
     *              @OA\Property(property="name", type="string", example="Project Team"),
     *              @OA\Property(property="description", type="string", example="Discussion about project tasks"),
     *              @OA\Property(property="participant_ids", type="array", @OA\Items(type="integer"), example="[1,2,3]")
     *          )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Created successfully",
     *          @OA\JsonContent(ref="#/components/schemas/ChatRoomResource")
     *       ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden - insufficient permissions",
     *          @OA\Response(response=403,    description="Forbidden - insufficient permissions")
     *      )
     * )
     */
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

    /**
     * @OA\Get(
     *      path="/chat-rooms/{id}",
     *      operationId="getChatRoomById",
     *      tags={"Chat Rooms"},
     *      summary="Get chat room information",
     *      description="Returns chat room data by ID",
     *      security={{"sanctum": {}}},
     *      @OA\Parameter(
     *          name="id",
     *          description="Chat room id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/ChatRoomResource")
     *       ),
     *      @OA\Response(
     *          response=404,
     *          description="Not found",
     *          @OA\Response(response=404, description="Not found")
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden - insufficient permissions",
     *          @OA\JsonContent(ref="#/components/schemas/ForbiddenException")
     *      )
     * )
     */
    public function show($id)
    {
        $chatRoom = ChatRoom::with('participants')->findOrFail($id);

        $this->authorize('view', $chatRoom);

        return new ChatRoomResource($chatRoom);
    }

    /**
     * @OA\Get(
     *      path="/chat-rooms/private",
     *      operationId="getOrCreatePrivateChat",
     *      tags={"Chat Rooms"},
     *      summary="Get or create private chat",
     *      description="Returns existing private chat or creates new one for two users",
     *      security={{"sanctum": {}}},
     *      @OA\Parameter(
     *          name="recipient_id",
     *          description="Recipient user id",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/ChatRoomResource")
     *       ),
     *      @OA\Response(
     *          response=404,
     *          description="Not found",
     *          @OA\JsonContent(ref="#/components/schemas/NotFoundException")
     *      )
     * )
     */
    public function getOrCreatePrivate(GetOrCreatePrivateChatRequest $request)
    {
        $user = auth()->user();
        $recipient = User::findOrFail($request->recipient_id);

        if ($user->id === $recipient->id) {
            abort(response()->json([
                'message' => 'Cannot create a private chat with yourself'
            ], Response::HTTP_NOT_FOUND));
        }

        $chatRoom = ChatRoom::getOrCreatePrivateChat($user, $recipient);

        return new ChatRoomResource($chatRoom->load('participants'));
    }

    /**
     * @OA\Post(
     *      path="/chat-rooms/{id}/participants",
     *      operationId="addParticipantsToChatRoom",
     *      tags={"Chat Rooms"},
     *      summary="Add participants to chat room",
     *      description="Adds specified users to chat room participants",
     *      security={{"sanctum": {}}},
     *      @OA\Parameter(
     *          name="id",
     *          description="Chat room id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"user_ids"},
     *              @OA\Property(property="user_ids", type="array", @OA\Items(type="integer"), example="[1,2,3]")
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/ChatRoomResource")
     *       ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden - insufficient permissions",
     *          @OA\JsonContent(ref="#/components/schemas/ForbiddenException")
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Not found",
     *          @OA\JsonContent(ref="#/components/schemas/NotFoundException")
     *      )
     * )
     */
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

    /**
     * @OA\Delete(
     *      path="/chat-rooms/{id}/participants/{user_id}",
     *      operationId="removeParticipantFromChatRoom",
     *      tags={"Chat Rooms"},
     *      summary="Remove participant from chat room",
     *      description="Removes specified user from chat room participants",
     *      security={{"sanctum": {}}},
     *      @OA\Parameter(
     *          name="id",
     *          description="Chat room id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="user_id",
     *          description="User id to remove",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\Response( response=200, description="Successful operation")
     *       ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden - insufficient permissions",
     *          @OA\JsonContent(ref="#/components/schemas/ForbiddenException")
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Not found",
     *          @OA\JsonContent(ref="#/components/schemas/NotFoundException")
     *      )
     * )
     */
    public function removeParticipant($id, $userId)
    {
        $chatRoom = ChatRoom::findOrFail($id);

        $this->authorize('removeParticipant', [$chatRoom, $userId]);

        $chatRoom->participants()->detach($userId);

        return response()->json(['message' => 'Participant removed successfully']);
    }

    /**
     * @OA\Put(
     *      path="/chat-rooms/{id}/read",
     *      operationId="markChatRoomAsRead",
     *      tags={"Chat Rooms"},
     *      summary="Mark chat room messages as read",
     *      description="Marks all messages in chat room as read for current user",
     *      security={{"sanctum": {}}},
     *      @OA\Parameter(
     *          name="id",
     *          description="Chat room id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/SuccessResponse")
     *       ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden - insufficient permissions",
     *          @OA\JsonContent(ref="#/components/schemas/ForbiddenException")
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Not found",
     *          @OA\JsonContent(ref="#/components/schemas/NotFoundException")
     *      )
     * )
     */
    public function markAsRead($id)
    {
        $chatRoom = ChatRoom::findOrFail($id);

        $this->authorize('view', $chatRoom);

        $chatRoom->markAsReadForUser(auth()->user());

        return response()->json(['message' => 'Messages marked as read']);
    }
}
