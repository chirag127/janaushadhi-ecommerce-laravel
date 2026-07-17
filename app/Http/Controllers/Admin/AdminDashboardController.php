<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repositories\Contracts\OrderRepositoryInterface;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function __construct(
        protected OrderRepositoryInterface $orders,
        protected ProductRepositoryInterface $products,
    ) {}

    public function index(): View
    {
        $salesSummary = $this->orders->salesSummary();
        $countByStatus = $this->orders->countByStatus();
        $lowStock = $this->products->lowStock(10, 50);
        $totalCustomers = User::where('role', User::ROLE_CUSTOMER)->count();
        $totalProducts = \App\Models\Product::where('is_active', true)->count();

        return view('admin.dashboard', [
            'salesSummary' => $salesSummary,
            'countByStatus' => $countByStatus,
            'lowStock' => $lowStock,
            'totalCustomers' => $totalCustomers,
            'totalProducts' => $totalProducts,
        ]);
    }
}
