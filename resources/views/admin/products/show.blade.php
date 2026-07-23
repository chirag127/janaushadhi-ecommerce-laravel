@extends('layouts.admin')

@section('title', $product->name)
@section('heading', 'Product Details')

@section('actions')
    <a href="{{ route('admin.products.edit', $product->slug) }}" class="btn-primary text-sm">Edit</a>
@endsection

@section('admin')
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="lg:col-span-1">
            <img src="{{ $product->imageOrPlaceholder() }}" alt="{{ $product->name }}" class="w-full rounded-xl border border-gray-100 object-cover dark:border-gray-700" />
        </div>
        <div class="lg:col-span-2">
            <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ $product->name }}</h2>
                <div class="mt-2 flex items-center gap-2">
                    <span class="badge {{ $product->is_active ? 'bg-brand-100 text-brand-800' : 'bg-gray-100 text-gray-600' }}">{{ $product->is_active ? 'Active' : 'Inactive' }}</span>
                    @if ($product->is_featured)
                        <span class="badge bg-accent-500/20 text-accent-600">Featured</span>
                    @endif
                </div>

                <dl class="mt-6 grid grid-cols-2 gap-4 text-sm">
                    <div><dt class="text-gray-400">Category</dt><dd class="font-medium text-gray-800 dark:text-gray-100">{{ $product->category->name ?? '—' }}</dd></div>
                    <div><dt class="text-gray-400">Drug code</dt><dd class="font-medium text-gray-800 dark:text-gray-100">{{ $product->drug_code ?? '—' }}</dd></div>
                    <div><dt class="text-gray-400">Unit size</dt><dd class="font-medium text-gray-800 dark:text-gray-100">{{ $product->unit_size ?? '—' }}</dd></div>
                    <div><dt class="text-gray-400">Slug</dt><dd class="font-medium text-gray-800 dark:text-gray-100">{{ $product->slug }}</dd></div>
                    <div><dt class="text-gray-400">MRP</dt><dd class="font-medium text-gray-800 dark:text-gray-100">₹{{ number_format((float) $product->mrp, 2) }}</dd></div>
                    <div><dt class="text-gray-400">Price</dt><dd class="font-medium text-brand-700">₹{{ number_format((float) $product->price, 2) }}</dd></div>
                    <div><dt class="text-gray-400">Stock</dt><dd class="font-medium text-gray-800 dark:text-gray-100">{{ $product->stock }}</dd></div>
                    <div><dt class="text-gray-400">Discount</dt><dd class="font-medium text-gray-800 dark:text-gray-100">{{ $product->discountPercent() }}%</dd></div>
                </dl>

                @if ($product->description)
                    <div class="mt-6">
                        <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Description</h3>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">{{ $product->description }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
