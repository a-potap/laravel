<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     title="Blog Comments Resource",
 *     description="Blog Comments Resource",
 *     @OA\Xml(
 *         name="CommentResource"
 *     )
 * )
 */
class CommentResource extends JsonResource
{
    /**
     * @OA\Property(
     *     title="Data",
     *     description="Data wrapper"
     * )
     *
     * @var \App\Models\Comment[]
     */
    private $data;
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user' => $this->iduser,
            'date' => $this->date,
            'text' => $this->text,
        ];
    }
}
