<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShowSeatResource extends JsonResource
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

            'show' => [
                'id' => $this->show?->id,
            ],

            'seat' => [
                'id' => $this->seat?->id,
                'seat_number' => $this->seat?->seat_number,
                'row' => $this->seat?->row_label,
                'type' => $this->seat?->seat_type,
            ],

            'status' => $this->status,

            'locked_by' => $this->lockedBy ? [
                'id' => $this->lockedBy->id,
                'name' => $this->lockedBy->name,
            ] : null,

            'locked_until' => $this->locked_until,

            'price' => $this->price,

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}