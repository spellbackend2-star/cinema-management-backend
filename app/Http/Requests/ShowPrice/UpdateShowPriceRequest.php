<?php

namespace App\Http\Requests\ShowPrice;

use Illuminate\Foundation\Http\FormRequest;

class UpdateShowPriceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'show_id' => [
                'sometimes',
                'exists:shows,id',
            ],

            'category_id' => [
                'sometimes',
                'exists:seat_categories,id',
            ],

            'base_price' => [
                'sometimes',
                'numeric',
                'min:0',
            ],

            'tax_percent' => [
                'sometimes',
                'numeric',
                'min:0',
                'max:100',
            ],
        ];
    }
}