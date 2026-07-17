@extends('layouts.admin')

@section('title', 'Product')

@section('content')
@php $symbol = config('store.currency_symbol'); @endphp
<div class="mx-auto max-w-3xl">
    <div class="mb-4 flex items-center justify-between">
        <a href="{{ route('admin.products.index') }}" class="text-sm text-brand-600 hover:underline">&larr; Back to products</a>
        <a href="{{ route('admin.products.edit', $product) }}" class="btn-primary">Edit</a>
    </div>
    <div class="overflow-hidden rounded-xl border border-gray-100 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900">
        <div class="flex flex-col gap-6 p-6 sm:flex-row">
            <img src="{{ $product->imageOrPlaceholder() }}" alt="{{ $product->name }}" class="h-40 w-40 shrink-0 rounded-lg object-cover" />
            <div class="min-w-0 flex-1">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ $product->name }}</h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $product->category?->name ?? 'Uncategorized' }} · {{ $product->drug_code ?? 'No code' }}</p>
                <div class="mt-3 flex items-baseline gap-3">
                    <span class="text-2xl font-bold text-gray-900 dark:text-white">{{ $symbol }}{{ number_format((float) $product->price, 2) }}</span>
                    @if ($product->discountPercent() > 0)
                        <span class="text-sm text-gray-400 line-through">{{ $symbol }}{{ number_format((float) $product->mrp, 2) }}</span>
                        <span class="rounded-full bg-accent-500 px-2 py-0.5 text-xs font-bold text-white">{{ $product->discountPercent() }}% OFF</span>
                    @endif
                </div>
                <dl class="mt-4 grid grid-cols-2 gap-3 text-sm">
                    <div><dt class="text-gray-500 dark:text-gray-400">Stock</dt><dd class="font-medium text-gray-900 dark:text-white">{{ $product->stock }}</dd></div>
                    <div><dt class="text-gray-500 dark:text-gray-400">Unit size</dt><dd class="font-medium text-gray-900 dark:text-white">{{ $product->unit_size ?? '—' }}</dd></div>
                    <div><dt class="text-gray-500 dark:text-gray-400">Status</dt><dd class="font-medium text-gray-900 dark:text-white">{{ $product->is_active ? 'Active' : 'Inactive' }}</dd></div>
                    <div><dt class="text-gray-500 dark:text-gray-400">Featured</dt><dd class="font-medium text-gray-900 dark:text-white">{{ $product->is_featured ? 'Yes' : 'No' }}</dd></div>
                </dl>
            </div>
        </div>
        @if ($product->description)
            <div class="border-t border-gray-100 p-6 dark:border-gray-800">
                <h3 class="mb-2 text-sm font-semibold text-gray-700 dark:text-gray-200">Description</h3>
                <p class="whitespace-pre-line text-sm text-gray-600 dark:text-gray-300">{{ $product->description }}</p>
            </div>
        @endif
    </div>
</div>
@endsection
