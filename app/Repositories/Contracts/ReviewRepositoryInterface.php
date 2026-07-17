<?php

namespace App\Repositories\Contracts;

use App\Models\Review;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ReviewRepositoryInterface
{
    public function paginateForProduct(string $productId, int $perPage = 10): LengthAwarePaginator;

    public function averageForProduct(string $productId): float;

    public function countForProduct(string $productId): int;

    public function userReviewForProduct(string $userId, string $productId): ?Review;

    public function create(array $data): Review;

    public function delete(Review $review): void;
}
