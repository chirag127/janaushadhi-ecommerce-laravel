<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $name = fake()->unique()->words(3, true);
        $mrp = fake()->randomFloat(2, 10, 500);

        return [
            'drug_code' => strtoupper(fake()->bothify('JA-####')),
            'name' => ucwords($name),
            'slug' => Str::slug($name).'-'.fake()->randomNumber(4),
            'description' => fake()->paragraph(),
            'category_id' => null,
            'unit_size' => fake()->randomElement(['10 tablets', '30 ml', '100 g', '1 strip']),
            'mrp' => $mrp,
            'price' => round($mrp * fake()->randomFloat(2, 0.5, 0.99), 2),
            'stock' => fake()->numberBetween(0, 200),
            'image_url' => null,
            'is_active' => true,
            'is_featured' => false,
        ];
    }

    public function inactive(): static
    {
        return $this->state(['is_active' => false]);
    }

    public function featured(): static
    {
        return $this->state(['is_featured' => true]);
    }

    public function outOfStock(): static
    {
        return $this->state(['stock' => 0]);
    }

    public function withCategory(Category $category): static
    {
        return $this->state(['category_id' => $category->id]);
    }
}
