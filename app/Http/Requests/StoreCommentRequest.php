<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *      title="Store Comment request",
 *      description="Store comment request body data",
 *      type="object",
 *      required={"name"},
 *     @OA\Xml(
 *         name="StoreCommentRequest"
 *     ),
 *     @OA\Property(
 *          property="iduser",
 *          title="User name",
 *          description="User name or nickname",
 *          example="Guest",
 *          type="string"
 *      ),
 *     @OA\Property(
 *          property="text",
 *          title="Comment text",
 *          description="Comment or message from user",
 *          example="Some comment text",
 *          type="string"
 *      )
 * )
 */
class StoreCommentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'blog_id' => 'required|integer',
            'iduser' => 'required',
            'text' => 'required',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'blog_id' => $this->route()->parameter('blog'),
        ]);
    }
}
