@extends('layouts.app')

@section('title', 'My Orders')

@section('content')
<div class="mx-auto max-w-5xl px-4 py-8 sm:px-6 lg:px-8">
    <h1 class="mb-6 text-2xl font-bold text-gray-900 dark:text-white">My Orders</h1>

    @if ($orders->isEmpty())
        <div class="rounded-xl border-2 border-dashed border-gray-200 py-20 text-center dark:border-gray-700">
            <p class="text-lg font-medium text-gray-700 dark:text-gray-200">No orders yet</p>
            <p class="mt-1 text-sm text-gray-500">When you place an order, it will show up here.</p>
            <a href="{{ route('products.index') }}" wire:navigate class="btn-primary mt-4 text-sm">Start Shopping</a>
        </div>
    @else
        <div class="space-y-4">
            @foreach ($orders as $order)
                <div class="rounded-xl border border-gray-100 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <div>
                            <p class="text-sm font-semibold text-gray-900 dark:text-white">#{{ $order->order_number }}</p>
                            <p class="text-xs text-gray-400">Placed {{ ($order->placed_at ?? $order->created_at)?->format('d M Y') }}</p>
                        </div>
                        <span class="badge {{ $order->statusColor() }}">{{ ucfirst($order->status) }}</span>
                        <div class="text-right">
                            <p class="text-sm font-bold text-gray-900 dark:text-white">₹{{ number_format((float) $order->total, 2) }}</p>
                            <a href="{{ route('orders.show', $order->id) }}" wire:navigate class="text-xs font-medium text-brand-700 hover:underline">View details</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-8">{{ $orders->links() }}</div>
    @endif
</div>
@endsection
