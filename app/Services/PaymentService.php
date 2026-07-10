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
        // protected EsewaService $esewaService,
    ) {}



    public function createFromBooking(
        Booking $booking,
        array $data
    ) {


        $reference = $this->generateUniqueReference();


        $payment = $this->paymentRepo->create([

            'payment_references' => $reference,

            'user_id' => $booking->user_id,

            'booking_id' => $booking->id,


            'amount' => $data['amount'],

            'currency' =>
                $data['currency'] ?? 'NPR',


            'payment_method' =>
                $data['payment_method'] ?? 'cash',


            'payment_status' =>
                'pending',


            'transaction_id' =>
                Str::uuid(),


            'payment_date' =>
                now(),

        ]);




        // CASH PAYMENT

        if($payment->payment_method === 'cash') {


            $payment->update([
                'payment_status'=>'completed',
            ]);


            $booking->update([
                'status'=>'confirmed'
            ]);



            return [
                'payment'=>$payment->fresh(),
                'status'=>'completed'
            ];

        }





        // ONLINE PAYMENT

        return match($payment->payment_method){

            'khalti' =>
                $this->handleKhalti($payment),


            'esewa' =>
                $this->esewaService->initiate($payment),


            default =>
                throw new \Exception(
                    'Unsupported payment method'
                ),

        };


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
            'payment'=>$payment->fresh(),
            'gateway'=>$response
        ];

    }




    private function generateUniqueReference()
    {

        do {

            $ref =
                'PAY-'.strtoupper(Str::random(8));


        } while(
            $this->paymentRepo
                ->existsByReference($ref)
        );


        return $ref;

    }

}