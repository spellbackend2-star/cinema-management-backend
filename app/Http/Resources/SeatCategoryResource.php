<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SeatCategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'screen_id' => $this->screen_id,

            'screen' => $this->whenLoaded('screen', function () {
                return [
                    'id' => $this->screen->id,
                    'name' => $this->screen->name,
                ];
            }),

            'name' => $this->name,
            'image_icon' => $this->image_icon,
            'display_order' => $this->display_order,

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}