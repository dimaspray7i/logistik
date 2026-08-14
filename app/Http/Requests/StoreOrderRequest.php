<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', \App\Models\Order::class);
    }

    public function rules(): array
    {
        return [
            'customer_id' => ['required', 'exists:customers,id'],
            'order_date' => ['required', 'date'],
            'status' => ['required', 'string', 'in:PENDING,PROCESSING,COMPLETED,CANCELLED'],
            'notes' => ['nullable', 'string'],
            
            // Order Items (array)
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.quantity' => ['required', 'numeric', 'min:0.01'],
            'items.*.weight' => ['required', 'numeric', 'min:0'],
            'items.*.unit' => ['required', 'string', 'max:20'],
            'items.*.notes' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'items.required' => 'Order harus memiliki minimal 1 item.',
            'items.*.product_id.exists' => 'Product yang dipilih tidak valid.',
        ];
    }
}