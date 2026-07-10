<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\BookingSeat;
use App\Models\ShowSeat;
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


            /*
            |--------------------------------------------------------------------------
            | 1. Lock Seats
            |--------------------------------------------------------------------------
            */

            foreach ($data['show_seat_ids'] as $seatId) {

                $this->showSeatService->lockSeat(
                    $seatId,
                    $userId
                );
            }



            /*
            |--------------------------------------------------------------------------
            | 2. Get Seat Prices
            |--------------------------------------------------------------------------
            */

            $showSeats = ShowSeat::whereIn(
                'id',
                $data['show_seat_ids']
            )->get();


            $subtotal = $showSeats->sum('price');



            /*
            |--------------------------------------------------------------------------
            | 3. Calculate Amount
            |--------------------------------------------------------------------------
            */

            $taxAmount = 0;

            $discountAmount = 0;

            $convenienceFee = 50;


            $totalAmount =
                $subtotal
                + $taxAmount
                + $convenienceFee
                - $discountAmount;



            /*
            |--------------------------------------------------------------------------
            | 4. Create Booking
            |--------------------------------------------------------------------------
            */


            $booking = $this->repository->create([

                'booking_reference' =>
                'BK-' . now()->format('Ymd') . '-' . strtoupper(str()->random(5)),


                'user_id' => $userId,

                'show_id' => $data['show_id'],


                'subtotal' => $subtotal,

                'tax_amount' => $taxAmount,

                'discount_amount' => $discountAmount,

                'convenience_fee' => $convenienceFee,

                'total_amount' => $totalAmount,


                'status' => 'PENDING',

                'payment_status' => 'UNPAID',

                'booking_source' =>
                $data['booking_source'] ?? 'WEB',


                'expires_at' =>
                now()->addMinutes(10)

            ]);




            /*
            |--------------------------------------------------------------------------
            | 5. Attach Seats
            |--------------------------------------------------------------------------
            */


            foreach ($showSeats as $seat) {

                BookingSeat::create([

                    'booking_id' => $booking->id,

                    'show_seat_id' => $seat->id,

                    'price' => $seat->price,

                    'is_active' => true

                ]);
            }



            /*
            |--------------------------------------------------------------------------
            | 6. Create Payment
            |--------------------------------------------------------------------------
            */


            $payment = $this->paymentService
                ->createFromBooking($booking,  [
                    'amount' => $booking->total_amount,
                    'currency' => 'NPR',
                    'payment_method' => $data['payment_method'] ?? 'CASH'
                ]);



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
}
