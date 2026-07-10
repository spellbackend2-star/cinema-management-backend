<?php

namespace App\Http\Requests\ShowSchedule;

use Illuminate\Foundation\Http\FormRequest;

class StoreShowScheduleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'movie_id' => ['required', 'integer', 'exists:movies,id'],
            'screen_id' => ['required', 'integer', 'exists:screens,id'],
            'language_id' => ['required', 'integer', 'exists:languages,id'],

            'start_date' => ['required', 'date', 'after_or_equal:today'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'show_time' => ['required', 'date_format:H:i:s'],

            'days_of_week' => ['required', 'array', 'min:1'],
            'days_of_week.*' => ['integer', 'between:1,7'],

            'format' => ['required', 'string', 'in:2D,3D,IMAX,4DX'],

            'booking_opens_offset_min' => ['required', 'integer', 'min:0'],
            'booking_closes_offset_min' => ['required', 'integer', 'min:0'],

            'is_active' => ['sometimes', 'boolean'],

            'prices' => ['required', 'array', 'min:1'],
            'prices.*.category_id' => ['required', 'integer', 'exists:seat_categories,id', 'distinct'],
            'prices.*.base_price' => ['required', 'numeric', 'min:0'],
            'prices.*.tax_percent' => ['sometimes', 'numeric', 'min:0', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'prices.*.category_id.distinct' => 'Each seat category can only have one price entry per schedule.',
            'end_date.after_or_equal' => 'End date must be on or after the start date.',
            'days_of_week.*.between' => 'Days of week must use ISO values 1 (Monday) through 7 (Sunday).',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'created_by' => $this->user()?->id,
        ]);
    }
}