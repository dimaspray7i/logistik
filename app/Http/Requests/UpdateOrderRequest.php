<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('order'));
    }

    public function rules(): array
    {
        return [
            'order_date' => ['required', 'date'],
            'status' => ['required', 'string', 'in:PENDING,PROCESSING,COMPLETED,CANCELLED'],
            'notes' => ['nullable', 'string'],
            
            'items' => ['required', 'array', 'min:1'],
            'items.*.id' => ['nullable', 'exists:order_items,id'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.quantity' => ['required', 'numeric', 'min:0.01'],
            'items.*.weight' => ['required', 'numeric', 'min:0'],
            'items.*.unit' => ['required', 'string', 'max:20'],
            'items.*.notes' => ['nullable', 'string'],
        ];
    }
}