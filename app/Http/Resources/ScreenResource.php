<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ScreenResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
             'cinema' => [
            'id' => $this->cinema->id,
            'name' => $this->cinema->name,
        ],
            'name' => $this->name,
            'screen_type' => $this->screen_type,
            'capacity' => $this->capacity,
            'sound_system' => $this->sound_system,
            'is_active' => (bool) $this->is_active,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

             'seat_categories' => SeatCategoryResource::collection(
            $this->whenLoaded('seatCategories')
        ),
        ];
        
    }
}
