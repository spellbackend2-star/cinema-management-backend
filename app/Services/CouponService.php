<?php

namespace App\Services;

use App\Models\Coupon;
use App\Repositories\Interfaces\CouponRepositoryInterface;

class CouponService
{

    public function __construct(
        protected CouponRepositoryInterface $couponRepository
    ) {}
    public function index()
    {
        return Coupon::latest()->paginate();
    }

    public function store(array $data)
    {
        return Coupon::create($data);
    }

    public function show(int $id)
    {
        return Coupon::findOrFail($id);
    }

    public function update(int $id, array $data)
    {
        $coupon = Coupon::findOrFail($id);

        $coupon->update($data);

        return $coupon->fresh();
    }

    public function destroy(int $id)
    {
        Coupon::findOrFail($id)->delete();
    }

    public function validateCoupon(string $code)
    {
        $coupon = Coupon::where('code', $code)
            ->where('is_active', true)
            ->first();

        if (!$coupon) {
            abort(404, 'Invalid coupon.');
        }

        if (now()->lt($coupon->valid_from) || now()->gt($coupon->valid_until)) {
            abort(422, 'Coupon has expired.');
        }

        return $coupon;
    }
}
