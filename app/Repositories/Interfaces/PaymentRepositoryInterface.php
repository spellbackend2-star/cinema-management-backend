<?php

namespace App\Repositories\Interfaces;

interface PaymentRepositoryInterface
{
    public function getAll(array $filters=[]);
    public function findById(int $id);
    public function create(array $data);
    public function update(int $id, array $data);
    public function existsByReference(string $ref): bool;
    public function findByReference(string $reference);
    public function delete(int $id);
}