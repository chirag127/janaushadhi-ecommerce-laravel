<?php

namespace App\Services;

use App\Models\CartItem;
use App\Models\User;
use App\Models\WishlistItem;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Support\Collection;

class WishlistService
{
    public function __construct(
        protected ProductRepositoryInterface $products
    ) {}

    public function items(User $user): Collection
    {
        return WishlistItem::with('product.category')
            ->where('user_id', $user->id)
            ->get()
            ->filter(fn (WishlistItem $i) => $i->product !== null)
            ->values();
    }

    public function toggle(User $user, string $productId): bool
    {
        $existing = WishlistItem::where('user_id', $user->id)
            ->where('product_id', $productId)->first();

        if ($existing) {
            $existing->delete();

            return false; // now removed
        }

        abort_if($this->products->findById($productId) === null, 404);

        WishlistItem::create(['user_id' => $user->id, 'product_id' => $productId]);

        return true; // now added
    }

    public function has(User $user, string $productId): bool
    {
        return WishlistItem::where('user_id', $user->id)
            ->where('product_id', $productId)->exists();
    }

    public function remove(User $user, string $productId): void
    {
        WishlistItem::where('user_id', $user->id)->where('product_id', $productId)->delete();
    }

    public function count(User $user): int
    {
        return WishlistItem::where('user_id', $user->id)->count();
    }

    /** Move a wishlisted product into the cart. */
    public function moveToCart(User $user, string $productId, CartService $cart): void
    {
        $cart->add($user, $productId, 1);
        $this->remove($user, $productId);
    }
}
