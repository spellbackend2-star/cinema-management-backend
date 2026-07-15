<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RefundResource extends JsonResource
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
            'payment_id' => $this->payment_id,
            'requestby' => $this->payment->booking->user->name,
            'contact' => $this->payment->booking->user->phone ?? null,


            'amount' => $this->amount,
            'reason' => $this->reason,
            'status' => $this->status,
            'processed_by' => new StaffResource($this->processedBy),
            'processed_at' => $this->processed_at,
            'admin_response' => $this->system_response,
            'created_at' => $this->created_at,
        ];
    }
}
