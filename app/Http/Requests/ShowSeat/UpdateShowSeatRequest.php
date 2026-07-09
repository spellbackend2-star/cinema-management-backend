<?php

namespace App\Http\Requests\ShowSeat;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateShowSeatRequest extends FormRequest
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
             'show_id' => 'sometimes|exists:shows,id',
            'seat_id' => 'sometimes|exists:seats,id',
            'status' => 'nullable|in:AVAILABLE,LOCKED,BOOKED,BLOCKED',
            'locked_by' => 'nullable|exists:users,id',
            'locked_until' => 'nullable|date',
            'price' => 'sometimes|numeric|min:0',
        ];
        
    }
}
