<?php

namespace App\Livewire;

use App\Services\CartService;
use App\Services\CouponService;
use Livewire\Component;

class CartManager extends Component
{
    public string $couponCode   = '';
    public array  $couponResult = [];
    public float  $discount     = 0.0;

    // ---- Cart actions ---------------------------------------------------------

    public function incrementQty(string $cartItemId, CartService $cartService): void
    {
        $user = auth()->user();

        if (! $user) {
            return;
        }

        $item = $cartService->items($user)->firstWhere('id', $cartItemId);

        if ($item) {
            $cartService->updateQuantity($user, $cartItemId, $item->quantity + 1);
        }

        $this->dispatch('cart-count-changed');
    }

    public function decrementQty(string $cartItemId, CartService $cartService): void
    {
        $user = auth()->user();

        if (! $user) {
            return;
        }

        $item = $cartService->items($user)->firstWhere('id', $cartItemId);

        if ($item) {
            $newQty = $item->quantity - 1;
            $cartService->updateQuantity($user, $cartItemId, $newQty);
        }

        $this->dispatch('cart-count-changed');
    }

    public function updateQty(string $cartItemId, int $qty, CartService $cartService): void
    {
        $user = auth()->user();

        if (! $user) {
            return;
        }

        $cartService->updateQuantity($user, $cartItemId, $qty);
        $this->dispatch('cart-count-changed');
    }

    public function removeItem(string $cartItemId, CartService $cartService): void
    {
        $user = auth()->user();

        if (! $user) {
            return;
        }

        $cartService->remove($user, $cartItemId);
        $this->dispatch('cart-count-changed');
    }

    // ---- Coupon actions -------------------------------------------------------

    public function applyCoupon(CartService $cartService, CouponService $couponService): void
    {
        $user = auth()->user();

        if (! $user) {
            return;
        }

        $subtotal = $cartService->subtotal($user);
        $result   = $couponService->validate($this->couponCode, $subtotal, $user);

        $this->couponResult = $result;
        $this->discount     = $result['ok'] ? (float) $result['discount'] : 0.0;

        // Store the coupon code in session for the checkout page.
        if ($result['ok']) {
            session(['applied_coupon_code' => $this->couponCode, 'applied_discount' => $this->discount]);
        }
    }

    public function removeCoupon(): void
    {
        $this->couponCode   = '';
        $this->couponResult = [];
        $this->discount     = 0.0;
        session()->forget(['applied_coupon_code', 'applied_discount']);
    }

    // ---- Render ---------------------------------------------------------------

    public function render(CartService $cartService): \Illuminate\View\View
    {
        $user = auth()->user();

        $items   = $user ? $cartService->items($user) : collect();
        $summary = $user ? $cartService->summary($user, $this->discount) : [
            'subtotal' => 0.0,
            'discount' => 0.0,
            'shipping' => 0.0,
            'tax'      => 0.0,
            'total'    => 0.0,
        ];

        $freeShippingThreshold = config('store.free_shipping_threshold');
        $currencySymbol        = config('store.currency_symbol');

        return view('livewire.cart-manager', compact('items', 'summary', 'freeShippingThreshold', 'currencySymbol'));
    }
}
