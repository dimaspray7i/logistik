<?php

namespace Database\Factories;

use App\Enums\VehicleStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

class VehicleFactory extends Factory
{
    public function definition(): array
    {
        return [
            'plate_number' => fake()->unique()->regexify('[A-Z]{2} [0-9]{4} [A-Z]{2}'), // BK 1234 XX
            'vehicle_type' => fake()->randomElement(['Truck', 'Van', 'Pickup', 'Container']),
            'brand' => fake()->randomElement(['Hino', 'Isuzu', 'Mitsubishi', 'Toyota']),
            'capacity' => fake()->numberBetween(1000, 10000),
            'status' => fake()->randomElement(VehicleStatus::cases())->value,
            'notes' => fake()->optional()->sentence(),
        ];
    }
}