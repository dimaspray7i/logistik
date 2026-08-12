<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RoleAndCustomerSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat/Pastikan PT Demo Customer ada dulu
        $demoCustomer = Customer::updateOrCreate(
            ['company_name' => 'PT Backburner'],
            [
                'name' => 'Budi Santoso',
                'phone' => '081234567890',
                'email' => 'budi@gmail.com',
                'address' => 'Jl. Merdeka No. 1',
                'city' => 'Medan',
                'province' => 'Sumatera Utara',
                'postal_code' => '20111',
                'notes' => 'Customer utama untuk testing portal customer.',
            ]
        );

        // 2. Buat Admin User
        User::updateOrCreate(
            ['email' => 'adminlogistik1@gmail.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('Bakwanla'),
                'role' => UserRole::ADMIN,
                'customer_id' => null,
            ]
        );

        // 3. Buat Customer User 
        User::updateOrCreate(
            ['email' => 'budi@gmail.com'],
            [
                'name' => 'Budi Santoso',
                'password' => Hash::make('budihaha'),
                'role' => UserRole::CUSTOMER,
                'customer_id' => $demoCustomer->id, 
            ]
        );
    }
}