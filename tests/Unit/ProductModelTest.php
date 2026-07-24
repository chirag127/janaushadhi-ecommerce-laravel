<?php

namespace Tests\Unit;

use App\Models\Product;
use PHPUnit\Framework\TestCase;

class ProductModelTest extends TestCase
{
    private function makeProduct(array $attrs = []): Product
    {
        $p = new Product;
        foreach (array_merge([
            'name' => 'Test Medicine',
            'slug' => 'test-medicine',
            'mrp' => '100.00',
            'price' => '80.00',
            'stock' => 10,
            'is_active' => true,
            'image_url' => null,
        ], $attrs) as $k => $v) {
            $p->$k = $v;
        }

        return $p;
    }

    public function test_discount_percent_calculated_correctly(): void
    {
        $p = $this->makeProduct(['mrp' => '100.00', 'price' => '75.00']);
        $this->assertSame(25, $p->discountPercent());
    }

    public function test_discount_percent_zero_when_price_equals_mrp(): void
    {
        $p = $this->makeProduct(['mrp' => '100.00', 'price' => '100.00']);
        $this->assertSame(0, $p->discountPercent());
    }

    public function test_discount_percent_zero_when_mrp_is_zero(): void
    {
        $p = $this->makeProduct(['mrp' => '0.00', 'price' => '0.00']);
        $this->assertSame(0, $p->discountPercent());
    }

    public function test_in_stock_true_when_stock_positive(): void
    {
        $this->assertTrue($this->makeProduct(['stock' => 5])->inStock());
    }

    public function test_in_stock_false_when_stock_zero(): void
    {
        $this->assertFalse($this->makeProduct(['stock' => 0])->inStock());
    }

    public function test_image_or_placeholder_returns_url_when_set(): void
    {
        $p = $this->makeProduct(['image_url' => 'https://cdn.example.com/drug.jpg']);
        $this->assertSame('https://cdn.example.com/drug.jpg', $p->imageOrPlaceholder());
    }

    public function test_image_or_placeholder_returns_placeholder_when_null(): void
    {
        $p = $this->makeProduct(['image_url' => null]);
        $result = $p->imageOrPlaceholder();
        // Must contain our committed SVG path, not an external placehold.co URL.
        $this->assertStringContainsString('placeholder-product.svg', $result);
    }
}
