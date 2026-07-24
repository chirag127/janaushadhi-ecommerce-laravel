<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Tests\TestCase;

class CartTest extends TestCase
{

    private function makeProduct(array $attrs = []): Product
    {
        $cat = Category::factory()->create();

        return Product::factory()->withCategory($cat)->create(array_merge(['stock' => 50], $attrs));
    }

    public function test_cart_requires_auth(): void
    {
        $this->get(route('cart.index'))->assertRedirect(route('login'));
    }

    public function test_cart_page_loads_for_auth_user(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user)->get(route('cart.index'))->assertOk();
    }

    public function test_add_to_cart_stores_item(): void
    {
        $user = User::factory()->create();
        $product = $this->makeProduct();

        $this->actingAs($user)
            ->post(route('cart.store'), ['product_id' => $product->id, 'quantity' => 2])
            ->assertRedirect();

        $this->assertDatabaseHas('laravel_cart_items', [
            'user_id' => $user->id,
            'product_id' => $product->id,
            'quantity' => 2,
        ]);
    }

    public function test_add_to_cart_rejects_invalid_product(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('cart.store'), ['product_id' => 'not-a-uuid', 'quantity' => 1])
            ->assertSessionHasErrors('product_id');
    }

    public function test_add_inactive_product_to_cart_returns_404(): void
    {
        $user = User::factory()->create();
        $product = $this->makeProduct(['is_active' => false]);

        $this->actingAs($user)
            ->post(route('cart.store'), ['product_id' => $product->id, 'quantity' => 1])
            ->assertNotFound();
    }

    public function test_update_cart_item_quantity(): void
    {
        $user = User::factory()->create();
        $product = $this->makeProduct();

        $this->actingAs($user)->post(route('cart.store'), ['product_id' => $product->id, 'quantity' => 1]);
        $item = $user->cartItems()->first();

        $this->actingAs($user)
            ->patch(route('cart.update', $item), ['quantity' => 3])
            ->assertRedirect();

        $this->assertDatabaseHas('laravel_cart_items', ['id' => $item->id, 'quantity' => 3]);
    }

    public function test_remove_cart_item(): void
    {
        $user = User::factory()->create();
        $product = $this->makeProduct();

        $this->actingAs($user)->post(route('cart.store'), ['product_id' => $product->id, 'quantity' => 1]);
        $item = $user->cartItems()->first();

        $this->actingAs($user)
            ->delete(route('cart.destroy', $item))
            ->assertRedirect();

        $this->assertDatabaseMissing('laravel_cart_items', ['id' => $item->id]);
    }

    public function test_cannot_update_another_users_cart_item(): void
    {
        $owner = User::factory()->create();
        $attacker = User::factory()->create();
        $product = $this->makeProduct();

        $this->actingAs($owner)->post(route('cart.store'), ['product_id' => $product->id, 'quantity' => 1]);
        $item = $owner->cartItems()->first();

        $this->actingAs($attacker)
            ->patch(route('cart.update', $item), ['quantity' => 5])
            ->assertForbidden();
    }
}
