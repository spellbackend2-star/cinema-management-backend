<?php

namespace App\Repositories\Interfaces;

use App\Models\LoyaltyTransaction;
use Illuminate\Database\Eloquent\Collection;

interface LoyaltyTransactionRepositoryInterface
{
    public function getAll(): Collection;

    public function findById(int $id): ?LoyaltyTransaction;

    public function getByAccount(int $loyaltyAccountId): Collection;

    public function create(array $data): LoyaltyTransaction;

    public function update(LoyaltyTransaction $transaction, array $data): bool;

    public function delete(LoyaltyTransaction $transaction): bool;
}