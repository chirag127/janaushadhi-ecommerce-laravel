@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
@php $symbol = config('store.currency_symbol'); @endphp
<div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
    <div class="rounded-xl border border-gray-100 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
        <p class="text-sm text-gray-500 dark:text-gray-400">Revenue</p>
        <p class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">{{ $symbol }}{{ number_format((float) ($salesSummary['revenue'] ?? 0), 2) }}</p>
    </div>
    <div class="rounded-xl border border-gray-100 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
        <p class="text-sm text-gray-500 dark:text-gray-400">Orders</p>
        <p class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">{{ number_format((int) ($salesSummary['orders'] ?? 0)) }}</p>
    </div>
    <div class="rounded-xl border border-gray-100 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
        <p class="text-sm text-gray-500 dark:text-gray-400">Avg. order value</p>
        <p class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">{{ $symbol }}{{ number_format((float) ($salesSummary['average_order_value'] ?? 0), 2) }}</p>
    </div>
    <div class="rounded-xl border border-gray-100 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
        <p class="text-sm text-gray-500 dark:text-gray-400">Active products</p>
        <p class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($totalProducts) }}</p>
        <p class="mt-1 text-xs text-gray-400">{{ number_format($totalCustomers) }} customers</p>
    </div>
</div>

<div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-2">
    {{-- Orders by status --}}
    <div class="rounded-xl border border-gray-100 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
        <h2 class="mb-4 text-base font-semibold text-gray-900 dark:text-white">Orders by status</h2>
        @if (empty($countByStatus))
            <p class="text-sm text-gray-500 dark:text-gray-400">No orders yet.</p>
        @else
            <ul class="space-y-2">
                @foreach ($countByStatus as $status => $total)
                    <li class="flex items-center justify-between text-sm">
                        <span class="inline-flex rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-700 dark:bg-gray-800 dark:text-gray-200">{{ ucfirst($status) }}</span>
                        <span class="font-semibold text-gray-900 dark:text-white">{{ number_format($total) }}</span>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

    {{-- Low stock --}}
    <div class="rounded-xl border border-gray-100 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
        <div class="mb-4 flex items-center justify-between">
            <h2 class="text-base font-semibold text-gray-900 dark:text-white">Low stock</h2>
            <a href="{{ route('admin.inventory.index') }}" class="text-sm font-medium text-brand-600 hover:underline">Manage</a>
        </div>
        @if ($lowStock->isEmpty())
            <p class="text-sm text-gray-500 dark:text-gray-400">All products well-stocked.</p>
        @else
            <ul class="divide-y divide-gray-100 dark:divide-gray-800">
                @foreach ($lowStock as $product)
                    <li class="flex items-center justify-between py-2 text-sm">
                        <a href="{{ route('admin.products.index', ['search' => $product->name]) }}" class="truncate pr-2 text-gray-700 hover:text-brand-600 dark:text-gray-200">{{ $product->name }}</a>
                        <span class="shrink-0 rounded-full bg-red-100 px-2 py-0.5 text-xs font-semibold text-red-700 dark:bg-red-900/40 dark:text-red-300">{{ $product->stock }} left</span>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</div>
@endsection
