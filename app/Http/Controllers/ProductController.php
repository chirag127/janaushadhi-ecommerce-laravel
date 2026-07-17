<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Repositories\Contracts\ReviewRepositoryInterface;
use App\Services\WishlistService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function __construct(
        protected ProductRepositoryInterface $products,
        protected CategoryRepositoryInterface $categories,
        protected ReviewRepositoryInterface $reviews,
        protected WishlistService $wishlist,
    ) {}

    public function index(Request $request): View
    {
        $filters = $request->only(['search', 'category', 'min_price', 'max_price', 'in_stock', 'sort']);

        $perPage = (int) config('store.products_per_page', 24);
        $products = $this->products->paginateFiltered($filters, $perPage)->withQueryString();

        return view('products.index', [
            'products' => $products,
            'categories' => $this->categories->ordered(),
            'filters' => $filters,
        ]);
    }

    public function show(Product $product): View
    {
        $related = $this->products->related($product, 4);
        $avgRating = $this->reviews->averageForProduct($product->id);
        $reviewCount = $this->reviews->countForProduct($product->id);
        $wishlisted = false;

        if ($user = auth()->user()) {
            $wishlisted = $this->wishlist->has($user, $product->id);
        }

        return view('products.show', [
            'product' => $product,
            'related' => $related,
            'avgRating' => $avgRating,
            'reviewCount' => $reviewCount,
            'wishlisted' => $wishlisted,
        ]);
    }
}
