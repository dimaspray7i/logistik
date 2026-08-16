<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTrackingUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', \App\Models\TrackingUpdate::class);
    }

    public function rules(): array
    {
        return [
            'status' => ['required', 'string', 'in:DRAFT,READY,IN_TRANSIT,ARRIVED,DELIVERED,DELAYED,CANCELLED'],
            'location' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'route_point_id' => ['nullable', 'exists:route_points,id'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'tracked_at' => ['nullable', 'date'],
        ];
    }
}