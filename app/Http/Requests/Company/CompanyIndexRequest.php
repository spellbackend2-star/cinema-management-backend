<?php

namespace App\Http\Requests\Company;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CompanyIndexRequest extends FormRequest
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
            'search' => 'nullable|string|max:150',
            'is_active' => 'nullable|boolean',
            'filter' => 'nullable|string|max:255',
            'sort_by' => 'nullable|in:id,name,email,created_at',
            'sort_order' => 'nullable|in:asc,desc',

            'per_page' => 'nullable|integer|min:1|max:100',
            'page' => 'nullable|integer|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'search.string' => 'The search term must be a valid string.',

            'is_active.boolean' => 'The active status must be true or false.',

            'sort_by.in' => 'The selected sort field is invalid.',
            'sort_order.in' => 'Sort order must be either asc or desc.',

            'per_page.integer' => 'Per page must be a valid number.',
            'per_page.min' => 'Per page must be at least 1.',
            'per_page.max' => 'Per page may not be greater than 100.',

            'page.integer' => 'Page must be a valid number.',
            'page.min' => 'Page must be at least 1.',
        ];
    }
}
