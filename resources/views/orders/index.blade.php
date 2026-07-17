@extends('layouts.app')

@section('title', 'My Orders')

@section('content')
@php $symbol = config('store.currency_symbol'); @endphp
<div class="mx-auto max-w-4xl px-4 py-8 sm:px-6 lg:px-8">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">My orders</h1>

    <div class="mt-6 space-y-4">
        @forelse ($orders as $order)
            <a href="{{ route('orders.show', $order) }}" wire:navigate class="block rounded-xl border border-gray-100 bg-white p-5 shadow-sm transition hover:shadow-md dark:border-gray-800 dark:bg-gray-900">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div>
                        <p class="font-mono text-sm font-medium text-gray-900 dark:text-white">{{ $order->order_number }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ optional($order->created_at)->format('d M Y') }} · {{ $order->items_count ?? $order->items->count() }} {{ Str::plural('item', $order->items_count ?? $order->items->count()) }}</p>
                    </div>
                    <div class="flex items-center gap-4">
                        <span class="rounded-full px-2.5 py-0.5 text-xs font-medium {{ $order->statusColor() }}">{{ ucfirst($order->status) }}</span>
                        <span class="text-base font-bold text-gray-900 dark:text-white">{{ $symbol }}{{ number_format((float) $order->total, 2) }}</span>
                    </div>
                </div>
            </a>
        @empty
            <div class="rounded-xl border border-dashed border-gray-300 bg-white p-12 text-center dark:border-gray-700 dark:bg-gray-900">
                <p class="text-gray-500 dark:text-gray-400">You haven't placed any orders yet.</p>
                <a href="{{ route('products.index') }}" wire:navigate class="btn-primary mt-4 inline-block">Browse products</a>
            </div>
        @endforelse
    </div>

    <div class="mt-8">{{ $orders->links() }}</div>
</div>
@endsection
