<?php

namespace App\Repositories\Eloquent;


use App\Models\Booking;
use App\Repositories\Interfaces\BookingRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;



class BookingRepository implements BookingRepositoryInterface
{


    public function __construct(
        protected Booking $model
    ){}



    public function getAll(int $perPage = 10): LengthAwarePaginator
    {
        return $this->model
            ->with([
                'user',
                'show',
                'seats'
            ])
            ->paginate($perPage);
    }



    public function findById(int $id): Booking
    {
        return $this->model
            ->with([
                'user',
                'show',
                'seats'
            ])
            ->findOrFail($id);
    }



    public function create(array $data): Booking
    {
        return $this->model->create($data);
    }



    public function update(
        Booking $booking,
        array $data
    ): Booking {

        $booking->update($data);

        return $booking;
    }



    public function delete(
        Booking $booking
    ): bool {

        return $booking->delete();

    }

}