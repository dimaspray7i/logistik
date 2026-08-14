<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Driver;
use App\Models\User;

class DriverPolicy 
{
    public function viewAny(User $user): bool
    {
        return $user->role === UserRole::ADMIN;
    }

    public function view(User $user, Driver $driver): bool
    {
        return $user->role === UserRole::ADMIN;
    }

    public function create(User $user): bool
    {
        return $user->role === UserRole::ADMIN;
    }

    public function update(User $user, Driver $driver): bool
    {
        return $user->role === UserRole::ADMIN;
    }

    public function delete(User $user, Driver $driver): bool
    {
        return $user->role === UserRole::ADMIN;
    }
}