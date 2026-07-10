<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


class BookingSeatResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [

            'id' => $this->id,


            'seat' => [

                'show_seat_id' => $this->show_seat_id,

                'seat_number' => 
                    $this->showSeat?->seat?->seat_number,

                'row' =>
                    $this->showSeat?->seat?->row,

            ],


            'price' => $this->price,


            'is_active' => (bool)$this->is_active,


        ];
    }

}