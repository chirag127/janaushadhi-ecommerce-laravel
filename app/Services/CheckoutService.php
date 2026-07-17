<?php

namespace App\Services;

use App\Jobs\SendOrderConfirmationEmail;
use App\Models\CartItem;
use App\Models\CouponRedemption;
use App\Models\Order;
use App\Models\User;
use App\Repositories\Contracts\CouponRepositoryInterface;
use App\Repositories\Contracts\OrderRepositoryInterface;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use RuntimeException;

/**
 * Orchestrates order placement: validates stock, computes totals, persists the
 * order + items in a transaction, decrements shared-catalog stock, records
 * coupon redemption and clears the cart. Queues the confirmation email.
 */
class CheckoutService
{
    public function __construct(
        protected CartService $cart,
        protected CouponService $couponService,
        protected OrderRepositoryInterface $orders,
        protected ProductRepositoryInterface $products,
        protected CouponRepositoryInterface $coupons,
    ) {}

    public function placeOrder(User $user, array $data): Order
    {
        $items = $this->cart->items($user);

        if ($items->isEmpty()) {
            throw new RuntimeException('Your cart is empty.');
        }

        // Re-validate stock at purchase time.
        foreach ($items as $item) {
            if ((int) $item->product->stock < $item->quantity) {
                throw new RuntimeException("Insufficient stock for {$item->product->name}.");
            }
        }

        $discount = 0.0;
        $coupon = null;
        if (! empty($data['coupon_code'])) {
            $result = $this->couponService->validate($data['coupon_code'], $this->cart->subtotal($user), $user);
            if ($result['ok']) {
                $discount = $result['discount'];
                $coupon = $result['coupon'];
            }
        }

        $summary = $this->cart->summary($user, $discount);

        return DB::transaction(function () use ($user, $data, $items, $summary, $coupon) {
            $order = $this->orders->create([
                'order_number' => $this->generateOrderNumber(),
                'user_id' => $user->id,
                'coupon_id' => $coupon?->id,
                'status' => 'confirmed',
                'payment_status' => ($data['payment_method'] ?? 'cod') === 'cod' ? 'unpaid' : 'paid',
                'payment_method' => $data['payment_method'] ?? 'cod',
                'subtotal' => $summary['subtotal'],
                'discount' => $summary['discount'],
                'shipping' => $summary['shipping'],
                'tax' => $summary['tax'],
                'total' => $summary['total'],
                'shipping_address' => $data['shipping_address'],
                'notes' => $data['notes'] ?? null,
                'placed_at' => now(),
            ]);

            $this->orders->addItems($order, $items->map(fn (CartItem $i) => [
                'product_id' => $i->product_id,
                'product_name' => $i->product->name,
                'product_slug' => $i->product->slug,
                'unit_price' => $i->product->price,
                'quantity' => $i->quantity,
                'line_total' => $i->lineTotal(),
            ])->all());

            // Decrement shared-catalog stock.
            foreach ($items as $i) {
                $this->products->decrementStock($i->product_id, $i->quantity);
            }

            if ($coupon) {
                $this->coupons->incrementUsage($coupon);
                CouponRedemption::create([
                    'coupon_id' => $coupon->id,
                    'user_id' => $user->id,
                    'order_id' => $order->id,
                    'amount' => $summary['discount'],
                ]);
            }

            $this->cart->clear($user);

            // Queue the confirmation email (queue-ready; falls back to sync).
            SendOrderConfirmationEmail::dispatch($order->id);

            return $order;
        });
    }

    protected function generateOrderNumber(): string
    {
        return 'JA-'.now()->format('Ymd').'-'.strtoupper(Str::random(6));
    }
}
