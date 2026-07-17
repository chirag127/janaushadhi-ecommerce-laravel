@extends('layouts.admin')

@section('title', 'Sales Reports')

@section('content')
@php
    $symbol = config('store.currency_symbol');
    $series = $salesSummary['series'] ?? [];
    $maxRev = collect($series)->max('revenue') ?: 1;
@endphp
<form method="GET" action="{{ route('admin.reports.index') }}" class="mb-6 flex flex-wrap items-end gap-3">
    <div>
        <label for="from" class="mb-1 block text-xs font-semibold text-gray-500 dark:text-gray-400">From</label>
        <input id="from" name="from" type="date" value="{{ $from ?? '' }}" class="form-input text-sm" />
    </div>
    <div>
        <label for="to" class="mb-1 block text-xs font-semibold text-gray-500 dark:text-gray-400">To</label>
        <input id="to" name="to" type="date" value="{{ $to ?? '' }}" class="form-input text-sm" />
    </div>
    <button type="submit" class="btn-primary">Apply</button>
    <a href="{{ route('admin.reports.index') }}" class="btn-secondary">Reset</a>
</form>

<div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
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
</div>

<div class="mt-6 rounded-xl border border-gray-100 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
    <h2 class="mb-4 text-base font-semibold text-gray-900 dark:text-white">Daily revenue (last 14 days)</h2>
    @if (empty($series))
        <p class="text-sm text-gray-500 dark:text-gray-400">No sales in this window.</p>
    @else
        <div class="flex h-48 items-end gap-1">
            @foreach ($series as $point)
                <div class="flex flex-1 flex-col items-center gap-1" title="{{ $point['day'] }}: {{ $symbol }}{{ number_format($point['revenue'], 2) }} ({{ $point['orders'] }} orders)">
                    <div class="w-full rounded-t bg-brand-500" style="height: {{ max(2, round(($point['revenue'] / $maxRev) * 100)) }}%"></div>
                    <span class="text-[10px] text-gray-400">{{ \Illuminate\Support\Carbon::parse($point['day'])->format('d/m') }}</span>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
