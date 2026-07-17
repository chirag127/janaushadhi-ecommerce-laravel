@extends('layouts.admin')

@section('title', 'Customer')

@section('content')
@php $symbol = config('store.currency_symbol'); @endphp
<a href="{{ route('admin.customers.index') }}" class="mb-4 inline-block text-sm text-brand-600 hover:underline">&larr; Back to customers</a>

<div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
    <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
        <h2 class="text-lg font-bold text-gray-900 dark:text-white">{{ $customer->name }}</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $customer->email }}</p>
        <dl class="mt-4 space-y-2 text-sm">
            <div class="flex justify-between"><dt class="text-gray-500 dark:text-gray-400">Phone</dt><dd class="text-gray-900 dark:text-white">{{ $customer->phone ?? '—' }}</dd></div>
            <div class="flex justify-between"><dt class="text-gray-500 dark:text-gray-400">Status</dt><dd class="text-gray-900 dark:text-white">{{ $customer->is_active ? 'Active' : 'Disabled' }}</dd></div>
            <div class="flex justify-between"><dt class="text-gray-500 dark:text-gray-400">Joined</dt><dd class="text-gray-900 dark:text-white">{{ optional($customer->created_at)->format('d M Y') }}</dd></div>
        </dl>
        <form method="POST" action="{{ route('admin.customers.toggle', $customer) }}" class="mt-4">
            @csrf @method('PATCH')
            <button type="submit" class="btn-secondary w-full">{{ $customer->is_active ? 'Disable account' : 'Enable account' }}</button>
        </form>
    </div>

    <div class="lg:col-span-2">
        <div class="overflow-hidden rounded-xl border border-gray-100 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <h3 class="border-b border-gray-100 px-5 py-4 text-base font-semibold text-gray-900 dark:border-gray-800 dark:text-white">Order history</h3>
            <table class="min-w-full divide-y divide-gray-100 text-sm dark:divide-gray-800">
                <thead class="bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:bg-gray-800/60 dark:text-gray-400">
                    <tr><th class="px-5 py-3">Order</th><th class="px-5 py-3">Total</th><th class="px-5 py-3">Status</th><th class="px-5 py-3">Placed</th></tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse ($orders as $order)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/40">
                            <td class="px-5 py-3"><a href="{{ route('admin.orders.show', $order) }}" class="font-mono text-brand-600 hover:underline">{{ $order->order_number }}</a></td>
                            <td class="px-5 py-3 text-gray-900 dark:text-white">{{ $symbol }}{{ number_format((float) $order->total, 2) }}</td>
                            <td class="px-5 py-3"><span class="rounded-full px-2 py-0.5 text-xs font-medium {{ $order->statusColor() }}">{{ ucfirst($order->status) }}</span></td>
                            <td class="px-5 py-3 text-gray-500 dark:text-gray-400">{{ optional($order->created_at)->format('d M Y') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-5 py-10 text-center text-gray-500 dark:text-gray-400">No orders yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-6">{{ $orders->links() }}</div>
    </div>
</div>
@endsection
