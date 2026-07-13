<?php

namespace App\Services;

use App\Repositories\Interfaces\LoyaltyAccountRepositoryInterface;
use App\Repositories\Interfaces\LoyaltyTransactionRepositoryInterface;

class LoyaltyTransactionService
{
    public function __construct(
        protected LoyaltyTransactionRepositoryInterface $transactionRepository,
        protected LoyaltyAccountRepositoryInterface $accountRepository
    ) {}

    public function index(int $userId)
    {
        $account = $this->accountRepository->findByUserId($userId);

        if (!$account) {
            abort(404, 'Loyalty account not found.');
        }

        return $this->transactionRepository->getByAccount($account->id);
    }

    public function show(int $id)
    {
        $transaction = $this->transactionRepository->findById($id);

        if (!$transaction) {
            abort(404, 'Loyalty transaction not found.');
        }

        return $transaction;
    }
}