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
        'name', 'company_name', 'phone', 'email', 'address', 'city', 'province', 'postal_code', 'notes'
    ];

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