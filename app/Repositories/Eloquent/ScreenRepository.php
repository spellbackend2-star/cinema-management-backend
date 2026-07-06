<?php

namespace App\Repositories\Eloquent;

use App\Models\Screen;
use App\Repositories\BaseRepository;
use App\Repositories\Interfaces\ScreenRepositoryInterface;

class ScreenRepository extends BaseRepository implements ScreenRepositoryInterface
{
    public function index(array $filters = [])
    {
        $query = Screen::query()->latest();

        // Search
        $query = $this->applyFilter(
            $query,
            $filters,
            [
                'name',
                'screen_type',
               
            ]
        );
        $query = $this->applyExactFilters(
            $query,
            $filters,
            [
                'cinema_id',
                'name',
                'screen_type',
                'is_active',
            ]
        );

        return $this->paginate($query, $filters);
    }
    public function find(int $id)
    {
        return Screen::findOrFail($id);
    }

    public function create(array $data)
    {
        return Screen::create($data);
    }

    public function update(int $id, array $data)
    {
        $screen = $this->find($id);

        $screen->update($data);

        return $screen->fresh();
    }

    public function delete(int $id)
    {
        return $this->find($id)->delete();
    }
}
