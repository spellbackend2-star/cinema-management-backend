<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShowResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [

            'id' => $this->id,

            'movie' => [
                'id' => $this->movie?->id,
                'title' => $this->movie?->title,
            ],

            'screen' => [
                'id' => $this->screen?->id,
                'name' => $this->screen?->name,
            ],

            'language' => [
                'id' => $this->language?->id,
                'name' => $this->language?->name,
            ],

            'schedule' => [
                'id' => $this->schedule?->id,
            ],
        
            

            'timing' => [
                'start_time' => $this->start_time?->format('Y-m-d H:i:s'),
                'end_time' => $this->end_time?->format('Y-m-d H:i:s'),
                'show_date' => $this->show_date,
            ],

            'booking' => [
                'open_at' => $this->booking_open_at?->format('Y-m-d H:i:s'),
                'close_at' => $this->booking_close_at?->format('Y-m-d H:i:s'),
            ],

            'format' => $this->format,

            'status' => $this->status,

            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),

        ];
    }
}