<?php

namespace App\Services;

use App\Models\CartItem;
use App\Models\User;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Support\Collection;

/**
 * Cart operations for an authenticated user. Cart lines live in
 * laravel_cart_items; product data is READ from the shared catalog.
 */
class CartService
{
    public function __construct(
        protected ProductRepositoryInterface $products
    ) {}

    public function items(User $user): Collection
    {
        return CartItem::with('product.category')
            ->where('user_id', $user->id)
            ->get()
            ->filter(fn (CartItem $i) => $i->product !== null && $i->product->is_active)
            ->values();
    }

    public function add(User $user, string $productId, int $quantity = 1): CartItem
    {
        $product = $this->products->findById($productId);

        abort_if($product === null || ! $product->is_active, 404, 'Product not available.');

        $quantity = max(1, $quantity);

        $item = CartItem::firstOrNew([
            'user_id' => $user->id,
            'product_id' => $productId,
        ]);

        $newQty = ($item->exists ? $item->quantity : 0) + $quantity;

        // Never let cart quantity exceed available stock.
        $item->quantity = min($newQty, max(1, (int) $product->stock));
        $item->save();

        return $item;
    }

    public function updateQuantity(User $user, string $cartItemId, int $quantity): void
    {
        $item = CartItem::with('product')->where('user_id', $user->id)->findOrFail($cartItemId);

        if ($quantity <= 0) {
            $item->delete();

            return;
        }

        $max = max(1, (int) ($item->product->stock ?? 1));
        $item->update(['quantity' => min($quantity, $max)]);
    }

    public function remove(User $user, string $cartItemId): void
    {
        CartItem::where('user_id', $user->id)->where('id', $cartItemId)->delete();
    }

    public function clear(User $user): void
    {
        CartItem::where('user_id', $user->id)->delete();
    }

    public function count(User $user): int
    {
        return (int) CartItem::where('user_id', $user->id)->sum('quantity');
    }

    public function subtotal(User $user): float
    {
        return (float) $this->items($user)->sum(fn (CartItem $i) => $i->lineTotal());
    }

    /** Full monetary breakdown used by cart + checkout. */
    public function summary(User $user, float $discount = 0): array
    {
        $subtotal = $this->subtotal($user);
        $discount = min($discount, $subtotal);
        $taxable = max(0, $subtotal - $discount);

        $threshold = (float) config('store.free_shipping_threshold');
        $shipping = ($subtotal <= 0 || $subtotal >= $threshold) ? 0.0 : (float) config('store.shipping_fee');

        $tax = round($taxable * ((float) config('store.tax_rate') / 100), 2);
        $total = round($taxable + $shipping + $tax, 2);

        return [
            'subtotal' => round($subtotal, 2),
            'discount' => round($discount, 2),
            'shipping' => $shipping,
            'tax' => $tax,
            'total' => $total,
        ];
    }
}
