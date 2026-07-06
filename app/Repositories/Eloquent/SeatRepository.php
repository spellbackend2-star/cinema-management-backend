<?php

namespace App\Repositories\Eloquent;

use App\Models\Seat;
use App\Repositories\Interfaces\SeatRepositoryInterface;

class SeatRepository implements SeatRepositoryInterface
{
    public function index(array $filters=[])
    {
        $query = Seat::with(['screen','category']);

        if (!empty($filters['screen_id'])) {
            $query->where('screen_id',$filters['screen_id']);
        }

        if (!empty($filters['category_id'])) {
            $query->where('category_id',$filters['category_id']);
        }

        if (!empty($filters['company_id'])) {
            $query->whereHas('screen',function($q) use($filters){
                $q->where('company_id',$filters['company_id']);
            });
        }

        return $query
            ->orderBy('row_label')
            ->orderBy('seat_number')
            ->paginate($filters['per_page'] ?? 20);
    }

    public function find(int $id)
    {
        return Seat::with(['screen','category'])->findOrFail($id);
    }

    public function create(array $data)
    {
        return Seat::create($data);
    }

    public function update(int $id,array $data)
    {
        $seat=$this->find($id);

        $seat->update($data);

        return $seat->fresh();
    }

    public function delete(int $id)
    {
        return $this->find($id)->delete();
    }
}