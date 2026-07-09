<?php

namespace App\Repositories\Eloquent;

use App\Models\Show;
use App\Repositories\Interfaces\ShowRepositoryInterface;

class ShowRepository implements ShowRepositoryInterface{

    public function index(array $filters = [])
    {
        $query = Show::query()
            ->with([
                'movie',
                'screen',
                'language',
                'schedule'
            ]);


        if (!empty($filters['movie_id'])) {
            $query->where('movie_id', $filters['movie_id']);
        }


        if (!empty($filters['screen_id'])) {
            $query->where('screen_id', $filters['screen_id']);
        }


        if (!empty($filters['date'])) {
            $query->whereDate('start_time', $filters['date']);
        }


        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }


        return $query
            ->orderBy('start_time')
            ->paginate();
    }



    public function find(int $id)
    {
        return Show::with([
            'movie',
            'screen',
            'language',
            'schedule'
        ])
        ->findOrFail($id);
    }



    public function store(array $data)
    {
        return Show::create($data);
    }



    public function update(int $id, array $data)
    {
        $show = Show::findOrFail($id);

        $show->update($data);

        return $show;
    }



    public function delete(int $id)
    {
        $show = Show::findOrFail($id);

        return $show->delete();
    }
}