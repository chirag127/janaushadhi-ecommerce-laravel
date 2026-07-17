<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Services\CartService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function __construct(
        protected CartService $cart,
    ) {}

    public function index(): View
    {
        $user = auth()->user();

        return view('cart.index', [
            'items' => $this->cart->items($user),
            'summary' => $this->cart->summary($user),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'uuid'],
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $this->cart->add(auth()->user(), $validated['product_id'], (int) $validated['quantity']);

        return redirect()->back()->with('success', 'Item added to cart.');
    }

    public function update(Request $request, CartItem $cartItem): RedirectResponse
    {
        $this->authorizeCartItem($cartItem);

        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:0'],
        ]);

        $this->cart->updateQuantity(auth()->user(), $cartItem->id, (int) $validated['quantity']);

        return redirect()->back()->with('success', 'Cart updated.');
    }

    public function destroy(CartItem $cartItem): RedirectResponse
    {
        $this->authorizeCartItem($cartItem);

        $this->cart->remove(auth()->user(), $cartItem->id);

        return redirect()->back()->with('success', 'Item removed from cart.');
    }

    protected function authorizeCartItem(CartItem $cartItem): void
    {
        if ($cartItem->user_id !== auth()->id()) {
            abort(403);
        }
    }
}
