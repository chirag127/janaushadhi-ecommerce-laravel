@extends('layouts.app')

@section('title', 'Order #' . $order->order_number)

@section('content')
<div class="mx-auto max-w-3xl px-4 py-8 sm:px-6 lg:px-8">
    <nav class="mb-4 text-sm text-gray-500" aria-label="Breadcrumb">
        <a href="{{ route('orders.index') }}" wire:navigate class="hover:text-brand-700">My Orders</a>
        <span class="mx-1">/</span>
        <span class="text-gray-700 dark:text-gray-300">#{{ $order->order_number }}</span>
    </nav>

    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Order #{{ $order->order_number }}</h1>
            <p class="text-sm text-gray-400">Placed {{ ($order->placed_at ?? $order->created_at)?->format('d M Y, h:i A') }}</p>
        </div>
        <span class="badge {{ $order->statusColor() }}">{{ ucfirst($order->status) }}</span>
    </div>

    <div class="mt-6 grid grid-cols-1 gap-6 sm:grid-cols-2">
        <div class="rounded-xl border border-gray-100 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Shipping Address</h2>
            @if (is_array($order->shipping_address))
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                    {{ $order->shipping_address['full_name'] ?? '' }}<br>
                    {{ $order->shipping_address['line1'] ?? '' }}@if(!empty($order->shipping_address['line2'])), {{ $order->shipping_address['line2'] }}@endif<br>
                    {{ $order->shipping_address['city'] ?? '' }}, {{ $order->shipping_address['state'] ?? '' }} {{ $order->shipping_address['pincode'] ?? '' }}<br>
                    {{ $order->shipping_address['country'] ?? '' }}<br>
                    {{ $order->shipping_address['phone'] ?? '' }}
                </p>
            @endif
        </div>
        <div class="rounded-xl border border-gray-100 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Payment</h2>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">Method: {{ strtoupper($order->payment_method) }}</p>
            <p class="text-sm text-gray-600 dark:text-gray-300">Status: {{ ucfirst($order->payment_status ?? 'pending') }}</p>
            @if ($order->notes)
                <p class="mt-2 text-sm text-gray-500">Notes: {{ $order->notes }}</p>
            @endif
        </div>
    </div>

    <div class="mt-6 rounded-xl border border-gray-100 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
        <ul class="divide-y divide-gray-100 dark:divide-gray-700">
            @foreach ($order->items as $item)
                <li class="flex items-center justify-between px-5 py-3 text-sm">
                    <div>
                        <a href="{{ route('products.show', $item->product_slug) }}" wire:navigate class="font-medium text-gray-800 hover:text-brand-700 dark:text-gray-100">{{ $item->product_name }}</a>
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

    @if ($order->isCancellable())
        <form method="POST" action="{{ route('orders.cancel', $order->id) }}" class="mt-6" onsubmit="return confirm('Cancel this order?');">
            @csrf
            <button type="submit" class="rounded-lg border border-red-200 bg-red-50 px-4 py-2 text-sm font-medium text-red-700 hover:bg-red-100">Cancel Order</button>
        </form>
    @endif
</div>
@endsection
