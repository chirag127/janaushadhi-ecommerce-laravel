@extends('layouts.app')

@section('title', 'Order ' . $order->order_number)

@section('content')
@php $symbol = config('store.currency_symbol'); $addr = $order->shipping_address ?? []; @endphp
<div class="mx-auto max-w-3xl px-4 py-8 sm:px-6 lg:px-8">
    <a href="{{ route('orders.index') }}" wire:navigate class="mb-4 inline-block text-sm text-brand-600 hover:underline">&larr; Back to orders</a>

    <div class="overflow-hidden rounded-xl border border-gray-100 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900">
        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-gray-100 px-6 py-4 dark:border-gray-800">
            <div>
                <h1 class="font-mono text-lg font-bold text-gray-900 dark:text-white">{{ $order->order_number }}</h1>
                <p class="text-xs text-gray-500 dark:text-gray-400">Placed {{ optional($order->created_at)->format('d M Y, h:i A') }}</p>
            </div>
            <span class="rounded-full px-2.5 py-0.5 text-xs font-medium {{ $order->statusColor() }}">{{ ucfirst($order->status) }}</span>
        </div>

        <table class="min-w-full divide-y divide-gray-100 text-sm dark:divide-gray-800">
            <thead class="bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:bg-gray-800/60 dark:text-gray-400">
                <tr><th class="px-6 py-3">Product</th><th class="px-6 py-3">Qty</th><th class="px-6 py-3 text-right">Total</th></tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @foreach ($order->items as $item)
                    <tr>
                        <td class="px-6 py-3 text-gray-900 dark:text-white">
                            @if ($item->product_slug)
                                <a href="{{ route('products.show', $item->product_slug) }}" wire:navigate class="hover:text-brand-600">{{ $item->product_name }}</a>
                            @else
                                {{ $item->product_name }}
                            @endif
                        </td>
                        <td class="px-6 py-3 text-gray-600 dark:text-gray-300">{{ $item->quantity }}</td>
                        <td class="px-6 py-3 text-right text-gray-900 dark:text-white">{{ $symbol }}{{ number_format((float) $item->line_total, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <dl class="space-y-1 border-t border-gray-100 px-6 py-4 text-sm dark:border-gray-800">
            <div class="flex justify-between text-gray-600 dark:text-gray-300"><dt>Subtotal</dt><dd>{{ $symbol }}{{ number_format((float) $order->subtotal, 2) }}</dd></div>
            @if ((float) $order->discount > 0)
                <div class="flex justify-between text-brand-700 dark:text-brand-400"><dt>Discount</dt><dd>-{{ $symbol }}{{ number_format((float) $order->discount, 2) }}</dd></div>
            @endif
            <div class="flex justify-between text-gray-600 dark:text-gray-300"><dt>Shipping</dt><dd>{{ (float) $order->shipping > 0 ? $symbol . number_format((float) $order->shipping, 2) : 'Free' }}</dd></div>
            @if ((float) $order->tax > 0)
                <div class="flex justify-between text-gray-600 dark:text-gray-300"><dt>Tax</dt><dd>{{ $symbol }}{{ number_format((float) $order->tax, 2) }}</dd></div>
            @endif
            <div class="flex justify-between border-t border-gray-100 pt-2 text-base font-bold text-gray-900 dark:border-gray-800 dark:text-white"><dt>Total</dt><dd>{{ $symbol }}{{ number_format((float) $order->total, 2) }}</dd></div>
        </dl>
    </div>

    <div class="mt-6 grid grid-cols-1 gap-6 sm:grid-cols-2">
        <div class="rounded-xl border border-gray-100 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <h2 class="mb-2 text-sm font-semibold text-gray-900 dark:text-white">Shipping address</h2>
            <address class="text-sm not-italic text-gray-600 dark:text-gray-300">
                {{ $addr['full_name'] ?? '' }}<br>
                {{ $addr['line1'] ?? '' }}@if(!empty($addr['line2'])), {{ $addr['line2'] }}@endif<br>
                {{ $addr['city'] ?? '' }}, {{ $addr['state'] ?? '' }} {{ $addr['pincode'] ?? '' }}<br>
                {{ $addr['country'] ?? '' }}
            </address>
        </div>
        <div class="rounded-xl border border-gray-100 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <h2 class="mb-2 text-sm font-semibold text-gray-900 dark:text-white">Payment</h2>
            <p class="text-sm text-gray-600 dark:text-gray-300">Method: {{ strtoupper($order->payment_method) }}</p>
            <p class="text-sm text-gray-600 dark:text-gray-300">Status: {{ ucfirst($order->payment_status) }}</p>
            @if ($order->isCancellable())
                <form method="POST" action="{{ route('orders.cancel', $order) }}" class="mt-4" onsubmit="return confirm('Cancel this order?')">
                    @csrf
                    <button type="submit" class="btn-secondary w-full text-red-600">Cancel order</button>
                </form>
            @endif
        </div>
    </div>
</div>
@endsection
