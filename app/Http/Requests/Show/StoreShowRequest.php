<?php

namespace App\Http\Requests;
namespace App\Http\Requests\Show;

use Illuminate\Foundation\Http\FormRequest;

class StoreShowRequest extends FormRequest
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
                'required',
                'exists:movies,id'
            ],

            'screen_id' => [
                'required',
                'exists:screens,id'
            ],

            'start_time' => [
                'required',
                'date'
            ],

            'end_time' => [
                'required',
                'date',
                'after:start_time'
            ],

            'language_id' => [
                'required',
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
                'nullable',
                'in:2D,3D,IMAX,4DX'
            ],

            'status' => [
                'nullable',
                'in:SCHEDULED,CANCELLED,COMPLETED'
            ],
        ];
    }
}