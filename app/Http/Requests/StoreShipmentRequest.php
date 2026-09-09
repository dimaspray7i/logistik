<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreShipmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', \App\Models\Shipment::class);
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'shipping_type' => 'EXTERNAL',
        ]);
    }

    public function rules(): array
    {
        return [
            'shipping_type' => ['nullable', 'string', 'in:INTERNAL,EXTERNAL'],
            'expedition_provider_id' => ['required', 'exists:expedition_providers,id'],
            'carrier' => ['nullable', 'string', 'max:255'],
            'tracking_number' => ['required', 'string', 'max:255'],
            'order_id' => ['required', 'exists:orders,id'],
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
            'shipping_type.required' => 'Jenis pengiriman wajib dipilih.',
            'shipping_type.in' => 'Jenis pengiriman yang dipilih tidak valid.',
            'expedition_provider_id.required' => 'Penyedia ekspedisi wajib dipilih.',
            'expedition_provider_id.exists' => 'Penyedia ekspedisi yang dipilih tidak ditemukan.',
            'tracking_number.required' => 'Nomor resi / pelacakan wajib diisi.',
            'invoice_payment_status.in' => 'Status Pencairan Invoice yang dipilih tidak valid.',
            'invoice_payment_date.required_if' => 'Tanggal Pencairan wajib diisi jika Status Pencairan Invoice adalah Sudah Dibayar.',
            'invoice_payment_date.date' => 'Format Tanggal Pencairan tidak valid.',
        ];
    }
}