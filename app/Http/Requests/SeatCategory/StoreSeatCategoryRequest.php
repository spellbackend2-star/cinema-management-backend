<?php

namespace App\Http\Requests\SeatCategory;

use Illuminate\Foundation\Http\FormRequest;

class StoreSeatCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'screen_id' => 'required|exists:screens,id',
            'name' => 'required|string|max:50',
            'image_icon' => 'nullable|string|max:255',
            'display_order' => 'nullable|integer|min:0|max:255',
        ];
    }
}