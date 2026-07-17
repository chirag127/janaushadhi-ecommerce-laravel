<div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
    <div class="flex flex-col gap-6 lg:flex-row">

        {{-- ============================================================
             SIDEBAR — filters
        ============================================================ --}}
        <aside class="w-full shrink-0 lg:w-64" aria-label="Product filters">
            <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm space-y-6">

                {{-- Search within catalog --}}
                <div>
                    <label for="catalog-search" class="block text-sm font-semibold text-gray-700 mb-1">Search</label>
                    <input
                        id="catalog-search"
                        type="search"
                        wire:model.live.debounce.300ms="search"
                        placeholder="Search medicines…"
                        class="w-full rounded-lg border-gray-300 text-sm focus:border-brand-500 focus:ring-brand-500"
                    />
                </div>

                {{-- Sort --}}
                <div>
                    <label for="catalog-sort" class="block text-sm font-semibold text-gray-700 mb-1">Sort by</label>
                    <select id="catalog-sort" wire:model.live="sort"
                        class="w-full rounded-lg border-gray-300 text-sm focus:border-brand-500 focus:ring-brand-500">
                        <option value="relevance">Relevance</option>
                        <option value="price_asc">Price: Low → High</option>
                        <option value="price_desc">Price: High → Low</option>
                        <option value="name">Name A–Z</option>
                        <option value="newest">Newest first</option>
                    </select>
                </div>

                {{-- Category --}}
                <fieldset>
                    <legend class="text-sm font-semibold text-gray-700 mb-2">Category</legend>
                    <div class="space-y-1 max-h-52 overflow-y-auto pr-1">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input
                                type="radio"
                                wire:model.live="category"
                                value=""
                                class="text-brand-600 focus:ring-brand-500"
                            />
                            <span class="text-sm text-gray-700">All categories</span>
                        </label>
                        @foreach ($categories as $cat)
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input
                                type="radio"
                                wire:model.live="category"
                                value="{{ $cat->slug }}"
                                class="text-brand-600 focus:ring-brand-500"
                            />
                            <span class="text-sm text-gray-700">{{ $cat->name }}</span>
                        </label>
                        @endforeach
                    </div>
                </fieldset>

                {{-- Price range --}}
                <fieldset>
                    <legend class="text-sm font-semibold text-gray-700 mb-2">Price range (₹)</legend>
                    <div class="flex items-center gap-2">
                        <input
                            type="number"
                            wire:model.live.debounce.400ms="minPrice"
                            placeholder="Min"
                            min="0"
                            aria-label="Minimum price"
                            class="w-full rounded-lg border-gray-300 text-sm focus:border-brand-500 focus:ring-brand-500"
                        />
                        <span class="text-gray-400">–</span>
                        <input
                            type="number"
                            wire:model.live.debounce.400ms="maxPrice"
                            placeholder="Max"
                            min="0"
                            aria-label="Maximum price"
                            class="w-full rounded-lg border-gray-300 text-sm focus:border-brand-500 focus:ring-brand-500"
                        />
                    </div>
                </fieldset>

                {{-- In-stock toggle --}}
                <div class="flex items-center justify-between">
                    <span class="text-sm font-semibold text-gray-700">In stock only</span>
                    <button
                        type="button"
                        wire:click="$toggle('inStock')"
                        role="switch"
                        aria-checked="{{ $inStock ? 'true' : 'false' }}"
                        aria-label="Show in-stock products only"
                        class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-1 {{ $inStock ? 'bg-brand-600' : 'bg-gray-300' }}"
                    >
                        <span class="inline-block h-4 w-4 transform rounded-full bg-white shadow transition-transform {{ $inStock ? 'translate-x-6' : 'translate-x-1' }}"></span>
                    </button>
                </div>

                {{-- Clear filters --}}
                <button
                    type="button"
                    wire:click="clearFilters"
                    class="w-full rounded-lg border border-gray-300 py-1.5 text-sm text-gray-600 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-brand-500"
                >
                    Clear all filters
                </button>
            </div>
        </aside>

        {{-- ============================================================
             MAIN — product grid
        ============================================================ --}}
        <div class="flex-1 min-w-0">

            {{-- Loading overlay --}}
            <div wire:loading.block class="mb-4 flex items-center gap-2 text-sm text-brand-700">
                <svg class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                </svg>
                <span>Loading products…</span>
            </div>

            {{-- Result count --}}
            <div wire:loading.remove class="mb-4 text-sm text-gray-500">
                {{ number_format($products->total()) }} {{ Str::plural('product', $products->total()) }} found
                @if ($search)
                    for &ldquo;{{ $search }}&rdquo;
                @endif
            </div>

            {{-- Empty state --}}
            @if ($products->isEmpty())
            <div wire:loading.remove class="flex flex-col items-center justify-center rounded-xl border-2 border-dashed border-gray-200 py-24 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M20 13V7a2 2 0 00-2-2H6a2 2 0 00-2 2v6m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0H4" />
                </svg>
                <p class="mt-4 text-lg font-medium text-gray-700">No products found</p>
                <p class="mt-1 text-sm text-gray-500">Try adjusting your filters or search term.</p>
                <button wire:click="clearFilters" type="button"
                    class="mt-4 rounded-lg bg-brand-600 px-5 py-2 text-sm font-medium text-white hover:bg-brand-700 focus:outline-none focus:ring-2 focus:ring-brand-500">
                    Clear filters
                </button>
            </div>
            @else

            {{-- Product grid --}}
            <div class="grid grid-cols-2 gap-4 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4" wire:loading.class="opacity-50">
                @foreach ($products as $product)
                <article class="group relative flex flex-col rounded-xl border border-gray-100 bg-white shadow-sm hover:shadow-md transition-shadow">

                    {{-- Wishlist toggle overlay --}}
                    <div class="absolute right-2 top-2 z-10">
                        @livewire('wishlist-toggle', ['product' => $product], key('wl-'.$product->id))
                    </div>

                    {{-- Product image --}}
                    <a href="{{ route('products.show', $product->slug) }}" wire:navigate class="block overflow-hidden rounded-t-xl">
                        <img
                            src="{{ $product->imageOrPlaceholder() }}"
                            alt="{{ $product->name }}"
                            loading="lazy"
                            class="h-44 w-full object-cover transition-transform duration-300 group-hover:scale-105"
                        />
                    </a>

                    {{-- Discount badge --}}
                    @if ($product->discountPercent() > 0)
                    <span class="absolute left-2 top-2 rounded bg-accent-500 px-1.5 py-0.5 text-xs font-bold text-white">
                        {{ $product->discountPercent() }}% OFF
                    </span>
                    @endif

                    <div class="flex flex-1 flex-col p-3">
                        {{-- Name --}}
                        <a
                            href="{{ route('products.show', $product->slug) }}"
                            wire:navigate
                            class="mb-1 line-clamp-2 text-sm font-medium text-gray-800 hover:text-brand-700 leading-snug"
                        >
                            {{ $product->name }}
                        </a>

                        {{-- Unit size --}}
                        @if ($product->unit_size)
                        <p class="mb-2 text-xs text-gray-400">{{ $product->unit_size }}</p>
                        @endif

                        {{-- Price --}}
                        <div class="mt-auto flex items-baseline gap-1.5">
                            <span class="text-base font-bold text-brand-700">
                                ₹{{ number_format((float) $product->price, 2) }}
                            </span>
                            @if ($product->discountPercent() > 0)
                            <span class="text-xs text-gray-400 line-through">
                                ₹{{ number_format((float) $product->mrp, 2) }}
                            </span>
                            @endif
                        </div>

                        {{-- Add to cart / out of stock --}}
                        <div class="mt-3">
                            @livewire('add-to-cart-button', ['product' => $product], key('atc-'.$product->id))
                        </div>
                    </div>
                </article>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="mt-8">
                {{ $products->links() }}
            </div>

            @endif
        </div>
    </div>
</div>
