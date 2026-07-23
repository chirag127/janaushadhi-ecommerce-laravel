@extends('layouts.admin')

@section('title', 'Products')
@section('heading', 'Products')

@section('actions')
    <a href="{{ route('admin.products.create') }}" class="btn-primary text-sm">Add Product</a>
@endsection

@section('admin')
    <form method="GET" action="{{ route('admin.products.index') }}" class="mb-4 flex flex-wrap gap-2">
        <input type="search" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Search products…" class="form-input max-w-xs text-sm" />
        <select name="category" class="form-select max-w-xs text-sm">
            <option value="">All categories</option>
            @foreach ($categories as $cat)
                <option value="{{ $cat->id }}" @selected(($filters['category'] ?? '') == $cat->id)>{{ $cat->name }}</option>
            @endforeach
        </select>
        <select name="is_active" class="form-select max-w-[10rem] text-sm">
            <option value="">All statuses</option>
            <option value="1" @selected(($filters['is_active'] ?? '') === '1')>Active</option>
            <option value="0" @selected(($filters['is_active'] ?? '') === '0')>Inactive</option>
        </select>
        <button type="submit" class="btn-primary text-sm">Filter</button>
        <a href="{{ route('admin.products.index') }}" class="btn-secondary text-sm">Reset</a>
    </form>

    <div class="overflow-hidden rounded-xl border border-gray-100 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100 text-sm dark:divide-gray-700">
                <thead class="bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:bg-gray-900/40">
                    <tr>
                        <th class="px-4 py-3">Product</th>
                        <th class="px-4 py-3">Category</th>
                        <th class="px-4 py-3 text-right">Price</th>
                        <th class="px-4 py-3 text-right">Stock</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse ($products as $product)
                        <tr>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $product->imageOrPlaceholder() }}" alt="" class="h-10 w-10 rounded object-cover" />
                                    <div>
                                        <p class="font-medium text-gray-800 dark:text-gray-100">{{ $product->name }}</p>
                                        <p class="text-xs text-gray-400">{{ $product->unit_size }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-gray-500">{{ $product->category->name ?? '—' }}</td>
                            <td class="px-4 py-3 text-right font-medium text-gray-800 dark:text-gray-100">₹{{ number_format((float) $product->price, 2) }}</td>
                            <td class="px-4 py-3 text-right">{{ $product->stock }}</td>
                            <td class="px-4 py-3">
                                <span class="badge {{ $product->is_active ? 'bg-brand-100 text-brand-800' : 'bg-gray-100 text-gray-600' }}">{{ $product->is_active ? 'Active' : 'Inactive' }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-2 text-xs">
                                    <a href="{{ route('admin.products.show', $product->slug) }}" class="text-gray-500 hover:text-brand-700">View</a>
                                    <a href="{{ route('admin.products.edit', $product->slug) }}" class="text-brand-700 hover:underline">Edit</a>
                                    <form method="POST" action="{{ route('admin.products.destroy', $product->slug) }}" onsubmit="return confirm('Delete this product?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-4 py-8 text-center text-gray-500">No products found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">{{ $products->links() }}</div>
@endsection
