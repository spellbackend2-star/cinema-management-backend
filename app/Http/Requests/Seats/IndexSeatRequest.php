<?php

namespace App\Http\Requests\Seats;

use Illuminate\Foundation\Http\FormRequest;

class IndexSeatRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'screen_id' => 'nullable|exists:screens,id',
            'category_id' => 'nullable|exists:seat_categories,id',
            'per_page' => 'nullable|integer|min:1|max:100'
        ];
    }
}
