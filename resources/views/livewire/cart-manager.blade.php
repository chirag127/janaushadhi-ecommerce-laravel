<div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Shopping Cart</h1>

    @if ($items->isEmpty())
        <div class="mt-8 rounded-xl border border-dashed border-gray-300 bg-white p-12 text-center dark:border-gray-700 dark:bg-gray-800">
            <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 110-1.5.75.75 0 010 1.5zm12.75 0a.75.75 0 110-1.5.75.75 0 010 1.5z" /></svg>
            <p class="mt-4 text-gray-500 dark:text-gray-400">Your cart is empty.</p>
            <a href="{{ route('products.index') }}" wire:navigate class="btn-primary mt-6">Browse Products</a>
        </div>
    @else
        <div class="mt-8 grid gap-8 lg:grid-cols-3">
            {{-- Line items --}}
            <div class="lg:col-span-2">
                <ul class="divide-y divide-gray-100 rounded-xl border border-gray-100 bg-white dark:divide-gray-700 dark:border-gray-700 dark:bg-gray-800">
                    @foreach ($items as $item)
                        <li class="flex gap-4 p-4" wire:key="cart-{{ $item->id }}">
                            <a href="{{ route('products.show', $item->product->slug) }}" wire:navigate class="shrink-0">
                                <img src="{{ $item->product->imageOrPlaceholder() }}" alt="{{ $item->product->name }}" class="h-20 w-20 rounded-lg border border-gray-100 object-cover dark:border-gray-700" loading="lazy" />
                            </a>
                            <div class="flex flex-1 flex-col">
                                <div class="flex justify-between gap-2">
                                    <a href="{{ route('products.show', $item->product->slug) }}" wire:navigate class="text-sm font-semibold text-gray-900 hover:text-brand-600 dark:text-white">{{ $item->product->name }}</a>
                                    <button type="button" wire:click="removeItem('{{ $item->id }}')" class="text-gray-400 hover:text-red-500" aria-label="Remove item">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                                    </button>
                                </div>
                                <p class="mt-0.5 text-xs text-gray-500 dark:text-gray-400">{{ $item->product->unit_size }}</p>
                                <div class="mt-auto flex items-center justify-between pt-2">
                                    <div class="flex items-center rounded-lg border border-gray-200 bg-gray-50 dark:border-gray-600 dark:bg-gray-700">
                                        <button type="button" wire:click="decrementQty('{{ $item->id }}')" class="flex h-8 w-8 items-center justify-center rounded-l-lg text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600" aria-label="Decrease quantity">&minus;</button>
                                        <span class="w-8 text-center text-sm font-medium text-gray-800 dark:text-gray-100">{{ $item->quantity }}</span>
                                        <button type="button" wire:click="incrementQty('{{ $item->id }}')" class="flex h-8 w-8 items-center justify-center rounded-r-lg text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600" aria-label="Increase quantity">+</button>
                                    </div>
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $currencySymbol }}{{ number_format($item->lineTotal(), 2) }}</p>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>

            {{-- Summary --}}
            <div class="lg:col-span-1">
                <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <h2 class="text-base font-semibold text-gray-900 dark:text-white">Order Summary</h2>

                    {{-- Coupon --}}
                    <div class="mt-4">
                        @if (($couponResult['ok'] ?? false))
                            <div class="flex items-center justify-between rounded-lg bg-brand-50 px-3 py-2 text-sm text-brand-800">
                                <span>Coupon <strong>{{ $couponCode }}</strong> applied</span>
                                <button type="button" wire:click="removeCoupon" class="text-brand-600 hover:underline">Remove</button>
                            </div>
                        @else
                            <div class="flex gap-2">
                                <input type="text" wire:model="couponCode" placeholder="Coupon code" class="form-input text-sm" />
                                <button type="button" wire:click="applyCoupon" class="btn-secondary shrink-0">Apply</button>
                            </div>
                            @if (isset($couponResult['ok']) && ! $couponResult['ok'])
                                <p class="mt-1 text-xs text-red-600">{{ $couponResult['message'] ?? 'Invalid coupon.' }}</p>
                            @endif
                        @endif
                    </div>

                    <dl class="mt-4 space-y-2 border-t border-gray-100 pt-4 text-sm dark:border-gray-700">
                        <div class="flex justify-between"><dt class="text-gray-500 dark:text-gray-400">Subtotal</dt><dd class="text-gray-900 dark:text-white">{{ $currencySymbol }}{{ number_format($summary['subtotal'], 2) }}</dd></div>
                        @if ($summary['discount'] > 0)
                            <div class="flex justify-between text-brand-600"><dt>Discount</dt><dd>&minus;{{ $currencySymbol }}{{ number_format($summary['discount'], 2) }}</dd></div>
                        @endif
                        <div class="flex justify-between"><dt class="text-gray-500 dark:text-gray-400">Shipping</dt><dd class="text-gray-900 dark:text-white">{{ $summary['shipping'] > 0 ? $currencySymbol . number_format($summary['shipping'], 2) : 'Free' }}</dd></div>
                        @if ($summary['tax'] > 0)
                            <div class="flex justify-between"><dt class="text-gray-500 dark:text-gray-400">Tax</dt><dd class="text-gray-900 dark:text-white">{{ $currencySymbol }}{{ number_format($summary['tax'], 2) }}</dd></div>
                        @endif
                        <div class="flex justify-between border-t border-gray-100 pt-2 text-base font-bold dark:border-gray-700"><dt class="text-gray-900 dark:text-white">Total</dt><dd class="text-gray-900 dark:text-white">{{ $currencySymbol }}{{ number_format($summary['total'], 2) }}</dd></div>
                    </dl>

                    @if ($summary['shipping'] > 0)
                        <p class="mt-3 text-xs text-gray-500 dark:text-gray-400">Add {{ $currencySymbol }}{{ number_format($freeShippingThreshold - $summary['subtotal'], 2) }} more for free shipping.</p>
                    @endif

                    <a href="{{ route('checkout.index') }}" wire:navigate class="btn-primary mt-6 w-full">Proceed to Checkout</a>
                    <a href="{{ route('products.index') }}" wire:navigate class="mt-3 block text-center text-sm text-brand-600 hover:underline">Continue shopping</a>
                </div>
            </div>
        </div>
    @endif
</div>
