@extends('layouts.app')

@section('title', 'My Wishlist')

@section('content')
<div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">My wishlist</h1>

    @if ($items->isEmpty())
        <div class="mt-6 rounded-xl border border-dashed border-gray-300 bg-white p-12 text-center dark:border-gray-700 dark:bg-gray-900">
            <p class="text-gray-500 dark:text-gray-400">Your wishlist is empty.</p>
            <a href="{{ route('products.index') }}" wire:navigate class="btn-primary mt-4 inline-block">Browse products</a>
        </div>
    @else
        <div class="mt-6 grid grid-cols-2 gap-4 sm:grid-cols-3 xl:grid-cols-4">
            @foreach ($items as $item)
                @include('partials.product-card', ['product' => $item->product])
            @endforeach
        </div>
    @endif
</div>
@endsection
