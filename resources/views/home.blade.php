@extends('layouts.app')

@section('title', 'Home')
@section('description', 'Jan Aushadhi — quality generic medicines at up to 90% less than branded equivalents. Order online with fast delivery across India.')

@section('content')
    {{-- Hero --}}
    <section class="bg-gradient-to-br from-brand-700 via-brand-600 to-brand-500 text-white">
        <div class="mx-auto grid max-w-7xl gap-8 px-4 py-16 sm:px-6 lg:grid-cols-2 lg:items-center lg:px-8">
            <div>
                <span class="inline-flex items-center rounded-full bg-white/15 px-3 py-1 text-xs font-semibold">Trusted generic medicines</span>
                <h1 class="mt-4 text-3xl font-extrabold leading-tight sm:text-4xl lg:text-5xl">Quality medicines, up to 90% less.</h1>
                <p class="mt-4 max-w-lg text-brand-50/90">The same active ingredients as branded drugs, at a fraction of the price. Order online and get it delivered across India.</p>
                <div class="mt-8 flex flex-wrap gap-3">
                    <a href="{{ route('products.index') }}" wire:navigate class="rounded-lg bg-white px-6 py-3 text-sm font-semibold text-brand-700 shadow hover:bg-brand-50">Shop Now</a>
                    <a href="{{ route('categories.index') }}" wire:navigate class="rounded-lg border border-white/40 px-6 py-3 text-sm font-semibold text-white hover:bg-white/10">Browse Categories</a>
                </div>
                <dl class="mt-10 grid grid-cols-3 gap-4 text-center">
                    <div><dt class="text-2xl font-bold">90%</dt><dd class="text-xs text-brand-50/80">Avg. savings</dd></div>
                    <div><dt class="text-2xl font-bold">2000+</dt><dd class="text-xs text-brand-50/80">Products</dd></div>
                    <div><dt class="text-2xl font-bold">Free</dt><dd class="text-xs text-brand-50/80">Delivery over ₹{{ number_format((float) config('store.free_shipping_threshold')) }}</dd></div>
                </dl>
            </div>
            <div class="hidden lg:block">
                <div class="rounded-2xl bg-white/10 p-6 backdrop-blur">
                    <div class="grid grid-cols-2 gap-3">
                        @foreach ($featuredProducts->take(4) as $fp)
                            <div class="rounded-xl bg-white p-3 text-gray-800">
                                <img src="{{ $fp->imageOrPlaceholder() }}" alt="{{ $fp->name }}" class="h-24 w-full rounded-lg object-cover" />
                                <p class="mt-2 line-clamp-1 text-xs font-medium">{{ $fp->name }}</p>
                                <p class="text-sm font-bold text-brand-700">₹{{ number_format((float) $fp->price, 2) }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Value props --}}
    <section class="border-b border-gray-100 bg-white dark:border-gray-800 dark:bg-gray-900">
        <div class="mx-auto grid max-w-7xl gap-6 px-4 py-8 sm:grid-cols-3 sm:px-6 lg:px-8">
            @foreach ([
                ['M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'Quality assured', 'WHO-GMP certified manufacturers.'],
                ['M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', 'Fast delivery', 'Doorstep delivery across India.'],
                ['M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3', 'Affordable pricing', 'Save big on every order.'],
            ] as [$icon, $t, $d])
                <div class="flex items-start gap-3">
                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-brand-50 text-brand-600"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $icon }}" /></svg></span>
                    <div><h3 class="text-sm font-semibold text-gray-900 dark:text-white">{{ $t }}</h3><p class="text-sm text-gray-500 dark:text-gray-400">{{ $d }}</p></div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- Categories --}}
    <section class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="mb-6 flex items-end justify-between">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white">Shop by Category</h2>
            <a href="{{ route('categories.index') }}" wire:navigate class="text-sm font-medium text-brand-700 hover:underline">View all</a>
        </div>
        <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-6">
            @foreach ($categories->take(6) as $category)
                <a href="{{ route('categories.show', $category->slug) }}" wire:navigate class="group rounded-xl border border-gray-100 bg-white p-4 text-center shadow-sm transition hover:shadow-md dark:border-gray-700 dark:bg-gray-800">
                    <img src="{{ $category->imageOrPlaceholder() }}" alt="{{ $category->name }}" class="mx-auto h-16 w-16 rounded-lg object-cover" />
                    <p class="mt-2 line-clamp-2 text-sm font-medium text-gray-800 group-hover:text-brand-700 dark:text-gray-100">{{ $category->name }}</p>
                    <p class="text-xs text-gray-400">{{ $category->products_count }} items</p>
                </a>
            @endforeach
        </div>
    </section>

    {{-- Featured products --}}
    <section class="bg-white py-12 dark:bg-gray-900">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mb-6 flex items-end justify-between">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white">Featured Products</h2>
                <a href="{{ route('products.index') }}" wire:navigate class="text-sm font-medium text-brand-700 hover:underline">Shop all</a>
            </div>
            <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
                @foreach ($featuredProducts as $product)
                    @include('partials.product-card', ['product' => $product])
                @endforeach
            </div>
            @if ($featuredProducts->isEmpty())
                <p class="text-sm text-gray-500">No featured products yet.</p>
            @endif
        </div>
    </section>
@endsection
