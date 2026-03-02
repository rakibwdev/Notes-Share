<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBookmarkRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => 'sometimes|required|exists:users,id',
            'brand_id' => 'sometimes|required|exists:brands,id',
            'unit_id' => 'sometimes|required|exists:units,id',
            'quantity' => 'sometimes|integer|min:1',
            'price' => 'nullable|numeric|min:0',
        ];
    }
}
