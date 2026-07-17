@extends('layouts.app')

@section('title', 'Order Confirmed')

@section('content')
@php $symbol = config('store.currency_symbol'); $addr = $order->shipping_address ?? []; @endphp
<div class="mx-auto max-w-2xl px-4 py-12 sm:px-6 lg:px-8">
    <div class="rounded-2xl border border-gray-100 bg-white p-8 text-center shadow-sm dark:border-gray-800 dark:bg-gray-900">
        <span class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-brand-100 text-brand-600 dark:bg-brand-900/40">
            <svg class="h-9 w-9" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
        </span>
        <h1 class="mt-4 text-2xl font-bold text-gray-900 dark:text-white">Thank you for your order!</h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Your order <span class="font-mono font-medium text-gray-700 dark:text-gray-200">{{ $order->order_number }}</span> has been placed.</p>
    </div>

    <div class="mt-6 overflow-hidden rounded-xl border border-gray-100 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900">
        <table class="min-w-full divide-y divide-gray-100 text-sm dark:divide-gray-800">
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @foreach ($order->items as $item)
                    <tr>
                        <td class="px-5 py-3 text-gray-900 dark:text-white">{{ $item->product_name }} <span class="text-gray-400">×{{ $item->quantity }}</span></td>
                        <td class="px-5 py-3 text-right text-gray-900 dark:text-white">{{ $symbol }}{{ number_format((float) $item->line_total, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <dl class="space-y-1 border-t border-gray-100 px-5 py-4 text-sm dark:border-gray-800">
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

    <div class="mt-6 rounded-xl border border-gray-100 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
        <h2 class="mb-2 text-sm font-semibold text-gray-900 dark:text-white">Delivering to</h2>
        <address class="text-sm not-italic text-gray-600 dark:text-gray-300">
            {{ $addr['full_name'] ?? '' }}<br>
            {{ $addr['line1'] ?? '' }}@if(!empty($addr['line2'])), {{ $addr['line2'] }}@endif<br>
            {{ $addr['city'] ?? '' }}, {{ $addr['state'] ?? '' }} {{ $addr['pincode'] ?? '' }}
        </address>
        <p class="mt-3 text-sm text-gray-500 dark:text-gray-400">Payment: {{ strtoupper($order->payment_method) }}</p>
    </div>

    <div class="mt-6 flex justify-center gap-3">
        <a href="{{ route('orders.show', $order) }}" wire:navigate class="btn-primary">View order</a>
        <a href="{{ route('products.index') }}" wire:navigate class="btn-secondary">Continue shopping</a>
    </div>
</div>
@endsection
