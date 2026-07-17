<?php

namespace App\Repositories\Eloquent;

use App\Models\SeatCategory;
use App\Repositories\BaseRepository;
use App\Repositories\Interfaces\SeatCategoryRepositoryInterface;

class SeatCategoryRepository extends BaseRepository implements SeatCategoryRepositoryInterface
{
    public function index(array $filters = [])
    {
        $query = SeatCategory::with('screen');

        // Search filter
        $query = $this->applyFilter(
            $query,
            $filters,
            [
                'name'
            ]
        );


        // Exact filter
        $query = $this->applyExactFilters(
            $query,
            $filters,
            [
                'screen_id'
            ]
        );

        // Company filter through relation
        if (!empty($filters['company_id'])) {

            $query->whereHas('screen', function ($q) use ($filters) {
                $q->where(
                    'company_id',
                    $filters['company_id']
                );
            });
        }


        return $this->paginate(
            $query
                ->orderBy('display_order')
                ->latest(),
            $filters
        );
    }

    public function find(int $id)
    {
        return SeatCategory::with('screen')->findOrFail($id);
    }

    public function create(array $data)
    {
        return SeatCategory::create($data);
    }

    public function update(int $id, array $data)
    {
        $category = $this->find($id);

        $category->update($data);

        return $category->fresh();
    }

    public function delete(int $id)
    {
        return $this->find($id)->delete();
    }
}
