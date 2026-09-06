<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne; 

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'company_name', 'shipment_code_prefix', 'phone', 'email', 'address', 'city', 'province', 'postal_code', 'notes'
    ];

    public function setShipmentCodePrefixAttribute($value): void
    {
        if (empty($value) || trim((string) $value) === '') {
            $this->attributes['shipment_code_prefix'] = null;
        } else {
            $sanitized = preg_replace('/[^A-Za-z0-9]/', '', (string) $value);
            $this->attributes['shipment_code_prefix'] = !empty($sanitized) ? strtoupper($sanitized) : null;
        }
    }

    public function contacts(): HasMany
    {
        return $this->hasMany(Contact::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function shipments(): HasMany
    {
        return $this->hasMany(Shipment::class);
    }

       public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }
}