<?php

namespace App\Repositories\Eloquent;

use App\Models\Coupon;
use App\Repositories\Interfaces\CouponRepositoryInterface;

class CouponRepository implements CouponRepositoryInterface
{
    public function getAll($perPage = 10)
    {
        return Coupon::paginate($perPage);
    }

    public function findById(int $id)
    {
        return Coupon::find($id);
    }

    public function findByCode(string $code)
    {
        return Coupon::where('code', $code)->first();
    }

    public function create(array $data)
    {
        return Coupon::create($data);
    }

    public function update($coupon, array $data)
    {
        $coupon->update($data);

        return $coupon;
    }

    public function delete($coupon)
    {
        return $coupon->delete();
    }
}
