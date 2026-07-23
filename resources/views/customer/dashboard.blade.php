@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Welcome back, {{ auth()->user()->name }}</h1>
            <p class="text-sm text-gray-500">Here's an overview of your account.</p>
        </div>
    </div>

    {{-- Stat cards --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
        <a href="{{ route('orders.index') }}" wire:navigate class="rounded-xl border border-gray-100 bg-white p-5 shadow-sm hover:shadow-md dark:border-gray-700 dark:bg-gray-800">
            <p class="text-sm text-gray-500">Total Orders</p>
            <p class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($orderCount) }}</p>
        </a>
        <a href="{{ route('wishlist.index') }}" wire:navigate class="rounded-xl border border-gray-100 bg-white p-5 shadow-sm hover:shadow-md dark:border-gray-700 dark:bg-gray-800">
            <p class="text-sm text-gray-500">Wishlist Items</p>
            <p class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($wishlistCount) }}</p>
        </a>
        <a href="{{ route('addresses.index') }}" wire:navigate class="rounded-xl border border-gray-100 bg-white p-5 shadow-sm hover:shadow-md dark:border-gray-700 dark:bg-gray-800">
            <p class="text-sm text-gray-500">Default Address</p>
            <p class="mt-1 truncate text-sm font-medium text-gray-900 dark:text-white">{{ $defaultAddress ? $defaultAddress->singleLine() : 'Not set' }}</p>
        </a>
    </div>

    {{-- Recent orders --}}
    <div class="mt-8">
        <div class="mb-4 flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Orders</h2>
            <a href="{{ route('orders.index') }}" wire:navigate class="text-sm font-medium text-brand-700 hover:underline">View all</a>
        </div>

        @if ($recentOrders->isEmpty())
            <div class="rounded-xl border-2 border-dashed border-gray-200 py-12 text-center dark:border-gray-700">
                <p class="text-sm text-gray-500">No orders yet.</p>
                <a href="{{ route('products.index') }}" wire:navigate class="btn-primary mt-3 text-sm">Start Shopping</a>
            </div>
        @else
            <div class="overflow-hidden rounded-xl border border-gray-100 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <table class="min-w-full divide-y divide-gray-100 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-900/40">
                        <tr class="text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                            <th class="px-5 py-3">Order</th>
                            <th class="px-5 py-3">Date</th>
                            <th class="px-5 py-3">Status</th>
                            <th class="px-5 py-3 text-right">Total</th>
                            <th class="px-5 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm dark:divide-gray-700">
                        @foreach ($recentOrders as $order)
                            <tr>
                                <td class="px-5 py-3 font-medium text-gray-800 dark:text-gray-100">#{{ $order->order_number }}</td>
                                <td class="px-5 py-3 text-gray-500">{{ ($order->placed_at ?? $order->created_at)?->format('d M Y') }}</td>
                                <td class="px-5 py-3"><span class="badge {{ $order->statusColor() }}">{{ ucfirst($order->status) }}</span></td>
                                <td class="px-5 py-3 text-right font-semibold text-gray-800 dark:text-gray-100">₹{{ number_format((float) $order->total, 2) }}</td>
                                <td class="px-5 py-3 text-right"><a href="{{ route('orders.show', $order->id) }}" wire:navigate class="text-brand-700 hover:underline">View</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
