<?php

namespace App\Http\Requests\Seats;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSeatRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'screen_id' => 'sometimes|exists:screens,id',
            'category_id' => 'sometimes|exists:seat_categories,id',
            'row_label' => 'sometimes|string|max:5',
            'seat_number' => 'sometimes|string|max:10',
            'seat_label' => 'nullable|string|max:30',
            'seat_type' => 'sometimes|in:NORMAL,RECLINER,WHEELCHAIR,COUPLE',
            'pos_x' => 'nullable|integer',
            'pos_y' => 'nullable|integer',
            'rotation' => 'nullable|integer',
            'width' => 'nullable|integer|min:1',
            'height' => 'nullable|integer|min:1',
            'is_active' => 'nullable|boolean',
        ];
    }
}
