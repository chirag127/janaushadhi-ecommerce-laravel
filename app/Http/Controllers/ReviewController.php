<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReviewRequest;
use App\Models\Product;
use App\Models\Review;
use App\Repositories\Contracts\ReviewRepositoryInterface;
use Illuminate\Http\RedirectResponse;

class ReviewController extends Controller
{
    public function __construct(
        protected ReviewRepositoryInterface $reviews,
    ) {}

    public function store(StoreReviewRequest $request, Product $product): RedirectResponse
    {
        $this->authorize('create', Review::class);

        $this->reviews->create([
            'user_id' => auth()->id(),
            'product_id' => $product->id,
            'rating' => $request->validated()['rating'],
            'title' => $request->validated()['title'] ?? null,
            'body' => $request->validated()['body'] ?? null,
        ]);

        return redirect()->back()->with('success', 'Review submitted successfully.');
    }

    public function destroy(Review $review): RedirectResponse
    {
        $this->authorize('delete', $review);

        $this->reviews->delete($review);

        return redirect()->back()->with('success', 'Review deleted.');
    }
}
