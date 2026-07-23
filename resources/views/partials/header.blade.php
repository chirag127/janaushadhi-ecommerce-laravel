<header class="sticky top-0 z-40 border-b border-gray-100 bg-white/95 backdrop-blur dark:border-gray-800 dark:bg-gray-900/95">
    {{-- Top strip --}}
    <div class="bg-brand-700 text-brand-50">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-1.5 text-xs sm:px-6 lg:px-8">
            <p class="hidden sm:block">Quality generic medicines at up to 90% less. Free delivery over ₹{{ number_format((float) config('store.free_shipping_threshold')) }}.</p>
            <div class="flex items-center gap-4">
                <a href="tel:{{ config('store.support_phone') }}" class="hover:underline">{{ config('store.support_phone') }}</a>
                <a href="{{ route('faq') }}" class="hover:underline">Help</a>
            </div>
        </div>
    </div>

    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8" x-data="{ mobileOpen: false }">
        <div class="flex h-16 items-center gap-4">
            {{-- Logo --}}
            <a href="{{ route('home') }}" wire:navigate class="flex shrink-0 items-center gap-2">
                <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-brand-600 text-white">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                </span>
                <span class="text-lg font-bold text-gray-900 dark:text-white">Jan Aushadhi</span>
            </a>

            {{-- Search (desktop) --}}
            <div class="hidden flex-1 md:block">
                @livewire('product-search')
            </div>

            {{-- Actions --}}
            <nav class="ml-auto flex items-center gap-1 sm:gap-2" aria-label="Primary">
                <a href="{{ route('products.index') }}" wire:navigate class="hidden rounded-lg px-3 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50 hover:text-brand-700 dark:text-gray-300 dark:hover:bg-gray-800 lg:block">Products</a>
                <a href="{{ route('categories.index') }}" wire:navigate class="hidden rounded-lg px-3 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50 hover:text-brand-700 dark:text-gray-300 dark:hover:bg-gray-800 lg:block">Categories</a>

                {{-- Dark-mode toggle --}}
                <button
                    type="button"
                    x-data
                    @click="document.documentElement.classList.toggle('dark'); localStorage.setItem('theme', document.documentElement.classList.contains('dark') ? 'dark' : 'light')"
                    class="rounded-lg p-2 text-gray-500 hover:bg-gray-50 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-gray-800"
                    aria-label="Toggle dark mode"
                >
                    <svg class="hidden h-5 w-5 dark:block" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" />
                    </svg>
                    <svg class="h-5 w-5 dark:hidden" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z" />
                    </svg>
                </button>

                @auth
                    {{-- Wishlist --}}
                    <a href="{{ route('wishlist.index') }}" wire:navigate class="relative rounded-lg p-2 text-gray-500 hover:bg-gray-50 hover:text-brand-700 dark:text-gray-400 dark:hover:bg-gray-800" aria-label="Wishlist">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                        </svg>
                        @livewire('wishlist-counter')
                    </a>

                    {{-- Cart --}}
                    <a href="{{ route('cart.index') }}" wire:navigate class="relative rounded-lg p-2 text-gray-500 hover:bg-gray-50 hover:text-brand-700 dark:text-gray-400 dark:hover:bg-gray-800" aria-label="Cart">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 110-1.5.75.75 0 010 1.5zm12.75 0a.75.75 0 110-1.5.75.75 0 010 1.5z" />
                        </svg>
                        @livewire('cart-counter')
                    </a>

                    {{-- Account dropdown --}}
                    <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                        <button @click="open = !open" class="flex items-center gap-1 rounded-lg px-2 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-800" aria-haspopup="true" :aria-expanded="open">
                            <span class="flex h-7 w-7 items-center justify-center rounded-full bg-brand-100 text-xs font-bold text-brand-700">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" /></svg>
                        </button>
                        <div x-show="open" x-transition x-cloak class="absolute right-0 mt-2 w-52 rounded-lg border border-gray-100 bg-white py-1 shadow-lg dark:border-gray-700 dark:bg-gray-800">
                            <div class="border-b border-gray-100 px-4 py-2 dark:border-gray-700">
                                <p class="truncate text-sm font-semibold text-gray-800 dark:text-gray-100">{{ auth()->user()->name }}</p>
                                <p class="truncate text-xs text-gray-400">{{ auth()->user()->email }}</p>
                            </div>
                            @if (auth()->user()->isAdmin())
                                <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-sm text-gray-600 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-700">Admin Panel</a>
                            @endif
                            <a href="{{ route('dashboard') }}" wire:navigate class="block px-4 py-2 text-sm text-gray-600 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-700">Dashboard</a>
                            <a href="{{ route('orders.index') }}" wire:navigate class="block px-4 py-2 text-sm text-gray-600 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-700">My Orders</a>
                            <a href="{{ route('profile.edit') }}" wire:navigate class="block px-4 py-2 text-sm text-gray-600 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-700">Profile</a>
                            <a href="{{ route('addresses.index') }}" wire:navigate class="block px-4 py-2 text-sm text-gray-600 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-700">Addresses</a>
                            <form method="POST" action="{{ route('logout') }}" class="border-t border-gray-100 dark:border-gray-700">
                                @csrf
                                <button type="submit" class="block w-full px-4 py-2 text-left text-sm text-red-600 hover:bg-red-50 dark:hover:bg-gray-700">Log out</button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" wire:navigate class="rounded-lg px-3 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-800">Log in</a>
                    <a href="{{ route('register') }}" wire:navigate class="rounded-lg bg-brand-600 px-3 py-2 text-sm font-semibold text-white hover:bg-brand-700">Sign up</a>
                @endauth
            </nav>
        </div>

        {{-- Search (mobile) --}}
        <div class="pb-3 md:hidden">
            @livewire('product-search')
        </div>
    </div>
</header>
