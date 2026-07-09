<?php

namespace App\Repositories\Interfaces;

interface ShowRepositoryInterface
{
    public function index(array $filters = []);

    public function find(int $id);

    public function store(array $data);

    public function update(int $id, array $data);

    public function delete(int $id); 
}
