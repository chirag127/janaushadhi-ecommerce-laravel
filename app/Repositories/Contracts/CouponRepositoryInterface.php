<?php

namespace App\Repositories\Contracts;

use App\Models\Coupon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface CouponRepositoryInterface
{
    public function findActiveByCode(string $code): ?Coupon;

    public function paginate(int $perPage = 20): LengthAwarePaginator;

    public function create(array $data): Coupon;

    public function update(Coupon $coupon, array $data): Coupon;

    public function delete(Coupon $coupon): void;

    public function incrementUsage(Coupon $coupon): void;

    public function userRedemptionCount(string $couponId, string $userId): int;
}
