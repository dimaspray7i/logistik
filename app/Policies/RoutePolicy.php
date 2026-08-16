<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Route;
use App\Models\User;

class RoutePolicy
{
    public function view(User $user, ?Route $route = null): bool
    {
        return $user->role === UserRole::ADMIN;
    }

    public function create(User $user): bool
    {
        return $user->role === UserRole::ADMIN;
    }

    public function update(User $user, ?Route $route = null): bool
    {
        return $user->role === UserRole::ADMIN;
    }

    public function delete(User $user, ?Route $route = null): bool
    {
        return $user->role === UserRole::ADMIN;
    }
}