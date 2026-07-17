@extends('layouts.admin')

@section('title', 'Products')

@section('content')
@php $symbol = config('store.currency_symbol'); @endphp
<div class="mb-4 flex flex-wrap items-center justify-between gap-3">
    <form method="GET" action="{{ route('admin.products.index') }}" class="flex flex-wrap items-center gap-2">
        <input type="search" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Search name / drug code…" class="form-input text-sm" />
        <select name="category" class="form-select text-sm">
            <option value="">All categories</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" @selected(($filters['category'] ?? '') === $category->id)>{{ $category->name }}</option>
            @endforeach
        </select>
        <select name="is_active" class="form-select text-sm">
            <option value="">Any status</option>
            <option value="1" @selected(($filters['is_active'] ?? '') === '1')>Active</option>
            <option value="0" @selected(($filters['is_active'] ?? '') === '0')>Inactive</option>
        </select>
        <button type="submit" class="btn-secondary">Filter</button>
    </form>
    <a href="{{ route('admin.products.create') }}" class="btn-primary">New product</a>
</div>

<div class="overflow-hidden rounded-xl border border-gray-100 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-100 text-sm dark:divide-gray-800">
            <thead class="bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:bg-gray-800/60 dark:text-gray-400">
                <tr>
                    <th class="px-4 py-3">Product</th>
                    <th class="px-4 py-3">Category</th>
                    <th class="px-4 py-3">Price</th>
                    <th class="px-4 py-3">Stock</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @forelse ($products as $product)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/40">
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                <img src="{{ $product->imageOrPlaceholder() }}" alt="" class="h-10 w-10 shrink-0 rounded object-cover" loading="lazy" />
                                <div class="min-w-0">
                                    <p class="truncate font-medium text-gray-900 dark:text-white">{{ $product->name }}</p>
                                    <p class="truncate text-xs text-gray-400">{{ $product->drug_code ?? '—' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-gray-600 dark:text-gray-300">{{ $product->category?->name ?? '—' }}</td>
                        <td class="px-4 py-3 text-gray-900 dark:text-white">{{ $symbol }}{{ number_format((float) $product->price, 2) }}</td>
                        <td class="px-4 py-3">
                            <span class="{{ $product->stock <= 10 ? 'text-red-600 dark:text-red-400' : 'text-gray-600 dark:text-gray-300' }}">{{ $product->stock }}</span>
                        </td>
                        <td class="px-4 py-3">
                            @if ($product->is_active)
                                <span class="rounded-full bg-brand-100 px-2 py-0.5 text-xs font-medium text-brand-800 dark:bg-brand-900/40 dark:text-brand-300">Active</span>
                            @else
                                <span class="rounded-full bg-gray-200 px-2 py-0.5 text-xs font-medium text-gray-600 dark:bg-gray-700 dark:text-gray-300">Inactive</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.products.show', $product) }}" class="text-brand-600 hover:underline">View</a>
                                <a href="{{ route('admin.products.edit', $product) }}" class="text-brand-600 hover:underline">Edit</a>
                                <form method="POST" action="{{ route('admin.products.destroy', $product) }}" onsubmit="return confirm('Deactivate this product?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-10 text-center text-gray-500 dark:text-gray-400">No products found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-6">{{ $products->links() }}</div>
@endsection
