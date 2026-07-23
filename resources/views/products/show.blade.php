@extends('layouts.app')

@section('title', $product->name)
@section('description', Str::limit(strip_tags($product->description ?? $product->name), 155))

@section('content')
<div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
    <nav class="mb-6 text-sm text-gray-500" aria-label="Breadcrumb">
        <a href="{{ route('home') }}" wire:navigate class="hover:text-brand-700">Home</a>
        <span class="mx-1">/</span>
        <a href="{{ route('products.index') }}" wire:navigate class="hover:text-brand-700">Products</a>
        @if ($product->category)
            <span class="mx-1">/</span>
            <a href="{{ route('categories.show', $product->category->slug) }}" wire:navigate class="hover:text-brand-700">{{ $product->category->name }}</a>
        @endif
    </nav>

    <div class="grid grid-cols-1 gap-10 lg:grid-cols-2">
        {{-- Image --}}
        <div class="relative">
            <img src="{{ $product->imageOrPlaceholder() }}" alt="{{ $product->name }}" class="w-full rounded-2xl border border-gray-100 object-cover shadow-sm dark:border-gray-700" />
            @if ($product->discountPercent() > 0)
                <span class="absolute left-4 top-4 rounded-lg bg-accent-500 px-2.5 py-1 text-sm font-bold text-white">{{ $product->discountPercent() }}% OFF</span>
            @endif
        </div>

        {{-- Details --}}
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $product->name }}</h1>

            <div class="mt-2 flex items-center gap-3">
                <div class="flex items-center" aria-label="Rating {{ number_format($avgRating, 1) }} out of 5">
                    @for ($i = 1; $i <= 5; $i++)
                        <svg class="h-4 w-4 {{ $i <= round($avgRating) ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.958a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.368 2.446a1 1 0 00-.364 1.118l1.287 3.957c.3.922-.755 1.688-1.54 1.118l-3.367-2.446a1 1 0 00-1.175 0l-3.368 2.446c-.784.57-1.838-.196-1.539-1.118l1.286-3.957a1 1 0 00-.363-1.118L2.98 9.385c-.783-.57-.38-1.81.588-1.81h4.162a1 1 0 00.95-.69l1.287-3.958z" /></svg>
                    @endfor
                </div>
                <span class="text-sm text-gray-500">{{ number_format($avgRating, 1) }} ({{ $reviewCount }} {{ Str::plural('review', $reviewCount) }})</span>
                @if ($product->drug_code)
                    <span class="text-xs text-gray-400">Code: {{ $product->drug_code }}</span>
                @endif
            </div>

            <div class="mt-4 flex items-baseline gap-3">
                <span class="text-3xl font-bold text-brand-700">₹{{ number_format((float) $product->price, 2) }}</span>
                @if ($product->discountPercent() > 0)
                    <span class="text-lg text-gray-400 line-through">₹{{ number_format((float) $product->mrp, 2) }}</span>
                    <span class="text-sm font-semibold text-accent-600">Save ₹{{ number_format((float) $product->mrp - (float) $product->price, 2) }}</span>
                @endif
            </div>

            <div class="mt-2">
                @if ($product->inStock())
                    <span class="badge bg-brand-100 text-brand-800">In stock ({{ $product->stock }} available)</span>
                @else
                    <span class="badge bg-red-100 text-red-800">Out of stock</span>
                @endif
                @if ($product->unit_size)
                    <span class="ml-2 text-sm text-gray-500">Pack: {{ $product->unit_size }}</span>
                @endif
            </div>

            <div class="mt-6 flex items-center gap-3">
                <div class="flex-1">
                    @livewire('add-to-cart-button', ['product' => $product], key('show-atc-'.$product->id))
                </div>
                <div class="relative">
                    @livewire('wishlist-toggle', ['product' => $product], key('show-wl-'.$product->id))
                </div>
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
    <section class="mt-14">
        <h2 class="text-xl font-bold text-gray-900 dark:text-white">Customer Reviews</h2>

        @auth
            <form method="POST" action="{{ route('reviews.store', $product->slug) }}" class="mt-4 rounded-xl border border-gray-100 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                @csrf
                <div>
                    <label for="rating" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Your rating</label>
                    <select id="rating" name="rating" required class="form-select mt-1 w-32 text-sm">
                        @for ($i = 5; $i >= 1; $i--)
                            <option value="{{ $i }}">{{ $i }} star{{ $i > 1 ? 's' : '' }}</option>
                        @endfor
                    </select>
                    @error('rating')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div class="mt-3">
                    <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Title (optional)</label>
                    <input id="title" type="text" name="title" maxlength="120" value="{{ old('title') }}" class="form-input mt-1 text-sm" />
                </div>
                <div class="mt-3">
                    <label for="body" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Review (optional)</label>
                    <textarea id="body" name="body" rows="3" maxlength="2000" class="form-textarea mt-1 text-sm">{{ old('body') }}</textarea>
                </div>
                <button type="submit" class="btn-primary mt-4 text-sm">Submit Review</button>
            </form>
        @else
            <p class="mt-4 text-sm text-gray-500"><a href="{{ route('login') }}" class="font-medium text-brand-700 hover:underline">Log in</a> to write a review.</p>
        @endauth

        <div class="mt-6 space-y-4">
            @forelse ($product->reviews()->with('user')->latest()->get() as $review)
                <div class="rounded-xl border border-gray-100 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="flex h-8 w-8 items-center justify-center rounded-full bg-brand-100 text-xs font-bold text-brand-700">{{ strtoupper(substr($review->user->name ?? 'U', 0, 1)) }}</span>
                            <span class="text-sm font-medium text-gray-800 dark:text-gray-100">{{ $review->user->name ?? 'Anonymous' }}</span>
                        </div>
                        <div class="flex" aria-label="{{ $review->rating }} out of 5">
                            @for ($i = 1; $i <= 5; $i++)
                                <svg class="h-4 w-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.958a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.368 2.446a1 1 0 00-.364 1.118l1.287 3.957c.3.922-.755 1.688-1.54 1.118l-3.367-2.446a1 1 0 00-1.175 0l-3.368 2.446c-.784.57-1.838-.196-1.539-1.118l1.286-3.957a1 1 0 00-.363-1.118L2.98 9.385c-.783-.57-.38-1.81.588-1.81h4.162a1 1 0 00.95-.69l1.287-3.958z" /></svg>
                            @endfor
                        </div>
                    </div>
                    @if ($review->title)
                        <p class="mt-2 text-sm font-semibold text-gray-800 dark:text-gray-100">{{ $review->title }}</p>
                    @endif
                    @if ($review->body)
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">{{ $review->body }}</p>
                    @endif
                    @auth
                        @if ($review->user_id === auth()->id())
                            <form method="POST" action="{{ route('reviews.destroy', $review->id) }}" class="mt-2">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-xs text-red-600 hover:underline">Delete</button>
                            </form>
                        @endif
                    @endauth
                </div>
            @empty
                <p class="text-sm text-gray-500">No reviews yet. Be the first to review this product.</p>
            @endforelse
        </div>
    </section>

    {{-- Related --}}
    @if ($related->isNotEmpty())
        <section class="mt-14">
            <h2 class="mb-6 text-xl font-bold text-gray-900 dark:text-white">Related Products</h2>
            <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
                @foreach ($related as $product)
                    @include('partials.product-card', ['product' => $product])
                @endforeach
            </div>
        </section>
    @endif
</div>
@endsection
