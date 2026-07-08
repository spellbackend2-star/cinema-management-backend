<?php

namespace App\Services;

use App\Models\Genre;

class GenreService
{
    public function index()
    {
        return Genre::latest()->get();
    }

    public function store(array $data): Genre
    {
        return Genre::create($data);
    }

    public function update(Genre $genre, array $data): Genre
    {
        $genre->update($data);

        return $genre->fresh();
    }

    public function destroy(Genre $genre): void
    {
        $genre->delete();
    }
}