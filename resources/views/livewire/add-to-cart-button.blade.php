<div class="flex flex-col gap-2">
    {{-- Quantity stepper --}}
    @if ($inStock)
    <div class="flex items-center rounded-lg border border-gray-200 bg-gray-50 w-fit">
        <button
            type="button"
            wire:click="decrementQty"
            aria-label="Decrease quantity"
            class="flex h-8 w-8 items-center justify-center rounded-l-lg text-gray-600 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-brand-500 disabled:opacity-40"
            @if($quantity <= 1) disabled @endif
        >
            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14" />
            </svg>
        </button>
        <span class="w-8 text-center text-sm font-medium text-gray-800 select-none" aria-live="polite" aria-label="Quantity">{{ $quantity }}</span>
        <button
            type="button"
            wire:click="incrementQty"
            aria-label="Increase quantity"
            class="flex h-8 w-8 items-center justify-center rounded-r-lg text-gray-600 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-brand-500"
        >
            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14" />
            </svg>
        </button>
    </div>
    @endif

    {{-- Add to cart / out of stock button --}}
    @if ($inStock)
    <button
        type="button"
        wire:click="add"
        wire:loading.attr="disabled"
        wire:target="add"
        class="flex w-full items-center justify-center gap-1.5 rounded-lg px-3 py-2 text-sm font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-1
            {{ $added
                ? 'bg-green-600 text-white'
                : 'bg-brand-600 text-white hover:bg-brand-700 active:bg-brand-800' }}"
        aria-label="{{ $added ? 'Added to cart' : 'Add to cart' }}"
    >
        {{-- Spinner while loading --}}
        <span wire:loading wire:target="add">
            <svg class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
            </svg>
        </span>

        <span wire:loading.remove wire:target="add">
            @if ($added)
            {{-- Checkmark --}}
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
            </svg>
            @else
            {{-- Cart icon --}}
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 110-1.5.75.75 0 010 1.5zm12.75 0a.75.75 0 110-1.5.75.75 0 010 1.5z" />
            </svg>
            @endif
        </span>

        <span wire:loading.remove wire:target="add">
            {{ $added ? 'Added!' : 'Add to Cart' }}
        </span>
    </button>
    @else
    <button
        type="button"
        disabled
        aria-disabled="true"
        class="w-full cursor-not-allowed rounded-lg bg-gray-200 px-3 py-2 text-sm font-semibold text-gray-500"
    >
        Out of Stock
    </button>
    @endif
</div>
