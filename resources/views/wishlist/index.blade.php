@extends('layouts.app')

@section('title', 'Wishlist')

@section('content')
<div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
    <h1 class="mb-6 text-2xl font-bold text-gray-900 dark:text-white">My Wishlist</h1>

    @if ($items->isEmpty())
        <div class="rounded-xl border-2 border-dashed border-gray-200 py-20 text-center dark:border-gray-700">
            <svg class="mx-auto h-14 w-14 text-gray-300" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" /></svg>
            <p class="mt-4 text-lg font-medium text-gray-700 dark:text-gray-200">Your wishlist is empty</p>
            <p class="mt-1 text-sm text-gray-500">Save products you love for later.</p>
            <a href="{{ route('products.index') }}" wire:navigate class="btn-primary mt-4 text-sm">Browse Products</a>
        </div>
    @else
        <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
            @foreach ($items as $item)
                <article class="group relative flex flex-col rounded-xl border border-gray-100 bg-white shadow-sm transition-shadow hover:shadow-md dark:border-gray-700 dark:bg-gray-800">
                    <form method="POST" action="{{ route('wishlist.destroy', $item->product->slug) }}" class="absolute right-2 top-2 z-10">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="flex h-8 w-8 items-center justify-center rounded-full bg-white/90 text-red-500 shadow-sm ring-1 ring-gray-200 hover:bg-white" aria-label="Remove from wishlist">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </form>

                    <a href="{{ route('products.show', $item->product->slug) }}" wire:navigate class="block overflow-hidden rounded-t-xl">
                        <img src="{{ $item->product->imageOrPlaceholder() }}" alt="{{ $item->product->name }}" loading="lazy" class="h-44 w-full object-cover transition-transform duration-300 group-hover:scale-105" />
                    </a>

                    <div class="flex flex-1 flex-col p-3">
                        <a href="{{ route('products.show', $item->product->slug) }}" wire:navigate class="mb-1 line-clamp-2 text-sm font-medium leading-snug text-gray-800 hover:text-brand-700 dark:text-gray-100">{{ $item->product->name }}</a>
                        <div class="mt-auto flex items-baseline gap-1.5">
                            <span class="text-base font-bold text-brand-700">₹{{ number_format((float) $item->product->price, 2) }}</span>
                            @if ($item->product->discountPercent() > 0)
                                <span class="text-xs text-gray-400 line-through">₹{{ number_format((float) $item->product->mrp, 2) }}</span>
                            @endif
                        </div>
                        <div class="mt-3">
                            @livewire('add-to-cart-button', ['product' => $item->product], key('wish-atc-'.$item->product->id))
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    @endif
</div>
@endsection
