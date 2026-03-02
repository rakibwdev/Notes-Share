<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => 'sometimes|required|exists:users,id',
            'date' => 'sometimes|required|date',
            'time' => 'sometimes|required|string',
            'total' => 'sometimes|required|numeric|min:0',
        ];
    }
}
