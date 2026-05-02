<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     title="ChatRoomResource",
 *     description="Represents a chat room and its associated data",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="The unique identifier of the chat room"
 *     ),
 *     @OA\Property(
 *         property="type",
 *         type="string",
 *         description="The type of the chat room"
 *     ),
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         description="The name of the chat room"
 *     ),
 *     @OA\Property(
 *         property="description",
 *         type="string",
 *         description="The description of the chat room"
 *     ),
 *     @OA\Property(
 *         property="created_by",
 *         type="integer",
 *         description="The ID of the user who created the chat room"
 *     ),
 *     @OA\Property(
 *         property="date",
 *         type="string",
 *         format="date-time",
 *         description="The date and time the chat room was created"
 *     ),
 *     @OA\Property(
 *         property="updated_at",
 *         type="string",
 *         format="date-time",
 *         description="The date and time the chat room was last updated"
 *     ),
 *     @OA\Property(
 *         property="participants",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/UserResource")
 *     ),
 *     @OA\Property(
 *         property="latest_message",
 *         ref="#/components/schemas/ChatMessageResource"
 *     ),
 *     @OA\Property(
 *         property="unread_count",
 *         type="integer",
 *         description="The number of unread messages for the authenticated user"
 *     )
 * )
 */
class ChatRoomResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'name' => $this->name,
            'description' => $this->description,
            'created_by' => $this->created_by,
            'date' => $this->date,
            'updated_at' => $this->updated_at,
            'participants' => UserResource::collection($this->whenLoaded('participants')),
            'latest_message' => new ChatMessageResource($this->whenLoaded('latestMessage')),
            'unread_count' => $this->when(
                auth()->check(),
                fn() => $this->getUnreadCountForUser(auth()->user())
            ),
        ];
    }
}