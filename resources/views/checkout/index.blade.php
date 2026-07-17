@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
@php $symbol = config('store.currency_symbol'); @endphp
<div class="mx-auto max-w-6xl px-4 py-8 sm:px-6 lg:px-8"
     x-data="{ mode: {{ $addresses->isNotEmpty() ? "'saved'" : "'new'" }}, payment: 'cod' }">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Checkout</h1>

    <form method="POST" action="{{ route('checkout.store') }}" class="mt-6 grid grid-cols-1 gap-8 lg:grid-cols-3">
        @csrf
        <div class="space-y-6 lg:col-span-2">
            {{-- Shipping address --}}
            <section class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                <h2 class="text-base font-semibold text-gray-900 dark:text-white">Shipping address</h2>

                @if ($addresses->isNotEmpty())
                    <div class="mt-4 space-y-3">
                        @foreach ($addresses as $address)
                            <label class="flex cursor-pointer items-start gap-3 rounded-lg border border-gray-200 p-4 dark:border-gray-700"
                                   :class="mode === 'saved' && $refs.addr{{ $loop->index }}?.checked ? 'ring-2 ring-brand-500' : ''">
                                <input type="radio" name="address_id" value="{{ $address->id }}" x-ref="addr{{ $loop->index }}"
                                       @click="mode = 'saved'" @checked($loop->first) class="mt-1 text-brand-600 focus:ring-brand-500" />
                                <span class="min-w-0 text-sm">
                                    <span class="font-medium text-gray-900 dark:text-white">{{ $address->label }} — {{ $address->full_name }}</span><br>
                                    <span class="text-gray-600 dark:text-gray-300">{{ $address->singleLine() }}, {{ $address->country }} · {{ $address->phone }}</span>
                                </span>
                            </label>
                        @endforeach
                        <label class="flex cursor-pointer items-center gap-3 text-sm text-gray-700 dark:text-gray-200">
                            <input type="radio" name="address_id" value="" @click="mode = 'new'" class="text-brand-600 focus:ring-brand-500" />
                            Ship to a new address
                        </label>
                    </div>
                @endif

                {{-- New-address fields --}}
                <div x-show="mode === 'new'" x-cloak class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label class="mb-1 block text-sm font-semibold text-gray-700 dark:text-gray-200">Full name</label>
                        <input name="full_name" type="text" value="{{ old('full_name', auth()->user()->name) }}" :required="mode === 'new'" class="form-input" />
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-semibold text-gray-700 dark:text-gray-200">Phone</label>
                        <input name="phone" type="tel" value="{{ old('phone', auth()->user()->phone) }}" :required="mode === 'new'" class="form-input" />
                    </div>
                    <div class="sm:col-span-2">
                        <label class="mb-1 block text-sm font-semibold text-gray-700 dark:text-gray-200">Address line 1</label>
                        <input name="line1" type="text" value="{{ old('line1') }}" :required="mode === 'new'" class="form-input" />
                    </div>
                    <div class="sm:col-span-2">
                        <label class="mb-1 block text-sm font-semibold text-gray-700 dark:text-gray-200">Address line 2</label>
                        <input name="line2" type="text" value="{{ old('line2') }}" class="form-input" />
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-semibold text-gray-700 dark:text-gray-200">City</label>
                        <input name="city" type="text" value="{{ old('city') }}" :required="mode === 'new'" class="form-input" />
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-semibold text-gray-700 dark:text-gray-200">State</label>
                        <input name="state" type="text" value="{{ old('state') }}" :required="mode === 'new'" class="form-input" />
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-semibold text-gray-700 dark:text-gray-200">Pincode</label>
                        <input name="pincode" type="text" inputmode="numeric" value="{{ old('pincode') }}" :required="mode === 'new'" class="form-input" />
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-semibold text-gray-700 dark:text-gray-200">Country</label>
                        <input name="country" type="text" value="{{ old('country', 'India') }}" class="form-input" />
                    </div>
                </div>
            </section>

            {{-- Payment --}}
            <section class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                <h2 class="text-base font-semibold text-gray-900 dark:text-white">Payment method</h2>
                <div class="mt-4 space-y-3">
                    <label class="flex cursor-pointer items-center gap-3 rounded-lg border border-gray-200 p-4 dark:border-gray-700" :class="payment === 'cod' ? 'ring-2 ring-brand-500' : ''">
                        <input type="radio" name="payment_method" value="cod" x-model="payment" checked class="text-brand-600 focus:ring-brand-500" />
                        <span class="text-sm">
                            <span class="font-medium text-gray-900 dark:text-white">Cash on Delivery</span><br>
                            <span class="text-gray-500 dark:text-gray-400">Pay in cash when your order arrives.</span>
                        </span>
                    </label>
                    <label class="flex cursor-pointer items-center gap-3 rounded-lg border border-gray-200 p-4 dark:border-gray-700" :class="payment === 'online' ? 'ring-2 ring-brand-500' : ''">
                        <input type="radio" name="payment_method" value="online" x-model="payment" class="text-brand-600 focus:ring-brand-500" />
                        <span class="text-sm">
                            <span class="font-medium text-gray-900 dark:text-white">Pay online (Razorpay)</span><br>
                            <span class="text-gray-500 dark:text-gray-400">Card / UPI / netbanking.</span>
                        </span>
                    </label>
                </div>
                <div x-show="payment === 'online'" x-cloak class="mt-3 rounded-lg bg-blue-50 px-4 py-3 text-sm text-blue-800 dark:bg-blue-900/30 dark:text-blue-300">
                    You'll be redirected to Razorpay to complete payment after placing the order.
                </div>
            </section>

            {{-- Notes --}}
            <section class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                <label for="notes" class="mb-1 block text-sm font-semibold text-gray-700 dark:text-gray-200">Order notes (optional)</label>
                <textarea id="notes" name="notes" rows="2" class="form-textarea">{{ old('notes') }}</textarea>
            </section>
        </div>

        {{-- Summary --}}
        <aside class="lg:col-span-1">
            <div class="sticky top-24 rounded-xl border border-gray-100 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                <h2 class="text-base font-semibold text-gray-900 dark:text-white">Order summary</h2>
                <ul class="mt-4 divide-y divide-gray-100 text-sm dark:divide-gray-800">
                    @foreach ($items as $item)
                        <li class="flex items-center justify-between gap-2 py-2">
                            <span class="min-w-0 truncate text-gray-700 dark:text-gray-200">{{ $item->product->name }} <span class="text-gray-400">×{{ $item->quantity }}</span></span>
                            <span class="shrink-0 text-gray-900 dark:text-white">{{ $symbol }}{{ number_format($item->lineTotal(), 2) }}</span>
                        </li>
                    @endforeach
                </ul>
                <dl class="mt-4 space-y-2 border-t border-gray-100 pt-4 text-sm dark:border-gray-800">
                    <div class="flex justify-between text-gray-600 dark:text-gray-300"><dt>Subtotal</dt><dd>{{ $symbol }}{{ number_format($summary['subtotal'], 2) }}</dd></div>
                    @if ($summary['discount'] > 0)
                        <div class="flex justify-between text-brand-700 dark:text-brand-400"><dt>Discount</dt><dd>-{{ $symbol }}{{ number_format($summary['discount'], 2) }}</dd></div>
                    @endif
                    <div class="flex justify-between text-gray-600 dark:text-gray-300"><dt>Shipping</dt><dd>{{ $summary['shipping'] > 0 ? $symbol . number_format($summary['shipping'], 2) : 'Free' }}</dd></div>
                    @if ($summary['tax'] > 0)
                        <div class="flex justify-between text-gray-600 dark:text-gray-300"><dt>Tax</dt><dd>{{ $symbol }}{{ number_format($summary['tax'], 2) }}</dd></div>
                    @endif
                    <div class="flex justify-between border-t border-gray-100 pt-2 text-base font-bold text-gray-900 dark:border-gray-800 dark:text-white"><dt>Total</dt><dd>{{ $symbol }}{{ number_format($summary['total'], 2) }}</dd></div>
                </dl>

                <div class="mt-4">
                    <label for="coupon_code" class="mb-1 block text-sm font-semibold text-gray-700 dark:text-gray-200">Coupon code</label>
                    <input id="coupon_code" name="coupon_code" type="text" value="{{ old('coupon_code') }}" placeholder="Optional" class="form-input text-sm" />
                </div>

                <button type="submit" class="btn-primary mt-6 w-full">Place order</button>
                <a href="{{ route('cart.index') }}" wire:navigate class="mt-3 block text-center text-sm text-brand-600 hover:underline">Back to cart</a>
            </div>
        </aside>
    </form>
</div>
@endsection
