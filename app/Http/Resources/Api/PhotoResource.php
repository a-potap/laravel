<?php

namespace App\Http\Resources\Api;

class PhotoResource extends BaseResource
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
            'date_create' => $this->date_create,
            'name' => $this->name,
            'description' => $this->description,
            'name_en' => $this->name_en,
            'description_en' => $this->description_en,
            'cover' => $this->getCover(),
        ];

        if($this->withExtraParams) {
            $fields['pictures'] = $this->getFiles();
        }

        return $fields;
    }
}
