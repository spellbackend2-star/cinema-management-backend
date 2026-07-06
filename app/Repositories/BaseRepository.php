<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Builder;

abstract class BaseRepository
{
    protected function applyFilter(
        Builder $query,
        array $filters,
        array $searchable = []
    ): Builder {
        if (!empty($filters['filter']) && !empty($searchable)) {

            $query->where(function ($q) use ($filters, $searchable) {

                foreach ($searchable as $column) {
                    $q->orWhere($column, 'like', "%{$filters['filter']}%");
                }
            });
        }

        return $query;
    }

    protected function paginate(
        Builder $query,
        array $filters = []
    ) {
        return $query->paginate(
            $filters['per_page'] ?? 10
        );
    }
    
    protected function applyExactFilters(
        Builder $query,
        array $filters,
        array $filterable = []
    ): Builder {
        foreach ($filterable as $column) {
            if (array_key_exists($column, $filters) && $filters[$column] !== '') {
                $query->where($column, $filters[$column]);
            }
        }

        return $query;
    }
}
