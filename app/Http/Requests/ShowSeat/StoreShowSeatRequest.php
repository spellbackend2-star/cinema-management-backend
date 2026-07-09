<?php

namespace App\Http\Requests\ShowSeat;

use Illuminate\Foundation\Http\FormRequest;

class StoreShowSeatRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'show_id' => 'required|exists:shows,id',
            'seat_id' => 'required|exists:seats,id',
            'status' => 'nullable|in:AVAILABLE,LOCKED,BOOKED,BLOCKED',
            'locked_by' => 'nullable|exists:users,id',
            'locked_until' => 'nullable|date',
            'price' => 'required|numeric|min:0',
        ];
    }
}