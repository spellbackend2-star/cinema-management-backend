<?php

namespace App\Repositories\Eloquent;

use App\Models\Seat;
use App\Repositories\BaseRepository;
use App\Repositories\Interfaces\SeatRepositoryInterface;

class SeatRepository extends BaseRepository implements SeatRepositoryInterface
{
     public function index(array $filters = [])
    {
        $query = Seat::with([
            'screen',
            'category'
        ]);


        // Search
        $query = $this->applyFilter(
            $query,
            $filters,
            [
                'row_label'
            ]
        );


        // Exact filters
        $query = $this->applyExactFilters(
            $query,
            $filters,
            [
                'screen_id',
                'category_id'
            ]
        );


        // Company filter through screen → cinema → company
        if (!empty($filters['company_id'])) {

            $query->whereHas('screen', function ($q) use ($filters) {

                $q->whereHas('cinema', function ($q2) use ($filters) {

                    $q2->where(
                        'company_id',
                        $filters['company_id']
                    );

                });

            });

        }


        return $this->paginate(
            $query
                ->orderBy('row_label')
                ->orderBy('seat_number'),
            $filters
        );
    }

    public function find(int $id)
    {
        return Seat::with(['screen', 'category'])->findOrFail($id);
    }

    public function create(array $data)
    {
        return Seat::create($data);
    }

    // In SeatRepository.php
    public function findByPosition($screenId, $rowLabel, $seatNumber)
    {
        return Seat::where('screen_id', $screenId)
            ->where('row_label', $rowLabel)
            ->where('seat_number', $seatNumber)
            ->first();
    }
    public function update(int $id, array $data)
    {
        $seat = $this->find($id);

        $seat->update($data);

        return $seat->fresh();
    }

    public function delete(int $id)
    {
        return $this->find($id)->delete();
    }
}
