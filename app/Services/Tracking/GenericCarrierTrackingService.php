<?php

namespace App\Services\Tracking;

use App\Contracts\CarrierTrackingServiceInterface;
use App\Models\Shipment;

class GenericCarrierTrackingService implements CarrierTrackingServiceInterface
{
    /**
     * Layanan standar pelacakan ekspedisi eksternal.
     * Menyiapkan integrasi ke API resmi (JNE, J&T, SiCepat, dll).
     */
    public function getTrackingDetails(Shipment $shipment): array
    {
        if (!$shipment->isExternal()) {
            return [
                'status' => $shipment->status?->value ?? $shipment->status,
                'carrier' => 'Armada Perusahaan',
                'tracking_number' => $shipment->shipment_number,
                'is_api_connected' => false,
                'checkpoint_history' => [],
                'notes' => 'Pengiriman menggunakan armada internal perusahaan.',
            ];
        }

        $carrier = $shipment->carrier ?: 'Ekspedisi Eksternal';
        $trackingNumber = $shipment->tracking_number ?: '-';

        // Tempat mendaftarkan API Key / Credential resmi jika integrasi API diaktifkan kelak.
        // Contoh: if ($carrier === 'JNE' && config('services.jne.api_key')) { ... }

        return [
            'status' => $shipment->status?->value ?? $shipment->status,
            'carrier' => $carrier,
            'tracking_number' => $trackingNumber,
            'is_api_connected' => false,
            'checkpoint_history' => [],
            'notes' => "Pelacakan dikelola melalui portal resmi {$carrier} menggunakan Nomor Resi: {$trackingNumber}.",
        ];
    }
}
