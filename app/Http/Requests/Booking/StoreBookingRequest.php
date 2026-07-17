<?php

namespace App\Http\Requests\Booking;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookingRequest extends FormRequest
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
                'required',
                'exists:shows,id'
            ],


            'show_seat_ids' => [
                'required',
                'array',
                'min:1'
            ],
            'payment_method' => [
                'required',
                'in:CASH,KHALTI,ESEWA'
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
