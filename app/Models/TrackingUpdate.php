<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TrackingUpdate extends Model
{
    use HasFactory;

    protected $fillable = [
        'shipment_id', 'route_point_id', 'user_id', 'status', 'location', 'description',
        'latitude', 'longitude', 'tracked_at'
    ];

    protected $casts = [
        'tracked_at' => 'datetime',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    public function shipment(): BelongsTo
    {
        return $this->belongsTo(Shipment::class);
    }

    public function routePoint(): BelongsTo
    {
        return $this->belongsTo(RoutePoint::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }
}