<?php

namespace App\Repositories\Eloquent;

use App\Models\Show;
use App\Repositories\BaseRepository;
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


         // Search movie title
        $query = $this->applyFilter(
            $query,
            $filters,
            [
                'status'
            ]
        );


        // Exact filters
        $query = $this->applyExactFilters(
            $query,
            $filters,
            [
                'movie_id',
                'screen_id',
                'status'
            ]
        );


        // Date filter
        if (!empty($filters['date'])) {
            $query->whereDate(
                'start_time',
                $filters['date']
            );
        }


        return $this->paginate(
            $query->orderBy('start_time'),
            $filters
        );
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