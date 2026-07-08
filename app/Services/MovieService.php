<?php

namespace App\Services;

use App\Repositories\Interfaces\MovieRepositoryInterface;

class MovieService
{

    public function __construct(
        protected MovieRepositoryInterface $repository
    ) {}



    public function index(array $filters = [])
    {
        return $this->repository->index($filters);
    }



    public function store(array $data)
    {
        $movie = $this->repository->create($data);


        if(isset($data['genre_ids'])) {
            $movie->genres()->sync($data['genre_ids']);
        }


        if(isset($data['language_ids'])) {
            $movie->languages()->sync($data['language_ids']);
        }


        return $movie;
    }



    public function show(int $id)
    {
        return $this->repository->findById($id);
    }



    public function update(int $id, array $data)
    {
        $movie = $this->repository->update($id, $data);


        if(isset($data['genre_ids'])) {
            $movie->genres()->sync($data['genre_ids']);
        }


        if(isset($data['language_ids'])) {
            $movie->languages()->sync($data['language_ids']);
        }


        return $movie;
    }



    public function destroy(int $id)
    {
        return $this->repository->delete($id);
    }
}