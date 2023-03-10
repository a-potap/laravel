<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
            'captcha' => 'required|captcha',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'blog_id' => $this->route()->parameter('blog'),
        ]);
    }
}
