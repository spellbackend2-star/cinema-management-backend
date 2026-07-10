<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [

            'id' => $this->id,

            'booking_reference' => $this->booking_reference,


            'customer' => [
                'id' => $this->user?->id,
                'name' => $this->user?->name,
                'email' => $this->user?->email,
            ],


            'booked_by' => $this->bookedBy ? [
                'id' => $this->bookedBy->id,
                'name' => $this->bookedBy->name,
                'email' => $this->bookedBy->email,
            ] : null,


            'show' => [
                'id' => $this->show?->id,
                'movie' => $this->show?->movie?->title,
                'start_time' => $this->show?->start_time,
                'end_time' => $this->show?->end_time,
            ],


            // 'seats' => BookingSeatResource::collection(
            //     $this->whenLoaded('seats')
            // ),


            'pricing' => [

                'subtotal' => $this->subtotal,

                'tax_amount' => $this->tax_amount,

                'discount_amount' => $this->discount_amount,

                'convenience_fee' => $this->convenience_fee,

                'total_amount' => $this->total_amount,

            ],


            'status' => $this->status,

            'payment_status' => $this->payment_status,

            'booking_source' => $this->booking_source,


            'coupon' => $this->coupon ? [

                'id' => $this->coupon->id,

                'code' => $this->coupon->code,

            ] : null,


            // 'payment' => new PaymentResource(
            //     $this->whenLoaded('payment')
            // ),


            'expires_at' => $this->expires_at,

            'confirmed_at' => $this->confirmed_at,

            'cancelled_at' => $this->cancelled_at,


            'created_at' => $this->created_at,

        ];
    }
}