<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Order;
use App\Models\User;

class OrderPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->role === UserRole::ADMIN || $user->role === UserRole::CUSTOMER;
    }

    public function view(User $user, Order $order): bool
    {
        if ($user->role === UserRole::ADMIN) {
            return true;
        }

        if ($user->role === UserRole::CUSTOMER) {
            return $user->customer_id === $order->customer_id;
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->role === UserRole::ADMIN;
    }

    public function update(User $user, Order $order): bool
    {
        return $user->role === UserRole::ADMIN;
    }

    public function delete(User $user, Order $order): bool
    {
        return $user->role === UserRole::ADMIN;
    }
}