<?php

namespace App\Repositories\Interfaces;

interface LoyaltyAccountRepositoryInterface
{
    public function getAll($perPage = 10);

    public function findById(int $id);

    public function findByUserId(int $userId);

    public function create(array $data);

    public function update($account, array $data);

    public function delete($account);
}