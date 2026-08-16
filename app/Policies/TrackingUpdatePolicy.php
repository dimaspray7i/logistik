<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\TrackingUpdate;
use App\Models\User;

class TrackingUpdatePolicy
{
    // Admin & Customer boleh lihat list (customer dibatasi scope di controller/view)
    public function viewAny(User $user): bool
    {
        return $user->role === UserRole::ADMIN || $user->role === UserRole::CUSTOMER;
    }

    public function view(User $user, TrackingUpdate $trackingUpdate): bool
    {
        if ($user->role === UserRole::ADMIN) {
            return true;
        }

        if ($user->role === UserRole::CUSTOMER) {
            return $user->customer_id === $trackingUpdate->shipment->customer_id;
        }

        return false;
    }

    // HANYA ADMIN yang boleh membuat/mengubah/menghapus tracking
    public function create(User $user): bool
    {
        return $user->role === UserRole::ADMIN;
    }

    public function update(User $user, TrackingUpdate $trackingUpdate): bool
    {
        return $user->role === UserRole::ADMIN;
    }

    public function delete(User $user, TrackingUpdate $trackingUpdate): bool
    {
        return $user->role === UserRole::ADMIN;
    }
}