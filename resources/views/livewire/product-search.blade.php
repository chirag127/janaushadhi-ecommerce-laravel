<div class="relative w-full" x-data="{ focused: false }" @click.outside="focused = false">
    <form wire:submit="search" role="search" class="flex items-center">
        <label for="product-search-input" class="sr-only">Search medicines</label>
        <div class="relative w-full">
            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                {{-- Search icon --}}
                <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1010.5 18a7.5 7.5 0 006.15-3.35z" />
                </svg>
            </div>
            <input
                id="product-search-input"
                type="search"
                wire:model.live.debounce.300ms="term"
                @focus="focused = true"
                placeholder="Search generic medicines…"
                autocomplete="off"
                class="block w-full rounded-lg border border-gray-300 bg-white py-2 pl-10 pr-4 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500"
                aria-autocomplete="list"
                aria-controls="search-suggestions"
                aria-haspopup="listbox"
            />
        </div>
        <button
            type="submit"
            class="ml-2 shrink-0 rounded-lg bg-brand-600 px-4 py-2 text-sm font-medium text-white hover:bg-brand-700 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-1"
        >
            Search
        </button>
    </form>

    {{-- Live suggestions dropdown --}}
    @if (strlen(trim($term)) >= 2 && $showDropdown && count($suggestions))
    <ul
        id="search-suggestions"
        role="listbox"
        aria-label="Search suggestions"
        class="absolute z-50 mt-1 w-full rounded-lg border border-gray-200 bg-white py-1 shadow-lg"
        wire:loading.class="opacity-50"
    >
        @foreach ($suggestions as $suggestion)
        <li role="option" aria-selected="false">
            <a
                href="{{ route('products.show', $suggestion['slug']) }}"
                wire:navigate
                class="flex items-center justify-between px-4 py-2 text-sm hover:bg-brand-50 focus:bg-brand-50 focus:outline-none"
            >
                <span class="truncate text-gray-800">{{ $suggestion['name'] }}</span>
                <span class="ml-4 shrink-0 font-medium text-brand-700">{{ $suggestion['price'] }}</span>
            </a>
        </li>
        @endforeach

        <li class="border-t border-gray-100">
            <button
                wire:click="search"
                type="button"
                class="w-full px-4 py-2 text-left text-xs text-brand-600 hover:underline focus:outline-none"
            >
                See all results for &ldquo;{{ $term }}&rdquo;
            </button>
        </li>
    </ul>
    @endif

    {{-- Loading spinner while fetching suggestions --}}
    <div wire:loading.flex wire:target="updatedTerm" class="absolute right-14 top-2.5 items-center">
        <svg class="h-4 w-4 animate-spin text-brand-600" fill="none" viewBox="0 0 24 24" aria-hidden="true">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
        </svg>
    </div>
</div>
