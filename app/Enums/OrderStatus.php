<?php

namespace App\Enums;

enum OrderStatus: string
{
    case PENDING = 'PENDING';
    case PROCESSING = 'PROCESSING';
    case COMPLETED = 'COMPLETED';
    case CANCELLED = 'CANCELLED';

    public function label(): string
    {
        return match($this) {
            self::PENDING => 'Menunggu',
            self::PROCESSING => 'Diproses',
            self::COMPLETED => 'Selesai',
            self::CANCELLED => 'Dibatalkan',
        };
    }
}