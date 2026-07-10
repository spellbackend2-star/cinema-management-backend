<?php

namespace App\Services;


use App\Models\Booking;
use App\Models\BookingSeat;
use App\Repositories\Interfaces\BookingRepositoryInterface;
use Illuminate\Support\Facades\DB;


class BookingService
{


    public function __construct(
        protected BookingRepositoryInterface $repository,
        protected ShowSeatService $showSeatService,
         protected PaymentService $paymentService
    ) {}


    public function getAll()
    {
        return $this->repository->getAll();
    }




    public function find(int $id)
    {
        return $this->repository->findById($id);
    }



    public function store(array $data, int $userId)
    {
        return DB::transaction(function () use ($data, $userId) {

            // 1. Lock seats
            foreach ($data['show_seat_ids'] as $seatId) {

                $this->showSeatService->lockSeat(
                    $seatId,
                    $userId
                );
            }


            // 2. Create booking
            $booking = Booking::create([
                'booking_reference' => 'BK' . str()->random(8),
                'user_id' => $userId,
                'show_id' => $data['show_id'],
                'status' => 'pending'
            ]);


            // 3. Attach seats
            foreach ($data['show_seat_ids'] as $seatId) {

                BookingSeat::create([
                    'booking_id' => $booking->id,
                    'show_seat_id' => $seatId
                ]);
            }


            // 4. Call PaymentService
            $payment = $this->paymentService->createFromBooking(
                $booking,
                $data
            );


            return [
                'booking' => $booking->load([
                    'show',
                    'bookingSeats.showSeat'
                ]),

                'payment' => $payment
            ];
        });
    }



    public function update(
        Booking $booking,
        array $data
    ) {

        return $this->repository
            ->update($booking, $data);
    }




    public function delete(Booking $booking)
    {
        return $this->repository
            ->delete($booking);
    }
}
