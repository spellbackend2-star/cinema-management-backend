<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

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
             'image_icon' => $this->image_icon
            ? asset('storage/' . $this->image_icon)
            : null,
            'seats' => SeatResource::collection(
                $this->whenLoaded('seats')
            ),

        ];
    }
}
