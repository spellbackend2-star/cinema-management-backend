<?php

namespace App\Services;

use App\Models\Booking;
use App\Repositories\Interfaces\PaymentRepositoryInterface;
use App\Services\Payments\KhaltiService;
use App\Services\Payments\EsewaService;
use Illuminate\Support\Str;


class PaymentService
{

    public function __construct(
        protected PaymentRepositoryInterface $paymentRepo,
        protected KhaltiService $khaltiService,
        protected EsewaService $esewaService,
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


            $payment->update([
                'payment_status' => 'completed',
            ]);


            $payment->update([
                'status' => 'SUCCESS',
                'paid_at' => now(),
            ]);



            return [
                'payment' => $payment->fresh(),
                'status' => 'completed'
            ];
        }





        // ONLINE PAYMENT

        return match ($payment->payment_method) {

            'KHALTI' =>
            $this->handleKhalti($payment),


            'ESEWA' =>
            $this->esewaService->initiate($payment),


            default =>
            throw new \Exception(
                'Unsupported payment method'
            ),
        };
    }



    public function updateStatus(int $id, string $status)
    {
        $payment = $this->paymentRepo->findById($id);

        if ($payment->payment_status === 'SUCCESS') {
            return $payment;
        }

        $this->paymentRepo->update($id, [
            'payment_status' => $status,
            'payment_date' => now(),
        ]);

        if ($status === 'completed') {
            $payment->Booking?->update([
                'status' => 'confirmed',
            ]);
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
