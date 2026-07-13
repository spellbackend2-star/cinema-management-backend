<?php

namespace App\Repositories\Eloquent;

use App\Models\LoyaltyTransaction;
use App\Repositories\Interfaces\LoyaltyTransactionRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class LoyaltyTransactionRepository implements LoyaltyTransactionRepositoryInterface
{
    public function getAll(): Collection
    {
        return LoyaltyTransaction::latest()->get();
    }

    public function findById(int $id): ?LoyaltyTransaction
    {
        return LoyaltyTransaction::find($id);
    }

    public function getByAccount(int $loyaltyAccountId): Collection
    {
        return LoyaltyTransaction::where('loyalty_account_id', $loyaltyAccountId)
            ->latest()
            ->get();
    }

    public function create(array $data): LoyaltyTransaction
    {
        return LoyaltyTransaction::create($data);
    }

    public function update(LoyaltyTransaction $transaction, array $data): bool
    {
        return $transaction->update($data);
    }

    public function delete(LoyaltyTransaction $transaction): bool
    {
        return $transaction->delete();
    }
}