<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\Interfaces\StaffRepositoryInterface;

class StaffRepository extends BaseRepository implements StaffRepositoryInterface
{
    public function index(array $filters)
    {
        $query = User::with(['roles', 'company', 'cinema']);

         // Search
        $query = $this->applyFilter(
            $query,
            $filters,
            [
                'name',
                'email',
                'employee_code'
            ]
        );


        // Exact filters
        $query = $this->applyExactFilters(
            $query,
            $filters,
            [
                'company_id',
                'cinema_id'
            ]
        );


        // Role filter (Spatie)
        if (!empty($filters['role'])) {
            $query->role($filters['role']);
        }


        return $this->paginate(
            $query->latest(),
            $filters
        );
    }

    public function find(int $id): User
    {
        return User::with(['roles', 'company', 'cinema'])
            ->findOrFail($id);
    }

    public function create(array $data): User
    {
      
        return User::create($data);
    }

    public function update(int $id, array $data): User
    {
        $staff = User::findOrFail($id);

        $staff->update($data);

        return $staff;
    }

    public function delete(int $id): bool
    {
        return User::findOrFail($id)->delete();
    }
}