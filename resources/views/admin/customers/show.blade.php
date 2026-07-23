@extends('layouts.admin')

@section('title', $customer->name)
@section('heading', 'Customer')

@section('admin')
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="lg:col-span-1">
            <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <div class="flex items-center gap-3">
                    <span class="flex h-12 w-12 items-center justify-center rounded-full bg-brand-100 text-lg font-bold text-brand-700">{{ strtoupper(substr($customer->name, 0, 1)) }}</span>
                    <div>
                        <p class="font-semibold text-gray-900 dark:text-white">{{ $customer->name }}</p>
                        <span class="badge {{ $customer->is_active ? 'bg-brand-100 text-brand-800' : 'bg-red-100 text-red-800' }}">{{ $customer->is_active ? 'Active' : 'Disabled' }}</span>
                    </div>
                </div>
                <dl class="mt-6 space-y-3 text-sm">
                    <div><dt class="text-gray-400">Email</dt><dd class="text-gray-800 dark:text-gray-100">{{ $customer->email }}</dd></div>
                    <div><dt class="text-gray-400">Phone</dt><dd class="text-gray-800 dark:text-gray-100">{{ $customer->phone ?? '—' }}</dd></div>
                    <div><dt class="text-gray-400">Joined</dt><dd class="text-gray-800 dark:text-gray-100">{{ $customer->created_at?->format('d M Y') }}</dd></div>
                </dl>
                <form method="POST" action="{{ route('admin.customers.toggle', $customer->id) }}" class="mt-6">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="w-full rounded-lg border {{ $customer->is_active ? 'border-red-200 bg-red-50 text-red-700 hover:bg-red-100' : 'border-brand-200 bg-brand-50 text-brand-700 hover:bg-brand-100' }} px-4 py-2 text-sm font-medium">
                        {{ $customer->is_active ? 'Disable Account' : 'Enable Account' }}
                    </button>
                </form>
            </div>
        </div>

        <div class="lg:col-span-2">
            <div class="rounded-xl border border-gray-100 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <div class="border-b border-gray-100 px-5 py-3 dark:border-gray-700">
                    <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Order History</h2>
                </div>
                @if ($orders->isEmpty())
                    <p class="px-5 py-8 text-center text-sm text-gray-500">No orders placed.</p>
                @else
                    <table class="min-w-full divide-y divide-gray-100 text-sm dark:divide-gray-700">
                        <thead class="bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:bg-gray-900/40">
                            <tr><th class="px-5 py-2">Order</th><th class="px-5 py-2">Date</th><th class="px-5 py-2">Status</th><th class="px-5 py-2 text-right">Total</th></tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @foreach ($orders as $order)
                                <tr>
                                    <td class="px-5 py-2"><a href="{{ route('admin.orders.show', $order->id) }}" class="font-medium text-brand-700 hover:underline">#{{ $order->order_number }}</a></td>
                                    <td class="px-5 py-2 text-gray-500">{{ ($order->placed_at ?? $order->created_at)?->format('d M Y') }}</td>
                                    <td class="px-5 py-2"><span class="badge {{ $order->statusColor() }}">{{ ucfirst($order->status) }}</span></td>
                                    <td class="px-5 py-2 text-right font-semibold text-gray-800 dark:text-gray-100">₹{{ number_format((float) $order->total, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="px-5 py-4">{{ $orders->links() }}</div>
                @endif
            </div>
        </div>
    </div>
@endsection
