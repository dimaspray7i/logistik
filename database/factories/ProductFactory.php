<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'sku' => 'SKU-' . fake()->unique()->numberBetween(1000, 9999),
            'name' => fake()->words(2, true), // contoh: "Kopi Arabica"
            'description' => fake()->sentence(),
            'unit' => fake()->randomElement(['Kg', 'Pcs', 'Box', 'Liter']),
        ];
    }
}