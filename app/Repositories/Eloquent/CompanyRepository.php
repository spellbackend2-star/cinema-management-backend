<?php

namespace App\Repositories\Eloquent;

use App\Models\Company;
use App\Repositories\BaseRepository;
use App\Repositories\Interfaces\CompanyRepositoryInterface;

class CompanyRepository extends BaseRepository implements CompanyRepositoryInterface
{
    public function index(array $filters = [])
    {
        $query = Company::query()->latest();

        $query = $this->applyFilter(
            $query,
            $filters,
            [
                'name',
                'email',
                'phone',
                'slug',
            ]
        );

        return $this->paginate($query, $filters);
    }

    public function find(int $id)
    {
        return Company::findOrFail($id);
    }

    public function create(array $data)
    {
        return Company::create($data);
    }

    public function update(int $id, array $data)
    {
        $company = $this->find($id);

        $company->update($data);

        return $company->fresh();
    }

    public function delete(int $id)
    {
        return $this->find($id)->delete();
    }
}