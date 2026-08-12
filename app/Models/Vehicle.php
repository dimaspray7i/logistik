<?php

namespace App\Models;

use App\Enums\VehicleStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'plate_number', 'vehicle_type', 'brand', 'capacity', 'status', 'notes'
    ];

    protected $casts = [
        'status' => VehicleStatus::class,
        'capacity' => 'decimal:2',
    ];

    public function shipments(): HasMany
    {
        return $this->hasMany(Shipment::class);
    }
}