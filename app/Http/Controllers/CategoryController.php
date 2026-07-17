<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function __construct(
        protected CategoryRepositoryInterface $categories,
        protected ProductRepositoryInterface $products,
    ) {}

    public function index(): View
    {
        return view('categories.index', [
            'categories' => $this->categories->withProductCounts(),
        ]);
    }

    public function show(Category $category): View
    {
        $perPage = (int) config('store.products_per_page', 24);
        $products = $this->products->paginateFiltered(['category_id' => $category->id], $perPage)
            ->withQueryString();

        return view('categories.show', [
            'category' => $category,
            'products' => $products,
        ]);
    }
}
