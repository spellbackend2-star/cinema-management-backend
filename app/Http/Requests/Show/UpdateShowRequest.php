<?php

namespace App\Http\Requests;
namespace App\Http\Requests\Show;
use Illuminate\Foundation\Http\FormRequest;

class UpdateShowRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'schedule_id' => [
                'nullable',
                'exists:show_schedules,id'
            ],

            'movie_id' => [
                'sometimes',
                'exists:movies,id'
            ],

            'screen_id' => [
                'sometimes',
                'exists:screens,id'
            ],

            'start_time' => [
                'sometimes',
                'date'
            ],

            'end_time' => [
                'sometimes',
                'date',
                'after:start_time'
            ],

            'language_id' => [
                'sometimes',
                'exists:languages,id'
            ],

            'booking_open_at' => [
                'nullable',
                'date',
                'before:start_time'
            ],

            'booking_close_at' => [
                'nullable',
                'date',
                'after:booking_open_at',
                'before:end_time'
            ],

            'format' => [
                'sometimes',
                'in:2D,3D,IMAX,4DX'
            ],

            'status' => [
                'sometimes',
                'in:SCHEDULED,CANCELLED,COMPLETED'
            ],
        ];
    }
}