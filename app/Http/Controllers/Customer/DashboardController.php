<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Services\OrderService;
use App\Services\WishlistService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        protected OrderService $orderService,
        protected WishlistService $wishlist,
    ) {}

    public function index(): View
    {
        $user = auth()->user();
        $recentOrders = $this->orderService->recentForUser($user, 5);
        $orderCount = $user->orders()->count();
        $wishlistCount = $this->wishlist->count($user);
        $defaultAddress = $user->addresses()->where('is_default', true)->first();

        return view('customer.dashboard', [
            'recentOrders' => $recentOrders,
            'orderCount' => $orderCount,
            'wishlistCount' => $wishlistCount,
            'defaultAddress' => $defaultAddress,
        ]);
    }
}
