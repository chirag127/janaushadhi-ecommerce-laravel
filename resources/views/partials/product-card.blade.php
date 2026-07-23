<article class="group relative flex flex-col rounded-xl border border-gray-100 bg-white shadow-sm transition-shadow hover:shadow-md dark:border-gray-700 dark:bg-gray-800">
    <div class="absolute right-2 top-2 z-10">
        @livewire('wishlist-toggle', ['product' => $product], key('home-wl-'.$product->id))
    </div>

    <a href="{{ route('products.show', $product->slug) }}" wire:navigate class="block overflow-hidden rounded-t-xl">
        <img src="{{ $product->imageOrPlaceholder() }}" alt="{{ $product->name }}" loading="lazy" class="h-44 w-full object-cover transition-transform duration-300 group-hover:scale-105" />
    </a>

    @if ($product->discountPercent() > 0)
        <span class="absolute left-2 top-2 rounded bg-accent-500 px-1.5 py-0.5 text-xs font-bold text-white">{{ $product->discountPercent() }}% OFF</span>
    @endif

    <div class="flex flex-1 flex-col p-3">
        <a href="{{ route('products.show', $product->slug) }}" wire:navigate class="mb-1 line-clamp-2 text-sm font-medium leading-snug text-gray-800 hover:text-brand-700 dark:text-gray-100">{{ $product->name }}</a>
        @if ($product->unit_size)
            <p class="mb-2 text-xs text-gray-400">{{ $product->unit_size }}</p>
        @endif
        @php($onRequest = ! $product->price || (float) $product->price <= 0)
        <div class="mt-auto flex items-baseline gap-1.5">
            @if ($onRequest)
                <span class="text-sm font-semibold text-brand-700">Available on request</span>
            @else
                <span class="text-base font-bold text-brand-700">₹{{ number_format((float) $product->price, 2) }}</span>
                @if ($product->discountPercent() > 0)
                    <span class="text-xs text-gray-400 line-through">₹{{ number_format((float) $product->mrp, 2) }}</span>
                @endif
            @endif
        </div>
        <div class="mt-3">
            @if ($onRequest)
                <a href="{{ route('contact') }}?product={{ urlencode($product->name) }}" class="block w-full rounded-md border border-brand-600 px-3 py-1.5 text-center text-xs font-medium text-brand-700 hover:bg-brand-50">Request this medicine</a>
            @else
                @livewire('add-to-cart-button', ['product' => $product], key('home-atc-'.$product->id))
            @endif
        </div>
    </div>
</article>
