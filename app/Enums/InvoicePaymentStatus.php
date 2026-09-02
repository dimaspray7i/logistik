<?php

namespace App\Enums;

enum InvoicePaymentStatus: string
{
    case UNPAID = 'Belum Dibayar';
    case PAID = 'Sudah Dibayar';

    public function label(): string
    {
        return match($this) {
            self::UNPAID => 'Belum Dibayar',
            self::PAID => 'Sudah Dibayar',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::UNPAID => 'warning',
            self::PAID => 'success',
        };
    }
}
