<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateShipmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('shipment'));
    }

    public function rules(): array
    {
        return [
            'vehicle_id' => ['nullable', 'exists:vehicles,id'],
            'driver_id' => ['nullable', 'exists:drivers,id'],
            'origin' => ['required', 'string', 'max:255'],
            'destination' => ['required', 'string', 'max:255'],
            'departure_date' => ['nullable', 'date'],
            'estimated_arrival' => ['nullable', 'date'],
            'actual_arrival' => ['nullable', 'date'],
            'status' => ['required', 'string', 'in:DRAFT,READY,IN_TRANSIT,ARRIVED,DELIVERED,DELAYED,CANCELLED'],
            'notes' => ['nullable', 'string'],
        ];
    }
}