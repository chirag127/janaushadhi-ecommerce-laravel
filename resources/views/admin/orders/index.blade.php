@extends('layouts.admin')

@section('title', 'Orders')

@section('content')
@php $symbol = config('store.currency_symbol'); @endphp
<form method="GET" action="{{ route('admin.orders.index') }}" class="mb-4 flex flex-wrap items-center gap-2">
    <input type="search" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Search order number…" class="form-input text-sm" />
    <select name="status" class="form-select text-sm">
        <option value="">All statuses</option>
        @foreach ($statuses as $status)
            <option value="{{ $status }}" @selected(($filters['status'] ?? '') === $status)>{{ ucfirst($status) }}</option>
        @endforeach
    </select>
    <button type="submit" class="btn-secondary">Filter</button>
</form>

<div class="overflow-hidden rounded-xl border border-gray-100 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-100 text-sm dark:divide-gray-800">
            <thead class="bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:bg-gray-800/60 dark:text-gray-400">
                <tr>
                    <th class="px-4 py-3">Order</th>
                    <th class="px-4 py-3">Customer</th>
                    <th class="px-4 py-3">Items</th>
                    <th class="px-4 py-3">Total</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Placed</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @forelse ($orders as $order)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/40">
                        <td class="px-4 py-3 font-mono font-medium text-gray-900 dark:text-white">{{ $order->order_number }}</td>
                        <td class="px-4 py-3 text-gray-600 dark:text-gray-300">{{ $order->user?->name ?? 'Guest' }}</td>
                        <td class="px-4 py-3 text-gray-600 dark:text-gray-300">{{ $order->items_count ?? $order->items->count() }}</td>
                        <td class="px-4 py-3 text-gray-900 dark:text-white">{{ $symbol }}{{ number_format((float) $order->total, 2) }}</td>
                        <td class="px-4 py-3"><span class="rounded-full px-2 py-0.5 text-xs font-medium {{ $order->statusColor() }}">{{ ucfirst($order->status) }}</span></td>
                        <td class="px-4 py-3 text-gray-500 dark:text-gray-400">{{ optional($order->created_at)->format('d M Y') }}</td>
                        <td class="px-4 py-3 text-right"><a href="{{ route('admin.orders.show', $order) }}" class="text-brand-600 hover:underline">View</a></td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="px-4 py-10 text-center text-gray-500 dark:text-gray-400">No orders found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-6">{{ $orders->links() }}</div>
@endsection
