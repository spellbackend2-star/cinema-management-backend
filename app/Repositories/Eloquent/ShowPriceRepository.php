<?php

namespace App\Repositories\Eloquent;

use App\Models\ShowPrice;
use App\Repositories\Interfaces\ShowPriceRepositoryInterface;

class ShowPriceRepository implements ShowPriceRepositoryInterface
{
    public function index(array $filters = [])
    {
        return ShowPrice::with(['show', 'category'])->paginate(10);
    }

    public function find(int $id): ?ShowPrice
    {
        return ShowPrice::with(['show', 'category'])->findOrFail($id);
    }

    public function create(array $data): ShowPrice
    {
        return ShowPrice::create($data);
    }

    public function update(ShowPrice $showPrice, array $data): ShowPrice
    {
        $showPrice->update($data);

        return $showPrice->fresh();
    }

    public function delete(ShowPrice $showPrice): bool
    {
        return $showPrice->delete();
    }
}