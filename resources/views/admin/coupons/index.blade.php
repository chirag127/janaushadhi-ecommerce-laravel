@extends('layouts.admin')

@section('title', 'Coupons')
@section('heading', 'Coupons')

@section('actions')
    <a href="{{ route('admin.coupons.create') }}" class="btn-primary text-sm">Add Coupon</a>
@endsection

@section('admin')
    <div class="overflow-hidden rounded-xl border border-gray-100 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100 text-sm dark:divide-gray-700">
                <thead class="bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:bg-gray-900/40">
                    <tr>
                        <th class="px-4 py-3">Code</th>
                        <th class="px-4 py-3">Discount</th>
                        <th class="px-4 py-3 text-right">Min order</th>
                        <th class="px-4 py-3 text-right">Usage</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse ($coupons as $coupon)
                        <tr>
                            <td class="px-4 py-3 font-mono font-medium text-gray-800 dark:text-gray-100">{{ $coupon->code }}</td>
                            <td class="px-4 py-3 text-gray-600 dark:text-gray-300">
                                {{ $coupon->type === 'percent' ? number_format((float) $coupon->value, 0) . '%' : '₹' . number_format((float) $coupon->value, 2) }}
                                @if ($coupon->max_discount)
                                    <span class="text-xs text-gray-400">(max ₹{{ number_format((float) $coupon->max_discount, 0) }})</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right">₹{{ number_format((float) $coupon->min_order_amount, 0) }}</td>
                            <td class="px-4 py-3 text-right">{{ $coupon->used_count }}{{ $coupon->usage_limit ? ' / ' . $coupon->usage_limit : '' }}</td>
                            <td class="px-4 py-3">
                                <span class="badge {{ $coupon->isValidNow() ? 'bg-brand-100 text-brand-800' : 'bg-gray-100 text-gray-600' }}">{{ $coupon->isValidNow() ? 'Active' : 'Inactive' }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-2 text-xs">
                                    <a href="{{ route('admin.coupons.edit', $coupon->id) }}" class="text-brand-700 hover:underline">Edit</a>
                                    <form method="POST" action="{{ route('admin.coupons.destroy', $coupon->id) }}" onsubmit="return confirm('Delete this coupon?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-4 py-8 text-center text-gray-500">No coupons yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">{{ $coupons->links() }}</div>
@endsection
