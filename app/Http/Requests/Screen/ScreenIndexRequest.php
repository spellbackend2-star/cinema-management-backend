<?php

namespace App\Http\Requests\Screen;

use Illuminate\Foundation\Http\FormRequest;

class ScreenIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // tighten if index should be role-restricted
    }

    public function rules(): array
    {
        return [
            'search'       => ['nullable', 'string', 'max:100'],
            'cinema_id'    => ['nullable', 'integer', 'exists:cinemas,id'],
            'screen_type'  => ['nullable', 'string', 'in:STANDARD,IMAX,3D,4DX,DOLBY_ATMOS,RECLINER_HALL'],
            'is_active'    => ['nullable', 'boolean'],
            'per_page'     => ['nullable', 'integer', 'min:1', 'max:100'],
            'page'         => ['nullable', 'integer', 'min:1'],
            'sort_by'      => ['nullable', 'in:id,name,capacity,created_at'],
            'sort_order'   => ['nullable', 'in:asc,desc'],
        ];
    }

    public function messages(): array
    {
        return [
            'cinema_id.exists'   => 'Selected cinema does not exist.',
            'screen_type.in'     => 'Invalid screen type selected.',
        ];
    }
}