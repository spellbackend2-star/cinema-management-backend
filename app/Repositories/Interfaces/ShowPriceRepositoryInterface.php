<?php

namespace App\Repositories\Interfaces;

use App\Models\ShowPrice;

interface ShowPriceRepositoryInterface
{
    public function index(array $filters = []);

    public function find(int $id): ?ShowPrice;

    public function create(array $data): ShowPrice;

    public function update(ShowPrice $showPrice, array $data): ShowPrice;

    public function delete(ShowPrice $showPrice): bool;
}