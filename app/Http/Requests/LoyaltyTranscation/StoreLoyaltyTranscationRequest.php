<?php

namespace App\Http\Requests\LoyaltyTransaction;

use Illuminate\Foundation\Http\FormRequest;

class StoreLoyaltyTransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'loyalty_account_id' => ['required', 'exists:loyalty_accounts,id'],
            'booking_id' => ['nullable', 'exists:bookings,id'],
            'points' => ['required', 'integer'],
            'reason' => ['nullable', 'string', 'max:150'],
        ];
    }
}