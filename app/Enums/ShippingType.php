<?php

namespace App\Enums;

enum ShippingType: string
{
    case INTERNAL = 'INTERNAL';
    case EXTERNAL = 'EXTERNAL';

    public function label(): string
    {
        return match ($this) {
            self::INTERNAL => 'Armada Perusahaan',
            self::EXTERNAL => 'Ekspedisi Eksternal',
        };
    }
}
