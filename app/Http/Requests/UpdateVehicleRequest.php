<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateVehicleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('vehicle'));
    }

    public function rules(): array
    {
        return [
            'plate_number' => ['required', 'string', 'max:50', Rule::unique('vehicles', 'plate_number')->ignore($this->route('vehicle'))],
            'vehicle_type' => ['required', 'string', 'max:50'],
            'brand' => ['nullable', 'string', 'max:100'],
            'capacity' => ['required', 'numeric', 'min:0'],
            'status' => ['required', 'string', 'in:AVAILABLE,IN_USE,MAINTENANCE'],
            'notes' => ['nullable', 'string'],
        ];
    }
}