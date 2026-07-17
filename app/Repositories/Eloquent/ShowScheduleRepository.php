<?php

namespace App\Repositories\Eloquent;

use App\Models\ShowSchedule;
use App\Repositories\Interfaces\ShowScheduleRepositoryInterface;

class ShowScheduleRepository extends BaseRepository implements ShowScheduleRepositoryInterface
{
    public function index(array $filters = [])
    {
        $query = ShowSchedule::with([
            'movie',
            'screen',
            'language',
        ]);

       // Search
        $query = $this->applyFilter(
            $query,
            $filters,
            [
                'name'
            ]
        );


        // Exact filters
        $query = $this->applyExactFilters(
            $query,
            $filters,
            [
                'movie_id',
                'screen_id',
                'language_id',
                'is_active'
            ]
        );


        return $this->paginate(
            $query->latest(),
            $filters
        );
    }


    public function find(int $id)
    {
        return ShowSchedule::with([
            'movie',
            'screen',
            'language',
            'shows',
        ])->findOrFail($id);
    }

    public function store(array $data)
    {
        if (isset($data['days_of_week']) && is_array($data['days_of_week'])) {
            $data['days_of_week'] = implode(',', $data['days_of_week']);
        }
        return ShowSchedule::create($data);
    }

    public function update(int $id, array $data)
    {
        if (isset($data['days_of_week']) && is_array($data['days_of_week'])) {
            $data['days_of_week'] = implode(',', $data['days_of_week']);
        }

        $schedule = ShowSchedule::findOrFail($id);

        $schedule->update($data);

        return $schedule->fresh([
            'movie',
            'screen',
            'language',
        ]);
    }

    public function delete(int $id)
    {
        return ShowSchedule::findOrFail($id)->delete();
    }
}
