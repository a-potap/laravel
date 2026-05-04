<?php

namespace App\Http\Resources\Api;

/**
 * @OA\Schema(
 *     title="ChatMessageResource",
 *     description="Represents a chat message",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="The unique identifier of the chat message"
 *     ),
 *     @OA\Property(
 *         property="chat_room_id",
 *         type="integer",
 *         description="The ID of the chat room the message belongs to"
 *     ),
 *     @OA\Property(
 *         property="user",
 *         ref="#/components/schemas/UserResource",
 *         description="The user who sent the message"
 *     ),
 *     @OA\Property(
 *         property="user_id",
 *         type="integer",
 *         description="The ID of the user who sent the message"
 *     ),
 *     @OA\Property(
 *         property="message",
 *         type="string",
 *         description="The content of the chat message"
 *     ),
 *     @OA\Property(
 *         property="date",
 *         type="string",
 *         format="date-time",
 *         description="The date and time the message was sent"
 *     ),
 *     @OA\Property(
 *         property="updated_at",
 *         type="string",
 *         format="date-time",
 *         description="The date and time the message was last updated"
 *     )
 * )
 */
class ChatMessageResource extends BaseResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'chat_room_id' => $this->chat_room_id,
            'user' => new UserResource($this->whenLoaded('user')),
            'user_id' => $this->user_id,
            'message' => $this->message,
            'date' => $this->date,
            'updated_at' => $this->updated_at,
        ];
    }
}
