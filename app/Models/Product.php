<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Maps to the SHARED public.products table (2439 rows, owned by the Neon
 * shared janaushadhi project). Laravel READS this catalog; admin product management
 * may also write name/price/stock/is_active. We do NOT recreate this table.
 *
 * Columns: id(uuid) drug_code name slug description category_id(uuid)
 * unit_size mrp price stock image_url is_active is_featured created_at updated_at
 */
class Product extends Model
{
    use HasUuids;

    protected $table = 'products';

    /** Shared catalog lives on pgsql (Neon) in prod; sqlite for offline dev. */
    public function getConnectionName(): ?string
    {
        return config('database.shared', 'pgsql');
    }

    // Shared table manages its own created_at/updated_at; let Eloquent use them.
    public $timestamps = true;

    protected $fillable = [
        'drug_code',
        'name',
        'slug',
        'description',
        'category_id',
        'unit_size',
        'mrp',
        'price',
        'stock',
        'image_url',
        'is_active',
        'is_featured',
    ];

    protected function casts(): array
    {
        return [
            'mrp' => 'decimal:2',
            'price' => 'decimal:2',
            'stock' => 'integer',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'product_id')->where('is_approved', true);
    }

    // ---- Scopes -------------------------------------------------------------

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeInStock($query)
    {
        return $query->where('stock', '>', 0);
    }

    // ---- Helpers ------------------------------------------------------------

    public function inStock(): bool
    {
        return (int) $this->stock > 0;
    }

    public function discountPercent(): int
    {
        if (! $this->mrp || (float) $this->mrp <= 0 || (float) $this->price >= (float) $this->mrp) {
            return 0;
        }

        return (int) round((((float) $this->mrp - (float) $this->price) / (float) $this->mrp) * 100);
    }

    public function imageOrPlaceholder(): string
    {
        return $this->image_url
            ?: 'https://placehold.co/600x600/eefdf3/11924a?text='.urlencode(mb_substr($this->name, 0, 18));
    }
}
