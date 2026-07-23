@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8" x-data="{ mode: '{{ $addresses->isNotEmpty() ? 'saved' : 'new' }}' }">
    <h1 class="mb-6 text-2xl font-bold text-gray-900 dark:text-white">Checkout</h1>

    <form method="POST" action="{{ route('checkout.store') }}" class="grid grid-cols-1 gap-8 lg:grid-cols-3">
        @csrf
        <input type="hidden" name="coupon_code" value="{{ session('applied_coupon_code') }}" />

        <div class="space-y-6 lg:col-span-2">
            {{-- Shipping address --}}
            <section class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Shipping Address</h2>

                @if ($addresses->isNotEmpty())
                    <div class="mt-4 flex gap-2 text-sm">
                        <button type="button" @click="mode = 'saved'" :class="mode === 'saved' ? 'bg-brand-600 text-white' : 'bg-gray-100 text-gray-600'" class="rounded-lg px-4 py-2 font-medium">Saved addresses</button>
                        <button type="button" @click="mode = 'new'" :class="mode === 'new' ? 'bg-brand-600 text-white' : 'bg-gray-100 text-gray-600'" class="rounded-lg px-4 py-2 font-medium">New address</button>
                    </div>

                    <div x-show="mode === 'saved'" class="mt-4 space-y-3">
                        @foreach ($addresses as $address)
                            <label class="flex cursor-pointer items-start gap-3 rounded-lg border border-gray-200 p-4 hover:border-brand-400 dark:border-gray-600">
                                <input type="radio" name="address_id" value="{{ $address->id }}" @checked($loop->first) class="mt-1 text-brand-600 focus:ring-brand-500" />
                                <span class="text-sm">
                                    <span class="font-semibold text-gray-800 dark:text-gray-100">{{ $address->label }} — {{ $address->full_name }}</span>
                                    <span class="block text-gray-500">{{ $address->singleLine() }}, {{ $address->country }}</span>
                                    <span class="block text-gray-500">{{ $address->phone }}</span>
                                </span>
                            </label>
                        @endforeach
                    </div>
                @endif

                <div x-show="mode === 'new'" class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2" @if ($addresses->isNotEmpty()) x-cloak @endif>
                    <div class="sm:col-span-2">
                        <label for="full_name" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Full name</label>
                        <input id="full_name" type="text" name="full_name" value="{{ old('full_name') }}" class="form-input mt-1 text-sm" />
                        @error('full_name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Phone</label>
                        <input id="phone" type="tel" name="phone" value="{{ old('phone') }}" class="form-input mt-1 text-sm" />
                        @error('phone')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="pincode" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Pincode</label>
                        <input id="pincode" type="text" name="pincode" value="{{ old('pincode') }}" inputmode="numeric" maxlength="6" class="form-input mt-1 text-sm" />
                        @error('pincode')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div class="sm:col-span-2">
                        <label for="line1" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Address line 1</label>
                        <input id="line1" type="text" name="line1" value="{{ old('line1') }}" class="form-input mt-1 text-sm" />
                        @error('line1')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div class="sm:col-span-2">
                        <label for="line2" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Address line 2 (optional)</label>
                        <input id="line2" type="text" name="line2" value="{{ old('line2') }}" class="form-input mt-1 text-sm" />
                    </div>
                    <div>
                        <label for="city" class="block text-sm font-medium text-gray-700 dark:text-gray-200">City</label>
                        <input id="city" type="text" name="city" value="{{ old('city') }}" class="form-input mt-1 text-sm" />
                        @error('city')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="state" class="block text-sm font-medium text-gray-700 dark:text-gray-200">State</label>
                        <input id="state" type="text" name="state" value="{{ old('state') }}" class="form-input mt-1 text-sm" />
                        @error('state')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <input type="hidden" name="country" value="India" />
                </div>
            </section>

            {{-- Payment --}}
            <section class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Payment Method</h2>
                <div class="mt-4 space-y-3">
                    <label class="flex cursor-pointer items-center gap-3 rounded-lg border border-gray-200 p-4 hover:border-brand-400 dark:border-gray-600">
                        <input type="radio" name="payment_method" value="cod" checked class="text-brand-600 focus:ring-brand-500" />
                        <span class="text-sm font-medium text-gray-800 dark:text-gray-100">Cash on Delivery</span>
                    </label>
                    <label class="flex cursor-pointer items-center gap-3 rounded-lg border border-gray-200 p-4 hover:border-brand-400 dark:border-gray-600">
                        <input type="radio" name="payment_method" value="online" class="text-brand-600 focus:ring-brand-500" />
                        <span class="text-sm font-medium text-gray-800 dark:text-gray-100">Online Payment (UPI / Card)</span>
                    </label>
                    @error('payment_method')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div class="mt-4">
                    <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Order notes (optional)</label>
                    <textarea id="notes" name="notes" rows="2" maxlength="500" class="form-textarea mt-1 text-sm">{{ old('notes') }}</textarea>
                </div>
            </section>
        </div>

        {{-- Order summary --}}
        <div class="lg:col-span-1">
            <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Order Summary</h2>
                <ul class="mt-4 max-h-64 space-y-3 overflow-y-auto">
                    @foreach ($items as $item)
                        <li class="flex items-center gap-3">
                            <img src="{{ $item->product->imageOrPlaceholder() }}" alt="{{ $item->product->name }}" class="h-12 w-12 rounded-lg object-cover" />
                            <div class="min-w-0 flex-1">
                                <p class="truncate text-sm font-medium text-gray-800 dark:text-gray-100">{{ $item->product->name }}</p>
                                <p class="text-xs text-gray-400">Qty {{ $item->quantity }} × ₹{{ number_format((float) $item->product->price, 2) }}</p>
                            </div>
                            <span class="text-sm font-semibold text-gray-800 dark:text-gray-100">₹{{ number_format($item->lineTotal(), 2) }}</span>
                        </li>
                    @endforeach
                </ul>
                <dl class="mt-4 space-y-2 border-t border-gray-100 pt-4 text-sm dark:border-gray-700">
                    <div class="flex justify-between text-gray-600 dark:text-gray-300"><dt>Subtotal</dt><dd>₹{{ number_format($summary['subtotal'], 2) }}</dd></div>
                    @if ($summary['discount'] > 0)
                        <div class="flex justify-between text-brand-700"><dt>Discount</dt><dd>&minus;₹{{ number_format($summary['discount'], 2) }}</dd></div>
                    @endif
                    <div class="flex justify-between text-gray-600 dark:text-gray-300"><dt>Shipping</dt><dd>{{ $summary['shipping'] > 0 ? '₹' . number_format($summary['shipping'], 2) : 'Free' }}</dd></div>
                    @if ($summary['tax'] > 0)
                        <div class="flex justify-between text-gray-600 dark:text-gray-300"><dt>Tax</dt><dd>₹{{ number_format($summary['tax'], 2) }}</dd></div>
                    @endif
                    <div class="flex justify-between border-t border-gray-100 pt-2 text-base font-bold text-gray-900 dark:border-gray-700 dark:text-white"><dt>Total</dt><dd>₹{{ number_format($summary['total'], 2) }}</dd></div>
                </dl>
                <button type="submit" class="btn-primary mt-6 w-full">Place Order</button>
                <a href="{{ route('cart.index') }}" wire:navigate class="mt-2 block text-center text-sm text-gray-500 hover:text-brand-700">Back to cart</a>
            </div>
        </div>
    </form>
</div>
@endsection
