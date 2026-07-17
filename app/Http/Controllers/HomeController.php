<?php

namespace App\Http\Controllers;

use App\Repositories\Contracts\CategoryRepositoryInterface;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __construct(
        protected ProductRepositoryInterface $products,
        protected CategoryRepositoryInterface $categories,
    ) {}

    public function index(): View
    {
        return view('home', [
            'featuredProducts' => $this->products->featured(8),
            'categories' => $this->categories->withProductCounts(),
        ]);
    }
}
