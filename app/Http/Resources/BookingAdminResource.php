<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingAdminResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [

            'id' => $this->id,

            'booking_reference' => $this->booking_reference,

            // Customer details
            'customer' => [
                'id' => $this->user?->id,
                'name' => $this->user?->name,
                'email' => $this->user?->email,
                'phone' => $this->user?->phone,
            ],



            'booked_by_staff_id' => $this->booked_by_user_id,
            'booked_by_name' => $this->bookedBy?->name,
            'booked_by_roles' => $this->bookedBy
                ? $this->bookedBy->getRoleNames()
                : [],

            // Show details
            'show' => [
                'id' => $this->show?->id,
                'movie' => $this->show?->movie?->title,
                'screen' => $this->show?->screen?->name,
                'start_time' => $this->show?->start_time,
                'end_time' => $this->show?->end_time,
            ],

            // Seats
            'seats' => $this->bookingSeats->map(function ($bookingSeat) {

                return [
                    'seat_id' => $bookingSeat->showSeat?->seat_id,
                    'seat_number' => $bookingSeat->showSeat?->seat?->seat_number,
                    'price' => $bookingSeat->showSeat?->price,
                ];
            }),

            // Amount
            'amount' => [
                'subtotal' => $this->subtotal,
                'tax' => $this->tax_amount,
                'discount' => $this->discount_amount,
                'convenience_fee' => $this->convenience_fee,
                'total' => $this->total_amount,
            ],


            // Payment details
            'payment' => $this->whenLoaded('payment', function () {

                return [
                    'id' => $this->payment->id,
                    'method' => $this->payment->payment_method,
                    'status' => $this->payment->status,
                    'transaction_id' => $this->payment->transaction_id,
                    'amount' => $this->payment->amount,
                ];
            }),


            'booking_status' => $this->status,

            'payment_status' => $this->payment_status,

            'booking_source' => $this->booking_source,

            'created_at' => $this->created_at,

            'updated_at' => $this->updated_at,
        ];
    }
}
