@extends('layouts.app')

@section('title', $category->name)
@section('description', Str::limit(strip_tags($category->description ?? ''), 155) ?: $category->name . ' — affordable generic medicines at ' . config('app.name'))

@section('content')
<div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
    <nav class="mb-4 text-sm text-gray-500 dark:text-gray-400" aria-label="Breadcrumb">
        <a href="{{ route('home') }}" wire:navigate class="hover:text-brand-600">Home</a><span class="mx-1">/</span>
        <a href="{{ route('categories.index') }}" wire:navigate class="hover:text-brand-600">Categories</a><span class="mx-1">/</span>
        <span class="text-gray-700 dark:text-gray-200">{{ $category->name }}</span>
    </nav>

    <div class="flex flex-col gap-4 sm:flex-row sm:items-center">
        <img src="{{ $category->imageOrPlaceholder() }}" alt="{{ $category->name }}" class="h-20 w-20 rounded-xl border border-gray-100 object-cover dark:border-gray-700" />
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $category->name }}</h1>
            @if ($category->description)<p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">{{ $category->description }}</p>@endif
            <p class="mt-1 text-sm text-gray-400">{{ $products->total() }} {{ Str::plural('product', $products->total()) }}</p>
        </div>
    </div>

    @if ($products->isEmpty())
        <div class="mt-8 rounded-xl border border-dashed border-gray-300 bg-white p-12 text-center dark:border-gray-700 dark:bg-gray-800">
            <p class="text-gray-500 dark:text-gray-400">No products in this category yet.</p>
        </div>
    @else
        <div class="mt-8 grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
            @foreach ($products as $product)
                @include('partials.product-card', ['product' => $product])
            @endforeach
        </div>
        <div class="mt-8">{{ $products->links() }}</div>
    @endif
</div>
@endsection
