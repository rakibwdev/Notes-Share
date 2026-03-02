<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBrandUnitRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'brand_id' => 'sometimes|required|exists:brands,id',
            'unit_id' => 'sometimes|required|exists:units,id',
            'user_id' => 'sometimes|required|exists:users,id',
            'quantity' => 'sometimes|integer|min:1',
            'isEditable' => 'sometimes|boolean',
        ];
    }
}
