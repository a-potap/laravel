<?php

namespace App\Http\Resources\Api;

/**
 * @OA\Schema(
 *     title="PhotoResource",
 *     description="Photo Resource",
 *     @OA\Xml(
 *         name="PhotoResource"
 *     )
 * )
 */
class PhotoResource extends BaseResource
{

    /**
     * @OA\Property(
     *     title="Data",
     *     description="Data wrapper"
     * )
     *
     * @var \App\Models\Photo[]
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
