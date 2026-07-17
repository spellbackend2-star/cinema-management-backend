<?php

namespace App\Repositories\Eloquent;

use App\Models\Movie;
use App\Repositories\BaseRepository;
use App\Repositories\Interfaces\MovieRepositoryInterface;

class MovieRepository extends BaseRepository  implements MovieRepositoryInterface
{

    public function index(array $filters = [])
    {
        $query = Movie::query()
            ->with([
                'genres',
                'languages',
                'people'
            ]);


        // Search filter
        $query = $this->applyFilter(
            $query,
            $filters,
            [
                'title',
                'description'
            ]
        );


        // Exact filters
        $query = $this->applyExactFilters(
            $query,
            $filters,
            [
                'status'
            ]
        );


        // Release date filter
        if (!empty($filters['release_date'])) {
            $query->whereDate(
                'release_date',
                $filters['release_date']
            );
        }


        return $this->paginate(
            $query->latest(),
            $filters
        );
    }



    public function findById(int $id)
    {
        return Movie::with([
            'genres',
            'languages',
            'people'
        ])->findOrFail($id);
    }



    public function create(array $data)
    {

        return Movie::create($data);
    }



    public function update(int $id, array $data)
    {
        $movie = Movie::findOrFail($id);

        $movie->update($data);

        return $movie;
    }



    public function delete(int $id)
    {
        return Movie::findOrFail($id)->delete();
    }
}
