<?php

namespace Database\Factories;

use App\Enums\ShipmentStatus;
use App\Models\Customer;
use App\Models\Driver;
use App\Models\Order;
use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Factories\Factory;

class ShipmentFactory extends Factory
{
    public function definition(): array
    {
        $status = fake()->randomElement(ShipmentStatus::cases());
        
        return [
            'shipment_number' => 'SHP-' . date('Ymd') . '-' . fake()->unique()->numberBetween(100, 999),
            'order_id' => Order::factory(),
            'customer_id' => Customer::factory(),
            'vehicle_id' => Vehicle::factory(),
            'driver_id' => Driver::factory(),
            'origin' => fake()->city(),
            'destination' => fake()->city(),
            'departure_date' => $status->value !== 'DRAFT' ? fake()->dateTimeBetween('-2 weeks', 'now') : null,
            'estimated_arrival' => fake()->dateTimeBetween('now', '+2 weeks'),
            'actual_arrival' => in_array($status->value, ['DELIVERED', 'ARRIVED']) ? fake()->dateTimeBetween('-1 week', 'now') : null,
            'total_weight' => fake()->numberBetween(100, 5000),
            'status' => $status->value,
            'notes' => fake()->optional()->sentence(),
        ];
    }
}