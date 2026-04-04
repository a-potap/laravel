<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ChatRoomCollection extends ResourceCollection
{
    public function toArray($request): array
    {
        return [
            'data' => ChatRoomResource::collection($this->collection),
        ];
    }
}
