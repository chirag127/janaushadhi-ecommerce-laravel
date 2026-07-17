<?php

namespace App\Repositories\Contracts;

use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface ProductRepositoryInterface
{
    /** Paginated, filtered catalog query. Filters: search, category (slug),
     *  min_price, max_price, in_stock (bool), sort (relevance|price_asc|price_desc|name|newest). */
    public function paginateFiltered(array $filters, int $perPage): LengthAwarePaginator;

    public function findBySlug(string $slug): ?Product;

    public function findById(string $id): ?Product;

    /** @param array<int,string> $ids */
    public function findManyById(array $ids): Collection;

    public function featured(int $limit = 8): Collection;

    public function related(Product $product, int $limit = 4): Collection;

    public function searchSuggestions(string $term, int $limit = 6): Collection;

    // Admin write operations against the shared catalog.
    public function create(array $data): Product;

    public function update(Product $product, array $data): Product;

    public function delete(Product $product): void;

    public function decrementStock(string $productId, int $qty): void;

    public function adminPaginate(array $filters, int $perPage): LengthAwarePaginator;

    public function lowStock(int $threshold = 10, int $limit = 50): Collection;
}
