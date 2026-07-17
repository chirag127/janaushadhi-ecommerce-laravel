@extends('layouts.app')

@section('title', ($filters['search'] ?? '') !== '' ? 'Search: ' . $filters['search'] : 'All Products')

@section('content')
<div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
    <nav class="mb-4 text-sm text-gray-500 dark:text-gray-400" aria-label="Breadcrumb">
        <a href="{{ route('home') }}" wire:navigate class="hover:text-brand-600">Home</a>
        <span class="mx-1">/</span>
        <span class="text-gray-700 dark:text-gray-200">Products</span>
    </nav>

    <div class="flex flex-col gap-8 lg:flex-row">
        {{-- Filters --}}
        <aside class="w-full shrink-0 lg:w-64" aria-label="Product filters">
            <form method="GET" action="{{ route('products.index') }}" class="space-y-6 rounded-xl border border-gray-100 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <div>
                    <label for="search" class="mb-1 block text-sm font-semibold text-gray-700 dark:text-gray-200">Search</label>
                    <input id="search" type="search" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Search medicines…" class="form-input text-sm" />
                </div>

                <div>
                    <label for="category" class="mb-1 block text-sm font-semibold text-gray-700 dark:text-gray-200">Category</label>
                    <select id="category" name="category" class="form-select text-sm">
                        <option value="">All categories</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->slug }}" @selected(($filters['category'] ?? '') === $category->slug)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <span class="mb-1 block text-sm font-semibold text-gray-700 dark:text-gray-200">Price range</span>
                    <div class="flex items-center gap-2">
                        <input type="number" name="min_price" value="{{ $filters['min_price'] ?? '' }}" placeholder="Min" min="0" class="form-input text-sm" />
                        <span class="text-gray-400">&ndash;</span>
                        <input type="number" name="max_price" value="{{ $filters['max_price'] ?? '' }}" placeholder="Max" min="0" class="form-input text-sm" />
                    </div>
                </div>

                <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-200">
                    <input type="checkbox" name="in_stock" value="1" @checked(! empty($filters['in_stock'])) class="rounded border-gray-300 text-brand-600 focus:ring-brand-500" />
                    In stock only
                </label>

                <input type="hidden" name="sort" value="{{ $filters['sort'] ?? '' }}" />

                <div class="flex gap-2">
                    <button type="submit" class="btn-primary flex-1">Apply</button>
                    <a href="{{ route('products.index') }}" wire:navigate class="btn-secondary">Reset</a>
                </div>
            </form>
        </aside>

        {{-- Results --}}
        <div class="min-w-0 flex-1">
            <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $products->total() }} {{ Str::plural('product', $products->total()) }} found</p>
                <form method="GET" action="{{ route('products.index') }}" class="flex items-center gap-2">
                    @foreach (['search', 'category', 'min_price', 'max_price', 'in_stock'] as $key)
                        @if (! empty($filters[$key]))
                            <input type="hidden" name="{{ $key }}" value="{{ $filters[$key] }}" />
                        @endif
                    @endforeach
                    <label for="sort" class="text-sm text-gray-500 dark:text-gray-400">Sort:</label>
                    <select id="sort" name="sort" onchange="this.form.submit()" class="form-select text-sm">
                        <option value="" @selected(empty($filters['sort']))>Featured</option>
                        <option value="price_asc" @selected(($filters['sort'] ?? '') === 'price_asc')>Price: Low → High</option>
                        <option value="price_desc" @selected(($filters['sort'] ?? '') === 'price_desc')>Price: High → Low</option>
                        <option value="name" @selected(($filters['sort'] ?? '') === 'name')>Name A → Z</option>
                        <option value="newest" @selected(($filters['sort'] ?? '') === 'newest')>Newest</option>
                    </select>
                </form>
            </div>

            @if ($products->isEmpty())
                <div class="rounded-xl border border-dashed border-gray-300 bg-white p-12 text-center dark:border-gray-700 dark:bg-gray-800">
                    <p class="text-gray-500 dark:text-gray-400">No products match your filters.</p>
                    <a href="{{ route('products.index') }}" wire:navigate class="btn-secondary mt-4">Clear filters</a>
                </div>
            @else
                <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 xl:grid-cols-4">
                    @foreach ($products as $product)
                        @include('partials.product-card', ['product' => $product])
                    @endforeach
                </div>
                <div class="mt-8">{{ $products->links() }}</div>
            @endif
        </div>
    </div>
</div>
@endsection
