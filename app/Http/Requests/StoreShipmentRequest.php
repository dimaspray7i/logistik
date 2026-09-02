<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreShipmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', \App\Models\Shipment::class);
    }

    public function rules(): array
    {
        return [
            'order_id' => ['required', 'exists:orders,id'],
            'vehicle_id' => ['nullable', 'exists:vehicles,id'],
            'driver_id' => ['nullable', 'exists:drivers,id'],
            'origin' => ['required', 'string', 'max:255'],
            'destination' => ['required', 'string', 'max:255'],
            'departure_date' => ['nullable', 'date'],
            'estimated_arrival' => ['nullable', 'date'],
            'status' => ['required', 'string', 'in:DRAFT,READY,IN_TRANSIT,ARRIVED,DELIVERED,DELAYED,CANCELLED'],
            'notes' => ['nullable', 'string'],
            'invoice_payment_status' => ['nullable', 'string', 'in:Belum Dibayar,Sudah Dibayar'],
            'invoice_payment_date' => ['nullable', 'required_if:invoice_payment_status,Sudah Dibayar', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'invoice_payment_status.in' => 'Status Pencairan Invoice yang dipilih tidak valid.',
            'invoice_payment_date.required_if' => 'Tanggal Pencairan wajib diisi jika Status Pencairan Invoice adalah Sudah Dibayar.',
            'invoice_payment_date.date' => 'Format Tanggal Pencairan tidak valid.',
        ];
    }
}