<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Document;
use App\Models\User;
use App\Enums\DocumentType;

class DocumentPolicy
{
    protected $casts = [
     'type' => DocumentType::class,
    ];

    public function viewAny(User $user): bool
    {
        return $user->role === UserRole::ADMIN || $user->role === UserRole::CUSTOMER;
    }

    public function view(User $user, Document $document): bool
    {
        if ($user->role === UserRole::ADMIN) {
            return true;
        }

        // Customer hanya boleh lihat dokumen milik pengirimannya sendiri
        if ($user->role === UserRole::CUSTOMER) {
            return $document->shipment && $user->customer_id === $document->shipment->customer_id;
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->role === UserRole::ADMIN;
    }

    public function delete(User $user, Document $document): bool
    {
        return $user->role === UserRole::ADMIN;
    }
}