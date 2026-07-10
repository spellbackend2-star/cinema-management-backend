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

            'user_id' => [
                'required',
                'exists:users,id'
            ],

            'booked_by_user_id' => [
                'nullable',
                'exists:users,id'
            ],

            'show_id' => [
                'required',
                'exists:shows,id'
            ],

            'coupon_id' => [
                'nullable',
                'exists:coupons,id'
            ],


            'subtotal' => [
                'required',
                'numeric',
                'min:0'
            ],

            'tax_amount' => [
                'nullable',
                'numeric',
                'min:0'
            ],

            'discount_amount' => [
                'nullable',
                'numeric',
                'min:0'
            ],

            'convenience_fee' => [
                'nullable',
                'numeric',
                'min:0'
            ],

            'total_amount' => [
                'required',
                'numeric',
                'min:0'
            ],


            'status' => [
                'nullable',
                'in:PENDING,CONFIRMED,CANCELLED,EXPIRED'
            ],


            'payment_status' => [
                'nullable',
                'in:UNPAID,PAID,PARTIALLY_REFUNDED,REFUNDED'
            ],


            'booking_source' => [
                'nullable',
                'in:WEB,APP,COUNTER,KIOSK'
            ],


            'expires_at' => [
                'nullable',
                'date'
            ],


            /*
             | Booking seats
             */
            'seats' => [
                'required',
                'array',
                'min:1'
            ],

            'seats.*.show_seat_id' => [
                'required',
                'exists:show_seats,id'
            ],

            'seats.*.price' => [
                'required',
                'numeric',
                'min:0'
            ]

        ];
    }
}