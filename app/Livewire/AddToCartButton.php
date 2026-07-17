<?php

namespace App\Livewire;

use App\Models\Product;
use App\Services\CartService;
use Livewire\Component;

class AddToCartButton extends Component
{
    public string $productId  = '';
    public string $productSlug = '';
    public bool   $inStock    = false;
    public int    $quantity   = 1;
    public bool   $added      = false;

    public function mount(Product $product): void
    {
        $this->productId   = $product->id;
        $this->productSlug = $product->slug;
        $this->inStock     = $product->inStock();
    }

    public function incrementQty(): void
    {
        $this->quantity++;
    }

    public function decrementQty(): void
    {
        if ($this->quantity > 1) {
            $this->quantity--;
        }
    }

    public function add(CartService $cartService): mixed
    {
        if (! auth()->check()) {
            return redirect()->route('login');
        }

        $cartService->add(auth()->user(), $this->productId, $this->quantity);

        $this->dispatch('cart-count-changed');
        $this->dispatchSelf('cart-updated');
        $this->js("window.dispatchEvent(new CustomEvent('cart-updated', { detail: { productId: '{$this->productId}' } }))");

        $this->added = true;

        // Reset the flag after a short delay via a browser event listener.
        $this->js("setTimeout(() => \$wire.set('added', false), 2000)");

        return null;
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.add-to-cart-button');
    }
}
