<?php

namespace App\Http\Requests\Seats;

use App\Models\Seat;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSeatRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'screen_id'   => ['sometimes', 'exists:screens,id'],
            'category_id' => ['sometimes', 'exists:seat_categories,id'],
            'row_label'   => ['sometimes', 'string', 'max:5'],
            'seat_number' => ['sometimes', 'string', 'max:10'],
            'seat_label'  => ['nullable', 'string', 'max:30'],
            'seat_type'   => ['sometimes', 'in:NORMAL,RECLINER,WHEELCHAIR,COUPLE'],
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
            // Only check if row_label or seat_number is being updated
            if ($this->has('row_label') || $this->has('seat_number')) {
                $seatId = $this->route('seat') ?? $this->route('id');
                
                // Check if another seat has this position
                $exists = Seat::where('screen_id', $this->screen_id ?? $this->seat->screen_id)
                    ->where('row_label', $this->row_label ?? $this->seat->row_label)
                    ->where('seat_number', $this->seat_number ?? $this->seat->seat_number)
                    ->where('id', '!=', $seatId)
                    ->exists();

                if ($exists) {
                    $rowLabel = $this->row_label ?? $this->seat->row_label;
                    $seatNumber = $this->seat_number ?? $this->seat->seat_number;
                    
                    $validator->errors()->add(
                        'seat_position', 
                        "A seat with row '{$rowLabel}' and number '{$seatNumber}' already exists in this screen."
                    );
                }
            }
        });
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'screen_id.exists' => 'Selected screen does not exist',
            'category_id.exists' => 'Selected category does not exist',
            'seat_type.in' => 'Seat type must be NORMAL, RECLINER, WHEELCHAIR, or COUPLE',
        ];
    }
}