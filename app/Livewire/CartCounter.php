<?php

namespace App\Livewire;

use App\Services\CartService;
use Livewire\Attributes\On;
use Livewire\Component;

class CartCounter extends Component
{
    public int $count = 0;

    public function mount(): void
    {
        $this->refresh();
    }

    #[On('cart-count-changed')]
    public function refresh(): void
    {
        if (auth()->check()) {
            /** @var CartService $cartService */
            $cartService = app(CartService::class);
            $this->count = $cartService->count(auth()->user());
        } else {
            $this->count = 0;
        }
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.cart-counter');
    }
}
