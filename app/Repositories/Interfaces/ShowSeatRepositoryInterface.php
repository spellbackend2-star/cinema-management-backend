<?php

namespace App\Repositories\Interfaces;

use App\Models\ShowSeat;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface ShowSeatRepositoryInterface
{
    public function getAll(int $perPage = 10): LengthAwarePaginator;

    public function findById(int $id): ?ShowSeat;

    public function findByShowAndSeat(int $showId, int $seatId): ?ShowSeat;

    public function getByShow(int $showId): Collection;

    public function getAvailableByShow(int $showId): Collection;

    public function create(array $data): ShowSeat;

    public function update(ShowSeat $showSeat, array $data): bool;

    public function delete(ShowSeat $showSeat): bool;
     public function findForUpdate(int $id): ?ShowSeat;
    
}