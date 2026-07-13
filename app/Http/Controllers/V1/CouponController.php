<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Coupon\StoreCouponRequest;
use App\Http\Requests\Coupon\UpdateCouponRequest;
use App\Http\Resources\CouponResource;
use App\Services\CouponService;
use App\Traits\AuthorizesWithPermission;
use App\Traits\ResponseTrait;

class CouponController extends Controller
{
    use ResponseTrait;
    use AuthorizesWithPermission;
    public function __construct(
        protected CouponService $service
    ) {}

    /**
     * Display a listing of coupons.
     */
    public function index()
    {
        $this->authorizePermission('coupon.view');
        $coupons = $this->service->index();

        return $this->successResponse(
            CouponResource::collection($coupons),
            'Coupons retrieved successfully.'
        );
    }

    /**
     * Store a newly created coupon.
     */
    public function store(StoreCouponRequest $request)
    {
        $this->authorizePermission('coupon.create');
        $coupon = $this->service->store(
            $request->validated()
        );

        return $this->successResponse(
            new CouponResource($coupon),
            'Coupon created successfully.',
            201
        );
    }

    /**
     * Display the specified coupon.
     */
    public function show(int $id)
    {

    $this->authorizePermission('coupon.view');
        $coupon = $this->service->show($id);

        return $this->successResponse(
            new CouponResource($coupon),
            'Coupon retrieved successfully.'
        );
    }

    /**
     * Update the specified coupon.
     */
    public function update(UpdateCouponRequest $request, int $id)
    {
        $this->authorizePermission('coupon.update');
        $coupon = $this->service->update(
            $id,
            $request->validated()
        );

        return $this->successResponse(
            new CouponResource($coupon),
            'Coupon updated successfully.'
        );
    }

    /**
     * Remove the specified coupon.
     */
    public function destroy(int $id)
    {
        $this->authorizePermission('coupon.delete');
        $this->service->destroy($id);

        return $this->successResponse(
            null,
            'Coupon deleted successfully.'
        );
    }

    /**
     * Validate coupon code.
     */
    public function validateCoupon(string $code)
    {
        $coupon = $this->service->validateCoupon($code);

        return $this->successResponse(
            new CouponResource($coupon),
            'Coupon validated successfully.'
        );
    }
}