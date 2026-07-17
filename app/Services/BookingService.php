<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\BookingSeat;
use App\Models\ShowSeat;
use App\Repositories\Interfaces\BookingRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

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



    public function store(array $data, int $userId, $authUser)
    {
        return DB::transaction(function () use ($data, $userId, $authUser) {

            $isStaffBooking = $authUser->hasAnyRole([
                'ticket_counter',
                'cashier',
                'company_admin',
                'branch_manager'
            ]);

            if ($isStaffBooking) {
                $bookingUserId = $data['customer_id'];
                $bookedByStaffId = $userId;
            } else {
                $bookingUserId = $userId;
                $bookedByStaffId = null;
            }
            foreach ($data['show_seat_ids'] as $seatId) {

                $this->showSeatService->lockSeat(
                    $seatId,
                    $bookingUserId
                );
            }




            $showSeats = ShowSeat::whereIn(
                'id',
                $data['show_seat_ids']
            )->get();


            $subtotal = $showSeats->sum('price');




            $taxAmount = 0;

            $discountAmount = 0;

            $convenienceFee = 50;


            $totalAmount =
                $subtotal
                + $taxAmount
                + $convenienceFee
                - $discountAmount;


            if ($isStaffBooking && !isset($data['customer_id'])) {
                throw new \Exception('Customer is required for counter booking');
            }

            $booking = $this->repository->create([

                'booking_reference' =>
                'BK-' . now()->format('Ymd') . '-' . strtoupper(str()->random(5)),


                'user_id' => $bookingUserId,
                'booked_by_user_id' => $bookedByStaffId,

                'show_id' => $data['show_id'],


                'subtotal' => $subtotal,

                'tax_amount' => $taxAmount,

                'discount_amount' => $discountAmount,

                'convenience_fee' => $convenienceFee,

                'total_amount' => $totalAmount,


                'status' => $isStaffBooking
                    ? 'CONFIRMED'
                    : 'PENDING',

                'payment_status' => ($isStaffBooking &&
                    ($data['payment_method'] ?? null) === 'CASH')
                    ? 'PAID'
                    : 'UNPAID',

                'booking_source' => $isStaffBooking
                    ? 'COUNTER'
                    : ($data['booking_source'] ?? 'WEB'),

                'expires_at' => $isStaffBooking
                    ? null
                    : now()->addMinutes(5),
            ]);





            foreach ($showSeats as $seat) {
                $exists = BookingSeat::where('show_seat_id', $seat->id)
                    ->where('is_active', true)
                    ->exists();

                if ($exists) {
                    throw new ConflictHttpException('Seat already booked.');
                }
                BookingSeat::create([

                    'booking_id' => $booking->id,

                    'show_seat_id' => $seat->id,

                    'price' => $seat->price,

                    'is_active' => true

                ]);
            }



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
