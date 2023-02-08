<?php

namespace App\Http\Resources\Api;

class BlogResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $fields = [
            'id' => $this->id,
            'date' => $this->date,
            'title' => $this->title,
            'title_en' => $this->title_en,
        ];

        if($this->withExtraParams) {
            $fields = array_merge($fields, [
                'text' => $this->text,
                'text_en' => $this->text_en,
                'comments' => CommentResource::collection($this->comments),
            ]);
        }

        return $fields;
    }
}
