<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Shipment;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ShipmentPolicy
{
    /**
     * Determine whether the user can view any models.
     * Admin: Bisa lihat semua.
     * Customer: Bisa lihat list (nanti di-query di-scope oleh controller).
     */
    public function viewAny(User $user): bool
    {
        return $user->role === UserRole::ADMIN || $user->role === UserRole::CUSTOMER;
    }

    /**
     * Determine whether the user can view the model.
     * ATURAN KETAT: Customer hanya bisa lihat jika shipment.customer_id == user.customer_id
     */
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

    /**
     * Determine whether the user can create models.
     * Hanya Admin yang bisa membuat Shipment.
     */
    public function create(User $user): bool
    {
        return $user->role === UserRole::ADMIN;
    }

    /**
     * Determine whether the user can update the model.
     * Hanya Admin yang bisa update. Customer READ-ONLY.
     */
    public function update(User $user, Shipment $shipment): bool
    {
        return $user->role === UserRole::ADMIN;
    }

    /**
     * Determine whether the user can delete the model.
     * Hanya Admin yang bisa delete.
     */
    public function delete(User $user, Shipment $shipment): bool
    {
        return $user->role === UserRole::ADMIN;
    }
}