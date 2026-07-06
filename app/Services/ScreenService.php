<?php

namespace App\Services;

use App\Repositories\Interfaces\ScreenRepositoryInterface;

class ScreenService
{
    public function __construct(
        protected ScreenRepositoryInterface $repository
    ) {}

    public function index(array $filters = [])
    {
        return $this->repository->index($filters);
    }

    public function find(int $id)
    {
        return $this->repository->find($id);
    }

    public function create(array $data)
    {
        return $this->repository->create($data);
    }

    public function update(int $id, array $data)
    {
        return $this->repository->update($id, $data);
    }

    public function delete(int $id)
    {
        return $this->repository->delete($id);
    }
}