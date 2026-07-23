@extends('layouts.app')

@section('title', ($filters['search'] ?? '') !== '' ? 'Search: ' . $filters['search'] : 'Products')

@section('content')
<div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
    <nav class="mb-4 text-sm text-gray-500" aria-label="Breadcrumb">
        <a href="{{ route('home') }}" wire:navigate class="hover:text-brand-700">Home</a>
        <span class="mx-1">/</span>
        <span class="text-gray-700 dark:text-gray-300">Products</span>
    </nav>

    <div class="flex flex-col gap-6 lg:flex-row">
        {{-- Filters sidebar --}}
        <aside class="w-full shrink-0 lg:w-64" aria-label="Product filters">
            <form method="GET" action="{{ route('products.index') }}" class="space-y-6 rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <div>
                    <label for="search" class="mb-1 block text-sm font-semibold text-gray-700 dark:text-gray-200">Search</label>
                    <input id="search" type="search" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Search medicines…" class="form-input text-sm" />
                </div>

                <div>
                    <label for="sort" class="mb-1 block text-sm font-semibold text-gray-700 dark:text-gray-200">Sort by</label>
                    <select id="sort" name="sort" class="form-select text-sm">
                        @foreach (['relevance' => 'Relevance', 'price_asc' => 'Price: Low → High', 'price_desc' => 'Price: High → Low', 'name' => 'Name A–Z', 'newest' => 'Newest first'] as $val => $label)
                            <option value="{{ $val }}" @selected(($filters['sort'] ?? 'relevance') === $val)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <fieldset>
                    <legend class="mb-2 text-sm font-semibold text-gray-700 dark:text-gray-200">Category</legend>
                    <select name="category" class="form-select text-sm">
                        <option value="">All categories</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->slug }}" @selected(($filters['category'] ?? '') === $cat->slug)>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </fieldset>

                <fieldset>
                    <legend class="mb-2 text-sm font-semibold text-gray-700 dark:text-gray-200">Price range (₹)</legend>
                    <div class="flex items-center gap-2">
                        <input type="number" name="min_price" value="{{ $filters['min_price'] ?? '' }}" placeholder="Min" min="0" aria-label="Minimum price" class="form-input text-sm" />
                        <span class="text-gray-400">–</span>
                        <input type="number" name="max_price" value="{{ $filters['max_price'] ?? '' }}" placeholder="Max" min="0" aria-label="Maximum price" class="form-input text-sm" />
                    </div>
                </fieldset>

                <label class="flex items-center gap-2">
                    <input type="checkbox" name="in_stock" value="1" @checked(! empty($filters['in_stock'])) class="rounded text-brand-600 focus:ring-brand-500" />
                    <span class="text-sm text-gray-700 dark:text-gray-200">In stock only</span>
                </label>

                <div class="flex gap-2">
                    <button type="submit" class="btn-primary flex-1 text-sm">Apply</button>
                    <a href="{{ route('products.index') }}" class="btn-secondary text-sm">Clear</a>
                </div>
            </form>
        </aside>

        {{-- Product grid --}}
        <div class="min-w-0 flex-1">
            <p class="mb-4 text-sm text-gray-500">
                {{ number_format($products->total()) }} {{ Str::plural('product', $products->total()) }} found
                @if (($filters['search'] ?? '') !== '')
                    for &ldquo;{{ $filters['search'] }}&rdquo;
                @endif
            </p>

            @if ($products->isEmpty())
                <div class="flex flex-col items-center justify-center rounded-xl border-2 border-dashed border-gray-200 py-24 text-center dark:border-gray-700">
                    <p class="text-lg font-medium text-gray-700 dark:text-gray-200">No products found</p>
                    <p class="mt-1 text-sm text-gray-500">Try adjusting your filters or search term.</p>
                    <a href="{{ route('products.index') }}" class="btn-primary mt-4 text-sm">Clear filters</a>
                </div>
            @else
                <div class="grid grid-cols-2 gap-4 md:grid-cols-3 xl:grid-cols-4">
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
