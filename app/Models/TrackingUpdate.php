<?php

namespace App\Models;

use App\Enums\ShipmentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TrackingUpdate extends Model
{
    use HasFactory;

    protected $fillable = [
        'shipment_id', 'route_point_id', 'user_id', 'status', 'location',
        'address', 'city', 'province', 'country',
        'description', 'latitude', 'longitude', 'tracked_at'
    ];

    protected $casts = [
        'tracked_at' => 'datetime',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'status' => ShipmentStatus::class,
    ];

    public function hasCoordinates(): bool
    {
        return !is_null($this->latitude) && !is_null($this->longitude);
    }

    public function getAddressAttribute($value = null)
    {
        if ($value !== null) return $value;
        if (!empty($this->attributes['address'] ?? null)) return $this->attributes['address'];
        if (!empty($this->attributes['description'] ?? null) && preg_match('/Alamat:\s*([^|]+)/i', $this->attributes['description'], $m)) {
            return trim($m[1]);
        }
        return null;
    }

    public function getCityAttribute($value = null)
    {
        return $value ?? ($this->attributes['city'] ?? null);
    }

    public function getProvinceAttribute($value = null)
    {
        return $value ?? ($this->attributes['province'] ?? null);
    }

    public function getCountryAttribute($value = null)
    {
        return $value ?? ($this->attributes['country'] ?? null);
    }

    public function getCleanDescriptionAttribute(): ?string
    {
        $desc = $this->description;
        if (empty($desc) || trim($desc) === '-' || trim($desc) === '') {
            return null;
        }
        // Bersihkan teks warisan "Alamat: ..." yang mungkin pernah tersimpan di kolom deskripsi
        $cleaned = preg_replace('/(\s*\|\s*)?Alamat:\s*[^|]+/i', '', $desc);
        $cleaned = trim($cleaned, " \t\n\r\0\x0B|-");
        return $cleaned !== '' ? $cleaned : null;
    }

    public function getFullLocationAttribute(): string
    {
        $parts = array_filter([
            $this->location,
            $this->address,
            $this->city,
            $this->province,
            $this->country,
        ]);
        return implode(', ', array_unique($parts)) ?: ($this->location ?? '-');
    }

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