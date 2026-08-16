<?php

namespace App\Enums;

enum VehicleStatus: string
{
    case AVAILABLE = 'AVAILABLE';
    case IN_USE = 'IN_USE';
    case MAINTENANCE = 'MAINTENANCE';

    public function label(): string
    {
        return match($this) {
            self::AVAILABLE => 'Tersedia',
            self::IN_USE => 'Sedang Digunakan',
            self::MAINTENANCE => 'Perawatan',
        };
    }
}