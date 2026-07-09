<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShowScheduleResource extends JsonResource
{
    /**
     * Transform resource into an array.
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

            'start_date' => $this->start_date?->format('Y-m-d'),

            'end_date' => $this->end_date?->format('Y-m-d'),

            'show_time' => $this->show_time
                ? $this->show_time->format('H:i')
                : null,

            'days_of_week' => $this->days_of_week
                ? explode(',', $this->days_of_week)
                : [],

            'format' => $this->format,

            'booking' => [
                'opens_before_minutes' =>
                    $this->booking_opens_offset_min,

                'closes_before_minutes' =>
                    $this->booking_closes_offset_min,
            ],

            'is_active' => (bool) $this->is_active,

            'created_by' => $this->created_by,

            'shows_count' => $this->whenCounted('shows'),

            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),

            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}