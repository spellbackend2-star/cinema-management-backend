<?php

namespace App\Http\Requests\Seats;

use Illuminate\Foundation\Http\FormRequest;

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
}