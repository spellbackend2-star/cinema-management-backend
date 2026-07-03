<?php

namespace App\Services;

use App\Repositories\Interfaces\CompanyRepositoryInterface;

class CompanyService 
{
    public function __construct(
        protected CompanyRepositoryInterface $repository
    ) {}

    public function index(array $filters = [])
    {
        return $this->repository->index($filters);
    }

    public function show(int $id)
    {
        return $this->repository->find($id);
    }

    public function store(array $data)
    {
        return $this->repository->create($data);
    }

    public function update(int $id, array $data)
    {
        return $this->repository->update($id, $data);
    }

    public function destroy(int $id)
    {
        return $this->repository->delete($id);
    }
}