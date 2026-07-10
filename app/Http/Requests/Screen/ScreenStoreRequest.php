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
                    fn($query) => $query->where('cinema_id', $this->input('cinema_id'))
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



            'status' => [
                'nullable',
                'in:active,inactive',
            ],

            'seat_categories' => [
                'required',
                'array',
                'min:1',
            ],

            'seat_categories.*.name' => [
                'required',
                'string',
            ],

            'seat_categories.*.seats.*.row_label'   => 'required|string|max:5',
            'seat_categories.*.seats.*.seat_number' => 'required|integer|min:1',
            'seat_categories.*.seats.*.seat_label'  => 'nullable|string|max:20',
            'seat_categories.*.seats.*.pos_x'       => 'nullable|integer|min:0',
            'seat_categories.*.seats.*.pos_y'       => 'nullable|integer|min:0',
            'seat_categories.*.image_icon' => 'nullable|string',
            'seat_categories.*.display_order' => 'nullable|integer',
            'seat_categories.*.seats' => [
                'required',
                'array',
                'min:1',
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
