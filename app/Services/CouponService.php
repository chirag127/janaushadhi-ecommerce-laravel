<?php

namespace App\Services;

use App\Models\Coupon;
use App\Models\User;
use App\Repositories\Contracts\CouponRepositoryInterface;

class CouponService
{
    public function __construct(
        protected CouponRepositoryInterface $coupons
    ) {}

    /**
     * Validate a coupon code against a subtotal for a given user.
     *
     * @return array{ok:bool, message:string, coupon:?Coupon, discount:float}
     */
    public function validate(string $code, float $subtotal, ?User $user = null): array
    {
        $coupon = $this->coupons->findActiveByCode($code);

        if (! $coupon) {
            return $this->fail('Invalid coupon code.');
        }

        if (! $coupon->isValidNow()) {
            return $this->fail('This coupon is expired or no longer available.');
        }

        if ($subtotal < (float) $coupon->min_order_amount) {
            return $this->fail(sprintf(
                'Add %s%s more to use this coupon.',
                config('store.currency_symbol'),
                number_format((float) $coupon->min_order_amount - $subtotal, 2)
            ));
        }

        // One redemption per customer.
        if ($user && $this->coupons->userRedemptionCount($coupon->id, $user->id) > 0) {
            return $this->fail('You have already used this coupon.');
        }

        $discount = $coupon->discountFor($subtotal);

        if ($discount <= 0) {
            return $this->fail('This coupon does not apply to your cart.');
        }

        return [
            'ok' => true,
            'message' => sprintf('Coupon applied — you saved %s%s.', config('store.currency_symbol'), number_format($discount, 2)),
            'coupon' => $coupon,
            'discount' => $discount,
        ];
    }

    protected function fail(string $message): array
    {
        return ['ok' => false, 'message' => $message, 'coupon' => null, 'discount' => 0.0];
    }
}
