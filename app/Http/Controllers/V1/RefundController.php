<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Refund\StoreRefundRequest;
use App\Http\Requests\Refund\ProcessRefundRequest;
use App\Http\Resources\RefundResource;
use App\Services\PaymentService;
use App\Services\RefundService;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RefundController extends Controller
{
    use ResponseTrait;

    public function __construct(
        protected RefundService $refundService,
        protected PaymentService $paymentService
    ) {}

    public function index(Request $request)
    {
        $refunds = $this->refundService->getAll($request->all());

        return $this->successResponse(
            RefundResource::collection($refunds),
            'Refunds retrieved successfully.'
        );
    }

    public function show(int $id)
    {
        $refund = $this->refundService->getById($id);

        return $this->successResponse(
            new RefundResource($refund),
            'Refund retrieved successfully.'
        );
    }

    public function store(StoreRefundRequest $request)
    {
        $data = $request->validated();

        $payment = $this->paymentService->findById($data['payment_id']);

        $this->authorize('requestRefund', $payment);

        $refund = $this->refundService->store($data);

        return $this->successResponse(
            new RefundResource($refund),
            'Refund created successfully.',
            201
        );
    }
    public function process(ProcessRefundRequest $request,int $id)
    {
        $refund = $this->refundService->getById($id);
        
        $this->authorize('process', $refund);

        $refund = $this->refundService->process($id,
        $request->validated());
        
        return $this->successResponse(
            new RefundResource($refund),
            'Refund processed successfully.'
        );
    }

    public function destroy(int $id)
    {
        $this->refundService->delete($id);

        return $this->successResponse(
            null,
            'Refund deleted successfully.'
        );
    }
}
