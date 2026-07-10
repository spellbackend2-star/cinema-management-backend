<?php

namespace App\Repositories\Eloquent;

use App\Models\ShowSeat;
use App\Repositories\Interfaces\ShowSeatRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class ShowSeatRepository implements ShowSeatRepositoryInterface
{
    public function __construct(
        protected ShowSeat $model
    ) {}

    public function getAll(int $perPage = 10): LengthAwarePaginator
    {
        return $this->model
            ->with(['show', 'seat', 'locker'])
            ->paginate($perPage);
    }

    public function findById(int $id): ?ShowSeat
    {
        return $this->model
            ->with(['show', 'seat', 'locker'])
            ->find($id);
    }
    public function findForUpdate(int $id): ?ShowSeat
    {
        return $this->model
            ->where('id', $id)
            ->lockForUpdate()
            ->first();
    }

    public function findByShowAndSeat(int $showId, int $seatId): ?ShowSeat
    {
        return $this->model
            ->where('show_id', $showId)
            ->where('seat_id', $seatId)
            ->first();
    }

    public function getByShow(int $showId): Collection
    {
        return $this->model
            ->with(['seat'])
            ->where('show_id', $showId)
            ->get();
    }

    public function getAvailableByShow(int $showId): Collection
    {
        return $this->model
            ->with(['show', 'seat', 'lockedBy'])
            ->where('show_id', $showId)
            ->where('status', 'AVAILABLE')
            ->get();
    }

    public function create(array $data): ShowSeat
    {
        return $this->model->create($data);
    }

    public function update(ShowSeat $showSeat, array $data): bool
    {
        return $showSeat->update($data);
    }

    public function delete(ShowSeat $showSeat): bool
    {
        return $showSeat->delete();
    }
}
