@extends('layouts.admin')

@section('title', 'Order ' . $order->order_number)

@section('content')
@php $symbol = config('store.currency_symbol'); $addr = $order->shipping_address ?? []; @endphp
<a href="{{ route('admin.orders.index') }}" class="mb-4 inline-block text-sm text-brand-600 hover:underline">&larr; Back to orders</a>

<div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
    {{-- Items --}}
    <div class="lg:col-span-2">
        <div class="overflow-hidden rounded-xl border border-gray-100 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <div class="flex items-center justify-between border-b border-gray-100 px-5 py-4 dark:border-gray-800">
                <h2 class="font-mono text-base font-semibold text-gray-900 dark:text-white">{{ $order->order_number }}</h2>
                <span class="rounded-full px-2.5 py-0.5 text-xs font-medium {{ $order->statusColor() }}">{{ ucfirst($order->status) }}</span>
            </div>
            <table class="min-w-full divide-y divide-gray-100 text-sm dark:divide-gray-800">
                <thead class="bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:bg-gray-800/60 dark:text-gray-400">
                    <tr><th class="px-5 py-3">Product</th><th class="px-5 py-3">Qty</th><th class="px-5 py-3">Unit</th><th class="px-5 py-3 text-right">Total</th></tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @foreach ($order->items as $item)
                        <tr>
                            <td class="px-5 py-3 text-gray-900 dark:text-white">{{ $item->product_name }}</td>
                            <td class="px-5 py-3 text-gray-600 dark:text-gray-300">{{ $item->quantity }}</td>
                            <td class="px-5 py-3 text-gray-600 dark:text-gray-300">{{ $symbol }}{{ number_format((float) $item->unit_price, 2) }}</td>
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
                <div class="flex justify-between text-gray-600 dark:text-gray-300"><dt>Shipping</dt><dd>{{ $symbol }}{{ number_format((float) $order->shipping, 2) }}</dd></div>
                @if ((float) $order->tax > 0)
                    <div class="flex justify-between text-gray-600 dark:text-gray-300"><dt>Tax</dt><dd>{{ $symbol }}{{ number_format((float) $order->tax, 2) }}</dd></div>
                @endif
                <div class="flex justify-between border-t border-gray-100 pt-2 text-base font-bold text-gray-900 dark:border-gray-800 dark:text-white"><dt>Total</dt><dd>{{ $symbol }}{{ number_format((float) $order->total, 2) }}</dd></div>
            </dl>
        </div>
    </div>

    {{-- Side panel --}}
    <div class="space-y-6">
        <div class="rounded-xl border border-gray-100 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <h3 class="mb-3 text-sm font-semibold text-gray-900 dark:text-white">Update status</h3>
            <form method="POST" action="{{ route('admin.orders.status', $order) }}" class="flex gap-2">
                @csrf @method('PATCH')
                <select name="status" class="form-select text-sm">
                    @foreach (\App\Models\Order::STATUSES as $status)
                        <option value="{{ $status }}" @selected($order->status === $status)>{{ ucfirst($status) }}</option>
                    @endforeach
                </select>
                <button type="submit" class="btn-primary">Save</button>
            </form>
        </div>

        <div class="rounded-xl border border-gray-100 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <h3 class="mb-3 text-sm font-semibold text-gray-900 dark:text-white">Customer</h3>
            <p class="text-sm text-gray-700 dark:text-gray-200">{{ $order->user?->name ?? ($addr['full_name'] ?? 'Guest') }}</p>
            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $order->user?->email }}</p>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Payment: {{ strtoupper($order->payment_method) }} · {{ ucfirst($order->payment_status) }}</p>
        </div>

        <div class="rounded-xl border border-gray-100 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <h3 class="mb-3 text-sm font-semibold text-gray-900 dark:text-white">Shipping address</h3>
            <address class="text-sm not-italic text-gray-600 dark:text-gray-300">
                {{ $addr['full_name'] ?? '' }}<br>
                {{ $addr['line1'] ?? '' }}@if(!empty($addr['line2'])), {{ $addr['line2'] }}@endif<br>
                {{ $addr['city'] ?? '' }}, {{ $addr['state'] ?? '' }} {{ $addr['pincode'] ?? '' }}<br>
                {{ $addr['country'] ?? '' }}<br>
                @if (!empty($addr['phone'])) Ph: {{ $addr['phone'] }} @endif
            </address>
        </div>

        @if ($order->notes)
            <div class="rounded-xl border border-gray-100 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                <h3 class="mb-2 text-sm font-semibold text-gray-900 dark:text-white">Notes</h3>
                <p class="text-sm text-gray-600 dark:text-gray-300">{{ $order->notes }}</p>
            </div>
        @endif
    </div>
</div>
@endsection
