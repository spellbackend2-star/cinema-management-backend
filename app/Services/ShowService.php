<?php

namespace App\Services;

use App\Repositories\Interfaces\ShowRepositoryInterface;

class ShowService
{

    public function __construct(
        protected ShowRepositoryInterface $showRepository
    ) {}


    public function index(array $filters = [])
    {
        return $this->showRepository->index($filters);
    }


    public function store(array $data)
    {
        return $this->showRepository->store($data);
    }


    public function show(int $id)
    {
        return $this->showRepository->find($id);
    }


    public function update(int $id,array $data)
    {
        return $this->showRepository->update($id,$data);
    }


    public function destroy(int $id)
    {
        return $this->showRepository->delete($id);
    }

}