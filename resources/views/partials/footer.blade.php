<footer class="mt-16 border-t border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
    <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 gap-8 md:grid-cols-4">
            <div class="col-span-2 md:col-span-1">
                <div class="flex items-center gap-2">
                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-brand-600 text-white">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 4.5v15m-3.75-15h9a2.25 2.25 0 012.25 2.25v10.5A2.25 2.25 0 0116.5 19.5h-9A2.25 2.25 0 015.25 17.25V6.75A2.25 2.25 0 017.5 4.5z" />
                        </svg>
                    </span>
                    <span class="text-base font-bold text-gray-900 dark:text-white">{{ config('app.name') }}</span>
                </div>
                <p class="mt-3 text-sm text-gray-500 dark:text-gray-400">Quality generic medicines and healthcare products at affordable prices, delivered across India.</p>
            </div>

            <div>
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Shop</h3>
                <ul class="mt-3 space-y-2 text-sm">
                    <li><a href="{{ route('products.index') }}" wire:navigate class="text-gray-500 hover:text-brand-600 dark:text-gray-400">All Products</a></li>
                    <li><a href="{{ route('categories.index') }}" wire:navigate class="text-gray-500 hover:text-brand-600 dark:text-gray-400">Categories</a></li>
                    <li><a href="{{ route('search') }}" wire:navigate class="text-gray-500 hover:text-brand-600 dark:text-gray-400">Search</a></li>
                </ul>
            </div>

            <div>
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Company</h3>
                <ul class="mt-3 space-y-2 text-sm">
                    <li><a href="{{ route('about') }}" wire:navigate class="text-gray-500 hover:text-brand-600 dark:text-gray-400">About Us</a></li>
                    <li><a href="{{ route('contact') }}" wire:navigate class="text-gray-500 hover:text-brand-600 dark:text-gray-400">Contact</a></li>
                    <li><a href="{{ route('faq') }}" wire:navigate class="text-gray-500 hover:text-brand-600 dark:text-gray-400">FAQ</a></li>
                </ul>
            </div>

            <div>
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Legal</h3>
                <ul class="mt-3 space-y-2 text-sm">
                    <li><a href="{{ route('privacy') }}" wire:navigate class="text-gray-500 hover:text-brand-600 dark:text-gray-400">Privacy Policy</a></li>
                    <li><a href="{{ route('terms') }}" wire:navigate class="text-gray-500 hover:text-brand-600 dark:text-gray-400">Terms of Service</a></li>
                </ul>
            </div>
        </div>

        <div class="mt-10 flex flex-col items-center justify-between gap-3 border-t border-gray-100 pt-6 text-sm text-gray-500 dark:border-gray-800 dark:text-gray-400 sm:flex-row">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
            <p>Support: <a href="mailto:{{ config('store.support_email') }}" class="hover:text-brand-600">{{ config('store.support_email') }}</a> &middot; {{ config('store.support_phone') }}</p>
        </div>
    </div>
</footer>
