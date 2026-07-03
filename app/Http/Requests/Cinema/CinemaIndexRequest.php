<?php

namespace App\Http\Requests\Cinema;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CinemaIndexRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'filter' => 'nullable|string|max:255',
            'per_page' => 'nullable|integer|min:1|max:100',
        ];
    }

    public function messages(): array
    {
        return [
            'filter.string' => 'The filter must be a valid string.',
            'per_page.integer' => 'Per page must be a valid number.',
            'per_page.min' => 'Per page must be at least 1.',
            'per_page.max' => 'Per page may not be greater than 100.',
        ];
    }
}