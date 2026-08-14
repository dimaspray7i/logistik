<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Customer;
use App\Models\User;

class CustomerPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->role === UserRole::ADMIN;
    }

    public function view(User $user, Customer $customer): bool
    {
        return $user->role === UserRole::ADMIN;
    }

    public function create(User $user): bool
    {
        return $user->role === UserRole::ADMIN;
    }

    public function update(User $user, Customer $customer): bool
    {
        return $user->role === UserRole::ADMIN;
    }

    public function delete(User $user, Customer $customer): bool
    {
        // Jangan hapus jika customer punya user account atau order/shipment
        if ($customer->user || $customer->orders()->count() > 0 || $customer->shipments()->count() > 0) {
            return false;
        }
        return $user->role === UserRole::ADMIN;
    }
}