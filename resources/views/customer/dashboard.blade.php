@extends('layouts.app')

@section('title', 'My Dashboard')

@section('content')
@php $symbol = config('store.currency_symbol'); @endphp
<div class="mx-auto max-w-5xl px-4 py-8 sm:px-6 lg:px-8">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Hello, {{ auth()->user()->name }}</h1>

    <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-3">
        <a href="{{ route('orders.index') }}" wire:navigate class="rounded-xl border border-gray-100 bg-white p-5 shadow-sm transition hover:shadow-md dark:border-gray-800 dark:bg-gray-900">
            <p class="text-sm text-gray-500 dark:text-gray-400">Orders</p>
            <p class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($orderCount) }}</p>
        </a>
        <a href="{{ route('wishlist.index') }}" wire:navigate class="rounded-xl border border-gray-100 bg-white p-5 shadow-sm transition hover:shadow-md dark:border-gray-800 dark:bg-gray-900">
            <p class="text-sm text-gray-500 dark:text-gray-400">Wishlist</p>
            <p class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($wishlistCount) }}</p>
        </a>
        <a href="{{ route('addresses.index') }}" wire:navigate class="rounded-xl border border-gray-100 bg-white p-5 shadow-sm transition hover:shadow-md dark:border-gray-800 dark:bg-gray-900">
            <p class="text-sm text-gray-500 dark:text-gray-400">Default address</p>
            <p class="mt-1 truncate text-sm font-medium text-gray-900 dark:text-white">{{ $defaultAddress?->singleLine() ?? 'Add an address' }}</p>
        </a>
    </div>

    <div class="mt-8">
        <div class="mb-3 flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Recent orders</h2>
            <a href="{{ route('orders.index') }}" wire:navigate class="text-sm font-medium text-brand-600 hover:underline">View all</a>
        </div>
        <div class="overflow-hidden rounded-xl border border-gray-100 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900">
            @forelse ($recentOrders as $order)
                <a href="{{ route('orders.show', $order) }}" wire:navigate class="flex items-center justify-between border-b border-gray-100 px-5 py-4 last:border-0 hover:bg-gray-50 dark:border-gray-800 dark:hover:bg-gray-800/40">
                    <div>
                        <p class="font-mono text-sm font-medium text-gray-900 dark:text-white">{{ $order->order_number }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ optional($order->created_at)->format('d M Y') }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $symbol }}{{ number_format((float) $order->total, 2) }}</p>
                        <span class="rounded-full px-2 py-0.5 text-xs font-medium {{ $order->statusColor() }}">{{ ucfirst($order->status) }}</span>
                    </div>
                </a>
            @empty
                <div class="px-5 py-10 text-center text-sm text-gray-500 dark:text-gray-400">
                    No orders yet. <a href="{{ route('products.index') }}" wire:navigate class="font-medium text-brand-600 hover:underline">Start shopping</a>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
