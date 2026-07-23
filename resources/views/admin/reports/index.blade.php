@extends('layouts.admin')

@section('title', 'Sales Reports')
@section('heading', 'Sales Reports')

@section('admin')
    <form method="GET" action="{{ route('admin.reports.index') }}" class="mb-6 flex flex-wrap items-end gap-3 rounded-xl border border-gray-100 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800">
        <div>
            <label for="from" class="block text-xs font-medium text-gray-500">From</label>
            <input id="from" type="date" name="from" value="{{ $from }}" class="form-input mt-1 text-sm" />
        </div>
        <div>
            <label for="to" class="block text-xs font-medium text-gray-500">To</label>
            <input id="to" type="date" name="to" value="{{ $to }}" class="form-input mt-1 text-sm" />
        </div>
        <button type="submit" class="btn-primary text-sm">Apply</button>
        <a href="{{ route('admin.reports.index') }}" class="btn-secondary text-sm">Reset</a>
    </form>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
        <div class="rounded-xl border border-gray-100 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <p class="text-sm text-gray-500">Total Revenue</p>
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
    </div>

    <div class="mt-6 rounded-xl border border-gray-100 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
        <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Daily Revenue (last 14 days)</h2>
        @php $max = collect($salesSummary['series'])->max('revenue') ?: 1; @endphp
        @if (count($salesSummary['series']))
            <div class="mt-6 flex h-48 items-end gap-2">
                @foreach ($salesSummary['series'] as $point)
                    <div class="group relative flex flex-1 flex-col items-center">
                        <div class="w-full rounded-t bg-brand-500 transition-all group-hover:bg-brand-600" style="height: {{ max(2, ($point['revenue'] / $max) * 100) }}%"></div>
                        <span class="pointer-events-none absolute -top-6 hidden whitespace-nowrap rounded bg-gray-800 px-1.5 py-0.5 text-[0.65rem] text-white group-hover:block">₹{{ number_format($point['revenue']) }} · {{ $point['orders'] }} ord</span>
                        <span class="mt-1 text-[0.6rem] text-gray-400">{{ \Illuminate\Support\Carbon::parse($point['day'])->format('d/m') }}</span>
                    </div>
                @endforeach
            </div>
        @else
            <p class="mt-4 text-sm text-gray-500">No sales data for this period.</p>
        @endif
    </div>
@endsection
