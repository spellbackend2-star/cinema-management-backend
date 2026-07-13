<?php

namespace App\Repositories\Eloquent;

use App\Models\LoyaltyAccount;
use App\Repositories\Interfaces\LoyaltyAccountRepositoryInterface;

class LoyaltyAccountRepository implements LoyaltyAccountRepositoryInterface
{
    public function getAll($perPage = 10)
    {
        return LoyaltyAccount::with('user')->paginate($perPage);
    }

    public function findById(int $id)
    {
        return LoyaltyAccount::with('user')->find($id);
    }

    public function findByUserId(int $userId)
    {
        return LoyaltyAccount::where('user_id', $userId)->first();
    }

    public function create(array $data)
    {
        return LoyaltyAccount::create($data);
    }

    public function update($account, array $data)
    {
        $account->update($data);

        return $account;
    }

    public function delete($account)
    {
        return $account->delete();
    }
}