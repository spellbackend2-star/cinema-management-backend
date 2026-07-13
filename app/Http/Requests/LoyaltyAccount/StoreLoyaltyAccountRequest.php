<?php

namespace App\Http\Requests\LoyaltyAccount;

use Illuminate\Foundation\Http\FormRequest;

class StoreLoyaltyAccountRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id|unique:loyalty_accounts,user_id',
            'points_balance' => 'nullable|integer|min:0',
            'tier' => 'required|in:SILVER,GOLD,PLATINUM',
        ];
    }
}