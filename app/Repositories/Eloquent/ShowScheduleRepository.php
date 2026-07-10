<?php

namespace App\Repositories\Eloquent;

use App\Models\ShowSchedule;
use App\Repositories\Interfaces\ShowScheduleRepositoryInterface;

class ShowScheduleRepository implements ShowScheduleRepositoryInterface
{
    public function index(array $filters = [])
    {
        $query = ShowSchedule::with([
            'movie',
            'screen',
            'language',
        ]);

        if (!empty($filters['movie_id'])) {
            $query->where('movie_id', $filters['movie_id']);
        }

        if (!empty($filters['screen_id'])) {
            $query->where('screen_id', $filters['screen_id']);
        }

        if (!empty($filters['language_id'])) {
            $query->where('language_id', $filters['language_id']);
        }

        if (isset($filters['is_active'])) {
            $query->where('is_active', $filters['is_active']);
        }

        return $query->latest()->paginate(10);
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
