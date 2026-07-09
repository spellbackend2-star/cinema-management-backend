<?php

namespace App\Http\Requests\ShowSchedule;

use Illuminate\Foundation\Http\FormRequest;

class UpdateShowScheduleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            'movie_id' => [
                'sometimes',
                'required',
                'exists:movies,id',
            ],

            'screen_id' => [
                'sometimes',
                'required',
                'exists:screens,id',
            ],

            'start_date' => [
                'sometimes',
                'required',
                'date',
            ],

            'end_date' => [
                'sometimes',
                'required',
                'date',
                'after_or_equal:start_date',
            ],

            'show_time' => [
                'sometimes',
                'required',
                'date_format:H:i',
            ],

            'days_of_week' => [
                'sometimes',
                'array',
            ],

            'days_of_week.*' => [
                'in:1,2,3,4,5,6,7',
            ],

            'language_id' => [
                'sometimes',
                'required',
                'exists:languages,id',
            ],

            'format' => [
                'sometimes',
                'required',
                'in:2D,3D,IMAX,4DX',
            ],

            'booking_opens_offset_min' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'booking_closes_offset_min' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'is_active' => [
                'nullable',
                'boolean',
            ],

            'created_by' => [
                'nullable',
                'exists:users,id',
            ],
        ];
    }
}