<?php

namespace App\Repositories\Eloquent;

use App\Models\Review;
use App\Repositories\Contracts\ReviewRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ReviewRepository implements ReviewRepositoryInterface
{
    public function paginateForProduct(string $productId, int $perPage = 10): LengthAwarePaginator
    {
        return Review::approved()->with('user')
            ->where('product_id', $productId)
            ->orderByDesc('created_at')
            ->paginate($perPage);
    }

    public function averageForProduct(string $productId): float
    {
        return (float) round(
            (float) Review::approved()->where('product_id', $productId)->avg('rating'),
            1
        );
    }

    public function countForProduct(string $productId): int
    {
        return Review::approved()->where('product_id', $productId)->count();
    }

    public function userReviewForProduct(string $userId, string $productId): ?Review
    {
        return Review::where('user_id', $userId)->where('product_id', $productId)->first();
    }

    public function create(array $data): Review
    {
        return Review::create($data);
    }

    public function delete(Review $review): void
    {
        $review->delete();
    }
}
