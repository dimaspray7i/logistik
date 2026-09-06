<?php

namespace App\Contracts;

use App\Models\Shipment;

interface CarrierTrackingServiceInterface
{
    /**
     * Dapatkan detail status pelacakan dari provider ekspedisi eksternal.
     *
     * @param Shipment $shipment
     * @return array{
     *     status: string,
     *     carrier: string,
     *     tracking_number: string,
     *     is_api_connected: bool,
     *     checkpoint_history: array,
     *     notes: string
     * }
     */
    public function getTrackingDetails(Shipment $shipment): array;
}
