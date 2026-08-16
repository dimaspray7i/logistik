<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRouteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', \App\Models\Route::class);
    }

    public function rules(): array
    {
        return [
            'distance' => ['nullable', 'numeric', 'min:0'],
            'duration' => ['nullable', 'integer', 'min:0'],
            
            'points' => ['required', 'array', 'min:2'],
            'points.*.location_name' => ['required', 'string', 'max:255'],
            'points.*.address' => ['nullable', 'string'],
            'points.*.latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'points.*.longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'points.*.estimated_arrival' => ['nullable', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'points.required' => 'Rute harus memiliki minimal 2 titik (asal dan tujuan).',
            'points.min' => 'Rute harus memiliki minimal 2 titik (asal dan tujuan).',
        ];
    }
}