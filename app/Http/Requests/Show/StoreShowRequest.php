<?php

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

            // Show
            'movie_id' => [
                'required',
                'exists:movies,id',
            ],

            'screen_id' => [
                'required',
                'exists:screens,id',
            ],

            'start_time' => [
                'required',
                'date',
            ],

            'end_time' => [
                'required',
                'date',
                'after:start_time',
            ],

            'language_id' => [
                'required',
                'exists:languages,id',
            ],

            'booking_open_at' => [
                'nullable',
                'date',
                'before:start_time',
            ],

            'booking_close_at' => [
                'nullable',
                'date',
                'after:booking_open_at',
                'before:end_time',
            ],

            'format' => [
                'nullable',
                'in:2D,3D,IMAX,4DX',
            ],

            'status' => [
                'nullable',
                'in:SCHEDULED,CANCELLED,COMPLETED',
            ],

            /*
            |--------------------------------------------------------------------------
            | Show Prices
            |--------------------------------------------------------------------------
            */

            'prices' => [
                'nullable',
                'array',
            ],

            'prices.*.category_id' => [
                'required_with:prices',
                'exists:seat_categories,id',
            ],

            'prices.*.base_price' => [
                'required_with:prices',
                'numeric',
                'min:0',
            ],

            'prices.*.tax_percent' => [
                'nullable',
                'numeric',
                'min:0',
                'max:100',
            ],

            /*
            |--------------------------------------------------------------------------
            | Show Schedules
            |--------------------------------------------------------------------------
            */

            'schedules' => [
                'nullable',
                'array',
            ],

            'schedules.*.start_date' => [
                'required_with:schedules',
                'date',
            ],

            'schedules.*.end_date' => [
                'required_with:schedules',
                'date',
                'after_or_equal:schedules.*.start_date',
            ],

            'schedules.*.show_time' => [
                'required_with:schedules',
                'date_format:H:i',
            ],

            'schedules.*.days_of_week' => [
                'nullable',
                'array',
            ],

            'schedules.*.days_of_week.*' => [
                'in:1,2,3,4,5,6,7',
            ],

            'schedules.*.booking_opens_offset_min' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'schedules.*.booking_closes_offset_min' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'schedules.*.is_active' => [
                'nullable',
                'boolean',
            ],
        ];
    }
}
