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

            'booking_reference' => [
                'nullable',
                'string',
            ],


            'user_id' => [
                'nullable',
                'integer',
                'exists:users,id',
            ],


            'show_id' => [
                'nullable',
                'integer',
                'exists:shows,id',
            ],


            'status' => [
                'nullable',
                'in:pending,confirmed,cancelled,expired',
            ],


            'payment_status' => [
                'nullable',
                'in:pending,paid,failed,refunded',
            ],


            'booking_source' => [
                'nullable',
                'in:online,staff',
            ],


            'date_from' => [
                'nullable',
                'date',
            ],


            'date_to' => [
                'nullable',
                'date',
                'after_or_equal:date_from',
            ],


            'per_page' => [
                'nullable',
                'integer',
                'min:1',
                'max:100',
            ],

        ];
    }
}