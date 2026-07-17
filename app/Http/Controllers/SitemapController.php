<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function __construct(
        protected ProductRepositoryInterface $products,
        protected CategoryRepositoryInterface $categories,
    ) {}

    public function __invoke(): Response
    {
        $activeProducts = $this->products->paginateFiltered(['sort' => 'newest'], 9999)->items();
        $categories = $this->categories->ordered();

        return response()
            ->view('sitemap', [
                'products' => $activeProducts,
                'categories' => $categories,
            ])
            ->header('Content-Type', 'text/xml');
    }
}
