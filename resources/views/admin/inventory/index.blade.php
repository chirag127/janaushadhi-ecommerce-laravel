@extends('layouts.admin')

@section('title', 'Inventory')

@section('content')
<form method="GET" action="{{ route('admin.inventory.index') }}" class="mb-4 flex flex-wrap items-center gap-2">
    <input type="search" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Search name / drug code…" class="form-input text-sm" />
    <button type="submit" class="btn-secondary">Search</button>
</form>

<div class="overflow-hidden rounded-xl border border-gray-100 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-100 text-sm dark:divide-gray-800">
            <thead class="bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:bg-gray-800/60 dark:text-gray-400">
                <tr>
                    <th class="px-4 py-3">Product</th>
                    <th class="px-4 py-3">Category</th>
                    <th class="px-4 py-3">Current stock</th>
                    <th class="px-4 py-3">Update stock</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @forelse ($products as $product)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/40 {{ $product->stock <= $lowStockThreshold ? 'bg-red-50/60 dark:bg-red-900/10' : '' }}">
                        <td class="px-4 py-3">
                            <p class="font-medium text-gray-900 dark:text-white">{{ $product->name }}</p>
                            <p class="text-xs text-gray-400">{{ $product->drug_code ?? '—' }}</p>
                        </td>
                        <td class="px-4 py-3 text-gray-600 dark:text-gray-300">{{ $product->category?->name ?? '—' }}</td>
                        <td class="px-4 py-3">
                            <span class="font-semibold {{ $product->stock <= $lowStockThreshold ? 'text-red-600 dark:text-red-400' : 'text-gray-900 dark:text-white' }}">{{ $product->stock }}</span>
                            @if ($product->stock <= $lowStockThreshold)
                                <span class="ml-1 text-xs text-red-500">low</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <form method="POST" action="{{ route('admin.inventory.update', $product) }}" class="flex items-center gap-2">
                                @csrf @method('PATCH')
                                <input type="number" name="stock" min="0" value="{{ $product->stock }}" class="form-input w-24 text-sm" />
                                <button type="submit" class="btn-secondary">Save</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-4 py-10 text-center text-gray-500 dark:text-gray-400">No products found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-6">{{ $products->links() }}</div>
@endsection
