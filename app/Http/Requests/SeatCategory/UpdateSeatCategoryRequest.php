<?php

namespace App\Http\Requests\SeatCategory;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSeatCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'sometimes|string|max:50',
            'image_icon' => 'nullable|string|max:255',
            'display_order' => 'nullable|integer|min:0|max:255',
        ];
    }
}