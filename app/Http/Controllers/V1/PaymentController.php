<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Payment\PaymentIndexRequest;
use App\Http\Resources\PaymentResource;
use App\Services\PaymentService;
use App\Traits\ResponseTrait;

class PaymentController extends Controller
{
    use ResponseTrait;


    public function __construct(
        protected PaymentService $paymentService
    ) {}



    public function index(PaymentIndexRequest $request)
    {
        $payments = $this->paymentService->index(
            $request->validated()
        );


        return $this->successResponse(
            [
                'data' => PaymentResource::collection($payments),

                'pagination' =>
                    $this->paymentService->pagination($payments),
            ],
            'Payments retrieved successfully'
        );
    }




    public function show(int $id)
    {
        $payment = $this->paymentService->findById($id);


        if (!$payment) {

            return $this->errorResponse(
                'Payment not found',
                404
            );

        }


        return $this->successResponse(
            new PaymentResource($payment),
            'Payment retrieved successfully'
        );
    }

}