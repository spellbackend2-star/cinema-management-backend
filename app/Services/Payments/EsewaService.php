<?php

namespace App\Services\Payments;

use App\Models\Payment;
use Illuminate\Support\Facades\Http;

class EsewaService
{
    protected string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.esewa.base_url');
    }


    public function initiate(Payment $payment): array
    {

        $signature = $this->generateSignature($payment->amount, $payment->transaction_id);
        $totalAmount = number_format((float)$payment->amount, 2, '.', '');
        return [
            'payment_url' => 'https://rc-epay.esewa.com.np/api/epay/main/v2/form',
            'params'      => [
                'amount'                  => $totalAmount,
                'tax_amount'              => 0,
                'total_amount'            => $totalAmount,
                'product_service_charge'  => 0,
                'product_delivery_charge' => 0,
                'transaction_uuid'        => $payment->transaction_id,
                'product_code'            => config('services.esewa.product_code'),
                'success_url' => route('payments.esewa.success'),
                'failure_url' => route('payments.esewa.failure'),


                'signed_field_names'      => 'total_amount,transaction_uuid,product_code',
                'signature'               => $signature,
            ],
        ];
    }
    private function generateSignature(int $totalAmount, $txn)
    {
        $secret = config('services.esewa.secret');

        $productCode = trim(config('services.esewa.product_code'));
        $transactionUuid = trim($txn);
        $totalAmount = number_format((float)$amount, 2, '.', '');

        $message = "total_amount={$totalAmount},transaction_uuid={$transactionUuid},product_code={$productCode}";

        // dd($secret,$message, $totalAmount, $transactionUuid, );
        return base64_encode(
            hash_hmac('sha256', $message, $secret, true)
        );
    }

    public function verify(array $data): array
    {


        $encoded = $data['data'] ?? null;

        if (!$encoded) {
            throw new \Exception('Missing eSewa data payload.');
        }

        $decoded = json_decode(base64_decode($encoded), true);

        if (!$decoded) {
            throw new \Exception('Invalid response.');
        }

        // Match payment_references with transaction_uuid
        $payment = Payment::where(
            'payment_references',
            $decoded['transaction_uuid']
        )->first();

        if (!$payment) {
            throw new \Exception('Payment not found.');
        }

        // Amount must also match
        if ((float)$payment->amount !== (float)$decoded['total_amount']) {
            throw new \Exception('Payment amount mismatch.');
        }
        // Already processed
        if ($payment->payment_status === 'completed') {

            return [
                'status' => 'Completed',
                'transaction_id' => $payment->transaction_id,
            ];
        }

        $response = Http::get(
            "{$this->baseUrl}/transaction/status/",
            [
                'product_code'     => $this->productCode,
                'total_amount'     => $decoded['total_amount'],
                'transaction_uuid' => $decoded['transaction_uuid'],
            ]
        );

        if ($response->failed()) {
            throw new \Exception('Verification failed.');
        }

        $verify = $response->json();

        if (($verify['status'] ?? '') !== 'COMPLETE') {

            $payment->update([
                'payment_status' => 'failed'
            ]);

            throw new \Exception('Payment not completed.');
        }

        $payment->update([
            'payment_status' => 'completed',
            'transaction_id' => $verify['transaction_code'],
            'payment_date'   => now(),
        ]);

        $payment->tourBooking?->update([
            'status' => 'confirmed',
        ]);

        return [
            'status' => 'Completed',
            'transaction_id' => $payment->transaction_id,
        ];
    }
}
