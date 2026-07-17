<?php

namespace App\Http\Middleware;

use App\Services\CartService;
use App\Services\WishlistService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

/**
 * Shares live cart + wishlist counts with every view so the header badges
 * stay accurate on full page loads.
 */
class ShareCartCount
{
    public function __construct(
        protected CartService $cart,
        protected WishlistService $wishlist,
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        View::composer('*', function ($view) use ($request) {
            $user = $request->user();
            $view->with('globalCartCount', $user ? $this->cart->count($user) : 0);
            $view->with('globalWishlistCount', $user ? $this->wishlist->count($user) : 0);
        });

        return $next($request);
    }
}
