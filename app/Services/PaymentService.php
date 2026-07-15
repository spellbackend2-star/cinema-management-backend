<?php

namespace App\Services;

use App\Models\Booking;
use App\Repositories\Interfaces\PaymentRepositoryInterface;
use App\Services\Payments\KhaltiService;
use App\Services\Payments\EsewaService;
use App\Services\ShowSeatService;
use Illuminate\Support\Str;


class PaymentService
{

    public function __construct(
        protected PaymentRepositoryInterface $paymentRepo,
        protected KhaltiService $khaltiService,
        protected EsewaService $esewaService,
        protected ShowSeatService $showSeatService,

    ) {}


    public function getAll(array $filters = [])
    {
        return $this->paymentRepo->getAll($filters);
    }

    public function findById(int $id)
    {
        return $this->paymentRepo->findById($id);
    }
    public function createFromBooking(
        Booking $booking,
        array $data
    ) {


        $reference = $this->generateUniqueReference();


        $payment = $this->paymentRepo->create([


            'user_id' => $booking->user_id,

            'booking_id' => $booking->id,


            'amount' => $data['amount'],

            'currency' =>
            $data['currency'] ?? 'NPR',


            'payment_method' =>
            $data['payment_method'],


            'status' =>
            'PENDING',


            'transaction_id' =>
            $reference,


            'payment_date' =>
            now(),

        ]);




        // CASH PAYMENT

        if ($payment->payment_method === 'CASH') {

            $booking->update([
                'status' => 'CONFIRMED',
                'payment_status' => 'PAID',
                'confirmed_at' => now(),
            ]);

            $booking = $payment->booking()->with('bookingSeats')->first();

            $booking->update([
                'status' => 'CONFIRMED',
                'payment_status' => 'PAID',
            ]);

            foreach ($booking->bookingSeats as $bookingSeat) {
                $this->showSeatService
                    ->bookDirectly($bookingSeat->show_seat_id);
            }

            return [
                'payment' => $payment->fresh(),
                'status' => 'SUCCESS',
            ];
        }




        // ONLINE PAYMENT

        return match ($payment->payment_method) {

            'KHALTI' =>
            $this->handleKhalti($payment),


            'ESEWA' =>
            $this->handleEsewa($payment),

            default =>
            throw new \Exception(
                'Unsupported payment method'
            ),
        };
    }

    private function handleEsewa($payment)
    {
        $response = $this->esewaService->initiate($payment);

        $payment->update([
            'payment_url' => $response['payment_url'],
        ]);

        return [
            'payment' => $payment->fresh(),
            'gateway' => $response,
        ];
    }

    public function updateStatus(int $id, string $status)
    {
        $payment = $this->paymentRepo->findById($id);

        if ($payment->status === 'SUCCESS') {
            return $payment;
        }

        $this->paymentRepo->update($id, [
            'status' => $status,
            'paid_at' => now(),
        ]);

        if ($status === 'SUCCESS') {
            $booking = $payment->booking()->with('bookingSeats')->first();

            $booking->update([
                'status' => 'CONFIRMED',
                'paid_at' => now(),
            ]);

            foreach ($booking->bookingSeats as $bookingSeat) {
                $this->showSeatService->bookDirectly($bookingSeat->show_seat_id);
            }
        }

        return $payment->fresh();
    }


    private function handleKhalti($payment)
    {

        $response =
            $this->khaltiService
            ->initiate($payment);



        $payment->update([

            'transaction_id' =>
            $response['pidx'] ?? null,


            'gateway_reference' =>
            $response['pidx'] ?? null,


            'payment_url' =>
            $response['payment_url'] ?? null,

        ]);



        return [
            'payment' => $payment->fresh(),
            'gateway' => $response
        ];
    }




    private function generateUniqueReference()
    {

        do {

            $ref =
                'PAY-' . strtoupper(Str::random(8));
        } while (
            $this->paymentRepo
            ->existsByReference($ref)
        );


        return $ref;
    }
}
