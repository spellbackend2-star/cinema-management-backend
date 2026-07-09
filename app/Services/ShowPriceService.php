<?php

namespace App\Services;

use App\Models\ShowPrice;
use App\Repositories\Interfaces\ShowPriceRepositoryInterface;

class ShowPriceService
{
    public function __construct(
        protected ShowPriceRepositoryInterface $repository
    ) {
    }

    public function index(array $filters = [])
    {
        return $this->repository->index($filters);
    }

    public function show(int $id): ShowPrice
    {
        return $this->repository->find($id);
    }

    public function create(array $data): ShowPrice
    {
        return $this->repository->create($data);
    }

    public function update(ShowPrice $showPrice, array $data): ShowPrice
    {
        return $this->repository->update($showPrice, $data);
    }

    public function delete(ShowPrice $showPrice): bool
    {
        return $this->repository->delete($showPrice);
    }
}