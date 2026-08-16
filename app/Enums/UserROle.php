<?php

namespace App\Enums;

enum UserRole: string
{
    case ADMIN = 'ADMIN';
    case CUSTOMER = 'CUSTOMER';

    public function label(): string
    {
        return match($this) {
            self::ADMIN => 'Administrator',
            self::CUSTOMER => 'Pelanggan',
        };
    }
}