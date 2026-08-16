<?php

namespace App\Enums;

enum ShipmentStatus: string
{
    case DRAFT = 'DRAFT';
    case READY = 'READY';
    case IN_TRANSIT = 'IN_TRANSIT';
    case ARRIVED = 'ARRIVED';
    case DELIVERED = 'DELIVERED';
    case DELAYED = 'DELAYED';
    case CANCELLED = 'CANCELLED';

    public function label(): string
    {
        return match($this) {
            self::DRAFT => 'Draf',
            self::READY => 'Siap Dikirim',
            self::IN_TRANSIT => 'Dalam Perjalanan',
            self::ARRIVED => 'Tiba di Tujuan',
            self::DELIVERED => 'Terkirim',
            self::DELAYED => 'Tertunda',
            self::CANCELLED => 'Dibatalkan',
        };
    }
    
    public function color(): string
    {
        return match($this) {
            self::DRAFT => 'gray',
            self::READY => 'warning',  
            self::IN_TRANSIT => 'info', 
            self::ARRIVED => 'success', 
            self::DELIVERED => 'success',
            self::DELAYED => 'primary', 
            self::CANCELLED => 'gray',
        };
    }
}