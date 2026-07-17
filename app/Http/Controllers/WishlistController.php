<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\WishlistService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WishlistController extends Controller
{
    public function __construct(
        protected WishlistService $wishlist,
    ) {}

    public function index(): View
    {
        return view('wishlist.index', [
            'items' => $this->wishlist->items(auth()->user()),
        ]);
    }

    public function toggle(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'uuid'],
        ]);

        $added = $this->wishlist->toggle(auth()->user(), $validated['product_id']);

        $message = $added ? 'Added to wishlist.' : 'Removed from wishlist.';

        return redirect()->back()->with('success', $message);
    }

    public function destroy(Product $product): RedirectResponse
    {
        $this->wishlist->remove(auth()->user(), $product->id);

        return redirect()->back()->with('success', 'Removed from wishlist.');
    }
}
