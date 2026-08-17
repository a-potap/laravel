<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\ResourceCollection;

/**
 * @OA\Schema(
 *     title="ChatRoomCollection",
 *     description="Paginated collection of chat rooms",
 *     @OA\Property(
 *         property="data",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/ChatRoomResource")
 *     ),
 *     @OA\Property(
 *         property="links",
 *         type="object",
 *         description="Pagination links"
 *     ),
 *     @OA\Property(
 *         property="meta",
 *         type="object",
 *         description="Pagination metadata"
 *     )
 * )
 */
class ChatRoomCollection extends ResourceCollection
{
    public function toArray($request): array
    {
        return [
            'data' => ChatRoomResource::collection($this->collection),
        ];
    }
}