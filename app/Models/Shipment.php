<?php

namespace App\Models;

use App\Enums\InvoicePaymentStatus;
use App\Enums\ShipmentStatus;
use App\Enums\ShippingType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Shipment extends Model
{
    use HasFactory;

    protected $fillable = [
        'shipment_number', 'shipping_type', 'carrier', 'tracking_number',
        'expedition_provider_id',
        'order_id', 'customer_id', 'vehicle_id', 'driver_id',
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
        'shipping_type' => ShippingType::class,
        'invoice_payment_status' => InvoicePaymentStatus::class,
        'invoice_payment_date' => 'date',
    ];

    public function isExternal(): bool
    {
        $val = is_object($this->shipping_type) ? $this->shipping_type->value : $this->shipping_type;
        return $val === ShippingType::EXTERNAL->value || $val === 'EXTERNAL';
    }

    public function isInternal(): bool
    {
        return !$this->isExternal();
    }

    public function getDisplayCodeAttribute(): string
    {
        if ($this->isExternal() && !empty($this->tracking_number)) {
            return $this->tracking_number;
        }

        return $this->shipment_number;
    }

    public function getCarrierLabelAttribute(): string
    {
        if ($this->isExternal()) {
            // Prioritas: nama provider dari DB, fallback ke teks carrier lama
            if ($this->expeditionProvider) {
                return $this->expeditionProvider->name;
            }
            if (!empty($this->carrier)) {
                return $this->carrier;
            }
        }

        return 'Armada Perusahaan';
    }

    /**
     * Get short provider code/name for display.
     */
    public function getProviderCodeAttribute(): string
    {
        if ($this->expeditionProvider) {
            return $this->expeditionProvider->code;
        }
        return $this->carrier ?? '—';
    }

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

    public function expeditionProvider(): BelongsTo
    {
        return $this->belongsTo(ExpeditionProvider::class);
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

    public function syncOrderStatus(): void
    {
        if (!$this->order_id) return;
        $order = $this->order;
        if (!$order) return;

        $allShipments = $order->shipments()->get();
        if ($allShipments->isEmpty()) return;

        $allDelivered = $allShipments->every(fn($s) => $s->status === ShipmentStatus::DELIVERED);
        $allCancelled = $allShipments->every(fn($s) => $s->status === ShipmentStatus::CANCELLED);

        if ($allDelivered) {
            $order->update(['status' => \App\Enums\OrderStatus::COMPLETED]);
        } elseif ($allCancelled) {
            $order->update(['status' => \App\Enums\OrderStatus::CANCELLED]);
        } else {
            $hasActive = $allShipments->some(fn($s) => in_array($s->status, [
                ShipmentStatus::READY,
                ShipmentStatus::IN_TRANSIT,
                ShipmentStatus::ARRIVED,
                ShipmentStatus::DELIVERED,
                ShipmentStatus::DELAYED
            ]));
            if ($hasActive && $order->status === \App\Enums\OrderStatus::PENDING) {
                $order->update(['status' => \App\Enums\OrderStatus::PROCESSING]);
            }
        }
    }
}