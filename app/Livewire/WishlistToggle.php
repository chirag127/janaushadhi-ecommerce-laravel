<?php

namespace App\Livewire;

use App\Models\Product;
use App\Services\WishlistService;
use Livewire\Component;

class WishlistToggle extends Component
{
    public string $productId = '';
    public bool   $isWished  = false;

    public function mount(Product $product): void
    {
        $this->productId = $product->id;

        if (auth()->check()) {
            /** @var WishlistService $wishlistService */
            $wishlistService = app(WishlistService::class);
            $this->isWished  = $wishlistService->has(auth()->user(), $product->id);
        }
    }

    public function toggle(WishlistService $wishlistService): mixed
    {
        if (! auth()->check()) {
            return redirect()->route('login');
        }

        $this->isWished = $wishlistService->toggle(auth()->user(), $this->productId);
        $this->dispatch('wishlist-count-changed');

        return null;
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.wishlist-toggle');
    }
}
