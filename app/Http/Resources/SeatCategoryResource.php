<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SeatCategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request)
{
    return [

        'id' => $this->id,

        'name' => $this->name,


        'seats' => SeatResource::collection(
            $this->whenLoaded('seats')
        ),

    ];
}
}