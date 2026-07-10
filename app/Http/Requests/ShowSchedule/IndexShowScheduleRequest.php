<?php

namespace App\Http\Requests\ShowSchedule;

use Illuminate\Foundation\Http\FormRequest;

class IndexShowScheduleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'movie_id' => ['sometimes', 'integer', 'exists:movies,id'],
            'screen_id' => ['sometimes', 'integer', 'exists:screens,id'],
            'language_id' => ['sometimes', 'integer', 'exists:languages,id'],
            'format' => ['sometimes', 'string', 'in:2D,3D,IMAX,4DX'],
            'is_active' => ['sometimes', 'boolean'],

            'date_from' => ['sometimes', 'date'],
            'date_to' => ['sometimes', 'date', 'after_or_equal:date_from'],

            'search' => ['sometimes', 'string', 'max:255'],

            'sort_by' => ['sometimes', 'string', 'in:start_date,end_date,show_time,created_at'],
            'sort_direction' => ['sometimes', 'string', 'in:asc,desc'],

            'per_page' => ['sometimes', 'integer', 'min:1', 'max:100'],
            'page' => ['sometimes', 'integer', 'min:1'],
        ];
    }
}