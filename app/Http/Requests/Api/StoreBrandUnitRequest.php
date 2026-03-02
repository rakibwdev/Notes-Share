<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class StoreBrandUnitRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'brand_id' => 'required|exists:brands,id',
            'unit_id' => 'required|exists:units,id',
            'user_id' => 'required|exists:users,id',
            'quantity' => 'sometimes|integer|min:1',
            'isEditable' => 'sometimes|boolean',
        ];
    }
}
