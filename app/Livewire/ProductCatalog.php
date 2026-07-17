<?php

namespace App\Livewire;

use App\Repositories\Contracts\CategoryRepositoryInterface;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class ProductCatalog extends Component
{
    use WithPagination;

    // ---- URL-bound filter props ------------------------------------------------

    #[Url(as: 'search', except: '')]
    public string $search = '';

    #[Url(as: 'category', except: '')]
    public string $category = '';

    #[Url(as: 'sort', except: 'relevance')]
    public string $sort = 'relevance';

    #[Url(as: 'minPrice', except: '')]
    public string $minPrice = '';

    #[Url(as: 'maxPrice', except: '')]
    public string $maxPrice = '';

    #[Url(as: 'inStock', except: false)]
    public bool $inStock = false;

    // ---- Optional prop passed by mount when embedded on a category page --------

    public ?string $categoryId = null;

    // ---- Lifecycle -------------------------------------------------------------

    public function mount(?string $categoryId = null, ?string $categorySlug = null): void
    {
        $this->categoryId = $categoryId;

        // If a category slug is forced from the parent page, pre-fill the URL prop.
        if ($categorySlug !== null && $this->category === '') {
            $this->category = $categorySlug;
        }
    }

    // ---- Reset page on any filter change --------------------------------------

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedCategory(): void
    {
        $this->resetPage();
    }

    public function updatedSort(): void
    {
        $this->resetPage();
    }

    public function updatedMinPrice(): void
    {
        $this->resetPage();
    }

    public function updatedMaxPrice(): void
    {
        $this->resetPage();
    }

    public function updatedInStock(): void
    {
        $this->resetPage();
    }

    // ---- Filter helpers -------------------------------------------------------

    public function clearFilters(): void
    {
        $this->search   = '';
        $this->category = '';
        $this->sort     = 'relevance';
        $this->minPrice = '';
        $this->maxPrice = '';
        $this->inStock  = false;
        $this->resetPage();
    }

    // ---- Render ---------------------------------------------------------------

    public function render(): \Illuminate\View\View
    {
        /** @var ProductRepositoryInterface $productRepo */
        $productRepo = app(ProductRepositoryInterface::class);

        /** @var CategoryRepositoryInterface $categoryRepo */
        $categoryRepo = app(CategoryRepositoryInterface::class);

        $filters = [
            'search'    => $this->search   !== '' ? $this->search   : null,
            'category'  => $this->category !== '' ? $this->category : null,
            'sort'      => $this->sort,
            'min_price' => $this->minPrice !== '' ? (float) $this->minPrice : null,
            'max_price' => $this->maxPrice !== '' ? (float) $this->maxPrice : null,
            'in_stock'  => $this->inStock  ?: null,
        ];

        $products   = $productRepo->paginateFiltered($filters, config('store.products_per_page'));
        $categories = $categoryRepo->ordered();

        return view('livewire.product-catalog', compact('products', 'categories'));
    }
}
