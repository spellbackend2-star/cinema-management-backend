<?php

namespace App\Http\Requests\Seats;

use App\Models\Seat;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSeatRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'screen_id'   => ['required', 'exists:screens,id'],
            'category_id' => ['required', 'exists:seat_categories,id'],
            'row_label'   => ['required', 'string', 'max:5'],
            'seat_number' => ['required', 'string', 'max:10'],
            'seat_label'  => ['nullable', 'string', 'max:30'],
            'seat_type'   => ['required', 'in:NORMAL,RECLINER,WHEELCHAIR,COUPLE'],
            'pos_x'       => ['nullable', 'integer'],
            'pos_y'       => ['nullable', 'integer'],
            'rotation'    => ['nullable', 'integer'],
            'width'       => ['nullable', 'integer'],
            'height'      => ['nullable', 'integer'],
            'is_active'   => ['nullable', 'boolean'],
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Check if seat already exists
            $exists = Seat::where('screen_id', $this->screen_id)
                ->where('row_label', $this->row_label)
                ->where('seat_number', $this->seat_number)
                ->exists();

            if ($exists) {
                $validator->errors()->add(
                    'seat_position', 
                    "A seat with row '{$this->row_label}' and number '{$this->seat_number}' already exists in this screen."
                );
            }
        });
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'screen_id.required' => 'Screen ID is required',
            'screen_id.exists' => 'Selected screen does not exist',
            'category_id.required' => 'Category ID is required',
            'category_id.exists' => 'Selected category does not exist',
            'row_label.required' => 'Row label is required',
            'seat_number.required' => 'Seat number is required',
            'seat_type.required' => 'Seat type is required',
            'seat_type.in' => 'Seat type must be NORMAL, RECLINER, WHEELCHAIR, or COUPLE',
        ];
    }
}