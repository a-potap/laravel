<?php

namespace App\Http\Resources\Api;

class ChatRoomResource extends BaseResource
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
