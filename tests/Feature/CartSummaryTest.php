<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\CartService;
use Tests\TestCase;

/**
 * Pure-logic tests for CartService::summary().
 * Extends feature TestCase so config() helpers are available.
 */
class CartSummaryTest extends TestCase
{

    private function serviceWithSubtotal(float $subtotal): CartService
    {
        $products = $this->createStub(\App\Repositories\Contracts\ProductRepositoryInterface::class);
        $svc = $this->getMockBuilder(CartService::class)
            ->setConstructorArgs([$products])
            ->onlyMethods(['subtotal', 'items'])
            ->getMock();

        $svc->method('subtotal')->willReturn($subtotal);
        $svc->method('items')->willReturn(collect());

        return $svc;
    }

    public function test_free_shipping_above_threshold(): void
    {
        config(['store.free_shipping_threshold' => 500, 'store.shipping_fee' => 40, 'store.tax_rate' => 0]);

        $svc = $this->serviceWithSubtotal(600.00);
        $user = User::factory()->create();
        $summary = $svc->summary($user);

        $this->assertSame(0.0, $summary['shipping']);
        $this->assertSame(600.0, $summary['subtotal']);
    }

    public function test_shipping_fee_applied_below_threshold(): void
    {
        config(['store.free_shipping_threshold' => 500, 'store.shipping_fee' => 40, 'store.tax_rate' => 0]);

        $svc = $this->serviceWithSubtotal(300.00);
        $user = User::factory()->create();
        $summary = $svc->summary($user);

        $this->assertSame(40.0, $summary['shipping']);
    }

    public function test_discount_capped_at_subtotal(): void
    {
        config(['store.free_shipping_threshold' => 500, 'store.shipping_fee' => 40, 'store.tax_rate' => 0]);

        $svc = $this->serviceWithSubtotal(100.00);
        $user = User::factory()->create();
        $summary = $svc->summary($user, discount: 999.00);

        $this->assertSame(100.0, $summary['discount']);
        $this->assertSame(0.0, $summary['total']);
    }
}
