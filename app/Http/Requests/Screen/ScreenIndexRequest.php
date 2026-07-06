<?php

namespace App\Http\Requests\Screen;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ScreenIndexRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'cinema_id' => [
                'nullable',
                'exists:cinemas,id',
            ],

            'screen_type' => [
                'nullable',
                Rule::in([
                    'STANDARD',
                    'IMAX',
                    '3D',
                    '4DX',
                    'DOLBY_ATMOS',
                    'RECLINER_HALL',
                ]),
            ],

            'is_active' => [
                'nullable',
                'boolean',
            ],

            'filter' => [
                'nullable',
                'string',
                'max:100',
            ],

            'per_page' => [
                'nullable',
                'integer',
                'min:1',
                'max:100',
            ],
        ];
    }
}