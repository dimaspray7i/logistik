<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'shipment_id', 'tracking_update_id', 'user_id', 'file_path', 'file_name', 'mime_type', 'file_size', 'type'
    ];

    protected function casts(): array
    {
        return [
            'type' => \App\Enums\DocumentType::class,
        ];
    }

    public function shipment(): BelongsTo
    {
        return $this->belongsTo(Shipment::class);
    }

    public function trackingUpdate(): BelongsTo
    {
        return $this->belongsTo(TrackingUpdate::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}