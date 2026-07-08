<?php

namespace App\Http\Requests\Language;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateLanguageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:50',
                Rule::unique('languages')->ignore($this->language),
            ],

            'iso_code' => [
                'nullable',
                'string',
                'max:5',
                Rule::unique('languages')->ignore($this->language),
            ],
        ];
    }
}