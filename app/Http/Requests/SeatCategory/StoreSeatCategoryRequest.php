<?php

namespace App\Http\Requests\SeatCategory;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'name' => [
                'required',
                Rule::unique('seat_categories')
                    ->where(fn($query) => $query->where('screen_id', $this->screen_id)),
            ],
            'image_icon' => 'nullable|string|max:255',
            'display_order' => 'nullable|integer|min:0|max:255',
        ];
    }
}
