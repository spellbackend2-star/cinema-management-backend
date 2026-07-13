<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Payment\PaymentIndexRequest;
use App\Http\Resources\PaymentResource;
use App\Services\PaymentService;
use App\Traits\AuthorizesWithPermission;
use App\Traits\ResponseTrait;

class PaymentController extends Controller
{
    use ResponseTrait, AuthorizesWithPermission;

    public function __construct(
        protected PaymentService $paymentService,
    ) {}

    public function index(PaymentIndexRequest $request)
    {
        $this->authorizePermission('payment.view');
        $payments = $this->paymentService->getAll($request->validated());

        return $this->successResponse(

            PaymentResource::collection($payments),

            'Payments retrieved successfully'
        );
    }

    public function show(int $id)
    {
        $this->authorizePermission('payment.view');
        $payment = $this->paymentService->findbyid($id);

        if (!$payment) {
            return $this->errorResponse('Payment not found', 404);
        }

        return $this->successResponse(
            new PaymentResource($payment),
            'Payment retrieved successfully'
        );
    }
}
