{{-- Reusable product card. Expects $product (App\Models\Product). --}}
@php $symbol = config('store.currency_symbol'); @endphp
<article class="group flex flex-col overflow-hidden rounded-xl border border-gray-100 bg-white shadow-sm transition hover:shadow-md dark:border-gray-700 dark:bg-gray-800">
    <a href="{{ route('products.show', $product->slug) }}" wire:navigate class="relative block overflow-hidden">
        <img src="{{ $product->imageOrPlaceholder() }}" alt="{{ $product->name }}" class="aspect-square w-full object-cover transition group-hover:scale-105" loading="lazy" />
        @if ($product->discountPercent() > 0)
            <span class="absolute left-2 top-2 rounded-full bg-accent-500 px-2 py-0.5 text-xs font-bold text-white">{{ $product->discountPercent() }}% OFF</span>
        @endif
        @unless ($product->inStock())
            <span class="absolute right-2 top-2 rounded-full bg-gray-800/80 px-2 py-0.5 text-xs font-semibold text-white">Out of stock</span>
        @endunless
    </a>
    <div class="flex flex-1 flex-col p-4">
        @if ($product->category)
            <a href="{{ route('categories.show', $product->category->slug) }}" wire:navigate class="text-xs font-medium text-brand-600 hover:underline">{{ $product->category->name }}</a>
        @endif
        <h3 class="mt-1 line-clamp-2 text-sm font-semibold text-gray-900 dark:text-white">
            <a href="{{ route('products.show', $product->slug) }}" wire:navigate>{{ $product->name }}</a>
        </h3>
        @if ($product->unit_size)
            <p class="mt-0.5 text-xs text-gray-500 dark:text-gray-400">{{ $product->unit_size }}</p>
        @endif
        <div class="mt-2 flex items-baseline gap-2">
            <span class="text-lg font-bold text-gray-900 dark:text-white">{{ $symbol }}{{ number_format((float) $product->price, 2) }}</span>
            @if ($product->discountPercent() > 0)
                <span class="text-sm text-gray-400 line-through">{{ $symbol }}{{ number_format((float) $product->mrp, 2) }}</span>
            @endif
        </div>
        <div class="mt-3">
            @livewire('add-to-cart-button', ['product' => $product], key('atc-'.$product->id))
        </div>
    </div>
</article>
