<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContactFactory extends Factory
{
    public function definition(): array
    {
        return [
            'customer_id' => Customer::factory(),
            'name' => fake()->name(),
            'position' => fake()->randomElement([
                'Manager Logistik', 'Staff Gudang', 'Purchasing', 'Admin', 'Finance', 'Direktur',
            ]),
            'phone' => fake()->phoneNumber(),
            'email' => fake()->unique()->safeEmail(),
            'is_primary' => false,
        ];
    }
}