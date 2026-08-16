<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Shipment;
use App\Models\User;

class ShipmentPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->role === UserRole::ADMIN || $user->role === UserRole::CUSTOMER;
    }

    public function view(User $user, Shipment $shipment): bool
    {
        if ($user->role === UserRole::ADMIN) {
            return true;
        }

        if ($user->role === UserRole::CUSTOMER) {
            return $user->customer_id === $shipment->customer_id;
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->role === UserRole::ADMIN;
    }

    public function update(User $user, Shipment $shipment): bool
    {
        return $user->role === UserRole::ADMIN;
    }

    public function delete(User $user, Shipment $shipment): bool
    {
        return $user->role === UserRole::ADMIN;
    }
}