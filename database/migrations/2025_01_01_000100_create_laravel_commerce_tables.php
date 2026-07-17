<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Laravel-local commerce tables. We READ the shared public.products and
 * public.categories catalog (already seeded, 2439 products / 60 categories),
 * but WRITE cart, wishlist, addresses, orders, order items, reviews and
 * coupons into our own laravel_-prefixed tables to avoid RLS/ownership
 * conflicts with the shared multi-app InsForge schema.
 *
 * product_id / category_id reference public.products.id (uuid) but we do NOT
 * add cross-table FK constraints (the shared tables are owned by other apps);
 * integrity is enforced at the application/service layer instead.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laravel_addresses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained('laravel_users')->cascadeOnDelete();
            $table->string('label')->default('Home'); // Home | Work | Other
            $table->string('full_name');
            $table->string('phone');
            $table->string('line1');
            $table->string('line2')->nullable();
            $table->string('city');
            $table->string('state');
            $table->string('pincode', 10);
            $table->string('country')->default('India');
            $table->boolean('is_default')->default(false);
            $table->timestamps();

            $table->index(['user_id', 'is_default']);
        });

        Schema::create('laravel_cart_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained('laravel_users')->cascadeOnDelete();
            $table->uuid('product_id'); // -> public.products.id
            $table->unsignedInteger('quantity')->default(1);
            $table->timestamps();

            $table->unique(['user_id', 'product_id']);
        });

        Schema::create('laravel_wishlist_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained('laravel_users')->cascadeOnDelete();
            $table->uuid('product_id'); // -> public.products.id
            $table->timestamps();

            $table->unique(['user_id', 'product_id']);
        });

        Schema::create('laravel_coupons', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('code')->unique();
            $table->string('type')->default('percent'); // percent | fixed
            $table->decimal('value', 10, 2);
            $table->decimal('min_order_amount', 10, 2)->default(0);
            $table->decimal('max_discount', 10, 2)->nullable();
            $table->unsignedInteger('usage_limit')->nullable();
            $table->unsignedInteger('used_count')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();

            $table->index(['is_active', 'expires_at']);
        });

        Schema::create('laravel_orders', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('order_number')->unique();
            $table->foreignUuid('user_id')->constrained('laravel_users')->cascadeOnDelete();
            $table->foreignUuid('coupon_id')->nullable()->constrained('laravel_coupons')->nullOnDelete();
            $table->string('status')->default('pending'); // pending|confirmed|processing|shipped|delivered|cancelled
            $table->string('payment_status')->default('unpaid'); // unpaid|paid|refunded
            $table->string('payment_method')->default('cod'); // cod|online
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('discount', 12, 2)->default(0);
            $table->decimal('shipping', 12, 2)->default(0);
            $table->decimal('tax', 12, 2)->default(0);
            $table->decimal('total', 12, 2)->default(0);
            $table->json('shipping_address');
            $table->text('notes')->nullable();
            $table->timestamp('placed_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index('status');
        });

        Schema::create('laravel_order_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('order_id')->constrained('laravel_orders')->cascadeOnDelete();
            $table->uuid('product_id'); // -> public.products.id
            $table->string('product_name'); // snapshot at purchase time
            $table->string('product_slug')->nullable();
            $table->decimal('unit_price', 10, 2);
            $table->unsignedInteger('quantity');
            $table->decimal('line_total', 12, 2);
            $table->timestamps();

            $table->index('order_id');
            $table->index('product_id');
        });

        Schema::create('laravel_reviews', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained('laravel_users')->cascadeOnDelete();
            $table->uuid('product_id'); // -> public.products.id
            $table->unsignedTinyInteger('rating'); // 1..5
            $table->string('title')->nullable();
            $table->text('body')->nullable();
            $table->boolean('is_approved')->default(true);
            $table->timestamps();

            $table->unique(['user_id', 'product_id']);
            $table->index(['product_id', 'is_approved']);
        });

        Schema::create('laravel_coupon_redemptions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('coupon_id')->constrained('laravel_coupons')->cascadeOnDelete();
            $table->foreignUuid('user_id')->constrained('laravel_users')->cascadeOnDelete();
            $table->foreignUuid('order_id')->nullable()->constrained('laravel_orders')->nullOnDelete();
            $table->decimal('amount', 10, 2)->default(0);
            $table->timestamps();

            $table->index(['coupon_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laravel_coupon_redemptions');
        Schema::dropIfExists('laravel_reviews');
        Schema::dropIfExists('laravel_order_items');
        Schema::dropIfExists('laravel_orders');
        Schema::dropIfExists('laravel_coupons');
        Schema::dropIfExists('laravel_wishlist_items');
        Schema::dropIfExists('laravel_cart_items');
        Schema::dropIfExists('laravel_addresses');
    }
};
