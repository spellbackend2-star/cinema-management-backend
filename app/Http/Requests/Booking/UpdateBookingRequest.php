<?php

namespace App\Http\Requests\Booking;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBookingRequest extends FormRequest
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
            'coupon_id' => [
                'nullable',
                'exists:coupons,id'
            ],


            'subtotal' => [
                'nullable',
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
                'nullable',
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


            'confirmed_at' => [
                'nullable',
                'date'
            ],


            'cancelled_at' => [
                'nullable',
                'date'
            ],


            /*
             | Optional seat update
             */
            'seats' => [
                'nullable',
                'array'
            ],


            'seats.*.show_seat_id' => [
                'required_with:seats',
                'exists:show_seats,id'
            ],


            'seats.*.price' => [
                'required_with:seats',
                'numeric',
                'min:0'
            ]

        ];
    }
}
