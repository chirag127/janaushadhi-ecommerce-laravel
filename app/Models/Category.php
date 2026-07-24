<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Maps to the SHARED public.categories table (60 rows). Read-only catalog for
 * the storefront; admin may edit name/description/sort_order. Not recreated.
 *
 * Columns: id(uuid) name slug description image_url sort_order created_at
 * (NOTE: no updated_at column in the shared schema).
 */
class Category extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'categories';

    /** Shared catalog lives on pgsql (Neon) in prod; sqlite for offline dev. */
    public function getConnectionName(): ?string
    {
        return config('database.shared', 'pgsql');
    }

    // Shared table has created_at but NO updated_at -> disable Eloquent timestamps.
    public $timestamps = false;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'image_url',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
            'created_at' => 'datetime',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'category_id');
    }

    public function activeProducts(): HasMany
    {
        return $this->products()->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    public function imageOrPlaceholder(): string
    {
        return $this->image_url ?: asset('images/placeholder-product.svg');
    }
}
