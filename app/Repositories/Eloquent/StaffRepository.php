<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\Interfaces\StaffRepositoryInterface;

class StaffRepository implements StaffRepositoryInterface
{
    public function index(array $filters)
    {
        $query = User::with(['roles', 'company', 'cinema']);

        if (!empty($filters['company_id'])) {
            $query->where('company_id', $filters['company_id']);
        }

        if (!empty($filters['cinema_id'])) {
            $query->where('cinema_id', $filters['cinema_id']);
        }

        if (!empty($filters['role'])) {
            $query->role($filters['role']);
        }

        if (!empty($filters['search'])) {
            $search = $filters['search'];

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('employee_code', 'like', "%{$search}%");
            });
        }

        return $query->latest()->paginate($filters['per_page'] ?? 15);
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