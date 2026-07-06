<?php

namespace App\Http\Requests\Screen;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ScreenStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'cinema_id' => [
                'required',
                'integer',
                'exists:cinemas,id',
            ],

            'name' => [
                'required',
                'string',
                'max:50',
                Rule::unique('screens', 'name')->where(
                    fn ($query) => $query->where('cinema_id', $this->input('cinema_id'))
                ),
            ],

            'screen_type' => [
                'required',
                'string',
                Rule::in(['STANDARD', 'IMAX', '3D', '4DX', 'DOLBY_ATMOS', 'RECLINER_HALL']),
            ],

            'capacity' => [
                'required',
                'integer',
                'min:1',
                'max:2000',
            ],

            'sound_system' => [
                'nullable',
                'string',
                'max:50',
            ],

            'is_active' => [
                'boolean',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'cinema_id.exists' => 'Selected cinema does not exist.',
            'name.unique' => 'A screen with this name already exists in this cinema.',
            'screen_type.in' => 'Invalid screen type selected.',
            'capacity.max' => 'Capacity seems unrealistically high — please verify.',
        ];
    }
}