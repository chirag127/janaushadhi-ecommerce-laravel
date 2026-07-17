<?php

namespace App\Repositories\Eloquent;

use App\Models\Coupon;
use App\Models\CouponRedemption;
use App\Repositories\Contracts\CouponRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CouponRepository implements CouponRepositoryInterface
{
    public function findActiveByCode(string $code): ?Coupon
    {
        return Coupon::whereRaw('UPPER(code) = ?', [strtoupper(trim($code))])->first();
    }

    public function paginate(int $perPage = 20): LengthAwarePaginator
    {
        return Coupon::orderByDesc('created_at')->paginate($perPage);
    }

    public function create(array $data): Coupon
    {
        return Coupon::create($data);
    }

    public function update(Coupon $coupon, array $data): Coupon
    {
        $coupon->update($data);

        return $coupon->refresh();
    }

    public function delete(Coupon $coupon): void
    {
        $coupon->delete();
    }

    public function incrementUsage(Coupon $coupon): void
    {
        $coupon->increment('used_count');
    }

    public function userRedemptionCount(string $couponId, string $userId): int
    {
        return CouponRedemption::where('coupon_id', $couponId)->where('user_id', $userId)->count();
    }
}
