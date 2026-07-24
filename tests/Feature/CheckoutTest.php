<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Tests\TestCase;

class CheckoutTest extends TestCase
{

    private function makeProductInCart(User $user, array $productAttrs = []): Product
    {
        $cat = Category::factory()->create();
        $product = Product::factory()->withCategory($cat)->create(array_merge([
            'price' => '100.00',
            'mrp' => '120.00',
            'stock' => 50,
        ], $productAttrs));

        $this->actingAs($user)
            ->post(route('cart.store'), ['product_id' => $product->id, 'quantity' => 2]);

        return $product;
    }

    private function shippingPayload(array $override = []): array
    {
        return array_merge([
            'payment_method' => 'cod',
            'full_name' => 'Test User',
            'phone' => '9876543210',
            'line1' => '123 Main Street',
            'city' => 'Mumbai',
            'state' => 'Maharashtra',
            'pincode' => '400001',
        ], $override);
    }

    public function test_checkout_requires_auth(): void
    {
        $this->get(route('checkout.index'))->assertRedirect(route('login'));
    }

    public function test_checkout_redirects_when_cart_empty(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('checkout.index'))
            ->assertRedirect(route('cart.index'));
    }

    public function test_checkout_page_loads_with_items_in_cart(): void
    {
        $user = User::factory()->create();
        $this->makeProductInCart($user);

        $this->actingAs($user)
            ->get(route('checkout.index'))
            ->assertOk();
    }

    public function test_place_order_cod_creates_order(): void
    {
        $user = User::factory()->create();
        $product = $this->makeProductInCart($user);

        $this->actingAs($user)
            ->post(route('checkout.store'), $this->shippingPayload())
            ->assertRedirect();

        $this->assertDatabaseHas('laravel_orders', [
            'user_id' => $user->id,
            'payment_method' => 'cod',
            'status' => 'confirmed',
        ]);

        // Cart must be cleared.
        $this->assertDatabaseMissing('laravel_cart_items', ['user_id' => $user->id]);
    }

    public function test_place_order_decrements_stock(): void
    {
        $user = User::factory()->create();
        $product = $this->makeProductInCart($user, ['stock' => 10]);

        $this->actingAs($user)->post(route('checkout.store'), $this->shippingPayload());

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'stock' => 8, // 10 - 2 (quantity in cart)
        ]);
    }

    public function test_checkout_validation_requires_payment_method(): void
    {
        $user = User::factory()->create();
        $this->makeProductInCart($user);

        $this->actingAs($user)
            ->post(route('checkout.store'), ['full_name' => 'Test User'])
            ->assertSessionHasErrors('payment_method');
    }

    public function test_checkout_validation_requires_address_fields_when_no_address_id(): void
    {
        $user = User::factory()->create();
        $this->makeProductInCart($user);

        $this->actingAs($user)
            ->post(route('checkout.store'), ['payment_method' => 'cod'])
            ->assertSessionHasErrors(['full_name', 'phone', 'line1', 'city', 'state', 'pincode']);
    }

    public function test_order_confirmation_accessible_to_order_owner(): void
    {
        $user = User::factory()->create();
        $this->makeProductInCart($user);

        $response = $this->actingAs($user)
            ->post(route('checkout.store'), $this->shippingPayload())
            ->assertRedirect();

        $order = $user->orders()->first();
        $this->actingAs($user)
            ->get(route('checkout.confirmation', $order->order_number))
            ->assertOk();
    }

    public function test_order_confirmation_forbidden_to_other_user(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $this->makeProductInCart($owner);

        $this->actingAs($owner)->post(route('checkout.store'), $this->shippingPayload());
        $order = $owner->orders()->first();

        $this->actingAs($other)
            ->get(route('checkout.confirmation', $order->order_number))
            ->assertForbidden();
    }

    public function test_out_of_stock_product_blocks_checkout(): void
    {
        $user = User::factory()->create();
        $product = $this->makeProductInCart($user, ['stock' => 1]);

        // Drain stock after adding to cart.
        $product->update(['stock' => 0]);

        $this->actingAs($user)
            ->post(route('checkout.store'), $this->shippingPayload())
            ->assertRedirect()
            ->assertSessionHas('error');
    }
}
