@extends('layouts.admin')

@section('title', 'Inventory')
@section('heading', 'Inventory')

@section('admin')
    <form method="GET" action="{{ route('admin.inventory.index') }}" class="mb-4 flex flex-wrap gap-2">
        <input type="search" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Search products…" class="form-input max-w-sm text-sm" />
        <button type="submit" class="btn-primary text-sm">Search</button>
        <a href="{{ route('admin.inventory.index') }}" class="btn-secondary text-sm">Reset</a>
    </form>

    <div class="overflow-hidden rounded-xl border border-gray-100 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100 text-sm dark:divide-gray-700">
                <thead class="bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:bg-gray-900/40">
                    <tr>
                        <th class="px-4 py-3">Product</th>
                        <th class="px-4 py-3">Category</th>
                        <th class="px-4 py-3 text-center">Current Stock</th>
                        <th class="px-4 py-3">Update</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse ($products as $product)
                        <tr class="{{ $product->stock <= $lowStockThreshold ? 'bg-yellow-50/50 dark:bg-yellow-900/10' : '' }}">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $product->imageOrPlaceholder() }}" alt="" class="h-9 w-9 rounded object-cover" />
                                    <span class="font-medium text-gray-800 dark:text-gray-100">{{ $product->name }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-gray-500">{{ $product->category->name ?? '—' }}</td>
                            <td class="px-4 py-3 text-center">
                                <span class="badge {{ $product->stock == 0 ? 'bg-red-100 text-red-800' : ($product->stock <= $lowStockThreshold ? 'bg-yellow-100 text-yellow-800' : 'bg-brand-100 text-brand-800') }}">{{ $product->stock }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <form method="POST" action="{{ route('admin.inventory.update', $product->slug) }}" class="flex items-center gap-2">
                                    @csrf
                                    @method('PATCH')
                                    <input type="number" name="stock" value="{{ $product->stock }}" min="0" class="form-input w-24 text-sm" />
                                    <button type="submit" class="btn-primary text-xs">Save</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-4 py-8 text-center text-gray-500">No products found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">{{ $products->links() }}</div>
@endsection
