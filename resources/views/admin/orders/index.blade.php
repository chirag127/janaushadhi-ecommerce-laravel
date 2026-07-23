@extends('layouts.admin')

@section('title', 'Orders')
@section('heading', 'Orders')

@section('admin')
    <form method="GET" action="{{ route('admin.orders.index') }}" class="mb-4 flex flex-wrap gap-2">
        <input type="search" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Search by order # …" class="form-input max-w-xs text-sm" />
        <select name="status" class="form-select max-w-[12rem] text-sm">
            <option value="">All statuses</option>
            @foreach ($statuses as $status)
                <option value="{{ $status }}" @selected(($filters['status'] ?? '') === $status)>{{ ucfirst($status) }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn-primary text-sm">Filter</button>
        <a href="{{ route('admin.orders.index') }}" class="btn-secondary text-sm">Reset</a>
    </form>

    <div class="overflow-hidden rounded-xl border border-gray-100 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100 text-sm dark:divide-gray-700">
                <thead class="bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:bg-gray-900/40">
                    <tr>
                        <th class="px-4 py-3">Order</th>
                        <th class="px-4 py-3">Date</th>
                        <th class="px-4 py-3">Payment</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3 text-right">Total</th>
                        <th class="px-4 py-3 text-right"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse ($orders as $order)
                        <tr>
                            <td class="px-4 py-3 font-medium text-gray-800 dark:text-gray-100">#{{ $order->order_number }}</td>
                            <td class="px-4 py-3 text-gray-500">{{ ($order->placed_at ?? $order->created_at)?->format('d M Y') }}</td>
                            <td class="px-4 py-3 text-gray-500">{{ strtoupper($order->payment_method) }}</td>
                            <td class="px-4 py-3"><span class="badge {{ $order->statusColor() }}">{{ ucfirst($order->status) }}</span></td>
                            <td class="px-4 py-3 text-right font-semibold text-gray-800 dark:text-gray-100">₹{{ number_format((float) $order->total, 2) }}</td>
                            <td class="px-4 py-3 text-right"><a href="{{ route('admin.orders.show', $order->id) }}" class="text-brand-700 hover:underline">Manage</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-4 py-8 text-center text-gray-500">No orders found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">{{ $orders->links() }}</div>
@endsection
