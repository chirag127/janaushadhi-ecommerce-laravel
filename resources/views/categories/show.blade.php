@extends('layouts.app')

@section('title', $category->name)
@section('description', Str::limit(strip_tags($category->description ?? $category->name . ' — generic medicines'), 155))

@section('content')
<div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
    <nav class="mb-4 text-sm text-gray-500" aria-label="Breadcrumb">
        <a href="{{ route('home') }}" wire:navigate class="hover:text-brand-700">Home</a>
        <span class="mx-1">/</span>
        <a href="{{ route('categories.index') }}" wire:navigate class="hover:text-brand-700">Categories</a>
        <span class="mx-1">/</span>
        <span class="text-gray-700 dark:text-gray-300">{{ $category->name }}</span>
    </nav>

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $category->name }}</h1>
        @if ($category->description)
            <p class="mt-1 max-w-2xl text-sm text-gray-500">{{ $category->description }}</p>
        @endif
        <p class="mt-1 text-sm text-gray-400">{{ number_format($products->total()) }} {{ Str::plural('product', $products->total()) }}</p>
    </div>

    @if ($products->isEmpty())
        <div class="rounded-xl border-2 border-dashed border-gray-200 py-20 text-center dark:border-gray-700">
            <p class="text-lg font-medium text-gray-700 dark:text-gray-200">No products in this category yet</p>
            <a href="{{ route('products.index') }}" wire:navigate class="btn-primary mt-4 text-sm">Browse all products</a>
        </div>
    @else
        <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
            @foreach ($products as $product)
                @include('partials.product-card', ['product' => $product])
            @endforeach
        </div>
        <div class="mt-8">{{ $products->links() }}</div>
    @endif
</div>
@endsection
