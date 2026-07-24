<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Tests\TestCase;

class ProductListingTest extends TestCase
{

    public function test_product_index_returns_200(): void
    {
        $this->get(route('products.index'))->assertOk();
    }

    public function test_product_index_lists_active_products(): void
    {
        $cat = Category::factory()->create();
        Product::factory()->withCategory($cat)->count(3)->create();
        Product::factory()->withCategory($cat)->inactive()->create();

        $response = $this->get(route('products.index'));

        $response->assertOk();
        // Page must contain the 3 active products, not the inactive one.
        $response->assertViewHas('products', fn ($paginator) => $paginator->total() === 3);
    }

    public function test_product_index_with_sort_param_returns_ok(): void
    {
        // Verifies sort param doesn't 500.
        $this->get(route('products.index', ['sort' => 'price_asc']))->assertOk();
    }

    public function test_product_detail_returns_200(): void
    {
        $cat = Category::factory()->create();
        $product = Product::factory()->withCategory($cat)->create();

        $this->get(route('products.show', $product->slug))->assertOk();
    }

    public function test_product_detail_404_for_unknown_slug(): void
    {
        $this->get(route('products.show', 'does-not-exist'))->assertNotFound();
    }

    public function test_product_detail_shows_placeholder_when_image_null(): void
    {
        $cat = Category::factory()->create();
        $product = Product::factory()->withCategory($cat)->create(['image_url' => null]);

        $this->get(route('products.show', $product->slug))
            ->assertOk()
            ->assertSee('placeholder-product.svg');
    }

    public function test_product_detail_shows_real_image_url(): void
    {
        $cat = Category::factory()->create();
        $product = Product::factory()->withCategory($cat)->create([
            'image_url' => 'https://cdn.example.com/drug.jpg',
        ]);

        $this->get(route('products.show', $product->slug))
            ->assertOk()
            ->assertSee('https://cdn.example.com/drug.jpg', false);
    }
}
