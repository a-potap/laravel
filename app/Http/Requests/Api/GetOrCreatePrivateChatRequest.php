<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class GetOrCreatePrivateChatRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'recipient_id' => 'required|exists:users,id|different:' . auth()->id(),
        ];
    }

    public function messages(): array
    {
        return [
            'recipient_id.different' => 'Cannot create a chat with yourself.',
        ];
    }
}
