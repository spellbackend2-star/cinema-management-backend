<?php

namespace App\Repositories\Interfaces;

interface SeatCategoryRepositoryInterface
{
    public function index(array $filters = []);

    public function find(int $id);

    public function create(array $data);

    public function update(int $id, array $data);

    public function delete(int $id);
}