<?php

namespace App\Livewire;

use App\Services\WishlistService;
use Livewire\Attributes\On;
use Livewire\Component;

class WishlistCounter extends Component
{
    public int $count = 0;

    public function mount(): void
    {
        $this->refresh();
    }

    #[On('wishlist-count-changed')]
    public function refresh(): void
    {
        if (auth()->check()) {
            /** @var WishlistService $wishlistService */
            $wishlistService = app(WishlistService::class);
            $this->count     = $wishlistService->count(auth()->user());
        } else {
            $this->count = 0;
        }
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.wishlist-counter');
    }
}
