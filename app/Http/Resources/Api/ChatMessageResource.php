<?php

namespace App\Http\Resources\Api;

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
