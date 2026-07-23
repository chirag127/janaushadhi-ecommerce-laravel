@extends('layouts.app')

@section('title', 'Categories')

@section('content')
<div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
    <nav class="mb-4 text-sm text-gray-500" aria-label="Breadcrumb">
        <a href="{{ route('home') }}" wire:navigate class="hover:text-brand-700">Home</a>
        <span class="mx-1">/</span>
        <span class="text-gray-700 dark:text-gray-300">Categories</span>
    </nav>
    <h1 class="mb-6 text-2xl font-bold text-gray-900 dark:text-white">Shop by Category</h1>

    @if ($categories->isEmpty())
        <p class="text-sm text-gray-500">No categories available.</p>
    @else
        <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
            @foreach ($categories as $category)
                <a href="{{ route('categories.show', $category->slug) }}" wire:navigate class="group rounded-xl border border-gray-100 bg-white p-5 text-center shadow-sm transition hover:shadow-md dark:border-gray-700 dark:bg-gray-800">
                    <img src="{{ $category->imageOrPlaceholder() }}" alt="{{ $category->name }}" class="mx-auto h-24 w-full rounded-lg object-cover" />
                    <p class="mt-3 line-clamp-2 font-medium text-gray-800 group-hover:text-brand-700 dark:text-gray-100">{{ $category->name }}</p>
                    <p class="mt-1 text-xs text-gray-400">{{ $category->products_count }} {{ Str::plural('product', $category->products_count) }}</p>
                </a>
            @endforeach
        </div>
    @endif
</div>
@endsection
