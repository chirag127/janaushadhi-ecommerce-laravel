@extends('layouts.app')

@section('title', $product->name)
@section('description', Str::limit(strip_tags($product->description ?? ''), 155) ?: $product->name . ' — affordable generic medicine at ' . config('app.name'))

@section('content')
@php $symbol = config('store.currency_symbol'); @endphp
<div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
    <nav class="mb-4 text-sm text-gray-500 dark:text-gray-400" aria-label="Breadcrumb">
        <a href="{{ route('home') }}" wire:navigate class="hover:text-brand-600">Home</a>
        <span class="mx-1">/</span>
        <a href="{{ route('products.index') }}" wire:navigate class="hover:text-brand-600">Products</a>
        @if ($product->category)
            <span class="mx-1">/</span>
            <a href="{{ route('categories.show', $product->category->slug) }}" wire:navigate class="hover:text-brand-600">{{ $product->category->name }}</a>
        @endif
    </nav>

    <div class="grid gap-8 lg:grid-cols-2">
        {{-- Image --}}
        <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white dark:border-gray-700 dark:bg-gray-800">
            <img src="{{ $product->imageOrPlaceholder() }}" alt="{{ $product->name }}" class="aspect-square w-full object-cover" />
        </div>

        {{-- Details --}}
        <div>
            @if ($product->category)
                <a href="{{ route('categories.show', $product->category->slug) }}" wire:navigate class="text-sm font-medium text-brand-600 hover:underline">{{ $product->category->name }}</a>
            @endif
            <h1 class="mt-1 text-2xl font-bold text-gray-900 dark:text-white sm:text-3xl">{{ $product->name }}</h1>

            {{-- Rating --}}
            <div class="mt-2 flex items-center gap-2">
                <div class="flex" aria-label="Rated {{ number_format($avgRating, 1) }} out of 5">
                    @for ($i = 1; $i <= 5; $i++)
                        <svg class="h-4 w-4 {{ $i <= round($avgRating) ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.955a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.368 2.447a1 1 0 00-.364 1.118l1.287 3.956c.3.92-.755 1.688-1.54 1.118l-3.367-2.447a1 1 0 00-1.175 0l-3.368 2.447c-.784.57-1.838-.197-1.539-1.118l1.287-3.956a1 1 0 00-.364-1.118L2.075 9.382c-.783-.57-.38-1.81.588-1.81h4.162a1 1 0 00.95-.69l1.286-3.955z" /></svg>
                    @endfor
                </div>
                <span class="text-sm text-gray-500 dark:text-gray-400">{{ number_format($avgRating, 1) }} ({{ $reviewCount }} {{ Str::plural('review', $reviewCount) }})</span>
            </div>

            {{-- Price --}}
            <div class="mt-4 flex items-baseline gap-3">
                <span class="text-3xl font-bold text-gray-900 dark:text-white">{{ $symbol }}{{ number_format((float) $product->price, 2) }}</span>
                @if ($product->discountPercent() > 0)
                    <span class="text-lg text-gray-400 line-through">{{ $symbol }}{{ number_format((float) $product->mrp, 2) }}</span>
                    <span class="rounded-full bg-accent-500 px-2 py-0.5 text-xs font-bold text-white">{{ $product->discountPercent() }}% OFF</span>
                @endif
            </div>

            <dl class="mt-4 space-y-1 text-sm">
                @if ($product->unit_size)<div class="flex gap-2"><dt class="text-gray-500 dark:text-gray-400">Pack:</dt><dd class="font-medium text-gray-900 dark:text-white">{{ $product->unit_size }}</dd></div>@endif
                @if ($product->drug_code)<div class="flex gap-2"><dt class="text-gray-500 dark:text-gray-400">Drug code:</dt><dd class="font-medium text-gray-900 dark:text-white">{{ $product->drug_code }}</dd></div>@endif
                <div class="flex gap-2"><dt class="text-gray-500 dark:text-gray-400">Availability:</dt>
                    <dd class="font-medium {{ $product->inStock() ? 'text-brand-600' : 'text-red-600' }}">{{ $product->inStock() ? 'In stock (' . $product->stock . ')' : 'Out of stock' }}</dd>
                </div>
            </dl>

            {{-- Actions --}}
            <div class="mt-6 flex flex-wrap items-end gap-3">
                <div class="w-full max-w-[220px]">
                    @livewire('add-to-cart-button', ['product' => $product], key('atc-show-'.$product->id))
                </div>
                @livewire('wishlist-toggle', ['product' => $product], key('wl-'.$product->id))
            </div>

            @if ($product->description)
                <div class="prose prose-sm mt-8 max-w-none text-gray-600 dark:prose-invert dark:text-gray-300">
                    <h2 class="text-base font-semibold text-gray-900 dark:text-white">Description</h2>
                    <p>{!! nl2br(e($product->description)) !!}</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Reviews --}}
    <section class="mt-12 border-t border-gray-100 pt-8 dark:border-gray-800">
        <h2 class="text-xl font-bold text-gray-900 dark:text-white">Customer Reviews</h2>

        @auth
            <form method="POST" action="{{ route('reviews.store', $product->slug) }}" class="mt-4 rounded-xl border border-gray-100 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                @csrf
                <div class="flex items-center gap-2">
                    <label for="rating" class="text-sm font-semibold text-gray-700 dark:text-gray-200">Rating</label>
                    <select id="rating" name="rating" class="form-select w-24 text-sm" required>
                        @for ($i = 5; $i >= 1; $i--)<option value="{{ $i }}">{{ $i }} ★</option>@endfor
                    </select>
                </div>
                <input type="text" name="title" placeholder="Review title (optional)" class="form-input mt-3 text-sm" maxlength="255" />
                <textarea name="body" rows="3" placeholder="Share your experience (optional)" class="form-textarea mt-3 text-sm"></textarea>
                <button type="submit" class="btn-primary mt-3">Submit Review</button>
            </form>
        @else
            <p class="mt-4 text-sm text-gray-500 dark:text-gray-400"><a href="{{ route('login') }}" wire:navigate class="text-brand-600 hover:underline">Log in</a> to write a review.</p>
        @endauth

        <div class="mt-6 space-y-4">
            @forelse ($product->reviews()->with('user')->latest()->get() as $review)
                <div class="rounded-xl border border-gray-100 bg-white p-4 dark:border-gray-700 dark:bg-gray-800">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="flex" aria-label="{{ $review->rating }} stars">
                                @for ($i = 1; $i <= 5; $i++)<svg class="h-3.5 w-3.5 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.955a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.368 2.447a1 1 0 00-.364 1.118l1.287 3.956c.3.92-.755 1.688-1.54 1.118l-3.367-2.447a1 1 0 00-1.175 0l-3.368 2.447c-.784.57-1.838-.197-1.539-1.118l1.287-3.956a1 1 0 00-.364-1.118L2.075 9.382c-.783-.57-.38-1.81.588-1.81h4.162a1 1 0 00.95-.69l1.286-3.955z" /></svg>@endfor
                            </div>
                            <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $review->user->name ?? 'Customer' }}</span>
                        </div>
                        @if (auth()->id() === $review->user_id)
                            <form method="POST" action="{{ route('reviews.destroy', $review) }}">@csrf @method('DELETE')<button type="submit" class="text-xs text-red-500 hover:underline">Delete</button></form>
                        @endif
                    </div>
                    @if ($review->title)<p class="mt-2 text-sm font-semibold text-gray-900 dark:text-white">{{ $review->title }}</p>@endif
                    @if ($review->body)<p class="mt-1 text-sm text-gray-600 dark:text-gray-300">{{ $review->body }}</p>@endif
                    <p class="mt-1 text-xs text-gray-400">{{ $review->created_at?->diffForHumans() }}</p>
                </div>
            @empty
                <p class="text-sm text-gray-500 dark:text-gray-400">No reviews yet. Be the first to review this product.</p>
            @endforelse
        </div>
    </section>

    {{-- Related --}}
    @if ($related->isNotEmpty())
    <section class="mt-12">
        <h2 class="text-xl font-bold text-gray-900 dark:text-white">Related Products</h2>
        <div class="mt-6 grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
            @foreach ($related as $product)
                @include('partials.product-card', ['product' => $product])
            @endforeach
        </div>
    </section>
    @endif
</div>
@endsection
