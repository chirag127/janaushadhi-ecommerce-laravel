<?php

namespace App\Livewire;

use App\Repositories\Contracts\ProductRepositoryInterface;
use Livewire\Attributes\Url;
use Livewire\Component;

class ProductSearch extends Component
{
    public string $term = '';

    /** @var array<int,array{id:string,name:string,slug:string,price:string}> */
    public array $suggestions = [];

    public bool $showDropdown = false;

    public function updatedTerm(string $value): void
    {
        $trimmed = trim($value);

        if (strlen($trimmed) < 2) {
            $this->suggestions = [];
            $this->showDropdown = false;

            return;
        }

        /** @var ProductRepositoryInterface $repo */
        $repo = app(ProductRepositoryInterface::class);

        $this->suggestions = $repo->searchSuggestions($trimmed, 6)
            ->map(fn ($product) => [
                'id'    => $product->id,
                'name'  => $product->name,
                'slug'  => $product->slug,
                'price' => config('store.currency_symbol') . number_format((float) $product->price, 2),
            ])
            ->all();

        $this->showDropdown = count($this->suggestions) > 0;
    }

    public function search(): mixed
    {
        $term = trim($this->term);

        $this->suggestions  = [];
        $this->showDropdown = false;

        if ($term === '') {
            return null;
        }

        return redirect()->route('products.index', ['search' => $term]);
    }

    public function closeDropdown(): void
    {
        $this->showDropdown = false;
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.product-search');
    }
}
