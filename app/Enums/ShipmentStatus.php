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
            self::DRAFT => 'Draft',
            self::READY => 'Ready to Ship',
            self::IN_TRANSIT => 'In Transit',
            self::ARRIVED => 'Arrived',
            self::DELIVERED => 'Delivered',
            self::DELAYED => 'Delayed',
            self::CANCELLED => 'Cancelled',
        };
    }
    
    public function color(): string
    {
        return match($this) {
            self::DRAFT => 'gray',
            self::READY => 'warning', // Yellow
            self::IN_TRANSIT => 'info', // Blue
            self::ARRIVED => 'success', // Green
            self::DELIVERED => 'success',
            self::DELAYED => 'primary', // Red
            self::CANCELLED => 'gray',
        };
    }
}