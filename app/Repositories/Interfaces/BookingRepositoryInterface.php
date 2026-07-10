<?php

namespace App\Repositories\Interfaces;

use App\Models\Booking;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;


interface BookingRepositoryInterface
{

    public function getAll(int $perPage = 10): LengthAwarePaginator;


    public function findById(int $id): Booking;


    public function create(array $data): Booking;


    public function update(Booking $booking, array $data): Booking;


    public function delete(Booking $booking): bool;

}