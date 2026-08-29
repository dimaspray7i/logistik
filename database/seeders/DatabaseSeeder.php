<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * Role-only architecture: Roles are managed via App\Enums\UserRole (ADMIN, CUSTOMER).
     * User accounts and business master data are created manually through the application.
     */
    public function run(): void
    {
        // No dummy data or accounts are seeded automatically.
    }
}