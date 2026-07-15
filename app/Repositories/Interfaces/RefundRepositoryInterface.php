<?php

namespace App\Repositories\Interfaces;

use App\Models\Refund;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface RefundRepositoryInterface
{
    public function getAll(array $filters = []): LengthAwarePaginator;

    public function findById(int $id): ?Refund;

    public function create(array $data): Refund;

    public function update(Refund $refund, array $data): Refund;

    public function delete(Refund $refund): bool;
}