<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SeatResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            

            'category_id' => $this->category_id,

            'category' => $this->whenLoaded('category', function () {
                return [
                    'id' => $this->category->id,
                    'name' => $this->category->name,
                ];
            }),

            'row_label' => $this->row_label,
            'seat_number' => $this->seat_number,
            'seat_label' => $this->seat_label,

            'seat_type' => $this->seat_type,

            'pos_x' => $this->pos_x,
            'pos_y' => $this->pos_y,

            'rotation' => $this->rotation,
            'width' => $this->width,
            'height' => $this->height,

            'is_active' => (bool) $this->is_active,

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}