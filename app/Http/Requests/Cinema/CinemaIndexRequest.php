<?php

namespace App\Http\Requests\Cinema;

use Illuminate\Foundation\Http\FormRequest;

class CinemaIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'company_id' => 'nullable|exists:companies,id',
            'city'       => 'nullable|string|max:100',
            'country'    => 'nullable|string|max:100',
            'is_active'  => 'nullable|boolean',
            'search'     => 'nullable|string|max:150',
            'per_page'   => 'nullable|integer|min:1|max:100',
        ];
    }
}