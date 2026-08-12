<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class DriverFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'phone' => fake()->phoneNumber(),
            'license_number' => 'SIM-' . fake()->unique()->numberBetween(10000, 99999),
            'status' => fake()->randomElement(['ACTIVE', 'INACTIVE']),
        ];
    }
}