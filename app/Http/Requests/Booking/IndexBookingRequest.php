<?php

namespace App\Http\Requests\Booking;

use Illuminate\Foundation\Http\FormRequest;

class IndexBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'customer_id' => [
                'nullable',
                'exists:users,id',
            ],
            'show_id' => [
                'nullable',
                'exists:shows,id'
            ],


            'show_seat_ids' => [
                'nullable',
                'array',
                'min:1'
            ],


            'show_seat_ids.*' => [
                'required',
                'exists:show_seats,id'
            ],


            'coupon_id' => [
                'nullable',
                'exists:coupons,id'
            ],


            'booking_source' => [
                'nullable',
                'in:WEB,APP,COUNTER,KIOSK'
            ]

        ];
    }
}
