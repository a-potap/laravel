<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ChatMessageCollection extends ResourceCollection
{
    public function toArray($request): array
    {
        return [
            'data' => ChatMessageResource::collection($this->collection),
        ];
    }
}
