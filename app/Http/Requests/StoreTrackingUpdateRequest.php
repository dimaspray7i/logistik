<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTrackingUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', \App\Models\TrackingUpdate::class);
    }

    protected function prepareForValidation(): void
    {
        // Normalisasi alias field bahasa Indonesia ke field kanonikal
        $merge = [];
        if ($this->has('alamat') && !$this->filled('address')) {
            $merge['address'] = $this->input('alamat');
        }
        if ($this->has('kota') && !$this->filled('city')) {
            $merge['city'] = $this->input('kota');
        }
        if ($this->has('provinsi') && !$this->filled('province')) {
            $merge['province'] = $this->input('provinsi');
        }
        if ($this->has('negara') && !$this->filled('country')) {
            $merge['country'] = $this->input('negara');
        }
        if ($this->has('lintang') && !$this->filled('latitude')) {
            $merge['latitude'] = $this->input('lintang');
        }
        if ($this->has('bujur') && !$this->filled('longitude')) {
            $merge['longitude'] = $this->input('bujur');
        }
        if ($this->has('dilacak_at') && !$this->filled('tracked_at')) {
            $merge['tracked_at'] = $this->input('dilacak_at');
        }
        if ($this->has('deskripsi') && !$this->filled('description')) {
            $merge['description'] = $this->input('deskripsi');
        }
        if ($this->has('lokasi') && !$this->filled('location')) {
            $merge['location'] = $this->input('lokasi');
        }
        if (!empty($merge)) {
            $this->merge($merge);
        }
    }

    public function rules(): array
    {
        return [
            'status' => ['required', 'string', 'in:DRAFT,READY,IN_TRANSIT,ARRIVED,DELIVERED,DELAYED,CANCELLED'],
            'location' => ['required', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:500'],
            'alamat' => ['nullable', 'string', 'max:500'],
            'city' => ['nullable', 'string', 'max:100'],
            'kota' => ['nullable', 'string', 'max:100'],
            'province' => ['nullable', 'string', 'max:100'],
            'provinsi' => ['nullable', 'string', 'max:100'],
            'country' => ['nullable', 'string', 'max:100'],
            'negara' => ['nullable', 'string', 'max:100'],
            'latitude' => ['nullable', 'required_with:longitude', 'numeric', 'between:-90,90'],
            'lintang' => ['nullable', 'required_with:bujur', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'required_with:latitude', 'numeric', 'between:-180,180'],
            'bujur' => ['nullable', 'required_with:lintang', 'numeric', 'between:-180,180'],
            'tracked_at' => ['nullable', 'date'],
            'dilacak_at' => ['nullable', 'date'],
            'description' => ['nullable', 'string', 'max:1000'],
            'deskripsi' => ['nullable', 'string', 'max:1000'],
            'route_point_id' => ['nullable', 'exists:route_points,id'],
        ];
    }
}