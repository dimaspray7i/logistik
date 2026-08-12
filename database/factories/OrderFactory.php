<?php

namespace Database\Factories;

use App\Enums\OrderStatus;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    public function definition(): array
    {
        return [
            'order_number' => 'ORD-' . date('Ymd') . '-' . fake()->unique()->numberBetween(100, 999),
            'customer_id' => Customer::factory(), // Otomatis buat customer jika belum ada
            'order_date' => fake()->dateTimeBetween('-1 month', 'now'),
            'status' => fake()->randomElement(OrderStatus::cases())->value,
            'notes' => fake()->optional()->sentence(),
        ];
    }
}