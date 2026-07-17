@extends('layouts.admin')

@section('title', 'Coupons')

@section('content')
@php $symbol = config('store.currency_symbol'); @endphp
<div class="mb-4 flex items-center justify-between">
    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $coupons->total() }} coupons</p>
    <a href="{{ route('admin.coupons.create') }}" class="btn-primary">New coupon</a>
</div>

<div class="overflow-hidden rounded-xl border border-gray-100 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-100 text-sm dark:divide-gray-800">
            <thead class="bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:bg-gray-800/60 dark:text-gray-400">
                <tr>
                    <th class="px-4 py-3">Code</th>
                    <th class="px-4 py-3">Discount</th>
                    <th class="px-4 py-3">Min order</th>
                    <th class="px-4 py-3">Usage</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @forelse ($coupons as $coupon)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/40">
                        <td class="px-4 py-3 font-mono font-medium text-gray-900 dark:text-white">{{ $coupon->code }}</td>
                        <td class="px-4 py-3 text-gray-600 dark:text-gray-300">
                            {{ $coupon->type === 'percent' ? rtrim(rtrim(number_format((float) $coupon->value, 2), '0'), '.') . '%' : $symbol . number_format((float) $coupon->value, 2) }}
                        </td>
                        <td class="px-4 py-3 text-gray-600 dark:text-gray-300">{{ $symbol }}{{ number_format((float) $coupon->min_order_amount, 2) }}</td>
                        <td class="px-4 py-3 text-gray-600 dark:text-gray-300">{{ $coupon->used_count }}{{ $coupon->usage_limit ? ' / ' . $coupon->usage_limit : '' }}</td>
                        <td class="px-4 py-3">
                            @if ($coupon->isValidNow())
                                <span class="rounded-full bg-brand-100 px-2 py-0.5 text-xs font-medium text-brand-800 dark:bg-brand-900/40 dark:text-brand-300">Active</span>
                            @else
                                <span class="rounded-full bg-gray-200 px-2 py-0.5 text-xs font-medium text-gray-600 dark:bg-gray-700 dark:text-gray-300">Inactive</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.coupons.edit', $coupon) }}" class="text-brand-600 hover:underline">Edit</a>
                                <form method="POST" action="{{ route('admin.coupons.destroy', $coupon) }}" onsubmit="return confirm('Delete this coupon?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-10 text-center text-gray-500 dark:text-gray-400">No coupons yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-6">{{ $coupons->links() }}</div>
@endsection
