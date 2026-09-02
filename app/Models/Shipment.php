<?php

namespace App\Models;

use App\Enums\InvoicePaymentStatus;
use App\Enums\ShipmentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Shipment extends Model
{
    use HasFactory;

    protected $fillable = [
        'shipment_number', 'order_id', 'customer_id', 'vehicle_id', 'driver_id',
        'origin', 'destination', 'departure_date', 'estimated_arrival', 'actual_arrival',
        'total_weight', 'status', 'notes',
        'invoice_payment_status', 'invoice_payment_date'
    ];

    protected $casts = [
        'departure_date' => 'datetime',
        'estimated_arrival' => 'datetime',
        'actual_arrival' => 'datetime',
        'total_weight' => 'decimal:2',
        'status' => ShipmentStatus::class,
        'invoice_payment_status' => InvoicePaymentStatus::class,
        'invoice_payment_date' => 'date',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(ShipmentItem::class);
    }

    public function route(): HasOne
    {
        return $this->hasOne(Route::class);
    }

    public function trackingUpdates(): HasMany
    {
        return $this->hasMany(TrackingUpdate::class)->orderBy('tracked_at', 'asc');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }
}