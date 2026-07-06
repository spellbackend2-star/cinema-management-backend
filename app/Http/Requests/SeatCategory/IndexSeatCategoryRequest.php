<?php

namespace App\Http\Requests\SeatCategory;

use Illuminate\Foundation\Http\FormRequest;

class IndexSeatCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'screen_id' => 'nullable|exists:screens,id',
            'per_page' => 'nullable|integer|min:1|max:100',
        ];
    }
}