<?php

namespace App\Repositories\Interfaces; 

interface SeatRepositoryInterface
{
    public function index(array $filters=[]);

    public function find(int $id);

    public function create(array $data);

    public function findByPosition(int $screenId, string $rowLabel, int $seatNumber);

    public function update(int $id,array $data);

    public function delete(int $id);
}