<?php

namespace App\Services\Payments;

use App\Models\Payment;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

class KhaltiService
{
    private string $secretKey;
    private string $baseUrl = 'https://a.khalti.com/api/v2';

    public function __construct()
    {
        // $this->secretKey = config('services.khalti.secret_key');
        $this->secretKey = Crypt::decryptString(
            setting('khalti_secret_key')
        );
    }

    public function initiate(Payment $payment): array
    {


        $response = Http::withHeaders([
            'Authorization' => 'Key ' . $this->secretKey,
        ])->post("{$this->baseUrl}/epayment/initiate/", [

            'return_url' => route('payments.khalti.verify', [
                'payment' => $payment->id,
            ]),



            'website_url'         => config('app.url'),
            'amount'              => (int) ($payment->amount * 100),
            'purchase_order_id'   => $payment->payment_references,
            'purchase_order_name' => 'Tour Booking #' . $payment->tour_booking_id,

        ]);

        if ($response->failed()) {
            throw new \Exception('Khalti initiation failed: ' . $response->body());
        }

        $result = $response->json();

        $payment->update([
            'transaction_id' => $result['pidx'],
        ]);

        return $result;
    }

    public function verify(Payment $payment): array
    {
        if ($payment->payment_status === 'completed') {
            return [
                'status' => 'Completed',
                'transaction_id' => $payment->transaction_id,
                'payment' => $payment,
            ];
        }

        if (!$payment->transaction_id) {
            throw new \Exception('Khalti pidx not found.');
        }

        $response = Http::withHeaders([
            'Authorization' => 'Key ' . $this->secretKey,
        ])->post("{$this->baseUrl}/epayment/lookup/", [
            'pidx' => $payment->transaction_id,
        ]);

    

        if ($response->failed()) {
            throw new \Exception($response->body());
        }

        $result = $response->json();

        if (($result['status'] ?? '') === 'Completed') {

            $payment->update([
                'payment_status' => 'completed',
                'payment_date' => now(),
            ]);

            if ($payment->tourBooking) {
                $payment->tourBooking->update([
                    'status' => 'confirmed',
                ]);
            }
        } elseif (($result['status'] ?? '') === 'Pending') {

            $payment->update([
                'payment_status' => 'pending',
            ]);
        } else {

            $payment->update([
                'payment_status' => 'failed',
            ]);
        }

        return [
            'status' => $result['status'] ?? 'Unknown',
            'transaction_id' => $result['transaction_id'] ?? $payment->transaction_id,
            'payment' => $payment->fresh(),
        ];
    }
}
