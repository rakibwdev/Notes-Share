<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'order_id' => 'sometimes|required|exists:orders,id',
            'brand_id' => 'sometimes|required|exists:brands,id',
            'unit_id' => 'sometimes|required|exists:units,id',
            'quantity' => 'sometimes|required|integer|min:1',
        ];
    }
}
