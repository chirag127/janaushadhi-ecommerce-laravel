@extends('layouts.admin')

@section('title', 'Admin Dashboard')
@section('heading', 'Dashboard')

@section('admin')
    {{-- Stat cards --}}
    <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
        <div class="rounded-xl border border-gray-100 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <p class="text-sm text-gray-500">Revenue</p>
            <p class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">₹{{ number_format($salesSummary['revenue'], 2) }}</p>
        </div>
        <div class="rounded-xl border border-gray-100 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <p class="text-sm text-gray-500">Orders</p>
            <p class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($salesSummary['orders']) }}</p>
        </div>
        <div class="rounded-xl border border-gray-100 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <p class="text-sm text-gray-500">Avg Order Value</p>
            <p class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">₹{{ number_format($salesSummary['average_order_value'], 2) }}</p>
        </div>
        <div class="rounded-xl border border-gray-100 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <p class="text-sm text-gray-500">Active Products</p>
            <p class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($totalProducts) }}</p>
        </div>
    </div>

    <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-3">
        {{-- Revenue series (simple bar chart) --}}
        <div class="rounded-xl border border-gray-100 bg-white p-5 shadow-sm lg:col-span-2 dark:border-gray-700 dark:bg-gray-800">
            <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Revenue — last 14 days</h2>
            @php $max = collect($salesSummary['series'])->max('revenue') ?: 1; @endphp
            @if (count($salesSummary['series']))
                <div class="mt-6 flex h-40 items-end gap-1.5">
                    @foreach ($salesSummary['series'] as $point)
                        <div class="group relative flex flex-1 flex-col items-center">
                            <div class="w-full rounded-t bg-brand-500 transition-all group-hover:bg-brand-600" style="height: {{ max(2, ($point['revenue'] / $max) * 100) }}%"></div>
                            <span class="pointer-events-none absolute -top-6 hidden whitespace-nowrap rounded bg-gray-800 px-1.5 py-0.5 text-[0.65rem] text-white group-hover:block">₹{{ number_format($point['revenue']) }}</span>
                        </div>
                    @endforeach
                </div>
                <div class="mt-2 flex justify-between text-[0.65rem] text-gray-400">
                    <span>{{ \Illuminate\Support\Carbon::parse($salesSummary['series'][0]['day'])->format('d M') }}</span>
                    <span>{{ \Illuminate\Support\Carbon::parse(end($salesSummary['series'])['day'])->format('d M') }}</span>
                </div>
            @else
                <p class="mt-4 text-sm text-gray-500">No sales data yet.</p>
            @endif
        </div>

        {{-- Orders by status --}}
        <div class="rounded-xl border border-gray-100 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Orders by Status</h2>
            <ul class="mt-4 space-y-2 text-sm">
                @forelse ($countByStatus as $status => $total)
                    <li class="flex items-center justify-between">
                        <span class="capitalize text-gray-600 dark:text-gray-300">{{ $status }}</span>
                        <span class="font-semibold text-gray-900 dark:text-white">{{ number_format($total) }}</span>
                    </li>
                @empty
                    <li class="text-gray-500">No orders yet.</li>
                @endforelse
            </ul>
            <div class="mt-4 border-t border-gray-100 pt-3 text-sm dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <span class="text-gray-600 dark:text-gray-300">Total Customers</span>
                    <span class="font-semibold text-gray-900 dark:text-white">{{ number_format($totalCustomers) }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Low stock --}}
    <div class="mt-6 rounded-xl border border-gray-100 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
        <div class="flex items-center justify-between border-b border-gray-100 px-5 py-3 dark:border-gray-700">
            <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Low Stock Alerts</h2>
            <a href="{{ route('admin.inventory.index') }}" class="text-xs font-medium text-brand-700 hover:underline">Manage inventory</a>
        </div>
        @if ($lowStock->isEmpty())
            <p class="px-5 py-6 text-sm text-gray-500">All products are well stocked.</p>
        @else
            <table class="min-w-full divide-y divide-gray-100 text-sm dark:divide-gray-700">
                <thead class="bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:bg-gray-900/40">
                    <tr><th class="px-5 py-2">Product</th><th class="px-5 py-2 text-right">Stock</th></tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @foreach ($lowStock as $product)
                        <tr>
                            <td class="px-5 py-2 text-gray-800 dark:text-gray-100">{{ $product->name }}</td>
                            <td class="px-5 py-2 text-right"><span class="badge {{ $product->stock == 0 ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800' }}">{{ $product->stock }}</span></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection
