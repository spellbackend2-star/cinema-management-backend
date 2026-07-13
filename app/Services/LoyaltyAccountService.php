<?php

namespace App\Services;

use App\Models\LoyaltyAccount;
use App\Models\LoyaltyTransaction;
use Illuminate\Support\Facades\DB;

class LoyaltyAccountService
{
    public function account(int $userId)
    {
        return LoyaltyAccount::where('user_id', $userId)->firstOrFail();
    }

    public function history(int $userId)
    {
        $account = $this->account($userId);

        return LoyaltyTransaction::where(
            'loyalty_account_id',
            $account->id
        )->latest()->paginate();
    }

    public function redeem(int $userId, int $points)
    {
        return DB::transaction(function () use ($userId, $points) {

            $account = LoyaltyAccount::where('user_id', $userId)
                ->lockForUpdate()
                ->firstOrFail();

            if ($account->points_balance < $points) {
                throw new \Exception('Insufficient loyalty points.');
            }

            $account->decrement('points_balance', $points);

            LoyaltyTransaction::create([
                'loyalty_account_id' => $account->id,
                'booking_id'         => null,
                'points'             => -$points,
                'reason'             => 'Points redeemed',
            ]);

            return $account->fresh();
        });
    }
}
