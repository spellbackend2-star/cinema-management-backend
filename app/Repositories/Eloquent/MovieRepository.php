<?php

namespace App\Repositories\Eloquent;

use App\Models\Movie;
use App\Repositories\Interfaces\MovieRepositoryInterface;

class MovieRepository implements MovieRepositoryInterface
{

    public function index(array $filters = [])
    {
        $query = Movie::query()
            ->with([
                'genres',
                'languages',
                'people'
            ]);


        if (!empty($filters['search'])) {
            $query->where('title', 'LIKE', '%' . $filters['search'] . '%');
        }


        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }


        if (!empty($filters['release_date'])) {
            $query->whereDate(
                'release_date',
                $filters['release_date']
            );
        }


        return $query->latest()->paginate(
            $filters['per_page'] ?? 10
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