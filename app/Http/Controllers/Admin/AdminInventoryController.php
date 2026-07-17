<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminInventoryController extends Controller
{
    public function __construct(
        protected ProductRepositoryInterface $products,
    ) {}

    public function index(Request $request): View
    {
        $filters = $request->only(['search', 'category']);
        $products = $this->products->adminPaginate($filters, 50)->withQueryString();
        $lowStockThreshold = 10;

        return view('admin.inventory.index', [
            'products' => $products,
            'lowStockThreshold' => $lowStockThreshold,
            'filters' => $filters,
        ]);
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'stock' => ['required', 'integer', 'min:0'],
        ]);

        $this->products->update($product, ['stock' => $validated['stock']]);

        return redirect()->back()->with('success', "Stock updated for {$product->name}.");
    }
}
