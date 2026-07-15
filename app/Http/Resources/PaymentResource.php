<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this['payment']->id,
            'amount' => $this['payment']->amount,
            'currency' => $this['payment']->currency,
            'payment_method' => $this['payment']->payment_method,
            'status' => $this['payment']->status,
            'transaction_id' => $this['payment']->transaction_id,
            'payment_url' => $this['gateway']['payment_url'] ?? null,
            'pidx' => $this['gateway']['pidx'] ?? null,

              'params' => $this['gateway']['params'] ?? null,
        ];
    }
}