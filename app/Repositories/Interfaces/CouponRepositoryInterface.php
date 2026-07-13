<?php

namespace App\Repositories\Interfaces;

use App\Models\Coupon;

interface CouponRepositoryInterface
{

    public function getAll(int $perPage = 10);
    public function  findById(int $id);
    public function findByCode(string $code);
    public function create(array $data);
    public function update(Coupon $coupon, array $data);
    public function delete(Coupon $coupon);
}
