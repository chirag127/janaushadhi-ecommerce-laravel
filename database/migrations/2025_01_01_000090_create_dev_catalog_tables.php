<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * DEV-ONLY: create local `categories` + `products` catalog tables for offline
 * SQLite development. In production these live in the shared Neon Postgres
 * (owned by the sibling storefronts) and are NEVER created by Laravel — so this
 * migration is guarded to run ONLY when the shared connection is sqlite.
 */
return new class extends Migration
{
    private function isSqliteShared(): bool
    {
        return config('database.shared') === 'sqlite';
    }

    public function up(): void
    {
        if (! $this->isSqliteShared()) {
            return; // prod pgsql: shared catalog already exists, do nothing
        }

        Schema::create('categories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('image_url')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('products', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('drug_code')->nullable();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->uuid('category_id')->nullable();
            $table->string('unit_size')->nullable();
            $table->decimal('mrp', 10, 2)->default(0);
            $table->decimal('price', 10, 2)->default(0);
            $table->integer('stock')->default(0);
            $table->string('image_url')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
            $table->index('category_id');
            $table->index('is_featured');
        });
    }

    public function down(): void
    {
        if (! $this->isSqliteShared()) {
            return;
        }
        Schema::dropIfExists('products');
        Schema::dropIfExists('categories');
    }
};
