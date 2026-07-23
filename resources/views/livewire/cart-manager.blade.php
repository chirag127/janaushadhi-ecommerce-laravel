<div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
    <h1 class="mb-6 text-2xl font-bold text-gray-900 dark:text-white">Your Cart</h1>

    @if ($items->isEmpty())
        <div class="flex flex-col items-center justify-center rounded-xl border-2 border-dashed border-gray-200 py-20 text-center dark:border-gray-700">
            <svg class="h-14 w-14 text-gray-300" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 110-1.5.75.75 0 010 1.5zm12.75 0a.75.75 0 110-1.5.75.75 0 010 1.5z" />
            </svg>
            <p class="mt-4 text-lg font-medium text-gray-700 dark:text-gray-200">Your cart is empty</p>
            <p class="mt-1 text-sm text-gray-500">Browse our catalog and add some medicines.</p>
            <a href="{{ route('products.index') }}" wire:navigate class="btn-primary mt-5">Shop Products</a>
        </div>
    @else
        <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
            {{-- Line items --}}
            <div class="lg:col-span-2">
                <ul class="divide-y divide-gray-100 rounded-xl border border-gray-100 bg-white dark:divide-gray-700 dark:border-gray-700 dark:bg-gray-800">
                    @foreach ($items as $item)
                        <li class="flex gap-4 p-4" wire:key="cart-{{ $item->id }}">
                            <a href="{{ route('products.show', $item->product->slug) }}" wire:navigate class="shrink-0">
                                <img src="{{ $item->product->imageOrPlaceholder() }}" alt="{{ $item->product->name }}" class="h-20 w-20 rounded-lg object-cover" />
                            </a>
                            <div class="flex flex-1 flex-col">
                                <a href="{{ route('products.show', $item->product->slug) }}" wire:navigate class="text-sm font-medium text-gray-800 hover:text-brand-700 dark:text-gray-100">{{ $item->product->name }}</a>
                                @if ($item->product->unit_size)
                                    <p class="text-xs text-gray-400">{{ $item->product->unit_size }}</p>
                                @endif
                                <p class="mt-1 text-sm font-semibold text-brand-700">{{ $currencySymbol }}{{ number_format((float) $item->product->price, 2) }}</p>

                                <div class="mt-auto flex items-center justify-between pt-2">
                                    <div class="flex items-center rounded-lg border border-gray-200 dark:border-gray-600">
                                        <button type="button" wire:click="decrementQty('{{ $item->id }}')" class="flex h-8 w-8 items-center justify-center rounded-l-lg text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700" aria-label="Decrease quantity">
                                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14" /></svg>
                                        </button>
                                        <span class="w-9 text-center text-sm font-medium">{{ $item->quantity }}</span>
                                        <button type="button" wire:click="incrementQty('{{ $item->id }}')" class="flex h-8 w-8 items-center justify-center rounded-r-lg text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700" aria-label="Increase quantity">
                                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14" /></svg>
                                        </button>
                                    </div>
                                    <div class="flex items-center gap-4">
                                        <span class="text-sm font-semibold text-gray-800 dark:text-gray-100">{{ $currencySymbol }}{{ number_format($item->lineTotal(), 2) }}</span>
                                        <button type="button" wire:click="removeItem('{{ $item->id }}')" class="text-gray-400 hover:text-red-500" aria-label="Remove item">
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
                <a href="{{ route('products.index') }}" wire:navigate class="mt-4 inline-flex items-center gap-1 text-sm font-medium text-brand-700 hover:underline">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
                    Continue shopping
                </a>
            </div>

            {{-- Summary --}}
            <div class="lg:col-span-1">
                <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Order Summary</h2>

                    {{-- Coupon --}}
                    <div class="mt-4">
                        <label for="coupon" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Coupon code</label>
                        <div class="mt-1 flex gap-2">
                            <input id="coupon" type="text" wire:model="couponCode" wire:keydown.enter="applyCoupon" placeholder="Enter code" class="form-input text-sm uppercase" />
                            @if ($discount > 0)
                                <button type="button" wire:click="removeCoupon" class="btn-secondary shrink-0 text-sm">Remove</button>
                            @else
                                <button type="button" wire:click="applyCoupon" class="btn-primary shrink-0 text-sm">Apply</button>
                            @endif
                        </div>
                        @if (! empty($couponResult))
                            <p class="mt-1.5 text-xs {{ $couponResult['ok'] ? 'text-brand-700' : 'text-red-600' }}">{{ $couponResult['message'] }}</p>
                        @endif
                    </div>

                    <dl class="mt-6 space-y-3 border-t border-gray-100 pt-4 text-sm dark:border-gray-700">
                        <div class="flex justify-between text-gray-600 dark:text-gray-300">
                            <dt>Subtotal</dt>
                            <dd>{{ $currencySymbol }}{{ number_format($summary['subtotal'], 2) }}</dd>
                        </div>
                        @if ($summary['discount'] > 0)
                            <div class="flex justify-between text-brand-700">
                                <dt>Discount</dt>
                                <dd>&minus;{{ $currencySymbol }}{{ number_format($summary['discount'], 2) }}</dd>
                            </div>
                        @endif
                        <div class="flex justify-between text-gray-600 dark:text-gray-300">
                            <dt>Shipping</dt>
                            <dd>{{ $summary['shipping'] > 0 ? $currencySymbol . number_format($summary['shipping'], 2) : 'Free' }}</dd>
                        </div>
                        @if ($summary['tax'] > 0)
                            <div class="flex justify-between text-gray-600 dark:text-gray-300">
                                <dt>Tax</dt>
                                <dd>{{ $currencySymbol }}{{ number_format($summary['tax'], 2) }}</dd>
                            </div>
                        @endif
                        <div class="flex justify-between border-t border-gray-100 pt-3 text-base font-bold text-gray-900 dark:border-gray-700 dark:text-white">
                            <dt>Total</dt>
                            <dd>{{ $currencySymbol }}{{ number_format($summary['total'], 2) }}</dd>
                        </div>
                    </dl>

                    @if ($summary['shipping'] > 0)
                        <p class="mt-3 text-xs text-gray-500">Add {{ $currencySymbol }}{{ number_format($freeShippingThreshold - $summary['subtotal'], 2) }} more for free shipping.</p>
                    @endif

                    <a href="{{ route('checkout.index') }}" wire:navigate class="btn-primary mt-6 w-full">Proceed to Checkout</a>
                </div>
            </div>
        </div>
    @endif
</div>