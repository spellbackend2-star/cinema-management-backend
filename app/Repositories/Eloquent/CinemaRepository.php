<?php

namespace App\Repositories\Eloquent;

use App\Models\Cinema;
use App\Repositories\BaseRepository;
use App\Repositories\Interfaces\CinemaRepositoryInterface;

class CinemaRepository extends BaseRepository implements CinemaRepositoryInterface
{
    public function index(array $filters = [])
    {
        $query = Cinema::query()->latest();

        $query = $this->applyFilter(
            $query,
            $filters,
            [
                'name',
                'address',
                'phone',
                'email',
            ]
        );

       

        return $query->paginate($query, $filters);
    }

    public function find(int $id)
    {
        return Cinema::findOrFail($id);
    }

    public function create(array $data)
    {
        return Cinema::create($data);
    }

    public function update(int $id, array $data)
    {
        $cinema = $this->find($id);

        $cinema->update($data);

        return $cinema->fresh();
    }

    public function delete(int $id)
    {
        return $this->find($id)->delete();
    }
}