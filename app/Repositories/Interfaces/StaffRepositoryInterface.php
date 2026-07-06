<?php

namespace App\Repositories\Interfaces;

use App\Models\User;

interface StaffRepositoryInterface
{
    public function index(array $filters);
    public function find(int $id): User;
    public function create(array $data): User;
    public function update(int $id, array $data): User;
    public function delete(int $id): bool;
}