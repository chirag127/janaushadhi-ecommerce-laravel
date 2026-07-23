<footer class="mt-16 border-t border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
    <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 gap-8 md:grid-cols-4">
            <div class="col-span-2 md:col-span-1">
                <div class="flex items-center gap-2">
                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-brand-600 text-white">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                    </span>
                    <span class="font-bold text-gray-900 dark:text-white">Jan Aushadhi</span>
                </div>
                <p class="mt-3 text-sm text-gray-500 dark:text-gray-400">Affordable, quality-assured generic medicines delivered across India.</p>
            </div>

            <div>
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Shop</h3>
                <ul class="mt-3 space-y-2 text-sm text-gray-500 dark:text-gray-400">
                    <li><a href="{{ route('products.index') }}" class="hover:text-brand-700">All Products</a></li>
                    <li><a href="{{ route('categories.index') }}" class="hover:text-brand-700">Categories</a></li>
                    <li><a href="{{ route('products.index', ['sort' => 'newest']) }}" class="hover:text-brand-700">New Arrivals</a></li>
                </ul>
            </div>

            <div>
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Company</h3>
                <ul class="mt-3 space-y-2 text-sm text-gray-500 dark:text-gray-400">
                    <li><a href="{{ route('about') }}" class="hover:text-brand-700">About Us</a></li>
                    <li><a href="{{ route('contact') }}" class="hover:text-brand-700">Contact</a></li>
                    <li><a href="{{ route('faq') }}" class="hover:text-brand-700">FAQ</a></li>
                </ul>
            </div>

            <div>
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Support</h3>
                <ul class="mt-3 space-y-2 text-sm text-gray-500 dark:text-gray-400">
                    <li><a href="{{ route('privacy') }}" class="hover:text-brand-700">Privacy Policy</a></li>
                    <li><a href="{{ route('terms') }}" class="hover:text-brand-700">Terms of Service</a></li>
                    <li><a href="tel:{{ config('store.support_phone') }}" class="hover:text-brand-700">{{ config('store.support_phone') }}</a></li>
                    <li><a href="mailto:{{ config('store.support_email') }}" class="hover:text-brand-700">{{ config('store.support_email') }}</a></li>
                </ul>
            </div>
        </div>

        <div class="mt-10 border-t border-gray-100 pt-6 dark:border-gray-800">
            <p class="text-center text-xs text-gray-400">&copy; {{ date('Y') }} Jan Aushadhi. All rights reserved. Prices in INR (₹).</p>
        </div>
    </div>
</footer>
