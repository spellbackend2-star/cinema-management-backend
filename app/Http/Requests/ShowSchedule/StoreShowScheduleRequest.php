<?php

namespace App\Http\Requests\ShowSchedule;

use Illuminate\Foundation\Http\FormRequest;

class StoreShowScheduleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation rules.
     */
    public function rules(): array
    {
        return [

            'movie_id' => [
                'required',
                'exists:movies,id',
            ],

            'screen_id' => [
                'required',
                'exists:screens,id',
            ],

            'start_date' => [
                'required',
                'date',
            ],

            'end_date' => [
                'required',
                'date',
                'after_or_equal:start_date',
            ],

            'show_time' => [
                'required',
                'date_format:H:i',
            ],

            'days_of_week' => [
                'nullable',
                'array',
            ],

            'days_of_week.*' => [
                'in:1,2,3,4,5,6,7',
            ],

            'language_id' => [
                'required',
                'exists:languages,id',
            ],

            'format' => [
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


    /**
     * Custom messages.
     */
    public function messages(): array
    {
        return [

            'movie_id.exists' => 'Selected movie does not exist.',

            'screen_id.exists' => 'Selected screen does not exist.',

            'language_id.exists' => 'Selected language does not exist.',

            'end_date.after_or_equal' => 
                'End date must be greater than or equal to start date.',

            'show_time.date_format' =>
                'Show time must be in HH:MM format.',

            'days_of_week.*.in' =>
                'Days of week must contain values from 1 to 7.',

            'format.in' =>
                'Invalid show format selected.',
        ];
    }
}