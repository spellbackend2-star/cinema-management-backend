<?php

namespace App\Http\Requests\ShowPrice;

use Illuminate\Foundation\Http\FormRequest;

class StoreShowPriceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'show_id' => [
                'required',
                'exists:shows,id',
            ],

            'category_id' => [
                'required',
                'exists:seat_categories,id',
            ],

            'base_price' => [
                'required',
                'numeric',
                'min:0',
            ],

            'tax_percent' => [
                'nullable',
                'numeric',
                'min:0',
                'max:100',
            ],
        ];
    }
}