<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Services\PaymentService;
use App\Services\Payments\EsewaService;
use App\Services\Payments\KhaltiService;
use Illuminate\Http\Request;

class PaymentGatewayController extends Controller
{
    public function __construct(
        private PaymentService $paymentService,
        private KhaltiService $khaltiService,
        private EsewaService $esewaService
    ) {}

    public function initiate(int $id)
    {
        $payment = $this->paymentService->findById($id);



        return match ($payment->payment_method) {
            'KHALTI' => $this->khaltiService->initiate($payment),
            'ESEWA'  => $this->esewaService->initiate($payment),
            default  => throw new \Exception('Unsupported payment method'),
        };
    }


    public function verifyKhalti(Payment $payment)
    {
        try {

            $result = $this->khaltiService->verify($payment);

            return response()->json([
                'success' => true,
                'message' => 'Khalti payment verified successfully.',
                'status' => $result['status'],
                'transaction_id' => $result['transaction_id'],
                // 'payment' => $result['payment'],
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function verify(Request $request)
    {


        try {

            $data = $request->input('data') ?? $request->query('data');

            if (!$data) {
                return response()->json([
                    'success' => false,
                    'message' => 'Missing eSewa callback data.',
                    'debug' => $request->all()
                ], 400);
            }

            $result = $this->esewaService->verify([
                'data' => $data
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Payment verified successfully.',
                'status' => $result['status'],
                'transaction_id' => $result['transaction_id'],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function success(Request $request)
    {

        try {
            $result = $this->esewaService->verify([
                'data' => $request->input('data') ?? $request->query('data')
            ]);



            return response()->json([
                'success' => true,
                'message' => 'Payment verified successfully.',
                'status' => $result['status'],
                'transaction_id' => $result['transaction_id'],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function failure(Request $request)
    {
        return response()->json([
            'success' => false,
            'message' => 'Payment was cancelled or failed.',
        ], 400);
    }
}
