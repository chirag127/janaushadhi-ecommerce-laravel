<?php

namespace App\Repositories\Eloquent;

use App\Models\Product;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class ProductRepository implements ProductRepositoryInterface
{
    public function paginateFiltered(array $filters, int $perPage): LengthAwarePaginator
    {
        $query = Product::query()->active()->with('category');

        $this->applyFilters($query, $filters);
        $this->applySort($query, $filters['sort'] ?? null);

        return $query->paginate($perPage)->withQueryString();
    }

    public function adminPaginate(array $filters, int $perPage): LengthAwarePaginator
    {
        $query = Product::query()->with('category');

        if (! empty($filters['search'])) {
            $term = trim($filters['search']);
            $query->where(function ($q) use ($term) {
                $q->where('name', 'ilike', "%{$term}%")
                    ->orWhere('drug_code', 'ilike', "%{$term}%");
            });
        }
        if (! empty($filters['category'])) {
            $query->where('category_id', $filters['category']);
        }
        if (isset($filters['is_active']) && $filters['is_active'] !== '') {
            $query->where('is_active', (bool) $filters['is_active']);
        }

        return $query->orderByDesc('updated_at')->paginate($perPage)->withQueryString();
    }

    protected function applyFilters($query, array $filters): void
    {
        if (! empty($filters['search'])) {
            $term = trim($filters['search']);
            $query->where(function ($q) use ($term) {
                $q->where('name', 'ilike', "%{$term}%")
                    ->orWhere('description', 'ilike', "%{$term}%")
                    ->orWhere('drug_code', 'ilike', "%{$term}%");
            });
        }

        if (! empty($filters['category'])) {
            $slug = $filters['category'];
            $query->whereHas('category', fn ($q) => $q->where('slug', $slug));
        }

        if (! empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        if (isset($filters['min_price']) && $filters['min_price'] !== '' && $filters['min_price'] !== null) {
            $query->where('price', '>=', (float) $filters['min_price']);
        }
        if (isset($filters['max_price']) && $filters['max_price'] !== '' && $filters['max_price'] !== null) {
            $query->where('price', '<=', (float) $filters['max_price']);
        }

        if (! empty($filters['in_stock'])) {
            $query->where('stock', '>', 0);
        }

        if (! empty($filters['exclude_on_request'])) {
            $query->where('price', '>', 0);
        }

        if (! empty($filters['featured'])) {
            $query->where('is_featured', true);
        }
    }

    protected function applySort($query, ?string $sort): void
    {
        match ($sort) {
            'price_asc' => $query->orderBy('price'),
            'price_desc' => $query->orderByDesc('price'),
            'name' => $query->orderBy('name'),
            'newest' => $query->orderByDesc('created_at'),
            default => $query->orderByDesc('is_featured')->orderBy('name'),
        };
    }

    public function findBySlug(string $slug): ?Product
    {
        return Product::query()->active()->with('category')->where('slug', $slug)->first();
    }

    public function findById(string $id): ?Product
    {
        return Product::query()->find($id);
    }

    public function findManyById(array $ids): Collection
    {
        if (empty($ids)) {
            return collect();
        }

        return Product::query()->whereIn('id', $ids)->get();
    }

    public function featured(int $limit = 8): Collection
    {
        return Product::query()->active()->featured()->inStock()
            ->with('category')->limit($limit)->get();
    }

    public function related(Product $product, int $limit = 4): Collection
    {
        return Product::query()->active()->inStock()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->limit($limit)->get();
    }

    public function searchSuggestions(string $term, int $limit = 6): Collection
    {
        $term = trim($term);
        if ($term === '') {
            return collect();
        }

        return Product::query()->active()
            ->where('name', 'ilike', "%{$term}%")
            ->orderBy('name')
            ->limit($limit)
            ->get(['id', 'name', 'slug', 'price', 'image_url']);
    }

    public function create(array $data): Product
    {
        return Product::create($data);
    }

    public function update(Product $product, array $data): Product
    {
        $product->update($data);

        return $product->refresh();
    }

    public function delete(Product $product): void
    {
        // Soft-disable rather than destroy shared-catalog rows.
        $product->update(['is_active' => false]);
    }

    public function decrementStock(string $productId, int $qty): void
    {
        Product::where('id', $productId)->where('stock', '>=', $qty)->decrement('stock', $qty);
    }

    public function lowStock(int $threshold = 10, int $limit = 50): Collection
    {
        return Product::query()->active()
            ->where('stock', '<=', $threshold)
            ->orderBy('stock')
            ->limit($limit)
            ->get(['id', 'name', 'slug', 'stock', 'price']);
    }
}
