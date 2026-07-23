@extends('layouts.admin')

@section('title', 'Order #' . $order->order_number)
@section('heading', 'Order #' . $order->order_number)

@section('admin')
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="space-y-6 lg:col-span-2">
            {{-- Items --}}
            <div class="rounded-xl border border-gray-100 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <div class="border-b border-gray-100 px-5 py-3 dark:border-gray-700">
                    <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Items</h2>
                </div>
                <ul class="divide-y divide-gray-100 dark:divide-gray-700">
                    @foreach ($order->items as $item)
                        <li class="flex items-center justify-between px-5 py-3 text-sm">
                            <div>
                                <p class="font-medium text-gray-800 dark:text-gray-100">{{ $item->product_name }}</p>
                                <p class="text-xs text-gray-400">Qty {{ $item->quantity }} × ₹{{ number_format((float) $item->unit_price, 2) }}</p>
                            </div>
                            <span class="font-semibold text-gray-800 dark:text-gray-100">₹{{ number_format((float) $item->line_total, 2) }}</span>
                        </li>
                    @endforeach
                </ul>
                <dl class="space-y-2 border-t border-gray-100 px-5 py-4 text-sm dark:border-gray-700">
                    <div class="flex justify-between text-gray-600 dark:text-gray-300"><dt>Subtotal</dt><dd>₹{{ number_format((float) $order->subtotal, 2) }}</dd></div>
                    @if ((float) $order->discount > 0)
                        <div class="flex justify-between text-brand-700"><dt>Discount</dt><dd>&minus;₹{{ number_format((float) $order->discount, 2) }}</dd></div>
                    @endif
                    <div class="flex justify-between text-gray-600 dark:text-gray-300"><dt>Shipping</dt><dd>₹{{ number_format((float) $order->shipping, 2) }}</dd></div>
                    @if ((float) $order->tax > 0)
                        <div class="flex justify-between text-gray-600 dark:text-gray-300"><dt>Tax</dt><dd>₹{{ number_format((float) $order->tax, 2) }}</dd></div>
                    @endif
                    <div class="flex justify-between border-t border-gray-100 pt-2 text-base font-bold text-gray-900 dark:border-gray-700 dark:text-white"><dt>Total</dt><dd>₹{{ number_format((float) $order->total, 2) }}</dd></div>
                </dl>
            </div>
        </div>

        <div class="space-y-6 lg:col-span-1">
            {{-- Status update --}}
            <div class="rounded-xl border border-gray-100 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Order Status</h2>
                <span class="badge mt-2 {{ $order->statusColor() }}">{{ ucfirst($order->status) }}</span>
                <form method="POST" action="{{ route('admin.orders.status', $order->id) }}" class="mt-4">
                    @csrf
                    @method('PATCH')
                    <select name="status" class="form-select text-sm">
                        @foreach (\App\Models\Order::STATUSES as $status)
                            <option value="{{ $status }}" @selected($order->status === $status)>{{ ucfirst($status) }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn-primary mt-3 w-full text-sm">Update Status</button>
                </form>
            </div>

            {{-- Customer --}}
            <div class="rounded-xl border border-gray-100 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Customer</h2>
                @if ($order->user)
                    <p class="mt-2 text-sm font-medium text-gray-800 dark:text-gray-100">{{ $order->user->name }}</p>
                    <p class="text-sm text-gray-500">{{ $order->user->email }}</p>
                    <a href="{{ route('admin.customers.show', $order->user->id) }}" class="mt-2 inline-block text-xs text-brand-700 hover:underline">View customer</a>
                @else
                    <p class="mt-2 text-sm text-gray-500">Guest / removed</p>
                @endif
            </div>

            {{-- Shipping --}}
            <div class="rounded-xl border border-gray-100 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Shipping Address</h2>
                @if (is_array($order->shipping_address))
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                        {{ $order->shipping_address['full_name'] ?? '' }}<br>
                        {{ $order->shipping_address['line1'] ?? '' }}@if(!empty($order->shipping_address['line2'])), {{ $order->shipping_address['line2'] }}@endif<br>
                        {{ $order->shipping_address['city'] ?? '' }}, {{ $order->shipping_address['state'] ?? '' }} {{ $order->shipping_address['pincode'] ?? '' }}<br>
                        {{ $order->shipping_address['phone'] ?? '' }}
                    </p>
                @endif
                <p class="mt-3 text-xs text-gray-400">Payment: {{ strtoupper($order->payment_method) }} · {{ ucfirst($order->payment_status ?? 'pending') }}</p>
                @if ($order->notes)
                    <p class="mt-2 text-sm text-gray-500">Notes: {{ $order->notes }}</p>
                @endif
            </div>
        </div>
    </div>
@endsection
