@extends('layouts.app')

@section('title', 'Order Confirmed')

@section('content')
<div class="mx-auto max-w-3xl px-4 py-12 sm:px-6 lg:px-8">
    <div class="rounded-2xl border border-gray-100 bg-white p-8 text-center shadow-sm dark:border-gray-700 dark:bg-gray-800">
        <span class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-brand-100 text-brand-600">
            <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
        </span>
        <h1 class="mt-4 text-2xl font-bold text-gray-900 dark:text-white">Thank you for your order!</h1>
        <p class="mt-1 text-sm text-gray-500">Your order <span class="font-semibold text-gray-800 dark:text-gray-100">#{{ $order->order_number }}</span> has been placed.</p>

        <div class="mt-8 rounded-xl border border-gray-100 text-left dark:border-gray-700">
            <div class="border-b border-gray-100 px-5 py-3 dark:border-gray-700">
                <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Order Details</h2>
            </div>
            <ul class="divide-y divide-gray-100 dark:divide-gray-700">
                @foreach ($order->items as $item)
                    <li class="flex items-center justify-between px-5 py-3 text-sm">
                        <div>
                            <p class="font-medium text-gray-800 dark:text-gray-100">{{ $item->product_name }}</p>
                            <p class="text-xs text-gray-400">Qty {{ $item->quantity }} × ₹{{ number_format((float) $item->unit_price, 2) }}</p>
                        </div>
                        <span class="font-semibold text-gray-800 dark:text-gray-100">₹{{ number_format((float) $item->line_total, 2) }}</span>
                    </li>
                @endforeach
            </ul>
            <dl class="space-y-2 border-t border-gray-100 px-5 py-4 text-sm dark:border-gray-700">
                <div class="flex justify-between text-gray-600 dark:text-gray-300"><dt>Subtotal</dt><dd>₹{{ number_format((float) $order->subtotal, 2) }}</dd></div>
                @if ((float) $order->discount > 0)
                    <div class="flex justify-between text-brand-700"><dt>Discount</dt><dd>&minus;₹{{ number_format((float) $order->discount, 2) }}</dd></div>
                @endif
                <div class="flex justify-between text-gray-600 dark:text-gray-300"><dt>Shipping</dt><dd>{{ (float) $order->shipping > 0 ? '₹' . number_format((float) $order->shipping, 2) : 'Free' }}</dd></div>
                @if ((float) $order->tax > 0)
                    <div class="flex justify-between text-gray-600 dark:text-gray-300"><dt>Tax</dt><dd>₹{{ number_format((float) $order->tax, 2) }}</dd></div>
                @endif
                <div class="flex justify-between border-t border-gray-100 pt-2 text-base font-bold text-gray-900 dark:border-gray-700 dark:text-white"><dt>Total</dt><dd>₹{{ number_format((float) $order->total, 2) }}</dd></div>
            </dl>
        </div>

        <div class="mt-4 rounded-xl border border-gray-100 p-4 text-left text-sm text-gray-600 dark:border-gray-700 dark:text-gray-300">
            <p class="font-semibold text-gray-800 dark:text-gray-100">Payment: {{ strtoupper($order->payment_method) }}</p>
            @if (is_array($order->shipping_address))
                <p class="mt-1">{{ $order->shipping_address['full_name'] ?? '' }}, {{ $order->shipping_address['line1'] ?? '' }}, {{ $order->shipping_address['city'] ?? '' }}, {{ $order->shipping_address['state'] ?? '' }} {{ $order->shipping_address['pincode'] ?? '' }}</p>
            @endif
        </div>

        <div class="mt-8 flex justify-center gap-3">
            <a href="{{ route('orders.show', $order->id) }}" wire:navigate class="btn-primary text-sm">View Order</a>
            <a href="{{ route('products.index') }}" wire:navigate class="btn-secondary text-sm">Continue Shopping</a>
        </div>
    </div>
</div>
@endsection
