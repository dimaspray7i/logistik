<?php

namespace App\Enums;

enum VehicleStatus: string
{
    case AVAILABLE = 'AVAILABLE';
    case IN_USE = 'IN_USE';
    case MAINTENANCE = 'MAINTENANCE';
}