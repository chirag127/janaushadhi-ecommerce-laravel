@extends('layouts.app')

@section('title', 'Categories')

@section('content')
<div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
    <nav class="mb-4 text-sm text-gray-500 dark:text-gray-400" aria-label="Breadcrumb">
        <a href="{{ route('home') }}" wire:navigate class="hover:text-brand-600">Home</a><span class="mx-1">/</span><span class="text-gray-700 dark:text-gray-200">Categories</span>
    </nav>
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Shop by Category</h1>
    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Browse our full range of generic medicines and healthcare products.</p>

    @if ($categories->isEmpty())
        <p class="mt-8 text-gray-500 dark:text-gray-400">No categories available.</p>
    @else
        <div class="mt-8 grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
            @foreach ($categories as $category)
                <a href="{{ route('categories.show', $category->slug) }}" wire:navigate class="group flex flex-col overflow-hidden rounded-xl border border-gray-100 bg-white shadow-sm transition hover:shadow-md dark:border-gray-700 dark:bg-gray-800">
                    <img src="{{ $category->imageOrPlaceholder() }}" alt="{{ $category->name }}" class="aspect-[4/3] w-full object-cover transition group-hover:scale-105" loading="lazy" />
                    <div class="p-4">
                        <h2 class="text-sm font-semibold text-gray-900 group-hover:text-brand-600 dark:text-white">{{ $category->name }}</h2>
                        <p class="mt-0.5 text-xs text-gray-500 dark:text-gray-400">{{ $category->products_count }} {{ Str::plural('product', $category->products_count) }}</p>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
</div>
@endsection
