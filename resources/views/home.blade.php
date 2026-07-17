@extends('layouts.app')

@section('title', 'Affordable Generic Medicines')

@section('content')
    {{-- Hero --}}
    <section class="bg-gradient-to-br from-brand-600 to-brand-800 text-white">
        <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8 lg:py-24">
            <div class="max-w-2xl">
                <h1 class="text-3xl font-bold tracking-tight sm:text-4xl lg:text-5xl">Quality generic medicines, honest prices.</h1>
                <p class="mt-4 text-lg text-brand-50">{{ config('app.name') }} brings affordable, quality-assured generic medicines and healthcare essentials to your doorstep across India.</p>
                <div class="mt-8 flex flex-wrap gap-3">
                    <a href="{{ route('products.index') }}" wire:navigate class="inline-flex items-center justify-center gap-2 rounded-lg bg-white px-5 py-3 text-sm font-semibold text-brand-700 hover:bg-brand-50">Shop All Products</a>
                    <a href="{{ route('categories.index') }}" wire:navigate class="inline-flex items-center justify-center gap-2 rounded-lg border border-white/40 px-5 py-3 text-sm font-semibold text-white hover:bg-white/10">Browse Categories</a>
                </div>
            </div>
        </div>
    </section>

    {{-- Trust bar --}}
    <section class="border-b border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
        <div class="mx-auto grid max-w-7xl grid-cols-2 gap-6 px-4 py-6 sm:px-6 md:grid-cols-4 lg:px-8">
            @foreach ([
                ['Quality Assured', 'WHO-GMP certified generics'],
                ['Affordable Pricing', 'Up to 90% savings vs brands'],
                ['Fast Delivery', 'Across India'],
                ['Secure Checkout', 'COD & online payments'],
            ] as $feature)
                <div class="flex items-start gap-3">
                    <span class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-brand-50 text-brand-600">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </span>
                    <div>
                        <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $feature[0] }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $feature[1] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- Categories --}}
    @if ($categories->isNotEmpty())
    <section class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="flex items-end justify-between">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white">Shop by Category</h2>
            <a href="{{ route('categories.index') }}" wire:navigate class="text-sm font-medium text-brand-600 hover:underline">View all</a>
        </div>
        <div class="mt-6 grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-6">
            @foreach ($categories->take(12) as $category)
                <a href="{{ route('categories.show', $category->slug) }}" wire:navigate class="group flex flex-col items-center rounded-xl border border-gray-100 bg-white p-4 text-center shadow-sm transition hover:shadow-md dark:border-gray-700 dark:bg-gray-800">
                    <img src="{{ $category->imageOrPlaceholder() }}" alt="{{ $category->name }}" class="h-16 w-16 rounded-lg object-cover" loading="lazy" />
                    <p class="mt-2 line-clamp-2 text-xs font-semibold text-gray-800 group-hover:text-brand-600 dark:text-gray-100">{{ $category->name }}</p>
                    <p class="text-[11px] text-gray-400">{{ $category->products_count }} items</p>
                </a>
            @endforeach
        </div>
    </section>
    @endif

    {{-- Featured products --}}
    @if ($featuredProducts->isNotEmpty())
    <section class="bg-white dark:bg-gray-900">
        <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
            <div class="flex items-end justify-between">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white">Featured Products</h2>
                <a href="{{ route('products.index') }}" wire:navigate class="text-sm font-medium text-brand-600 hover:underline">View all</a>
            </div>
            <div class="mt-6 grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
                @foreach ($featuredProducts as $product)
                    @include('partials.product-card', ['product' => $product])
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- CTA --}}
    <section class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="rounded-2xl bg-brand-50 px-6 py-10 text-center dark:bg-gray-800">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Save on every prescription</h2>
            <p class="mx-auto mt-2 max-w-xl text-sm text-gray-600 dark:text-gray-300">Generic medicines contain the same active ingredients as branded drugs at a fraction of the cost. Start saving today.</p>
            <a href="{{ route('products.index') }}" wire:navigate class="btn-primary mt-6">Start Shopping</a>
        </div>
    </section>
@endsection
