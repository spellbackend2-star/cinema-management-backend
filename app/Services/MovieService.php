<?php

namespace App\Services;

use App\Repositories\Interfaces\MovieRepositoryInterface;
use Illuminate\Support\Facades\Storage;

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
        if (isset($data['poster_url'])) {
            $data['poster_url'] = $data['poster_url']->store('movies/posters', 'public');
        }

        if (isset($data['banner_url'])) {
            $data['banner_url'] = $data['banner_url']->store('movies/banners', 'public');
        }
        $movie = $this->repository->create($data);


        if (array_key_exists('genres', $data)) {
            $movie->genres()->sync($data['genres']);
        }

        if (isset($data['languages'])) {

            $languages = [];

            foreach ($data['languages'] as $language) {
                $languages[$language['language_id']] = [
                    'is_original' => $language['is_original'] ?? false,
                ];
            }

            $movie->languages()->sync($languages);
        }
        if (isset($data['people'])) {

            $people = [];

            foreach ($data['people'] as $person) {
                $people[$person['person_id']] = [
                    'credit_type'   => $person['credit_type'],
                    'character_name' => $person['character_name'] ?? null,
                    'display_order' => $person['display_order'] ?? 0,
                ];
            }

            $movie->people()->sync($people);
        }

        return $movie->load('genres', 'languages', 'people');
    }


    public function show(int $id)
    {
        return $this->repository->findById($id);
    }



    public function update(int $id, array $data)
    {
        $movie = $this->repository->findById($id);

        if (isset($data['poster_url'])) {
            if ($movie->poster_url && Storage::disk('public')->exists($movie->poster_url)) {
                Storage::disk('public')->delete($movie->poster_url);
            }

            $data['poster_url'] = $data['poster_url']->store('movies/posters', 'public');
        }

        if (isset($data['banner_url'])) {
            if ($movie->banner_url && Storage::disk('public')->exists($movie->banner_url)) {
                Storage::disk('public')->delete($movie->banner_url);
            }

            $data['banner_url'] = $data['banner_url']->store('movies/banners', 'public');
        }

        $movie = $this->repository->update($id, $data);

        if (array_key_exists('genres', $data)) {
            $movie->genres()->sync($data['genres']);
        }
        if (isset($data['languages'])) {

            $languages = [];

            foreach ($data['languages'] as $language) {
                $languages[$language['language_id']] = [
                    'is_original' => $language['is_original'] ?? false,
                ];
            }

            $movie->languages()->sync($languages);
        }

        if (isset($data['people'])) {

            $people = [];

            foreach ($data['people'] as $person) {
                $people[$person['person_id']] = [
                    'credit_type'   => $person['credit_type'],
                    'character_name' => $person['character_name'] ?? null,
                    'display_order' => $person['display_order'] ?? 0,
                ];
            }

            $movie->people()->sync($people);
        }
        return $movie->load('genres', 'languages', 'people');
    }


    public function destroy(int $id)
    {
        return $this->repository->delete($id);
    }
}
